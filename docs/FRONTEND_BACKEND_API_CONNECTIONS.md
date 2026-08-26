# CityHangAround — Frontend ↔ Backend API Connections

> Complete reference of how the Next.js frontend connects to the Laravel backend.
> Every frontend service file, every API endpoint, every data shape — documented in one place.

**Backend Base URL:** `http://localhost:8000` (dev) / `https://cityhangaround.com` (prod)
**Frontend Base URL:** `http://localhost:3000` (dev)
**CORS:** Configured with `allowed_origins => ['*']` — cross-origin calls work in dev.

---

## Table of Contents

1. [Architecture Overview](#1-architecture-overview)
2. [Frontend Services Structure](#2-frontend-services-structure)
3. [HTTP Client (`client.ts`)](#3-http-client-clientts)
4. [Shared Types (`types.ts`)](#4-shared-types-typests)
5. [Public API Endpoints (`/api/public/*`)](#5-public-api-endpoints-apipublic)
6. [Auth-Protected Endpoints (Legacy Blade Routes)](#6-auth-protected-endpoints-legacy-blade-routes)
7. [Service-by-Service Mapping](#7-service-by-service-mapping)
8. [Data Shapes](#8-data-shapes)

---

## 1. Architecture Overview

```
┌─────────────────────────────────────────────────────────┐
│                    Next.js Frontend                      │
│                   (localhost:3000)                        │
│                                                         │
│  src/services/                                          │
│  ├── client.ts ──── Shared HTTP wrapper                 │
│  ├── types.ts ───── Shared TypeScript types              │
│  ├── index.ts ───── Barrel re-exports                   │
│  │                                                     │
│  │  ┌── home.ts ─────────── Cities, Areas, Categories  │
│  │  ├── events.ts ───────── Events, RSVP, Categories   │
│  │  ├── marketplace.ts ──── Products, Brands, Enquiry  │
│  │  ├── pages.ts ────────── Business Listings          │
│  │  ├── groups.ts ───────── Groups, Join/Invite        │
│  │  ├── blog.ts ─────────── Blogs, Categories          │
│  │  ├── chat.ts ─────────── User/Page/Marketplace Chat │
│  │  ├── posts.ts ────────── Timeline, Comments, Stories│
│  │  ├── videos.ts ───────── Videos, Shorts             │
│  │  ├── user.ts ─────────── Profile, Wallet, Subs      │
│  │  ├── search.ts ───────── Global + Entity Search     │
│  │  ├── notifications.ts ── Notifications              │
│  │  └── auth.ts ─────────── Register, Login, Logout    │
│  └─────────────────────────────────────────────────────│
└──────────────────────┬──────────────────────────────────┘
                       │
                       │  fetch() with Bearer token from localStorage
                       │
┌──────────────────────▼──────────────────────────────────┐
│                   Laravel Backend                        │
│                  (localhost:8000)                         │
│                                                         │
│  routes/api.php (prefix: /api)                          │
│  ├── GET  /api/health              ← Health check      │
│  ├── GET  /api/user                ← Sanctum user      │
│  └── /api/public/*                 ← 34 public routes  │
│      ├── cities, areas                                 │
│      ├── categories (page, event, product, group, blog)│
│      ├── events, products, pages, groups, blogs        │
│      └── scroll loaders for each content type          │
│                                                         │
│  routes/custom_routes.php (prefix: / — web middleware)  │
│  ├── /ajax/cities                 ← Legacy public JSON │
│  ├── /event/*                     ← Auth-protected CRUD│
│  ├── /product/*                   ← Auth-protected CRUD│
│  ├── /page/*                      ← Auth-protected CRUD│
│  ├── /group/*                     ← Auth-protected CRUD│
│  ├── /blog/*                      ← Auth-protected CRUD│
│  ├── /chat/*                      ← Auth-protected     │
│  ├── /search/*                    ← Auth-protected     │
│  └── /load_*_by_scrolling         ← Blade views (old)  │
│                                                         │
│  routes/web.php                                         │
│  ├── /                            ← Landing page       │
│  ├── /home                        ← Timeline           │
│  ├── /profile/*                   ← Profile CRUD       │
│  ├── /auth/google, /auth/facebook ← Social login       │
│  └── /load-all-cities-json        ← Public city list   │
│                                                         │
│  routes/user.php                                        │
│  ├── /user/dashboard/*            ← Dashboard          │
│  ├── /user/wallet/*               ← Wallet             │
│  ├── /user/subscriptions/*        ← Subscriptions      │
│  └── /user/leads/*                ← Leads              │
└─────────────────────────────────────────────────────────┘
```

### Key Design Decisions

| Decision | Reason |
|----------|--------|
| `/api/public/*` routes return **JSON** | The old `load_*_by_scrolling` routes returned Blade HTML — unusable by Next.js |
| `/api/public/*` routes have **no auth** | Public browsing (cities, events, products) must work without login |
| Auth-protected CRUD routes stay on **web middleware** | Existing Blade auth flow — will migrate to `/api/auth/*` later |
| `client.ts` auto-attaches `Bearer` token | Reads `auth_token` from `localStorage` — works for both public and auth routes |
| City selection persists in `localStorage` | Key: `cha_selected_city` — survives page reloads |

---

## 2. Frontend Services Structure

```
frontend/src/services/
│
├── client.ts            Core HTTP client
│   ├── API_BASE_URL     process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000'
│   ├── api.get()        GET requests
│   ├── api.post()       POST requests
│   ├── api.put()        PUT requests
│   ├── api.patch()      PATCH requests
│   ├── api.delete()     DELETE requests
│   └── checkHealth()    GET /api/health
│
├── types.ts             Shared TypeScript interfaces
│   ├── City             { id, city_name, city_slug, city_image, city_state, ... }
│   ├── Area             { id, area_name, city_id }
│   ├── User             { id, name, email, avatar, ... }
│   ├── AuthResponse     { user, token }
│   ├── LoginPayload     { email, password, remember? }
│   ├── RegisterPayload  { name, email, password, password_confirmation }
│   ├── HealthResponse   { status, timestamp, message }
│   └── PaginatedResponse<T>  { data, current_page, last_page, per_page, total }
│
├── index.ts             Barrel re-export (single import point)
│
├── auth.ts              Authentication
├── home.ts              Cities, Areas, Categories
├── events.ts            Events CRUD, RSVP, Categories
├── marketplace.ts       Products/Deals CRUD, Categories, Brands
├── pages.ts             Business Listings CRUD
├── groups.ts            Groups CRUD, Join/Invite
├── blog.ts              Blogs CRUD, Categories
├── chat.ts              User/Page/Marketplace Chat
├── posts.ts             Timeline Posts, Comments, Stories
├── videos.ts            Videos, Shorts
├── user.ts              Profile, Friends, Wallet, Subscriptions
├── search.ts            Global + Entity Search
└── notifications.ts     Notifications, Request Accept/Decline
```

### Import Patterns

```typescript
// Pattern 1: Import from barrel (tree-shaking works)
import { getCities, createEvent } from "@/src/services";

// Pattern 2: Import from specific module (preferred for large apps)
import { getCities } from "@/src/services/home";
import type { City } from "@/src/services/types";

// Pattern 3: Import client directly
import { api, API_BASE_URL } from "@/src/services/client";
```

---

## 3. HTTP Client (`client.ts`)

The shared `api` object handles all HTTP communication:

```typescript
import { api } from "@/src/services/client";

// GET request
const cities = await api.get<City[]>("/api/public/cities");

// POST request with JSON body
const result = await api.post<{ id: number }>("/event/store", payload);

// POST with FormData (file uploads)
const fd = new FormData();
fd.append("coverphoto", file);
const event = await api.post<{ id: number }>("/event/store", fd);

// GET with query params
const products = await api.get<Product[]>("/api/public/products/scroll", {
  params: { offset: 0, limit: 20 },
});
```

### Authentication Handling

The client automatically:
1. Reads `auth_token` from `localStorage`
2. Attaches `Authorization: Bearer {token}` header when present
3. Sets `Accept: application/json` on all requests
4. Sets `Content-Type: application/json` for JSON bodies
5. Omits `Content-Type` for FormData (lets browser set boundary)

### Error Handling

```typescript
// On HTTP errors (4xx, 5xx), the client throws:
// Error: "API GET /api/public/events failed (500): ..."

// Services catch errors and return empty arrays:
export async function getCities(): Promise<City[]> {
  try {
    return await api.get<City[]>("/api/public/cities");
  } catch (error) {
    console.error("Error fetching cities:", error);
    return [];  // Graceful degradation
  }
}
```

---

## 4. Shared Types (`types.ts`)

```typescript
interface City {
  id: number;
  city_name: string;
  city_slug: string;
  city_image: string | null;
  city_state: string | null;
  state_id: number | null;
  city_about: string | null;
  city_lat: string | null;
  city_lng: string | null;
}

interface Area {
  id: number;
  area_name: string;
  city_id: number;
}

interface User {
  id: number;
  name: string;
  email: string;
  avatar: string | null;
  cover_photo: string | null;
  bio: string | null;
  phone: string | null;
  gender: string | null;
  dob: string | null;
}

interface AuthResponse {
  user: User;
  token: string;
}

interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
}
```

---

## 5. Public API Endpoints (`/api/public/*`)

> **No authentication required.** These are served from `routes/api.php` → `Api\PublicController`.
> All return JSON. CORS is enabled for cross-origin calls from the Next.js dev server.

### 5.1 Cities

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/public/cities` | `home.getCities()` | All active cities with approved pages |
| `POST` | `/api/public/cities/search` | `home.searchCities(filter)` | Search cities by name (for city picker) |
| `GET` | `/api/public/cities/{state_id}` | `home.getCitiesByState(id)` | Cities in a state |

**GET /api/public/cities — Response:**
```json
[
  {
    "id": 1,
    "city_name": "Ahmedabad",
    "city_slug": "ahmedabad",
    "city_image": "https://...",
    "city_state": "GJ",
    "state_id": 2,
    "city_about": "...",
    "city_lat": "23.0225",
    "city_lng": "72.5714"
  }
]
```

**POST /api/public/cities/search — Request:**
```json
{ "filter": "ahm" }
```
**Response:**
```json
[
  { "id": 1, "city_name": "Ahmedabad", "city_slug": "ahmedabad" }
]
```

---

### 5.2 Areas

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/public/areas/{city_id}` | `home.getAreasByCity(id)` | Areas in a city |

**Response:**
```json
[
  { "id": 1, "area_name": "Vastrapur", "city_id": 1 },
  { "id": 2, "area_name": "SG Highway", "city_id": 1 }
]
```

---

### 5.3 Page Categories

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/public/categories` | `home.getCategories()` | All categories with parent info |
| `GET` | `/api/public/categories/parent` | `home.getParentCategories()` | Top-level categories only |
| `GET` | `/api/public/categories/{city_id}` | `home.getCategoriesByCity(id)` | Categories in a city |
| `GET` | `/api/public/subcategories/{id}` | `home.getSubcategories(id)` | Subcategories for a parent |

**GET /api/public/categories — Response:**
```json
[
  { "id": 1, "category_name": "Restaurant", "parent": "Food & Dining" },
  { "id": 2, "category_name": "Gym", "parent": "Health & Fitness" }
]
```

---

### 5.4 Event Categories

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/public/event-categories` | `events.getEventCategories()` | All event categories |
| `GET` | `/api/public/event-categories/parent` | `events.getEventParentCategories()` | Parent categories only |

**Response:**
```json
[
  { "id": 1, "category_name": "Conference", "parent": "Business" },
  { "id": 2, "category_name": "Music Festival", "parent": "Entertainment" }
]
```

---

### 5.5 Product Categories & Brands

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/public/product-categories` | `marketplace.getProductCategories()` | All product categories |
| `GET` | `/api/public/product-categories/parent` | `marketplace.getProductParentCategories()` | Parent categories only |
| `GET` | `/api/public/brands` | `marketplace.getBrands()` | All brands |

**GET /api/public/brands — Response:**
```json
[
  { "id": 1, "name": "Nike" },
  { "id": 2, "name": "Samsung" }
]
```

---

### 5.6 Group Categories

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/public/group-categories` | `groups.getGroupCategories()` | All group categories |
| `GET` | `/api/public/group-categories/parent` | `groups.getGroupParentCategories()` | Parent categories only |

---

### 5.7 Blog Categories

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/public/blog-categories` | `blog.getBlogCategories()` | All blog categories |
| `GET` | `/api/public/blog-categories/parent` | `blog.getBlogParentCategories()` | Parent categories only |

---

### 5.8 Events

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/public/events` | `events.getAllEvents()` | All upcoming public events |
| `GET` | `/api/public/events/scroll` | `events.loadEventsByScrolling(offset, limit)` | Infinite scroll |
| `GET` | `/api/public/events/{city_slug}` | `events.getEventsByCity(slug)` | Events in a city |
| `GET` | `/api/public/events/category/{slug}` | `events.getEventsByCategory(slug)` | Events by category |
| `GET` | `/api/public/events/{cat}-in-{city}` | `events.getEventsByCategoryInCity(cat, city)` | Filtered |

**GET /api/public/events — Response:**
```json
[
  {
    "id": 101,
    "event_name": "Tech Summit 2026",
    "event_slug": "tech-summit-2026",
    "short_description": "...",
    "event_date": "2026-09-15",
    "event_time": "10:00:00",
    "event_status": "upcoming",
    "venue": "Convention Center",
    "address": "SG Highway, Ahmedabad",
    "city_id": 1,
    "area_id": null,
    "category_id": 5,
    "cover_image": "https://...",
    "view": 0,
    "created_at": "2026-08-20T10:00:00.000000Z"
  }
]
```

**GET /api/public/events/scroll — Query Params:**
| Param | Type | Default | Description |
|-------|------|---------|-------------|
| `offset` | int | 0 | Skip N records |
| `limit` | int | 20 | Max records (capped at 50) |

---

### 5.9 Products / Deals

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/public/products` | `marketplace.getAllProducts()` | All products |
| `GET` | `/api/public/products/scroll` | `marketplace.loadProductsByScrolling(offset, limit)` | Infinite scroll |
| `GET` | `/api/public/products/{city_slug}` | `marketplace.getProductsByCity(slug)` | Products in a city |

**GET /api/public/products — Response:**
```json
[
  {
    "id": 201,
    "product_name": "Running Shoes",
    "product_slug": "running-shoes",
    "description": "...",
    "price": 2999,
    "condition": "new",
    "images": ["..."],
    "city_id": 1,
    "area_id": null,
    "category_id": 3,
    "brand_id": 1,
    "view": 0,
    "created_at": "2026-08-20T10:00:00.000000Z"
  }
]
```

---

### 5.10 Pages / Business Listings

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/public/pages` | `pages.getAllPages()` | All active pages |
| `GET` | `/api/public/pages/scroll` | `pages.loadPagesByScrolling(offset, limit)` | Infinite scroll |
| `GET` | `/api/public/pages/{city_slug}` | `pages.getPagesByCity(slug)` | Pages in a city |

---

### 5.11 Groups

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/public/groups` | `groups.getAllGroups()` | All public groups |
| `GET` | `/api/public/groups/scroll` | `groups.loadGroupsByScrolling(offset, limit)` | Infinite scroll |
| `GET` | `/api/public/groups/{city_slug}` | `groups.getGroupsByCity(slug)` | Groups in a city |

---

### 5.12 Blogs

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/public/blogs` | `blog.getAllBlogs()` | All blogs |
| `GET` | `/api/public/blogs/scroll` | `blog.loadBlogsByScrolling(offset, limit)` | Infinite scroll |
| `GET` | `/api/public/blogs/{city_slug}` | `blog.getBlogsByCity(slug)` | Blogs in a city |

---

### 5.13 Health Check

| Method | Endpoint | Frontend Function | Description |
|--------|----------|-------------------|-------------|
| `GET` | `/api/health` | `client.checkHealth()` | Backend health status |

**Response:**
```json
{
  "status": "healthy",
  "timestamp": "2026-08-26T10:00:00.000000Z",
  "message": "Laravel API is running"
}
```

---

## 6. Auth-Protected Endpoints (Legacy Blade Routes)

> These routes are under the **web middleware** (not `/api/*`) and require a valid Sanctum token.
> The frontend `client.ts` auto-attaches the `Authorization: Bearer {token}` header from `localStorage`.
> **These will migrate to `/api/auth/*` in a future iteration.**

### 6.1 Authentication

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `POST` | `/register` | `auth.register()` | `auth.ts` | Register new user |
| `POST` | `/login` | `auth.login()` | `auth.ts` | Log in |
| `POST` | `/logout` | `auth.logout()` | `auth.ts` | Log out |
| `POST` | `/forgot-password` | `auth.forgotPassword()` | `auth.ts` | Request password reset |
| `POST` | `/reset-password` | `auth.resetPassword()` | `auth.ts` | Reset password with token |
| `GET` | `/auth/google` | — | — | Redirect to Google OAuth |
| `GET` | `/auth/facebook` | — | — | Redirect to Facebook OAuth |

**POST /login — Request:**
```json
{ "email": "user@example.com", "password": "secret", "remember": true }
```
**Response:** Redirects to dashboard (Blade). Token stored in session.

> ⚠️ **Note:** The Blade auth flow uses session cookies, not Bearer tokens.
> For the Next.js frontend, you'll need to implement token-based auth via `/api/*` routes.

---

### 6.2 Events (Auth Required)

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `POST` | `/event/store` | `events.createEvent()` | `events.ts` | Create event |
| `POST` | `/event/update/{id}` | `events.updateEvent()` | `events.ts` | Update event |
| `GET` | `/event/delete?id={id}` | `events.deleteEvent()` | `events.ts` | Delete event |
| `GET` | `/event/going/{id}` | `events.markGoing()` | `events.ts` | RSVP: Going |
| `GET` | `/event/notgoing/{id}` | `events.markNotGoing()` | `events.ts` | RSVP: Not Going |
| `GET` | `/event/interested/{id}` | `events.markInterested()` | `events.ts` | RSVP: Interested |
| `GET` | `/event/notinterested/{id}` | `events.markNotInterested()` | `events.ts` | RSVP: Not Interested |

---

### 6.3 Products / Deals (Auth Required)

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `POST` | `/product/store` | `marketplace.createProduct()` | `marketplace.ts` | Create product |
| `POST` | `/update/product/{id}` | `marketplace.updateProduct()` | `marketplace.ts` | Update product |
| `GET` | `/product/delete?id={id}` | `marketplace.deleteProduct()` | `marketplace.ts` | Delete product |
| `GET` | `/save/product/{id}` | `marketplace.saveProduct()` | `marketplace.ts` | Save for later |
| `GET` | `/unsave/product/{id}` | `marketplace.unsaveProduct()` | `marketplace.ts` | Unsave |
| `GET` | `/product/saved/` | `marketplace.getSavedProducts()` | `marketplace.ts` | List saved |
| `POST` | `/enquiry` | `marketplace.submitEnquiry()` | `marketplace.ts` | Enquiry (public) |

---

### 6.4 Pages / Business Listings (Auth Required)

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `POST` | `/page/store` | `pages.createPage()` | `pages.ts` | Create page |
| `POST` | `/update/page/{id}` | `pages.updatePage()` | `pages.ts` | Update page |
| `GET` | `/pages/delete?id={id}` | `pages.deletePage()` | `pages.ts` | Delete page |
| `POST` | `/claim-listing/submit` | `pages.claimListing()` | `pages.ts` | Claim listing |
| `GET` | `/page/suggestions?q={q}` | `pages.getPageSuggestions()` | `pages.ts` | Search suggestions |
| `GET` | `/page/load_videos?page_id={id}` | `pages.loadPageVideos()` | `pages.ts` | Page videos |

---

### 6.5 Groups (Auth Required)

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `POST` | `/group/store` | `groups.createGroup()` | `groups.ts` | Create group |
| `POST` | `/update/group/{id}` | `groups.updateGroup()` | `groups.ts` | Update group |
| `GET` | `/group/join/{id}` | `groups.joinGroup()` | `groups.ts` | Join group |
| `GET` | `/group/rjoin/{id}` | `groups.requestJoinGroup()` | `groups.ts` | Request join (private) |
| `POST` | `/group/invites/sent` | `groups.sendGroupInvites()` | `groups.ts` | Send invites |
| `POST` | `/report/group` | `groups.reportGroup()` | `groups.ts` | Report group |

---

### 6.6 Blogs (Auth Required)

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `POST` | `/blog/store` | `blog.createBlog()` | `blog.ts` | Create blog |
| `POST` | `/update/blog/{id}` | `blog.updateBlog()` | `blog.ts` | Update blog |
| `GET` | `/blog/delete?id={id}` | `blog.deleteBlog()` | `blog.ts` | Delete blog |
| `GET` | `/blog/search/?q={q}` | `blog.searchBlogs()` | `blog.ts` | Search blogs |

---

### 6.7 Chat (Auth Required)

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `POST` | `/chat/save` | `chat.sendMessage()` | `chat.ts` | Send message |
| `GET` | `/chat/inbox/load/data/ajax/` | `chat.loadChatData()` | `chat.ts` | Load conversations |
| `GET` | `/chat/inbox/read/message/ajax/` | `chat.markMessagesRead()` | `chat.ts` | Mark read |
| `GET` | `/chat/own/remove/{id}` | `chat.deleteMessage()` | `chat.ts` | Delete message |
| `GET` | `/chat/profile/search/?q={q}` | `chat.searchChatProfiles()` | `chat.ts` | Search users |
| `POST` | `/my_message_react` | `chat.reactToMessage()` | `chat.ts` | React to message |
| `POST` | `/chat/with-us` | `chat.chatWithSupport()` | `chat.ts` | Support chat |
| `POST` | `/chat/send` | `chat.sendPageMessage()` | `chat.ts` | Page chat |
| `GET` | `/chat/fetch/{id}` | `chat.fetchPageChatMessages()` | `chat.ts` | Fetch page messages |
| `POST` | `/chat/marketplace/send` | `chat.sendMarketplaceMessage()` | `chat.ts` | Marketplace chat |
| `GET` | `/chat/marketplace/fetch/{id}` | `chat.fetchMarketplaceMessages()` | `chat.ts` | Fetch marketplace msgs |

---

### 6.8 Posts & Timeline (Auth Required)

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `POST` | `/create_post` | `posts.createPost()` | `posts.ts` | Create post |
| `POST` | `/edit_post/{id}` | `posts.updatePost()` | `posts.ts` | Update post |
| `GET` | `/load_post_by_scrolling` | `posts.loadPostsByScrolling()` | `posts.ts` | Infinite scroll |
| `GET` | `/view/single/post/{id}` | `posts.getSinglePost()` | `posts.ts` | Single post |
| `GET` | `/delete/my/post?id={id}` | `posts.deletePost()` | `posts.ts` | Delete post |
| `GET` | `/post_comment` | `posts.addComment()` | `posts.ts` | Add comment |
| `GET` | `/load_post_comments` | `posts.loadComments()` | `posts.ts` | Load comments |
| `POST` | `/my_react` | `posts.reactToPost()` | `posts.ts` | React to post |
| `POST` | `/share/on/my/timeline` | `posts.shareToTimeline()` | `posts.ts` | Share to timeline |
| `POST` | `/share/on/group` | `posts.shareToGroup()` | `posts.ts` | Share to group |
| `POST` | `/create_story` | `posts.createStory()` | `posts.ts` | Create story |
| `GET` | `/stories/{offset}/{limit}` | `posts.getStories()` | `posts.ts` | Get stories |
| `GET` | `/story_details/{id}/{offset}/{limit}` | `posts.getStoryDetails()` | `posts.ts` | Story details |

---

### 6.9 Videos (Auth Required)

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `GET` | `/videos` | `videos.getAllVideos()` | `videos.ts` | All videos |
| `GET` | `/shorts` | `videos.getAllShorts()` | `videos.ts` | All shorts |
| `GET` | `/load_videos_by_scrolling` | `videos.loadVideosByScrolling()` | `videos.ts` | Scroll videos |
| `GET` | `/load_shorts_by_scrolling` | `videos.loadShortsByScrolling()` | `videos.ts` | Scroll shorts |
| `POST` | `/videos/sorts/store` | `videos.createVideo()` | `videos.ts` | Create video |
| `GET` | `/video/delete?id={id}` | `videos.deleteVideo()` | `videos.ts` | Delete video |
| `GET` | `/save/video/short/{id}` | `videos.saveShort()` | `videos.ts` | Save short |
| `GET` | `/unsave/video/short/{id}` | `videos.unsaveShort()` | `videos.ts` | Unsave short |
| `GET` | `/saved/video/view` | `videos.getSavedVideos()` | `videos.ts` | Saved videos |

---

### 6.10 Profile & User (Auth Required)

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `GET` | `/profile` | `user.getProfile()` | `user.ts` | Current user profile |
| `GET` | `/user/view-profile/{id}` | `user.getViewProfile(id)` | `user.ts` | Other user profile |
| `POST` | `/profile/update_profile/` | `user.updateProfile()` | `user.ts` | Update profile |
| `POST` | `/profile/upload_photo/{type}` | `user.uploadPhoto()` | `user.ts` | Upload avatar/cover |
| `POST` | `/profile/about/{action?}` | `user.updateAbout()` | `user.ts` | Update about section |
| `GET` | `/profile/friends` | `user.getFriends()` | `user.ts` | Friends list |
| `GET` | `/user/friend/{id}` | `user.addFriend(id)` | `user.ts` | Add friend |
| `GET` | `/user/unfriend/{id}` | `user.unfriend(id)` | `user.ts` | Unfriend |
| `GET` | `/user/account/follow/{id}` | `user.followUser(id)` | `user.ts` | Follow |
| `GET` | `/user/account/unfollow/{id}` | `user.unfollowUser(id)` | `user.ts` | Unfollow |
| `POST` | `/user/password/update` | `user.changePassword()` | `user.ts` | Change password |
| `GET` | `/user/dashboard/view` | `user.getDashboard()` | `user.ts` | Dashboard |
| `GET` | `/user/view/ads` | `user.getUserAds()` | `user.ts` | User ads |
| `POST` | `/user/ad/store` | `user.createAd()` | `user.ts` | Create ad |
| `GET` | `/user/wallet/view` | `user.getWalletBalance()` | `user.ts` | Wallet balance |
| `POST` | `/user/wallet/add` | `user.addWalletMoney()` | `user.ts` | Add money |
| `GET` | `/user/subscriptions/view` | `user.getSubscriptionPlans()` | `user.ts` | Plans |
| `POST` | `/user/subscribe` | `user.subscribe()` | `user.ts` | Subscribe |
| `POST` | `/user/pages/reviews` | `user.reviewPage()` | `user.ts` | Review page |
| `POST` | `/user/marketplace/reviews` | `user.reviewProduct()` | `user.ts` | Review product |
| `GET` | `/user/leads/view` | `user.getLeads()` | `user.ts` | View leads |
| `POST` | `/user/leads/buy/{id}` | `user.buyLead(id)` | `user.ts` | Buy lead |
| `POST` | `/user/support/tickets` | `user.createTicket()` | `user.ts` | Create ticket |
| `GET` | `/user/support/tickets-view/` | `user.getTickets()` | `user.ts` | View tickets |

---

### 6.11 Search (Auth Required for Entity Search)

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `GET` | `/search-globally?q={q}` | `search.searchGlobally(q)` | `search.ts` | Global search (**public**) |
| `GET` | `/search/people/?q={q}` | `search.searchPeople(q)` | `search.ts` | Search people |
| `GET` | `/search/post/?q={q}` | `search.searchPosts(q)` | `search.ts` | Search posts |
| `GET` | `/search/video/?q={q}` | `search.searchVideos(q)` | `search.ts` | Search videos |
| `GET` | `/search/product/?q={q}` | `search.searchProducts(q)` | `search.ts` | Search products |
| `GET` | `/search/page/?q={q}` | `search.searchPages(q)` | `search.ts` | Search pages |
| `GET` | `/search/group/?q={q}` | `search.searchGroups(q)` | `search.ts` | Search groups |
| `GET` | `/search/event/?q={q}` | `search.searchEvents(q)` | `search.ts` | Search events |

---

### 6.12 Notifications (Auth Required)

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `GET` | `/all/notification` | `notifications.getNotifications()` | `notifications.ts` | List all |
| `GET` | `/mark/as/read/notification/{id}` | `notifications.markAsRead(id)` | `notifications.ts` | Mark read |
| `GET` | `/accept/friend/request/notification/{id}` | `notifications.acceptFriendRequest(id)` | `notifications.ts` | Accept friend |
| `GET` | `/decline/friend/request/notification/{id}` | `notifications.declineFriendRequest(id)` | `notifications.ts` | Decline friend |
| `GET` | `/accept/group/request/notification/{id}/{gid}` | `notifications.acceptGroupRequest(id, gid)` | `notifications.ts` | Accept group |
| `GET` | `/decline/group/request/notification/{id}/{gid}` | `notifications.declineGroupRequest(id, gid)` | `notifications.ts` | Decline group |
| `GET` | `/accept/event/request/notification/{id}/{eid}` | `notifications.acceptEventRequest(id, eid)` | `notifications.ts` | Accept event |
| `GET` | `/decline/event/request/notification/{id}/{eid}` | `notifications.declineEventRequest(id, eid)` | `notifications.ts` | Decline event |

---

### 6.13 Follow (Auth Required)

| Method | Endpoint | Frontend Function | File | Description |
|--------|----------|-------------------|------|-------------|
| `GET` | `/user/account/follow/{id}` | `user.followUser(id)` | `user.ts` | Follow user |
| `GET` | `/user/account/unfollow/{id}` | `user.unfollowUser(id)` | `user.ts` | Unfollow user |

---

## 7. Service-by-Service Mapping

This table shows which frontend service file calls which backend routes:

| Frontend File | Public Endpoints | Auth Endpoints |
|---------------|-----------------|----------------|
| **`home.ts`** | `GET /api/public/cities` | — |
| | `POST /api/public/cities/search` | — |
| | `GET /api/public/cities/{state_id}` | — |
| | `GET /api/public/areas/{city_id}` | — |
| | `GET /api/public/categories` | — |
| | `GET /api/public/categories/parent` | — |
| | `GET /api/public/categories/{city_id}` | — |
| | `GET /api/public/subcategories/{id}` | — |
| **`events.ts`** | `GET /api/public/events` | `POST /event/store` |
| | `GET /api/public/events/scroll` | `POST /event/update/{id}` |
| | `GET /api/public/events/{city_slug}` | `GET /event/delete?id={id}` |
| | `GET /api/public/events/category/{slug}` | `GET /event/going/{id}` |
| | `GET /api/public/events/{cat}-in-{city}` | `GET /event/notgoing/{id}` |
| | `GET /api/public/event-categories` | `GET /event/interested/{id}` |
| | `GET /api/public/event-categories/parent` | `GET /event/notinterested/{id}` |
| **`marketplace.ts`** | `GET /api/public/products` | `POST /product/store` |
| | `GET /api/public/products/scroll` | `POST /update/product/{id}` |
| | `GET /api/public/products/{city_slug}` | `GET /product/delete?id={id}` |
| | `GET /api/public/product-categories` | `GET /save/product/{id}` |
| | `GET /api/public/product-categories/parent` | `GET /unsave/product/{id}` |
| | `GET /api/public/brands` | `GET /product/saved/` |
| | | `POST /enquiry` |
| **`pages.ts`** | `GET /api/public/pages` | `POST /page/store` |
| | `GET /api/public/pages/scroll` | `POST /update/page/{id}` |
| | `GET /api/public/pages/{city_slug}` | `GET /pages/delete?id={id}` |
| | | `POST /claim-listing/submit` |
| | | `GET /page/suggestions?q={q}` |
| | | `GET /page/load_videos?page_id={id}` |
| **`groups.ts`** | `GET /api/public/groups` | `POST /group/store` |
| | `GET /api/public/groups/scroll` | `POST /update/group/{id}` |
| | `GET /api/public/groups/{city_slug}` | `GET /group/join/{id}` |
| | `GET /api/public/group-categories` | `GET /group/rjoin/{id}` |
| | `GET /api/public/group-categories/parent` | `POST /group/invites/sent` |
| | | `POST /report/group` |
| **`blog.ts`** | `GET /api/public/blogs` | `POST /blog/store` |
| | `GET /api/public/blogs/scroll` | `POST /update/blog/{id}` |
| | `GET /api/public/blogs/{city_slug}` | `GET /blog/delete?id={id}` |
| | `GET /api/public/blog-categories` | `GET /blog/search/?q={q}` |
| | `GET /api/public/blog-categories/parent` | — |
| **`chat.ts`** | — | `POST /chat/save` |
| | | `GET /chat/inbox/load/data/ajax/` |
| | | `GET /chat/inbox/read/message/ajax/` |
| | | `GET /chat/own/remove/{id}` |
| | | `GET /chat/profile/search/?q={q}` |
| | | `POST /my_message_react` |
| | | `POST /chat/with-us` |
| | | `POST /chat/send` |
| | | `GET /chat/fetch/{id}` |
| | | `POST /chat/marketplace/send` |
| | | `GET /chat/marketplace/fetch/{id}` |
| **`posts.ts`** | — | `POST /create_post` |
| | | `POST /edit_post/{id}` |
| | | `GET /load_post_by_scrolling` |
| | | `GET /view/single/post/{id}` |
| | | `GET /delete/my/post?id={id}` |
| | | `GET /post_comment` |
| | | `GET /load_post_comments` |
| | | `POST /my_react` |
| | | `POST /share/on/my/timeline` |
| | | `POST /share/on/group` |
| | | `POST /create_story` |
| | | `GET /stories/{offset}/{limit}` |
| | | `GET /story_details/{id}/{offset}/{limit}` |
| **`videos.ts`** | — | `GET /videos` |
| | | `GET /shorts` |
| | | `GET /load_videos_by_scrolling` |
| | | `GET /load_shorts_by_scrolling` |
| | | `POST /videos/sorts/store` |
| | | `GET /video/delete?id={id}` |
| | | `GET /save/video/short/{id}` |
| | | `GET /unsave/video/short/{id}` |
| | | `GET /saved/video/view` |
| **`user.ts`** | — | `GET /profile` |
| | | `GET /user/view-profile/{id}` |
| | | `POST /profile/update_profile/` |
| | | `POST /profile/upload_photo/{type}` |
| | | `POST /profile/about/{action?}` |
| | | `GET /profile/friends` |
| | | `GET /user/friend/{id}` |
| | | `GET /user/unfriend/{id}` |
| | | `POST /user/password/update` |
| | | `GET /user/dashboard/view` |
| | | `GET /user/view/ads` |
| | | `POST /user/ad/store` |
| | | `GET /user/wallet/view` |
| | | `POST /user/wallet/add` |
| | | `GET /user/subscriptions/view` |
| | | `POST /user/subscribe` |
| | | `POST /user/pages/reviews` |
| | | `POST /user/marketplace/reviews` |
| | | `GET /user/leads/view` |
| | | `POST /user/leads/buy/{id}` |
| | | `POST /user/support/tickets` |
| | | `GET /user/support/tickets-view/` |
| **`search.ts`** | `GET /search-globally?q={q}` | `GET /search/people/?q={q}` |
| | | `GET /search/post/?q={q}` |
| | | `GET /search/video/?q={q}` |
| | | `GET /search/product/?q={q}` |
| | | `GET /search/page/?q={q}` |
| | | `GET /search/group/?q={q}` |
| | | `GET /search/event/?q={q}` |
| **`notifications.ts`** | — | `GET /all/notification` |
| | | `GET /mark/as/read/notification/{id}` |
| | | `GET /accept/friend/request/notification/{id}` |
| | | `GET /decline/friend/request/notification/{id}` |
| | | `GET /accept/group/request/notification/{id}/{gid}` |
| | | `GET /decline/group/request/notification/{id}/{gid}` |
| | | `GET /accept/event/request/notification/{id}/{eid}` |
| | | `GET /decline/event/request/notification/{id}/{eid}` |
| **`auth.ts`** | — | `POST /register` |
| | | `POST /login` |
| | | `POST /logout` |
| | | `POST /forgot-password` |
| | | `POST /reset-password` |
| | | `GET /auth/google` (redirect) |
| | | `GET /auth/facebook` (redirect) |

---

## 8. Data Shapes

### City (from `/api/public/cities`)
```json
{
  "id": 1,
  "city_name": "Ahmedabad",
  "city_slug": "ahmedabad",
  "city_image": "https://images.unsplash.com/...",
  "city_state": "GJ",
  "state_id": 2,
  "city_about": "Ahmedabad is the largest city in Gujarat...",
  "city_lat": "23.0225",
  "city_lng": "72.5714"
}
```

### Event (from `/api/public/events`)
```json
{
  "id": 101,
  "event_name": "Tech Summit 2026",
  "event_slug": "tech-summit-2026",
  "short_description": "Annual tech conference",
  "event_date": "2026-09-15",
  "event_time": "10:00:00",
  "event_status": "upcoming",
  "venue": "Convention Center",
  "address": "SG Highway, Ahmedabad",
  "city_id": 1,
  "area_id": null,
  "category_id": 5,
  "cover_image": "https://...",
  "view": 0,
  "created_at": "2026-08-20T10:00:00.000000Z"
}
```

### Product (from `/api/public/products`)
```json
{
  "id": 201,
  "product_name": "Running Shoes",
  "product_slug": "running-shoes",
  "description": "Lightweight running shoes",
  "price": 2999,
  "condition": "new",
  "images": ["https://..."],
  "city_id": 1,
  "area_id": null,
  "category_id": 3,
  "brand_id": 1,
  "view": 0,
  "created_at": "2026-08-20T10:00:00.000000Z"
}
```

### Category (from `/api/public/categories`)
```json
{
  "id": 1,
  "category_name": "Restaurant",
  "parent": "Food & Dining"
}
```

### Area (from `/api/public/areas/{city_id}`)
```json
{
  "id": 1,
  "area_name": "Vastrapur",
  "city_id": 1
}
```

---

## Migration Roadmap

The following auth-protected endpoints currently use the Blade web routes and need to be migrated to proper JSON API routes:

| Priority | Module | What's Needed |
|----------|--------|---------------|
| 🔴 High | Auth | Token-based login/register under `/api/auth/*` |
| 🔴 High | Events CRUD | `POST /api/events`, `PUT /api/events/{id}`, `DELETE /api/events/{id}` |
| 🟡 Medium | Products CRUD | `POST /api/products`, `PUT /api/products/{id}`, `DELETE /api/products/{id}` |
| 🟡 Medium | Pages CRUD | `POST /api/pages`, `PUT /api/pages/{id}`, `DELETE /api/pages/{id}` |
| 🟡 Medium | Groups CRUD | `POST /api/groups`, `PUT /api/groups/{id}` |
| 🟡 Medium | Blogs CRUD | `POST /api/blogs`, `PUT /api/blogs/{id}`, `DELETE /api/blogs/{id}` |
| 🟢 Low | Chat | Real-time WebSocket or polling under `/api/chat/*` |
| 🟢 Low | Profile | `GET /api/user/profile`, `PUT /api/user/profile` |
| 🟢 Low | Notifications | `GET /api/notifications` |

---

## Database Configuration

### The Problem

The Laravel backend was originally built against a **PostgreSQL** database (`cityhangaround2`), but the `.env.example` defaults to `DB_CONNECTION=sqlite`. If your `.env` is configured for SQLite, the database won't have the `cities`, `pages`, `events`, etc. tables, and **every API call will return a 500 error**.

### `.env.example` Default

```
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

### Fix: Connect to PostgreSQL

Update `backend/.env` to point to your actual database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cityhangaround2
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### Fix: Use MySQL Instead

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cityhangaround2
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Fix: Use SQLite (Development)

If you want to use SQLite for local dev, you need to create the tables first:

```bash
cd backend
# Create the SQLite database file
touch database/database.sqlite

# Run migrations (requires migration files for all tables)
php artisan migrate
```

> ⚠️ **Note:** The project's migration files assume the tables already exist (they add indexes, not create tables). You'll need the original SQL dump or a full migration set to populate SQLite.

### Verify Your Connection

```bash
cd backend
php artisan tinker --execute="echo \App\Helpers\CityHelper::getActiveCities();"
```

If this returns an empty array `[]` instead of a SQL error, the connection is working.

---

## Error Handling Strategy

### Backend — `PublicController` Safe Query Wrapper

Every public API endpoint in `Api\PublicController` is wrapped in a `safeQuery()` helper that catches database exceptions and returns `[]` instead of a 500:

```php
// backend/app/Http/Controllers/Api/PublicController.php

private function safeQuery(callable $callback)
{
    try {
        return response()->json($callback());
    } catch (\Exception $e) {
        report($e);  // Log the error
        return response()->json([]);  // Return empty array, not 500
    }
}

// Usage:
public function cities()
{
    return $this->safeQuery(fn() => CityHelper::getActiveCities());
}
```

**Why this matters:** If the database is misconfigured, missing tables, or the connection drops, the frontend still gets a valid JSON response (`[]`) instead of crashing.

### Frontend — Service-Level Error Handling

Every service function that calls a public API endpoint has try/catch and returns an empty array on failure:

```typescript
// frontend/src/services/home.ts

export async function getCities() {
  try {
    return await api.get("/api/public/cities");
  } catch (error) {
    console.error("Error fetching cities:", error);
    return [];  // Graceful degradation
  }
}
```

### Frontend — Component-Level Error Handling

Components that fetch data on mount have their own try/catch:

```typescript
// frontend/src/components/layout/Navbar/PrimaryNavbar/index.tsx

useEffect(() => {
  const loadCities = async () => {
    try {
      const data = await getCities();
      setCities(data);
      const saved = localStorage.getItem(CITY_STORAGE_KEY);
      if (saved) {
        const match = data.find((c) => c.city_name === saved);
        if (match) setSelectedCity(match.city_name);
      }
    } catch (err) {
      console.error("Failed to load cities:", err);
      // Gracefully degrade — dropdown stays empty
    }
  };
  loadCities();
}, []);
```

### Error Flow Diagram

```
PrimaryNavbar mounts
    │
    ▼
getCities()  ──→  api.get("/api/public/cities")
    │                      │
    │                      ▼
    │              fetch() to localhost:8000
    │                      │
    │              ┌───────┴────────┐
    │              │                │
    │         ✅ 200 OK       ❌ 500 / Network Error
    │              │                │
    │              ▼                ▼
    │        Return JSON     safeQuery catches
    │        City[]           DB exception
    │              │                │
    │              │                ▼
    │              │          Return [] (empty)
    │              │                │
    │              ▼                ▼
    │        setCities(data)  setCities([])
    │              │                │
    │              ▼                ▼
    │        Dropdown works   Dropdown shows
    │        with cities      "No city found"
    └──────────────────────────────────
```

---

## Troubleshooting Guide

### Issue: "API failed (500)" in the browser console

**Cause:** The Laravel backend can't connect to the database, or the required tables don't exist.

**Fix:**
1. Check `backend/.env` — make sure `DB_CONNECTION` matches your database
2. Verify the database has the required tables
3. Test with: `php artisan route:list --path=api/public`
4. Check Laravel logs: `backend/storage/logs/laravel.log`

### Issue: "Failed to fetch" / CORS error in the browser console

**Cause:** The Laravel backend isn't running, or CORS is blocking the request.

**Fix:**
1. Start the Laravel backend: `cd backend && php artisan serve`
2. Verify CORS in `backend/config/cors.php` — should have `'allowed_origins' => ['*']`
3. Check `NEXT_PUBLIC_API_URL` in frontend `.env` — defaults to `http://localhost:8000`

### Issue: Cities dropdown is empty (no error)

**Cause:** The database is connected but has no cities with approved pages.

**Fix:**
1. Verify cities exist: `php artisan tinker` → `\App\Helpers\CityHelper::getActiveCities()->count()`
2. If count is 0, you need to add cities via the admin panel or database
3. The `cities` table needs `is_approved = 'Y'` and matching entries in the `pages` table with `item_status = 2`

### Issue: "SQLSTATE[42P01] Undefined table" error

**Cause:** SQLite database is empty — tables haven't been created.

**Fix:**
1. Connect to PostgreSQL/MySQL instead (see Database Configuration above)
2. Or run the full migration set against SQLite
3. The `safeQuery()` wrapper will return `[]` so the frontend doesn't crash

### Issue: City selection doesn't persist across page reloads

**Cause:** `localStorage` is cleared or the key doesn't match.

**Fix:**
1. Check browser DevTools → Application → Local Storage
2. Key should be `cha_selected_city`
3. Value should be the city name (e.g., "Ahmedabad")

---

## Environment Variables Reference

### Frontend (`frontend/.env`)

```env
# Backend API base URL (default: http://localhost:8000)
NEXT_PUBLIC_API_URL=http://localhost:8000
```

### Backend (`backend/.env`)

```env
# Database
DB_CONNECTION=pgsql          # pgsql, mysql, or sqlite
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cityhangaround2
DB_USERNAME=postgres
DB_PASSWORD=your_password

# App URL (for CORS, redirects)
APP_URL=http://localhost:8000
APP_DEBUG=true

# Session / Cache (must match DB_CONNECTION)
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Required Database Tables

The following tables are required for the public API endpoints to work:

| Table | Used By | Key Columns |
|-------|---------|-------------|
| `cities` | `/api/public/cities` | `id`, `city_name`, `city_slug`, `city_image`, `is_approved` |
| `areas` | `/api/public/areas/{id}` | `id`, `area_name`, `city_id` |
| `pages` | `/api/public/pages`, city/category joins | `id`, `city_id`, `item_status`, `item_slug` |
| `pagecategories` | `/api/public/categories` | `id`, `category_name`, `category_parent_id` |
| `page_category` | `/api/public/categories/{city_id}` | `page_id`, `category_id` |
| `events` | `/api/public/events` | `id`, `event_name`, `event_date`, `city_id`, `category_id`, `privacy` |
| `eventcategories` | `/api/public/event-categories` | `id`, `category_name`, `category_slug`, `category_parent_id` |
| `marketplaces` | `/api/public/products` | `id`, `page_id`, `product_name`, `product_slug` |
| `productcategories` | `/api/public/product-categories` | `id`, `product_category_name`, `product_category_parent_id` |
| `brands` | `/api/public/brands` | `id`, `name` |
| `groups` | `/api/public/groups` | `id`, `group_name`, `city_id`, `privacy` |
| `groupcategories` | `/api/public/group-categories` | `id`, `category_name`, `category_parent_id` |
| `blogs` | `/api/public/blogs` | `id`, `title`, `city_id` |
| `blogcategories` | `/api/public/blog-categories` | `id`, `category_name`, `category_parent_id` |
| `states` | `/api/public/cities/{state_id}` joins | `id`, `state_name`, `state_abbr` |

---

## Quick Start Checklist

- [ ] `backend/.env` has correct `DB_CONNECTION` and credentials
- [ ] Database has the required tables (see above)
- [ ] Laravel backend is running: `cd backend && php artisan serve`
- [ ] Frontend `.env` has `NEXT_PUBLIC_API_URL=http://localhost:8000`
- [ ] Frontend is running: `cd frontend && npm run dev`
- [ ] Test: visit `http://localhost:8000/api/public/cities` — should return JSON
- [ ] Test: visit `http://localhost:3000` — navbar should load cities in dropdown

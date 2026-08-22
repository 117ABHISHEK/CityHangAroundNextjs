# CityHangAround — API Reference

> Complete documentation of all backend routes, requests, and responses.

**Base URL:** `http://localhost:8000` (dev) / `https://cityhangaround.com` (prod)

**Auth:** Laravel Sanctum token-based auth. Include `Authorization: Bearer {token}` header for protected routes.

---

## Table of Contents

1. [Authentication](#1-authentication)
2. [Search](#2-search)
3. [User Dashboard](#3-user-dashboard)
4. [Events](#4-events)
5. [Marketplace / Deals](#5-marketplace--deals)
6. [Pages / Business Listings](#6-pages--business-listings)
7. [Groups](#7-groups)
8. [Blog](#8-blog)
9. [Videos](#9-videos)
10. [Chat](#10-chat)
11. [Posts & Timeline](#11-posts--timeline)
12. [Stories](#12-stories)
13. [Profile](#13-profile)
14. [Follow](#14-follow)
15. [Notifications](#15-notifications)
16. [Wallet & Payments](#16-wallet--payments)
17. [Subscriptions](#17-subscriptions)
18. [Reviews](#18-reviews)
19. [Leads](#19-leads)
20. [Tickets / Support](#20-tickets--support)
21. [Settings & Pages](#21-settings--pages)
22. [Sitemap](#22-sitemap)
23. [Admin Routes](#23-admin-routes)

---

## 1. Authentication

**Route file:** `routes/auth.php`

### Register
```
POST /register
```
| Field | Type | Required |
|-------|------|----------|
| name | string | ✅ |
| email | string | ✅ |
| password | string | ✅ |
| password_confirmation | string | ✅ |

**Response:** Redirects to dashboard on success.

### Login
```
POST /login
```
| Field | Type | Required |
|-------|------|----------|
| email | string | ✅ |
| password | string | ✅ |
| remember | boolean | ❌ |

**Response:** Redirects to dashboard on success.

### Logout
```
POST /logout
```
**Auth:** Required

### Forgot Password
```
POST /forgot-password
```
| Field | Type | Required |
|-------|------|----------|
| email | string | ✅ |

### Reset Password
```
POST /reset-password
```
| Field | Type | Required |
|-------|------|----------|
| token | string | ✅ |
| email | string | ✅ |
| password | string | ✅ |
| password_confirmation | string | ✅ |

### Social Login
```
GET /auth/google          → Redirects to Google OAuth
GET /auth/google/callback → Handles callback
GET /auth/facebook        → Redirects to Facebook OAuth
GET /auth/facebook/callback → Handles callback
```

---

## 2. Search

**Route file:** `routes/custom_routes.php`

### Global Search (Public)
```
GET /search?q={query}
```
**Auth:** Not required

**Response:** HTML page with search results across all content types.

### Search People
```
GET /search/people/?q={query}
```
**Auth:** Required

### Search Posts
```
GET /search/post/?q={query}
```
**Auth:** Required

### Search Videos
```
GET /search/video/?q={query}
```
**Auth:** Required

### Search Products
```
GET /search/product/?q={query}
```
**Auth:** Required

### Search Pages
```
GET /search/page/?q={query}
```
**Auth:** Required

### Search Groups
```
GET /search/group/?q={query}
```
**Auth:** Required

### Search Events
```
GET /search/event/?q={query}
```
**Auth:** Required

### Search Globally (AJAX)
```
GET /search-globally?q={query}
```
**Response:** JSON with results across all types.

### SEO Search
```
GET /search/{city_slug}/{category_slug}
GET /search/{category_slug}
```

---

## 3. User Dashboard

**Route file:** `routes/user.php`
**Auth:** All routes require `auth`, `user`, `verified`, `activity`

### Dashboard
```
GET /user/dashboard/view
```

### Ads
```
GET    /user/view/ads                    → List all ads
GET    /user/ad/create                   → Create ad form
POST   /user/ad/store                    → Store new ad
GET    /user/ad/edit/{id}                → Edit ad form
POST   /user/ad/update/{id}              → Update ad
GET    /user/ad-delete/{id}              → Delete ad
GET    /user/ad/ad_charge_by_daterange   → Get ad charges by date range
POST   /user/ad/payment_configuration/{id} → Configure payment
GET    /user/ad/payment_success/{id}     → Payment success page
```

### Ad Store Request
| Field | Type | Required |
|-------|------|----------|
| title | string | ✅ |
| description | string | ✅ |
| category_id | integer | ✅ |
| city_id | integer | ✅ |
| images[] | file | ✅ |

### Pages (User)
```
GET /user/pages/view/           → List user's pages
GET /user/page/create           → Create page form
POST /user/page/created/        → Store new page
```

### Products (User)
```
GET /user/products/view/        → List user's products
GET /user/product/enquiry-view/ → View product enquiries
```

### Events (User)
```
GET /user/events/view/          → List user's events
```

### Incomplete Listings
```
GET /user/listings/incomplete   → Show incomplete listings
```

---

## 4. Events

**Route file:** `routes/custom_routes.php`
**Controller:** `EventController`

### Public Event Routes (No Auth)
```
GET /event/all                                    → All events listing
GET /event/{city_slug}                            → Events in city
GET /event/category/{category_slug}               → Events by category
GET /event/{category_slug}-in-{city_slug}         → Events by category in city
GET /event/{city_slug}/{area_slug}                → Events in city area
GET /event/{city_slug}/{category_slug}-in-{area_slug} → Events by category in city area
GET /event/{city_slug}/{area_slug}/{category_slug}/{event_slug} → Single event
```

### Event CRUD (Auth Required)
```
GET    /events/create              → Create event form
POST   /event/store                → Store new event
GET    /events/edit/{id}           → Edit event form
POST   /event/update/{id}          → Update event
GET    /event/delete               → Delete event
GET    /event/view/{id}            → View single event
GET    /user/event                 → User's events
```

### Event Store Request
| Field | Type | Required |
|-------|------|----------|
| event_name | string | ✅ |
| parent_category_id | integer | ✅ |
| category_id | integer | ✅ |
| tags[] | string[] | ❌ |
| short_description | string | ✅ |
| cover_image | file | ✅ |
| event_status | enum | ✅ (upcoming/live/completed/cancelled) |
| event_date | date | ✅ |
| event_time | time | ✅ |
| venue | string | ✅ |
| city_id | integer | ✅ |
| area_id | integer | ❌ |
| address | string | ✅ |

### Event RSVP
```
GET /event/going/{id}            → Mark as going
GET /event/notgoing/{id}         → Mark as not going
GET /event/interested/{id}       → Mark as interested
GET /event/notinterested/{id}    → Mark as not interested
```

### Event Invites
```
GET  /event/invite/{friend_id}/{requester_id}/{event_id} → Invite friend
POST /event/invites/sent                              → Send invitations
GET  /search_user_for_event_inviting?q={query}        → Search users to invite
```

### Event Categories (AJAX)
```
GET  /ajax/parent/event/catgories   → Parent categories
GET  /ajax/event/catgories          → All categories
GET  /event-categories-autocomplete-ajax?q={query} → Autocomplete
POST /ajax/store/event/categories   → Create category from select2
GET  /ajax/eventareas/{city_id}     → Areas by city
```

### Event Infinite Scroll
```
GET /load_event_by_scrolling?offset={offset}&limit={limit}
```

### Event Share
```
GET /share/event/?id={event_id}
```

---

## 5. Marketplace / Deals

**Route file:** `routes/custom_routes.php`
**Controller:** `MarketplaceController`

### Public Product Routes
```
GET /deals/                                              → All products
GET /deals/category/{category_slug}                      → Products by category
GET /deals/{city_slug}                                   → Products in city
GET /deals/{category_slug}-in-{city_slug}                → Products by category in city
GET /deals/{city_slug}/{area_slug}                       → Products in city area
GET /deals/{city_slug}/{category_slug}-in-{area_slug}    → Products by category in city area
GET /deals/{city_slug}/{area_slug}/{category_slug}/{item_slug}/{product_category_slug}/{product_slug} → Single product
GET /deals/product/filter/{category}/{max}/{min}/{brand}/{location} → Filtered products
```

### Product CRUD (Auth Required)
```
GET  /products/create              → Create product form
POST /product/store                → Store new product
GET  /products/edit/{product_id}   → Edit product form
POST /update/product/{id}          → Update product
GET  /product/delete               → Delete product
GET  /user/product                 → User's products
```

### Product Store Request
| Field | Type | Required |
|-------|------|----------|
| product_name | string | ✅ |
| parent_category_id | integer | ✅ |
| category_id | integer | ✅ |
| brand_id | integer | ❌ |
| description | string | ✅ |
| price | decimal | ✅ |
| images[] | file | ✅ |
| city_id | integer | ✅ |
| area_id | integer | ❌ |
| condition | enum | ✅ (new/used) |

### Product Actions
```
GET /save/product/{id}        → Save for later
GET /unsave/product/{id}      → Unsave
GET /product/saved/           → Saved products
```

### Product Categories (AJAX)
```
GET  /ajax/productparentcatgories     → Parent categories
GET  /ajax/produ/ctcatgories          → All categories
GET  /product-categories-autocomplete-ajax?q={query} → Autocomplete
POST /ajax/storeproductcategories     → Create category from select2
POST /ajax/storeparentcategories      → Create parent category
GET  /get-category-names              → Category names
GET  /product/suggestions?q={query}   → Product suggestions
GET  /product/details?id={id}         → Product details
```

### Product Enquiry (Public)
```
POST /enquiry
```
| Field | Type | Required |
|-------|------|----------|
| product_id | integer | ✅ |
| name | string | ✅ |
| email | string | ✅ |
| phone | string | ✅ |
| message | string | ✅ |

### Product Infinite Scroll
```
GET /load_product_by_scrolling?offset={offset}&limit={limit}
```

---

## 6. Pages / Business Listings

**Route file:** `routes/custom_routes.php`
**Controller:** `PageController`

### Public Page Routes
```
GET /pages                                                    → All pages
GET /{city_slug}                                              → Pages in city
GET /category/{category_slug}                                 → Pages by category
GET /{category_slug}-in-{city_slug}                           → Pages by category in city
GET /{city_slug}/{area_slug}                                  → Pages in city area
GET /{city_slug}/{category_slug}-in-{area_slug}               → Pages by category in city area
GET /{city_slug}/{area_slug}/{category_slug}/{item_slug}      → Single page
GET /page/{id}                                                → Redirect to SEO URL
```

### Page Sub-routes
```
GET /{city_slug}/{area_slug}/{category_slug}/{item_slug}/blogs    → Page blogs
GET /{city_slug}/{area_slug}/{category_slug}/{item_slug}/events   → Page events
GET /{city_slug}/{area_slug}/{category_slug}/{item_slug}/groups   → Page groups
GET /{city_slug}/{area_slug}/{category_slug}/{item_slug}/deals    → Page deals
GET /{city_slug}/{area_slug}/{category_slug}/{item_slug}/about    → Page about
GET /{city_slug}/{area_slug}/{category_slug}/{item_slug}/photo    → Page photos
GET /{city_slug}/{area_slug}/{category_slug}/{item_slug}/videos   → Page videos
```

### Page CRUD (Auth Required)
```
GET  /pages/create                → Create page form
POST /page/store                  → Store new page
GET  /pages/edit/{id}             → Edit page form
POST /update/page/{id}            → Update page
GET  /pages/delete                → Delete page
POST /update/coverphoto/page/{id} → Update cover photo
POST /update/info/page/{id}       → Update page info
GET  /pages/user                  → User's pages
GET  /pages/suggested             → Suggested pages
GET  /pages/joined                → Joined pages
GET  /pages/incomplete            → Incomplete pages
```

### Page Store Request
| Field | Type | Required |
|-------|------|----------|
| page_name | string | ✅ |
| category_id | integer | ✅ |
| city_id | integer | ✅ |
| area_id | integer | ❌ |
| description | string | ✅ |
| phone | string | ❌ |
| email | string | ❌ |
| website | string | ❌ |
| address | string | ❌ |
| cover_image | file | ❌ |
| images[] | file[] | ❌ |

### Page Actions
```
GET /page/like/{id}       → Like page
GET /page/dislike/{id}    → Dislike page
POST /claim-listing/submit → Claim a listing
POST /page/save-draft     → Save incomplete listing
```

### Page Categories (AJAX)
```
GET  /categories-autocomplete-ajax?q={query}   → Autocomplete
GET  /ajax/catgories                           → All categories
GET  /ajax/parentcatgories                     → Parent categories
GET  /ajax/categories/{city_id}                → Categories by city
POST /ajax/storecategories                     → Create category
POST /ajax/storecities                         → Create city
POST /ajax/storeareas                          → Create area
GET  /ajax/cities/{state_id}                   → Cities by state
GET  /ajax/areas/{city_id}                     → Areas by city
GET  /ajax/itemareas/{city_id}                 → Areas by city (items)
GET  /ajax/cities                              → All cities
GET  /ajax/subcategories/{category_id}         → Subcategories
GET  /categories/search?q={query}              → Search categories
GET  /categories/selected                      → Selected categories
GET  /page/suggestions?q={query}               → Page suggestions
GET  /page/check-match?name={name}             → Check duplicate
```

### Page Infinite Scroll
```
GET /load_page_by_scrolling?offset={offset}&limit={limit}
```

### Page Videos
```
GET /page/load_videos?page_id={id}
```

---

## 7. Groups

**Route file:** `routes/custom_routes.php`
**Controller:** `GroupController`

### Public Group Routes
```
GET /groups                                         → All groups
GET /group/{city_slug}                              → Groups in city
GET /group/category/{category_slug}                 → Groups by category
GET /group/{category_slug}-in-{city_slug}           → Groups by category in city
GET /group/{city_slug}/{area_slug}                  → Groups in city area
GET /group-view/{category_slug}/{group_slug}/{city_slug?}/{area_slug?} → Single group
```

### Group CRUD (Auth Required)
```
GET  /groups/create              → Create group form
POST /group/store                → Store new group
GET  /groups/edit/{id}           → Edit group form
POST /update/group/{id}          → Update group
POST /update/coverphoto/group/{id} → Update cover photo
GET  /user/group                 → User's groups
```

### Group Store Request
| Field | Type | Required |
|-------|------|----------|
| group_name | string | ✅ |
| category_id | integer | ✅ |
| city_id | integer | ✅ |
| description | string | ✅ |
| cover_image | file | ❌ |
| privacy | enum | ✅ (public/private) |

### Group Actions
```
GET /group/join/{id}            → Join group
GET /group/rjoin/{id}           → Request to join
GET /group/peopel/info/{id}     → Group members info
GET /group/photo/view/{id}      → Group photos
GET /all/peopel/group/view/{id} → All group members
GET /group/event/view/{id}      → Group events
GET /group/user/create          → Groups created by user
GET /group/user/joined          → Groups joined by user
POST /group/invites/sent        → Send group invites
POST /album/add/image           → Add album image
POST /report/group              → Report group
POST /discussion/save           → Create discussion post
```

### Group Categories (AJAX)
```
GET  /ajax/parent/group/catgories   → Parent categories
GET  /ajax/group/catgories          → All categories
POST /ajax/store/group/categories   → Create category
GET  /group-categories-autocomplete-ajax?q={query} → Autocomplete
GET  /ajax/groupareas/{city_id}     → Areas by city
```

### Group Infinite Scroll
```
GET /load_groups_by_scrolling?offset={offset}&limit={limit}
```

---

## 8. Blog

**Route file:** `routes/custom_routes.php`
**Controller:** `BlogController`

### Public Blog Routes
```
GET /blog/all                                         → All blogs
GET /blog/{city_slug}                                 → Blogs in city
GET /blog/category/{category_slug}                    → Blogs by category
GET /blog/{category_slug}-in-{city_slug}              → Blogs by category in city
GET /blog/{city_slug}/{area_slug}                     → Blogs in city area
GET /blog/{city_slug}/{category_slug}-in-{area_slug}  → Blogs by category in city area
GET /blog-view/{category_slug}/{blog_slug}/{city_slug?}/{area_slug?} → Single blog
```

### Blog CRUD (Auth Required)
```
GET  /create/blog              → Create blog form
POST /blog/store               → Store new blog
GET  /edit/blog/{id}           → Edit blog form
POST /update/blog/{id}         → Update blog
GET  /blog/delete              → Delete blog
GET  /my/blog                  → User's blogs
GET  /blog/search/?q={query}   → Search blogs
```

### Blog Store Request
| Field | Type | Required |
|-------|------|----------|
| title | string | ✅ |
| category_id | integer | ✅ |
| content | text | ✅ |
| featured_image | file | ❌ |
| tags[] | string[] | ❌ |

### Blog Categories (AJAX)
```
GET  /ajax/blog/parentcatgories           → Parent categories
GET  /ajax/blog/catgories                 → All categories
POST /ajax/storeblogcategories            → Create category
GET  /blog-categories-autocomplete-ajax?q={query} → Autocomplete
GET  /ajax/get-pages?page_id={id}         → Get pages for blog
```

### Blog Infinite Scroll
```
GET /load_blog_by_scrolling?offset={offset}&limit={limit}
```

---

## 9. Videos

**Route file:** `routes/custom_routes.php`
**Controller:** `VideoController`

```
GET  /videos                              → All videos
POST /videos/sorts/store                  → Store video sort
GET  /video/details/info/{id}             → Video details
GET  /shorts                              → All shorts
GET  /save/video/short/{id}               → Save short
GET  /unsave/video/short/{id}             → Unsave short
GET  /saved/video/view                    → Saved videos
GET  /video/delete                        → Delete video
GET  /load_videos_by_scrolling            → Infinite scroll videos
GET  /load_shorts_by_scrolling            → Infinite scroll shorts
```

### Video Store Request
| Field | Type | Required |
|-------|------|----------|
| title | string | ✅ |
| video_url | string | ✅ |
| description | string | ❌ |
| type | enum | ✅ (video/short) |

---

## 10. Chat

**Route file:** `routes/custom_routes.php`
**Controller:** `ChatController`

### User Chat
```
GET  /chat/inbox/{receiver}/{product?}/           → Open chat
POST /chat/save                                   → Send message
GET  /chat/own/remove/{id}                        → Delete message
POST /my_message_react                            → React to message
GET  /chat/profile/search/?q={query}              → Search profiles
GET  /chat/inbox/load/data/ajax/                  → Load chat data
GET  /chat/inbox/read/message/ajax/               → Mark messages read
POST /chat/with-us                                → Chat with support
```

### Chat Message Request
| Field | Type | Required |
|-------|------|----------|
| receiver_id | integer | ✅ |
| message | string | ✅ |
| product_id | integer | ❌ |

### Page Chat
```
GET  /chat/page/{page}                    → Open page chat
GET  /chat/conversation/{conversation}    → View conversation
POST /chat/send                           → Send message
GET  /chat/fetch/{id}                     → Fetch messages
```

### Marketplace Chat
```
GET  /chat/marketplace/{marketplace}      → Open marketplace chat
POST /chat/marketplace/send               → Send message
GET  /chat/marketplace/fetch/{id}         → Fetch messages
```

### User-side Chat (Auth Required)
```
GET  /user/chat/conversations                    → List conversations
GET  /user/chat-conversations/{id}               → View conversation
POST /user/chat/conversations/{id}/message       → Send message
GET  /user/chat/{id}                             → Fetch messages

GET  /user/market-chat/conversations             → List marketplace conversations
GET  /user/market-chat-conversations/{id}        → View marketplace conversation
POST /user/market-chat/conversations/{id}/message → Send marketplace message
GET  /user/market-chat/{id}                      → Fetch marketplace messages
```

---

## 11. Posts & Timeline

**Route file:** `routes/web.php`
**Controller:** `MainController`

```
GET  /home                          → Timeline feed
POST /create_post                   → Create post
GET  /edit_post_form/{id}          → Edit post form
POST /edit_post/{id}               → Update post
GET  /load_post_by_scrolling       → Infinite scroll posts
POST /my_react                      → React to post
GET  /my_comment_react             → React to comment
GET  /post_comment                 → Add comment
GET  /load_post_comments           → Load comments
GET  /search_friends_for_tagging?q={query} → Search friends to tag
GET  /view/single/post/{id}        → View single post
GET  /preview_post                 → Preview post
GET  /post_comment_count           → Comment count
POST /post/report/save/            → Report post
GET  /delete/my/post               → Delete post
GET  /comment/delete               → Delete comment
POST /share/on/group               → Share post to group
POST /share/on/my/timeline         → Share to timeline
GET  /media/file/delete/{id}       → Delete media file
GET  /custom/shared/post/view/{id} → View shared post
```

### Create Post Request
| Field | Type | Required |
|-------|------|----------|
| content | text | ✅ |
| images[] | file[] | ❌ |
| privacy | enum | ❌ (public/friends/private) |
| group_id | integer | ❌ |

---

## 12. Stories

**Route file:** `routes/web.php`
**Controller:** `StoryController`

```
POST /create_story                           → Create story
GET  /stories/{offset?}/{limit?}            → Get stories
GET  /story_details/{story_id}/{offset?}/{limit?} → Story details
GET  /single_story_details/{story_id}       → Single story details
```

---

## 13. Profile

**Route file:** `routes/web.php`
**Controller:** `Profile`

```
GET  /profile                                → View profile
GET  /profile/load_post_by_scrolling         → Load posts
GET  /profile/friends                        → Friends list
GET  /profile/photos                         → Photos
GET  /profile/load_photos                    → Load more photos
POST /profile/album/{action_type?}           → Album actions
GET  /profile/load_albums                    → Load albums
GET  /profile/videos                         → Videos
GET  /profile/load_videos                    → Load videos
POST /profile/upload_video                   → Upload video
GET  /profile/page                           → Pages
GET  /profile/blogs                          → Blogs
GET  /profile/events                         → Events
GET  /profile/groups                         → Groups
GET  /profile/products                       → Products
GET  /profile/load_my_friends                → Load friends
GET  /profile/load_my_friend_requests        → Load friend requests
POST /profile/accept_friend_request          → Accept friend request
GET  /profile/delete_friend_request          → Delete friend request
POST /profile/about/{action_type?}           → Update about
POST /profile/upload_photo/{photo_type}      → Upload photo
GET  /profile/update_profile                 → Load edit profile
POST /profile/update_profile/                → Update profile
POST /profile/track_view                     → Track profile view
```

### Profile Update Request
| Field | Type | Required |
|-------|------|----------|
| name | string | ❌ |
| email | string | ❌ |
| phone | string | ❌ |
| gender | enum | ❌ |
| dob | date | ❌ |
| bio | string | ❌ |
| avatar | file | ❌ |
| cover_photo | file | ❌ |

### View Other Profile
```
GET /user/view-profile/{id}           → View profile data
GET /user/friend/{id}                 → Add friend
GET /user/unfriend/{id}              → Unfriend
GET /user/friends/{id}               → User's friends
GET /user/photos/{id}                → User's photos
GET /user/videos/{id}                → User's videos
GET /user/password/change            → Change password form
POST /user/password/update           → Update password
GET  /video/delete/{id}              → Delete media file
GET  /download/media/file/{id}       → Download media file
GET  /download/media/file/image/{id} → Download image
```

---

## 14. Follow

**Route file:** `routes/custom_routes.php`
**Controller:** `FollowController`

```
GET /user/account/follow/{id}     → Follow user
GET /user/account/unfollow/{id}   → Unfollow user
```

---

## 15. Notifications

**Route file:** `routes/custom_routes.php`
**Controller:** `NotificationController`

```
GET /all/notification                                         → All notifications
GET /accept/friend/request/notification/{id}                  → Accept friend request
GET /decline/friend/request/notification/{id}                 → Decline friend request
GET /accept/group/request/notification/{id}/{group_id}        → Accept group request
GET /decline/group/request/notification/{id}/{group_id}       → Decline group request
GET /accept/event/request/notification/{id}/{event_id}        → Accept event request
GET /decline/event/request/notification/{id}/{event_id}       → Decline event request
GET /mark/as/read/notification/{id}                           → Mark as read
```

---

## 16. Wallet & Payments

**Route file:** `routes/user.php`, `routes/payment.php`
**Controller:** `WalletController`, `PaymentController`, `PaymentHistory`

### Wallet
```
GET  /user/wallet/view           → View wallet balance
POST /user/wallet/add            → Add money to wallet
POST /user/wallet/use            → Use wallet balance
POST /wallet/payment-success     → Payment success callback
```

### Payment Gateway
```
GET  /payment                                        → Payment page
GET  /payment/show_payment_gateway_by_ajax/{identifier} → Get payment gateway
GET  /payment/success/{identifier}                   → Payment success
GET  /payment/create/{identifier}                    → Create payment
```

### Payment History
```
GET /user/payment-histories    → User payment history
GET /admin/payment-histories   → Admin payment history
```

---

## 17. Subscriptions

**Route file:** `routes/user.php`, `routes/custom_routes.php`
**Controller:** `SubscriptionController`

### User Subscriptions
```
GET  /user/subscriptions/view                → View plans
POST /user/subscribe                         → Subscribe to plan
POST /user/subscription/payment-success      → Payment success
GET  /user/subscribe/{id}                    → Subscribe to specific plan
GET  /user/subscribe-free/{id}               → Subscribe to free plan
POST /user/create-razorpay-order             → Create Razorpay order
POST /user/wallet/payment                    → Pay with wallet
GET  /user/transactions/report               → Transaction report
```

### Subscribe Request
| Field | Type | Required |
|-------|------|----------|
| plan_id | integer | ✅ |
| payment_method | enum | ✅ (razorpay/wallet/free) |

---

## 18. Reviews

**Route file:** `routes/user.php`
**Controller:** `ReviewController`

```
POST /user/marketplace/reviews              → Review marketplace product
POST /user/pages/reviews                    → Review a page
POST /user/blog-reviews/reviews             → Review a blog
GET  /user/marketplace/{id}                 → Load more marketplace reviews
GET  /user/blog-reviews/{id}               → Load more blog reviews
GET  /user/pages-reviews/{id}             → Load more page reviews
```

### Review Store Request
| Field | Type | Required |
|-------|------|----------|
| reviewable_id | integer | ✅ |
| reviewable_type | string | ✅ (product/page/blog) |
| rating | integer | ✅ (1-5) |
| comment | string | ✅ |

---

## 19. Leads

**Route file:** `routes/user.php`
**Controller:** `LeadController`

```
GET  /user/leads/view                  → View available leads
POST /user/leads/buy/{id}             → Buy lead
GET  /user/leads-view/{id}            → View lead details
POST /leads/buy/wallet                 → Buy lead with wallet
POST /leads/buy/online                 → Buy lead online
GET|POST /user/payment/success         → Payment success callback
GET  /user/lead/purchase-report        → Purchase report
```

### Lead Fetch Data
```
GET /user/fetch/states               → Fetch states
GET /user/fetch/cities               → Fetch cities
GET /user/fetch/areas                → Fetch areas
GET /user/fetch/categories           → Fetch categories
GET /user/fetch/get-areas-by-city    → Areas by city
```

---

## 20. Tickets / Support

**Route file:** `routes/user.php`
**Controller:** `TicketController`

```
GET  /user/support/tickets-view/           → View tickets
GET  /user/support/tickets-create          → Create ticket form
POST /user/support/tickets                 → Store ticket
GET  /user/support-tickets/{ticket}        → View ticket
GET  /user/support/tickets/{ticket}/edit   → Edit ticket form
PUT  /user/tickets/{ticket}                → Update ticket
DELETE /admin/tickets/{ticket}             → Delete ticket
```

### Ticket Store Request
| Field | Type | Required |
|-------|------|----------|
| subject | string | ✅ |
| message | string | ✅ |
| priority | enum | ❌ (low/medium/high) |
| attachment | file | ❌ |

---

## 21. Settings & Pages

**Route file:** `routes/custom_routes.php`
**Controller:** `SettingController`

### Public Settings
```
GET /about/page/view/       → About page
GET /policy/page/view/      → Privacy policy
GET /term/condition/view/   → Terms & conditions
GET /contact/us/view/       → Contact page
POST /contact/us/send/      → Submit contact form
```

### Contact Form Request
| Field | Type | Required |
|-------|------|----------|
| name | string | ✅ |
| email | string | ✅ |
| subject | string | ✅ |
| message | string | ✅ |

### Custom Pages
```
GET  /pages/custom/{slug}                 → View custom page
GET  /admin/custom/pages                  → List custom pages (admin)
GET  /admin/custom-pages/create           → Create custom page (admin)
POST /admin/custom/pages/store            → Store custom page (admin)
GET  /admin/custom/pages/edit/{id}        → Edit custom page (admin)
POST /admin/custom/pages/update/{id}      → Update custom page (admin)
DELETE /admin/custom/pages/delete/{id}    → Delete custom page (admin)
POST /admin/custom_pages/toggle/{id}      → Toggle status (admin)
```

---

## 22. Sitemap

```
GET /sitemap                          → Sitemap index
GET /sitemap.xml                      → Sitemap index (alias)
GET /sitemap/pages-{part}.xml         → Pages sitemap (paginated)
GET /sitemap/events.xml               → Events sitemap
GET /sitemap/marketplace.xml          → Marketplace sitemap
GET /sitemap/blogs.xml                → Blogs sitemap
GET /sitemap/videos.xml               → Videos sitemap
GET /sitemap/posts.xml                → Posts sitemap
GET /sitemap/groups.xml               → Groups sitemap
GET /sitemap/static.xml               → Static pages sitemap
```

---

## 23. Admin Routes

**Auth:** All admin routes require `auth`, `verified`, `admin`

### Admin Dashboard
```
GET /admin/dashboard/
```

### Admin Users
```
GET  /admin/users/                    → List users
POST /admin/users/data                → Server-side user data (DataTable)
GET  /admin/user/add                  → Add user form
POST /admin/user/store                → Store user
GET  /admin/user-edit/{id}           → Edit user form
POST /admin/user/update/{id}         → Update user
GET  /admin/user/delete/{id}         → Delete user
GET  /admin/user-status/{id}         → Toggle user status
```

### Admin Pages
```
GET  /admin/page                       → List pages
GET  /admin/pending-page               → Pending pages
GET  /admin/page/create                → Create page
GET  /admin/page-edit/{id}            → Edit page
POST /admin/page/created/              → Store page
POST /admin/page/updated/{id}         → Update page
GET  /admin/page/sync                  → Sync pages
POST /admin/page/bulk-action           → Bulk action on pages
POST /admin/page/toggle-verified       → Toggle verified status
GET  /admin/page/search?q={query}      → Search pages
GET  /admin/pages/search?q={query}     → Search pages (alias)
GET  /admin/pending-pages/search       → Search pending pages
```

### Admin Categories (Page)
```
GET  /admin/page/category-view/           → List categories
GET  /admin/page/category-create/         → Create form
POST /admin/page/category-save/           → Store category
GET  /admin/page/category/edit/{id}      → Edit form
POST /admin/page/category/update/{id}    → Update category
GET  /admin/page/category/delete/{id}    → Delete category
GET  /admin/page/search-category?q={query} → Search categories
GET  /admin/category/check-exists        → Check if category exists
```

### Admin Events
```
GET  /admin/event-view/                  → List events
GET  /admin/event/upcoming/              → Upcoming events
GET  /admin/event/previous/              → Previous events
GET  /admin/event/create/                → Create event
POST /admin/event/store/                 → Store event
GET  /admin/event/edit/{id}             → Edit event
POST /admin/event/update/{id}           → Update event
GET  /admin/event/category-view/        → List event categories
GET  /admin/event/category-create/      → Create event category
POST /admin/event/category-save/        → Store event category
GET  /admin/event/category/edit/{id}   → Edit event category
POST /admin/event/category/update/{id} → Update event category
GET  /admin/event/category/delete/{id} → Delete event category
```

### Admin Products
```
GET  /admin/product                       → List products
GET  /admin/product/create                → Create product
GET  /admin/product-edit/{id}            → Edit product
POST /admin/product/created/              → Store product
POST /admin/product/updated/{id}         → Update product
GET  /admin/product-listing-search        → Search products
GET  /admin/products/ajax                 → AJAX product data
GET  /admin/product-categories            → Product categories
GET  /admin/product-categories/ajax       → AJAX product categories
```

### Admin Product Categories
```
GET  /admin/product/category-view/           → List categories
GET  /admin/product/category/create/         → Create form
POST /admin/product/category/save/           → Store category
GET  /admin/product/category/edit/{id}      → Edit form
POST /admin/product/category/update/{id}    → Update category
GET  /admin/product/category/delete/{id}    → Delete category
GET  /admin/product/enquiry-view/            → Product enquiries
```

### Admin Product Brands
```
GET  /admin/product/brand-view/           → List brands
GET  /admin/product/brand-create/         → Create form
POST /admin/product/brand/save/           → Store brand
GET  /admin/product/brand/edit/{id}      → Edit form
POST /admin/product/brand/update/{id}    → Update brand
GET  /admin/product/brand/delete/{id}    → Delete brand
```

### Admin Blogs
```
GET  /admin/blog                        → List blogs
GET  /admin/blog/create                 → Create blog
GET  /admin/blog-edit/{id}             → Edit blog
POST /admin/blog/created/               → Store blog
POST /admin/blog/updated/{id}          → Update blog
GET  /admin/blog-listing-search         → Search blogs
GET  /admin/blog/category-view/         → List blog categories
GET  /admin/blog/category-create/       → Create blog category
POST /admin/blog/category/save/         → Store blog category
GET  /admin/blog/category/edit/{id}    → Edit blog category
POST /admin/blog/category/update/{id}  → Update blog category
GET  /admin/blog/category/delete/{id}  → Delete blog category
```

### Admin Groups
```
GET  /admin/group                       → List groups
GET  /admin/group/create                → Create group
GET  /admin/group-edit/{id}            → Edit group
POST /admin/group/created/              → Store group
POST /admin/group/updated/{id}         → Update group
GET  /admin/group-categories            → Group categories
GET  /admin/group-categories/create     → Create group category
POST /admin/group-categories/store      → Store group category
GET  /admin/group-categories/edit/{id} → Edit group category
PUT  /admin/group-categories/update/{id} → Update group category
DELETE /admin/group-categories/delete/{id} → Delete group category
```

### Admin Videos
```
GET  /admin/videos                          → List videos
POST /admin/video/approve                   → Approve video
POST /admin/video/approve-multiple          → Approve multiple
POST /admin/video/approve-all               → Approve all
```

### Admin Chat
```
GET  /admin/chat/conversations              → List page chat conversations
GET  /admin/chat-conversations/{id}         → View conversation
POST /admin/chat/conversations/{id}/message → Send message
GET  /admin/chat/{id}                       → Fetch messages

GET  /admin/market-chat/conversations              → List marketplace conversations
GET  /admin/market-chat-conversations/{id}         → View conversation
POST /admin/market-chat/conversations/{id}/message → Send message
GET  /admin/market-chat/{id}                       → Fetch messages
```

### Admin Approvals
```
GET  /admin/manage/approval              → Manage approvals
POST /admin/manage/toggle/{id}           → Toggle service status
GET  /admin/claims/search                → Search claim listings
GET  /admin/claim-listings               → List claim listings
POST /admin/claim/update-status          → Update claim status
GET  /admin/reports                      → List reports
```

### Admin Tickets
```
GET  /admin/tickets              → List tickets
GET  /admin/tickets/{ticket}     → View ticket
PUT  /admin/tickets/{ticket}     → Update ticket
```

### Admin Settings
```
GET  /admin/about-page/data/                        → About page data
POST /admin/about-page/data/update/{id}             → Update about page
POST /admin/privacy/page/data/update/{id}           → Update privacy page
POST /admin/term/page/data/update/{id}              → Update terms page
GET  /admin/reported/post/                          → Reported posts
GET  /admin/reported/post/delete/{id}               → Delete reported post
GET  /admin/live-video-setting/view                 → Live video settings
POST /admin/live-video-setting/update               → Update live video
GET  /admin/smtp-setting/view/                      → SMTP settings
POST /admin/smtp-setting/save/{id}                  → Save SMTP settings
GET  /admin/system-setting/view/                    → System settings
POST /admin/system-setting/save/                    → Save system settings
POST /admin/system-setting/logo/save/               → Save logo
GET  /admin/settings/payment                        → Payment settings
GET  /admin/payment-gateway/create                  → Create payment gateway
POST /admin/payment-gateway/store                   → Store payment gateway
GET  /admin/payment_gateway/edit/{id}               → Edit payment gateway
POST /admin/payment_gateway/update/{id}             → Update payment gateway
GET  /admin/payment_gateway/status/{id}             → Toggle gateway status
GET  /admin/payment_gateway/environment/{id}        → Toggle environment
GET  /admin/settings/about                          → About settings
GET  /admin/settings/amazon_s3                      → Amazon S3 settings
POST /admin/settings/amazon_s3/update               → Update S3 settings
```

### Admin Profile
```
GET  /admin/change/password         → Change admin password
GET  /admin/profile/                → Admin profile
POST /admin/profile/update/         → Update admin profile
```

### Admin States/Cities/Areas/Countries
```
GET    /states                        → List states
GET    /states/create                 → Create state form
POST   /states                        → Store state
GET    /states/edit/{id}             → Edit state form
PUT    /states/update/{id}           → Update state
DELETE /states/{id}                  → Delete state

GET    /cities                        → List cities
GET    /cities/create                 → Create city form
POST   /cities                        → Store city
GET    /cities/{city}                 → View city
GET    /cities/{city}/edit           → Edit city form
PUT    /cities/{city}                 → Update city
DELETE /cities/{city}                 → Delete city

GET    /countries                     → List countries
GET    /countries/create              → Create country form
POST   /countries                     → Store country
GET    /countries/{country}           → View country
GET    /countries/{country}/edit      → Edit country form
PUT    /countries/{country}           → Update country
DELETE /countries/{country}           → Delete country

GET    /areas                         → List areas
GET    /areas/create                  → Create area form
POST   /areas                         → Store area
GET    /areas/{area}/edit            → Edit area form
PUT    /areas/{area}                  → Update area
DELETE /areas/{area}                  → Delete area
```

### Admin Spam Words
```
GET    /admin/spam-words              → List spam words
POST   /admin/spam-words              → Store spam word
POST   /admin/spam/update             → Update spam word
DELETE /admin/spam-words/{id}         → Delete spam word
POST   /admin/spam-words/import       → Import CSV
GET    /admin/spam-words/download-template → Download CSV template
```

### Admin Subscriptions
```
GET    /admin/subscriptions                    → List subscriptions
GET    /admin/subscriptions/create             → Create form
POST   /admin/subscriptions                    → Store subscription
GET    /admin/subscriptions/{id}/edit          → Edit form
PUT    /admin/subscriptions/{id}               → Update subscription
DELETE /admin/subscriptions/{id}               → Delete subscription
GET    /admin/transactions/report              → Transaction report
GET    /admin/user/search?q={query}           → Search users
```

### Admin Features
```
GET    /admin/features                 → List features
GET    /admin/features/create          → Create form
POST   /admin/features                 → Store feature
GET    /admin/features/{id}/edit       → Edit form
PUT    /admin/features/{id}            → Update feature
DELETE /admin/features/{id}            → Delete feature
```

### Admin Feature Mappings
```
GET    /admin/mappings                 → List mappings
GET    /admin/mappings/create          → Create form
POST   /admin/mappings                 → Store mapping
GET    /admin/mappings/{id}/edit       → Edit form
PUT    /admin/mappings/{id}            → Update mapping
DELETE /admin/mappings/{id}            → Delete mapping
```

### Admin Campaigns
```
GET    /admin/campaigns/view           → List campaigns
GET    /admin/campaigns/create         → Create form
POST   /admin/campaigns/store          → Store campaign
GET    /admin/campaigns/{id}           → View campaign
GET    /admin/campaigns/{id}/edit      → Edit form
PUT    /admin/campaigns/{id}           → Update campaign
DELETE /admin/campaigns/{id}           → Delete campaign
POST   /admin/campaigns/{id}/send      → Send campaign
```

### Admin Campaign Templates
```
GET    /admin/campaign_templates/view    → List templates
GET    /admin/campaign_templates/create  → Create form
POST   /admin/campaign_templates/store   → Store template
GET    /admin/campaign_templates/{id}    → Edit form
PUT    /admin/campaign_templates/{id}    → Update template
DELETE /admin/campaign_templates/{id}    → Delete template
```

### Admin Mailing Lists
```
GET    /admin/mailing_lists/view                    → List mailing lists
GET    /admin/mailing_lists/create                  → Create form
POST   /admin/mailing_lists/store                   → Store mailing list
GET    /admin/mailing_lists/{id}/edit               → Edit form
PUT    /admin/mailing_lists/{id}                    → Update mailing list
DELETE /admin/mailing_lists/{id}                    → Delete mailing list
POST   /admin/mailing-lists/bulk-action/store       → Bulk action
GET    /admin/mailing-lists/pages-{listId}          → Pages by list
GET    /admin/mailing-lists/page-all                → All pages
GET    /admin/areas-by-city                         → Areas by city
```

### Admin Help Articles
```
GET    /admin/help-articles/view       → List articles
GET    /admin/help-articles/create     → Create form
POST   /admin/help-articles            → Store article
GET    /admin/help-articles/{id}       → Edit form
PUT    /admin/help-articles/{id}       → Update article
DELETE /admin/help-articles/{id}       → Delete article
```

### Admin Services
```
GET  /admin/services/view       → List services
GET  /admin/services/create     → Create form
POST /admin/services/create     → Store service
```

### Admin Activity
```
GET /admin/user/activity                    → User activity
GET /admin/activity-cities/{user_id}        → City breakdown
GET /activity-city/{cityId}/{user_id}       → City activity report
```

### Admin Enquiry Lead Stages
```
GET    /admin/enquiry-lead-stages              → List stages
GET    /admin/enquiry-lead-stages/create       → Create form
POST   /admin/enquiry-lead-stages              → Store stage
GET    /admin/enquiry-lead-stages/{id}/edit    → Edit form
PUT    /admin/enquiry-lead-stages/{id}         → Update stage
DELETE /admin/enquiry-lead-stages/{id}         → Delete stage
GET    /admin/enquiry-lead-stages/{id}         → Show stage
```

### Admin Attributes
```
GET    /admin/attributes/categories/search?q={query} → Search categories
GET    /admin/attributes/view                        → List attributes
GET    /admin/attributes/create                      → Create form
POST   /admin/attributes/store                       → Store attribute
GET    /admin/attributes-edit/{id}                   → Edit form
POST   /admin/attributes/{id}/update                 → Update attribute
DELETE /admin/attributes/{id}                        → Delete attribute
```

### Admin Dev Tools
```
GET  /admin/dev-tools                    → Dev tools page
POST /admin/dev-tools/clear              → Clear cache
GET  /admin/dev-tools/log/download/{filename} → Download log
GET  /admin/dev-tools/log/delete/{filename}   → Delete log
GET  /admin/dev-tools/logs/delete-all         → Delete all logs
```

### Utility Routes
```
GET  /clear-cache                → Clear all caches
GET  /queue-run                  → Restart queue
GET  /run_queue                  → Run queue worker
GET  /health                     → Health check
GET  /auth-checker               → Check if authenticated
GET  /language/switch/{language} → Switch language
POST /load-cities-ajax           → Load cities (AJAX)
GET  /load-all-cities-json       → All cities (JSON)
GET  /search/all                 → Universal search
GET  /track-email                → Email tracking pixel
POST /submit-contact             → Submit contact form
GET  /admin/contact-queries      → Contact queries (admin)
GET  /get-smart-cities           → Smart city search
GET  /get-menu-by-city           → Menu by city
GET  /master-dashboard           → Master dashboard
GET  /build-master-aggregate     → Build master aggregate
GET  /build-content-master       → Build content master
GET  /build-city-guide-aggregate → Build city guide aggregate
GET  /build-market-aggregate     → Build market aggregate
GET  /build-community-aggregate  → Build community aggregate
GET  /build-event-aggregate      → Build event aggregate
GET  /build-blog-aggregate       → Build blog aggregate
```

---

## Common Response Formats

### Success (JSON)
```json
{
  "success": true,
  "message": "Operation completed",
  "data": { ... }
}
```

### Error (JSON)
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Validation error message"]
  }
}
```

### Paginated Response
```json
{
  "data": [...],
  "current_page": 1,
  "last_page": 10,
  "per_page": 15,
  "total": 150
}
```

---

## Middleware

| Middleware | Purpose |
|-----------|---------|
| `auth` | Requires authenticated user |
| `verified` | Requires verified email |
| `admin` | Requires admin role |
| `guest` | Requires guest (not logged in) |
| `activity` | Logs user activity |
| `prevent-back-history` | Prevents back button after logout |
| `throttle:6,1` | Rate limit: 6 attempts per minute |
| `signed` | Requires valid signature |

---

## Notes

- This is primarily a **server-rendered Laravel app**, not a pure REST API
- Most routes return **Blade views** (HTML), not JSON
- AJAX endpoints return JSON for dynamic content loading
- The frontend Next.js app connects to this backend for data
- File uploads use `multipart/form-data`
- All dates follow `YYYY-MM-DD` format
- All times follow `HH:MM:SS` format
- Currency is in INR (₹)

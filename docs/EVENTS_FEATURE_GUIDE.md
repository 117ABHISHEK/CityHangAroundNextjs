# Events Feature — Developer Guide

## Overview

The Events feature handles event creation, browsing, and details. It's located under `frontend/src/features/events/` and follows Next.js App Router conventions with server components at the route level and client components for interactivity.

---

## Folder Structure

```
frontend/src/features/events/
└── createEvent/
    ├── index.tsx                    # Page wrapper (Client Component)
    ├── index.css                    # Page-level styles
    └── components/
        ├── EventForm/
        │   ├── index.tsx            # Multi-step form orchestrator (empty — to be built)
        │   └── index.css            # Form styles
        ├── BasicInformation/
        │   ├── index.tsx            # Step 1: Name, category, tags, description, cover image
        │   └── index.css            # Basic info styles
        ├── EventSidebar/
        │   ├── index.tsx            # Left sidebar stepper + help section
        │   └── index.css            # Sidebar styles
        ├── AutoSaveStatus/
        │   ├── index.tsx            # Auto-save indicator at bottom
        │   └── index.css            # Auto-save styles
        ├── BottomActionBar/
        │   └── index.tsx            # Back/Save/Continue buttons (no CSS yet)
        ├── EventImageUpload/
        │   ├── index.tsx            # Cover image upload with preview
        │   └── index.css            # Upload styles
        ├── EventPreviewCard/
        │   ├── index.tsx            # Live preview card
        │   └── index.css            # Preview card styles
        ├── EventStatus/
        │   ├── index.tsx            # Status selector (Upcoming/Live/Completed/Cancelled)
        │   └── index.css            # Status styles
        ├── LivePreview/
        │   ├── index.tsx            # Right sidebar live preview
        │   └── index.css            # Live preview styles
        └── QuickTips/
            ├── index.tsx            # Tips panel
            └── index.css            # Tips styles
```

## App Routes

```
frontend/app/events/
├── page.tsx          # /events — Server Component, imports EventHome
└── [slug]/
    └── page.tsx      # /events/:slug — Server Component, renders slug param

```

---

## How to Write Code Here

### 1. Page Wrapper Pattern (Client Component)

Every feature page uses this exact pattern in `index.tsx`:

```tsx
// frontend/src/features/events/createEvent/index.tsx
"use client";

import EventForm from "./components/EventForm";

export default function CreateEvent() {
  return (
    <main>
      <EventForm />
    </main>
  );
}
```

**Rules:**
- Always `"use client"` at the top
- Import components from `./components/ComponentName`
- Keep the page wrapper thin — all logic lives in child components
- No CSS imports in the page wrapper (import in child components)

### 2. Route Page Pattern (Server Component)

The `app/events/page.tsx` is a **server component** — no `"use client"`:

```tsx
// frontend/app/events/page.tsx
import EventHome from "@/features/events/eventHome";

export const metadata = {
  title: "Events | CityHangAround",
  description: "Discover events...",
};

export default function EventsPage() {
  return <EventHome />;
}
```

**Rules:**
- Server Component by default (no `"use client"`)
- Set `metadata` export for SEO
- Import the feature component from `@/features/events/featureName`
- Route params come from Next.js: `params.slug` for `[slug]` pages

### 3. Component Pattern

```tsx
// frontend/src/features/events/createEvent/components/BasicInformation/index.tsx
"use client";

import { useState } from "react";
import "./index.css";

export default function BasicInformation() {
  return (
    <section className="basic-info">
      {/* content */}
    </section>
  );
}
```

**Rules:**
- Always `"use client"` if using state, effects, or event handlers
- Import CSS from `./index.css` (co-located)
- Export as **default function**
- Use BEM-like class naming: `feature-component__element--modifier`

### 4. CSS Architecture

Follow the same CSS patterns as the Home feature:

```css
/* Scoped to feature — prefix all classes */
.basic-info { }
.basic-info__label { }
.basic-info__input { }
.basic-info__input--error { }
```

**Global styles available (no import needed):**
- `.container` — max-width responsive container
- `.btn-primary` / `.btn-primary-outline` — orange buttons
- `.section-title` / `.section-subtitle` — heading styles
- `.badge` / `.badge-primary` — tag badges
- CSS variables: `--primary` (#ff6b35), `--text`, `--bg`, `--text-muted`, `--radius`, etc.

---

## The Event Creation Flow (5 Steps)

Based on the UI design screenshot, the Create Event page has:

| Step | Section | Fields |
|------|---------|--------|
| 1 | Basic Information | Event Name, Parent Category, Category, Tags, Short Description, Cover Image, Event Status |
| 2 | Date & Location | Date/time picker, venue, address |
| 3 | Event Details | Event type, tickets, capacity, pricing |
| 4 | Media & Images | Additional images, videos, documents |
| 5 | Description | Full description, agenda, schedule |

### Layout (3-column):
- **Left sidebar** (EventSidebar): Step indicator + "Need Help?" box
- **Center** (EventForm): Active step's form fields
- **Right sidebar** (LivePreview): Real-time preview card + QuickTips

### Bottom bar (BottomActionBar):
- Left: Auto-save status indicator
- Center: Decorative element
- Right: Back button + Save & Continue button

---

## Key Imports Reference

```tsx
// UI components (from shared components)
import LetterSwap3D from "@/components/ui/letter-swap-3d";
import { SearchIcon, CloseIcon } from "@/components/ui/icons";

// Hooks
import { useMediaQuery } from "@/hooks/useMediaQuery";
import { useAppDispatch, useAppSelector } from "@/store/hooks";

// API services
import { eventApi } from "@/services/api/eventApi";

// Types
import type { Event, EventFormData } from "@/types/event";

// Utils
import { formatDate } from "@/utils/dateHelpers";
import { slugify } from "@/utils/slugify";
```

---

## What Needs to Be Built

All component files in the events feature are **empty scaffolds**. Here's what to implement:

### Priority 1 — Create Event Form
1. **EventForm** — Multi-step form with state management (current step, form data)
2. **BasicInformation** — Input fields for name, category dropdowns, tags input, description textarea, cover image upload, status radio buttons
3. **EventSidebar** — Step indicator with numbered circles, active/completed states
4. **LivePreview** — Real-time preview card showing event as it would appear to users
5. **BottomActionBar** — Navigation buttons (Back / Save & Continue) + auto-save status
6. **EventImageUpload** — Drag-and-drop or click upload with preview
7. **EventStatus** — Radio button group (Upcoming / Live / Completed / Cancelled)
8. **AutoSaveStatus** — Shows "Auto-save is ON" with last-saved timestamp
9. **QuickTips** — Checklist of tips for creating a good event

### Priority 2 — Event Listing (to be scaffolded)
10. Create `eventHome/index.tsx` + `index.css` — Event listing page
11. **EventsFilter** — Filter by category, date, location
12. **EventCard** — Card with image, title, date, location, attendees

### Priority 3 — Event Detail (to be scaffolded)
13. **EventBanner** — Hero banner with event image
14. **EventSummary** — Event details, description, organizer info
15. **EventTicketing** — Ticket selection, pricing, purchase flow
16. **EventLocation** — Map view, venue details
17. **Ticketing** — Ticket types, availability, pricing

---

## Naming Conventions

| Type | Convention | Example |
|------|-----------|---------|
| Component folder | PascalCase | `BasicInformation/` |
| Component file | PascalCase | `EventCard.tsx` |
| CSS file | Always `index.css` inside component folder | `BasicInformation/index.css` |
| CSS class | kebab-case with feature prefix | `.event-card__title` |
| Route folder | camelCase | `[slug]/`, `createEvent/` |
| Import alias | `@/` maps to `frontend/src/` | `@/components/ui/icons` |

---

## State Management Pattern

For the multi-step form, use local state in `EventForm`:

```tsx
const [currentStep, setCurrentStep] = useState(1);
const [formData, setFormData] = useState<EventFormData>({
  name: "",
  parentCategory: "",
  category: "",
  tags: [],
  shortDescription: "",
  coverImage: null,
  status: "upcoming",
  // ... more fields per step
});

const updateField = (field: string, value: unknown) => {
  setFormData(prev => ({ ...prev, [field]: value }));
};
```

Pass `formData` and `updateField` down to each step component as props.

For global state (user events, saved drafts), use Redux slices in `src/store/slices/`.

---

## Common Pitfalls

1. **Don't forget `"use client"`** — any component using `useState`, `useEffect`, or event handlers needs it
2. **CSS is co-located** — each component has its own `index.css`, imported inside the component
3. **Don't use global Tailwind** — the project uses custom CSS variables and BEM classes, not Tailwind utility classes
4. **Icons come from `@/components/ui/icons`** — check what's exported before creating new ones
5. **Route pages are Server Components** — they can't have `"use client"` or hooks
6. **Feature index.tsx is always a thin wrapper** — don't put logic there, put it in child components

---

## Backend API (Laravel)

Event-related routes will go in `backend/routes/api.php`. The existing pattern:

```php
Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{slug}', [EventController::class, 'show']);
    Route::post('/', [EventController::class, 'store']);
    // ...
});
```

Models will go in `backend/app/Models/` (e.g., `Event.php`).

---

## Quick Start for New Contributors

1. Read `docs/PROJECT_STRUCTURE.md` for overall architecture
2. Read this file for events-specific patterns
3. Pick an empty component from the list above
4. Create the component following the patterns in this guide
5. Test locally with `npm run dev` and navigate to `/events`
6. Submit PR to your personal branch → Abhishek reviews → merge to main

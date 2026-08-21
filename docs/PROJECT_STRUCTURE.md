# CityHangAround — Project Structure & Architecture

> A hyperlocal discovery platform for India — find restaurants, events, services, and deals near you.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | Next.js 16, React 19, TypeScript 5 |
| Styling | CSS Modules + Tailwind CSS 4 |
| Animations | Custom CSS keyframes + `motion` (Framer Motion) |
| Icons | `lucide-react`, `react-icons`, `lineicons-react` |
| Backend | Laravel 12 (PHP) |
| Database | SQLite (dev) / PostgreSQL or MySQL (prod) |
| Deployment | Docker + Coolify |

---

## Root Layout

```
CityHangAroundNextjs/
├── frontend/          # Next.js 16 app (App Router)
├── backend/           # Laravel 12 API
├── docs/              # This documentation
├── tools/             # Local dev helpers (composer.phar)
├── nginx/             # Reverse proxy config (empty)
├── Dockerfile         # Single-container build
├── docker-entrypoint.sh
├── newui.html         # Design reference / mockup
└── .gitignore
```

---

## Frontend Architecture (`frontend/`)

```
frontend/
├── app/                          # Next.js App Router
│   ├── layout.tsx                # Root layout (fonts, providers)
│   ├── page.tsx                  # Home page
│   ├── globals.css               # Global styles + Tailwind import
│   ├── business/                 # /business routes
│   ├── community/                # /community routes
│   ├── events/                   # /events routes
│   ├── marketplace/              # /marketplace routes
│   ├── search/                   # /search routes
│   └── [city]/                   # Dynamic city routes
│
├── src/
│   ├── components/               # Shared, reusable components
│   │   ├── layout/               # App shell components
│   │   │   ├── Navbar/           # Top navigation
│   │   │   ├── Footer/           # Site footer
│   │   │   └── MobileNavigation/ # Mobile bottom nav
│   │   ├── shared/               # Cross-feature components
│   │   │   ├── Ads/              # Ad placement components
│   │   │   ├── ErrorState/       # Error boundary UI
│   │   │   ├── LoadingState/     # Skeleton / spinner
│   │   │   ├── Pagination/       # List pagination
│   │   │   └── SectionHeading/   # Reusable section header
│   │   └── ui/                   # Primitive UI components
│   │       ├── icons.tsx         # All Lucide icon re-exports
│   │       ├── animated-icon.tsx # Animated icon wrapper
│   │       ├── letter-swap-3d/   # 3D letter flip animation
│   │       └── magic-card/       # 3D tilt card component
│   │
│   ├── features/                 # Feature-based modules
│   │   ├── home/                 # ⭐ Homepage (main focus)
│   │   ├── auth/                 # Authentication
│   │   ├── business/             # Business listings
│   │   ├── categories/           # Category browsing
│   │   ├── cities/               # City pages
│   │   ├── community/            # Community / forums
│   │   ├── events/               # Events & nightlife
│   │   ├── marketplace/          # Marketplace
│   │   ├── notifications/        # Push / in-app notifications
│   │   ├── payments/             # Payment integration
│   │   ├── profile/              # User profiles
│   │   └── search/               # Search functionality
│   │
│   ├── hooks/                    # Custom React hooks
│   │   └── useHealth.ts          # Health check hook
│   │
│   ├── lib/                      # Core libraries
│   │   ├── analytics/            # Analytics / tracking
│   │   ├── api/                  # API client utilities
│   │   ├── auth/                 # Auth helpers
│   │   └── seo/                  # SEO utilities
│   │
│   ├── services/                 # External service integrations
│   │   └── api.ts                # API service layer
│   │
│   ├── config/                   # App configuration
│   │   └── ads.ts                # Ad configuration
│   │
│   ├── types/                    # Shared TypeScript types
│   │
│   └── utils/                    # Utility functions
│
├── public/                       # Static assets
│   ├── fonts/                    # Custom fonts
│   ├── icons/                    # Favicon & app icons
│   └── images/                   # Static images
│
├── package.json
├── tsconfig.json
├── next.config.ts
├── postcss.config.mjs
└── eslint.config.mjs
```

---

## Home Page Deep Dive (`src/features/home/`)

The homepage is the most complex feature. It's composed of independent section components, each with its own CSS file.

```
features/home/
├── index.tsx                     # Home page entry — assembles all sections
├── base.css                      # Shared CSS variables & base classes
│
└── components/
    ├── HeroSection/              # Hero with animated headline + search bar
    │   ├── index.tsx
    │   └── index.css
    ├── HeroBackdrop/             # Animated city skyline (day/night cycle)
    │   └── index.tsx             # Buildings, stars, twinkling windows
    ├── SecondarySection/         # Secondary CTA / info section
    │   └── index.tsx
    ├── TrendingCities/           # Trending cities carousel
    │   ├── index.tsx
    │   └── index.css
    ├── Categories/               # Category grid / mobile carousel
    │   ├── index.tsx
    │   └── index.css
    ├── HomeHighlights/           # Feature highlights grid
    │   ├── index.tsx
    │   └── index.css
    ├── BusinessGrowth/           # Business growth / advertise section
    │   ├── index.tsx
    │   └── index.css
    └── TrustAndFaq/              # Trust signals + FAQ accordion
        ├── index.tsx
        └── index.css
```

### Section Order (top → bottom)

1. **HeroSection** — Full-width hero with animated "Deals" headline, search bar, popular tags, CTAs, stats
2. **SecondarySection** — Secondary info / feature callout
3. **TrendingCities** — Horizontal scrolling city cards
4. **Categories** — Category grid (4-col desktop, horizontal scroll carousel on mobile)
5. **HomeHighlights** — 4-column feature highlight cards
6. **BusinessGrowth** — "Advertise with us" section
7. **TrustAndFaq** — Trust badges + FAQ accordion

---

## CSS Architecture

### Naming Convention

All home page CSS uses a **BEM-inspired** naming pattern:

```
.home-{section}                    # Block
.home-{section}__{element}         # Element
.home-{section}__{element}--{mod}  # Modifier
```

Example:
```css
.home-category                     # Card block
.home-category__icon               # Icon element
.home-category__icon--large        # Modifier
```

### CSS Variables (`base.css`)

```css
--home-primary: #e8432c;        /* Brand coral/red */
--home-primary-dark: #cf3a24;   /* Darker variant for hover */
--home-text: #1a1e29;           /* Body text */
--home-muted: #5b6472;          /* Muted / secondary text */
--home-light: #8d94a3;          /* Light text / placeholders */
--home-border: #eceef2;         /* Border color */
```

### Layout Classes

```css
.home-container    /* Max-width 1528px, centered, responsive padding */
.home-section      /* Vertical padding: 72px (48px on mobile) */
.home-section__heading  /* Centered section header with max-width */
```

### Button Classes

```css
.home-button                    /* Base button */
.home-button--primary           /* Coral gradient fill */
.home-button--search            /* Search bar button */
.home-button--outline           /* White border, transparent */
.home-button--outline-dark      /* Dark border variant */
.home-button--light             /* White fill, coral text */
```

---

## Key UI Components

### LetterSwap3D (`components/ui/letter-swap-3d/`)

A 3D letter-flip animation component. Each letter flips independently with staggered timing.

```tsx
<LetterSwap3D
  staggerInterval={0.055}    // Delay between each letter
  staggerOrigin="first"      // Animation starts from first letter
  flipDirection="top"         // Letters flip downward
  duration={0.42}             // Per-letter animation duration
  blur                        // Enable blur during flip
  blurAmount={3}
  autoPlay                    // Auto-trigger periodically
  interval={5000}             // ms between auto-plays
  respectReducedMotion        // Disable for prefers-reduced-motion
>
  Deals
</LetterSwap3D>
```

**Usage pattern:** Wrap the animated word in a `<span>` with `display: inline-block` and `white-space: nowrap`. Always provide `className`, `frontFaceClassName`, and `backFaceClassName` for color overrides.

### MagicCard (`components/ui/magic-card/`)

A 3D tilt card that follows the cursor. Used on HomeHighlights cards.

### Icons (`components/ui/icons.tsx`)

All Lucide icons are re-exported from a single file. Import from here, not directly from `lucide-react`:

```tsx
import { FoodIcon, HealthIcon, SearchIcon } from "@/src/components/ui/icons";
```

---

## Responsive Breakpoints

| Breakpoint | Layout |
|-----------|--------|
| > 1150px | 4-column category grid |
| ≤ 1150px | 3-column category grid |
| ≤ 900px | 2-column category grid, reduced section padding |
| ≤ 700px | Search bar stacks vertically |
| ≤ 560px | Mobile: horizontal scroll carousels, full-width CTAs, reduced font sizes |

---

## Mobile Carousel Behavior (Categories)

On mobile (≤560px), the category grid transforms into a horizontal scroll-snap carousel:

- `scroll-snap-type: x mandatory` on the container
- Each card: `flex: 0 0 85%` + `scroll-snap-align: center`
- **Auto-activation:** An `IntersectionObserver` watches all cards and sets `is-active` on whichever card has the highest intersection ratio (the front card)
- The `is-active` class triggers all the hover animations (icon fill, card lift, pills float, arrow slide)
- Top padding on the carousel container prevents clipping when cards lift up

---

## Animations & Motion

### Entrance Animations

Most sections use staggered `fadeUp` entrance animations:

```css
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(28px); }
  to { opacity: 1; transform: translateY(0); }
}
```

Each content layer gets an incrementing `animation-delay` (e.g., 0.1s, 0.2s, 0.3s...).

### Reduced Motion

All animations respect `prefers-reduced-motion: reduce`. The global `globals.css` also resets all transitions:

```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
```

---

## Backend Structure (`backend/`)

```
backend/
├── app/
│   ├── Http/Controllers/       # API controllers
│   ├── Models/                 # Eloquent models
│   ├── Services/               # Business logic
│   └── ...
├── routes/
│   ├── web.php                 # Web routes
│   ├── api.php                 # API routes
│   ├── auth.php                # Auth routes
│   └── payment.php             # Payment routes
├── database/
│   └── migrations/             # Database migrations
├── config/                     # Laravel config files
├── composer.json               # PHP dependencies
└── artisan                     # Laravel CLI
```

---

## Getting Started

### Prerequisites

- Node.js 18+ and npm
- PHP 8.2+ (XAMPP or standalone)
- Composer

### Frontend

```bash
cd frontend
npm install
npm run dev
# → http://localhost:3000
```

### Backend

```bash
cd backend
# Windows (XAMPP PHP)
"C:\xampp\php\php.exe" artisan serve
# → http://localhost:8000
```

### Docker

```bash
docker build -t cityhangaround .
docker run --rm -p 3000:3000 -p 9000:9000 cityhangaround
```

---

## Development Guidelines

### Adding a New Section to the Homepage

1. Create `src/features/home/components/YourSection/index.tsx`
2. Create `src/features/home/components/YourSection/index.css`
3. Import in `src/features/home/index.tsx`
4. Use BEM naming: `.home-your-section`, `.home-your-section__element`
5. Use CSS variables from `base.css` for colors
6. Add entrance animations with staggered delays
7. Add `prefers-reduced-motion` support

### Creating a New Feature Module

1. Create `src/features/yourfeature/`
2. Add `index.tsx` as the entry point
3. Add a `components/` subdirectory for feature-specific components
4. Co-locate CSS with each component (`index.css`)
5. Add a route in `app/yourfeature/page.tsx`

### CSS Rules

- **Never use Tailwind for homepage components** — use CSS files with BEM naming
- Tailwind is available for new pages and utility styling
- All homepage colors must use CSS variables from `base.css`
- All animations must include `prefers-reduced-motion` support
- Test responsive behavior at 1150px, 900px, 700px, and 560px breakpoints

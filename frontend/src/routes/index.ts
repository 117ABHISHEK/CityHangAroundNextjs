import type { LucideIcon } from "@/src/components/ui/icons";
import {
  HomeIcon,
  CityGuideIcon,
  BuySellIcon,
  MarketplaceIcon,
  CommunityIcon,
  BlogIcon,
  EventIcons,
} from "@/src/components/ui/icons";

/* ===========================================================
   TYPES
=========================================================== */

export type TabType =
  | "home"
  | "community"
  | "city-guide"
  | "buy-sell"
  | "marketplace"
  | "blog"
  | "event";

export type DropdownItem = {
  label: string;
  href: string;
};

export type RouteConfig = {
  tab: TabType;
  path: string;
  label: string;
  icon: LucideIcon;
  hasDropdown?: boolean;
  dropdownItems?: DropdownItem[];
};

/* ===========================================================
   ROUTE DEFINITIONS
=========================================================== */

export const routes: RouteConfig[] = [
  { tab: "home", path: "/", label: "Home", icon: HomeIcon },
  {
    tab: "city-guide",
    path: "/city-guide",
    label: "City Guide",
    icon: CityGuideIcon,
    hasDropdown: true,
    dropdownItems: [
      { label: "Top Attractions", href: "#" },
      { label: "Restaurants & Dining", href: "#" },
      { label: "Nightlife & Bars", href: "#" },
      { label: "Upcoming Events", href: "#" },
    ],
  },
  {
    tab: "buy-sell",
    path: "/buy-sell",
    label: "Buy/Sell",
    icon: BuySellIcon,
    hasDropdown: true,
    dropdownItems: [
      { label: "Vehicles & Cars", href: "#" },
      { label: "Property & Real Estate", href: "#" },
      { label: "Electronics & Gadgets", href: "#" },
      { label: "Home & Furniture", href: "#" },
    ],
  },
  {
    tab: "marketplace",
    path: "/marketplace",
    label: "Marketplace",
    icon: MarketplaceIcon,
    hasDropdown: true,
    dropdownItems: [
      { label: "Local Services", href: "#" },
      { label: "Freelancers & Jobs", href: "#" },
      { label: "Deals & Discounts", href: "#" },
    ],
  },
  { tab: "community", path: "/community", label: "Community", icon: CommunityIcon },
  {
    tab: "blog",
    path: "/blog",
    label: "Blog",
    icon: BlogIcon,
    hasDropdown: true,
    dropdownItems: [
      { label: "City Stories", href: "#" },
      { label: "Local News", href: "#" },
      { label: "Travel Tips", href: "#" },
    ],
  },
  { tab: "event", path: "/events", label: "Event", icon: EventIcons },
];

/* ===========================================================
   LOOKUP MAPS
=========================================================== */

/** path → tab */
export const pathToTab: Record<string, TabType> = Object.fromEntries(
  routes.map((r) => [r.path, r.tab]),
);

/** tab → path */
export const tabToPath: Record<TabType, string> = Object.fromEntries(
  routes.map((r) => [r.tab, r.path]),
) as Record<TabType, string>;

"use client";

import { useEffect, useRef, useState } from "react";
import { useRouter } from "next/navigation";
import AnimatedIcon from "@/src/components/ui/animated-icon";
import { ChevronDown } from "@/src/components/ui/icons";
import type { TabType, RouteConfig } from "@/src/routes";
import { routes } from "@/src/routes";

type SecondaryNavbarProps = {
  activeTab?: TabType;
  onTabChange?: (tab: TabType) => void;
};

// const navItems: NavItem[] = [
//   { label: "Home", icon: HomeIcon, tab: "home" },
//   {
//     label: "City Guide",
//     icon: CityGuideIcon,
//     tab: "city-guide",
//     hasDropdown: true,
//     dropdownItems: [
//       { label: "Top Attractions", href: "#" },
//       { label: "Restaurants & Dining", href: "#" },
//       { label: "Nightlife & Bars", href: "#" },
//       { label: "Upcoming Events", href: "/events" },
//     ],
//   },
//   {
//     label: "Buy/Sell",
//     icon: BuySellIcon,
//     tab: "buy-sell",
//     hasDropdown: true,
//     dropdownItems: [
//       { label: "Vehicles & Cars", href: "#" },
//       { label: "Property & Real Estate", href: "#" },
//       { label: "Electronics & Gadgets", href: "#" },
//       { label: "Home & Furniture", href: "#" },
//     ],
//   },
//   {
//     label: "Marketplace",
//     icon: MarketplaceIcon,
//     tab: "marketplace",
//     hasDropdown: true,
//     dropdownItems: [
//       { label: "Local Services", href: "#" },
//       { label: "Freelancers & Jobs", href: "#" },
//       { label: "Deals & Discounts", href: "#" },
//     ],
//   },
//   { label: "Community", icon: CommunityIcon, tab: "community" },
//   {
//     label: "Blog",
//     icon: BlogIcon,
//     tab: "blog",
//     hasDropdown: true,
//     dropdownItems: [
//       { label: "City Stories", href: "#" },
//       { label: "Local News", href: "#" },
//       { label: "Travel Tips", href: "#" },
//     ],
//   },
//   { label: "Event", icon: EventIcons, tab: "event" },
// ];

export default function SecondaryNavbar({
  activeTab = "home",
  onTabChange,
}: SecondaryNavbarProps) {
  const router = useRouter();
  const [openDropdown, setOpenDropdown] = useState<TabType | null>(null);
  const navRef = useRef<HTMLElement>(null);

  useEffect(() => {
    const handleOutsideClick = (e: MouseEvent) => {
      if (navRef.current && !navRef.current.contains(e.target as Node)) {
        setOpenDropdown(null);
      }
    };
    document.addEventListener("mousedown", handleOutsideClick);
    return () => document.removeEventListener("mousedown", handleOutsideClick);
  }, []);

  const handleTabClick = (e: React.MouseEvent, item: RouteConfig) => {
    e.preventDefault();
    if (item.hasDropdown) {
      setOpenDropdown((current) => (current === item.tab ? null : item.tab));
      onTabChange?.(item.tab);
    } else {
      setOpenDropdown(null);
      onTabChange?.(item.tab);
      if (item.tab === "event") {
        router.push("/events");
      } else if (item.tab === "home") {
        router.push("/");
      }
    }
  };

  return (
    <nav className="navbar__secondary" ref={navRef} aria-label="Secondary navigation">
      <div className="navbar__nav-inner">
        {routes.map((item) => {
          const { label, icon: Icon, tab, hasDropdown, dropdownItems } = item;
          const isOpen = openDropdown === tab;
          const isActive = activeTab === tab;

          return (
            <div key={tab} className="navbar__nav-wrapper">
              <a
                href="#"
                onClick={(e) => handleTabClick(e, item)}
                className={`navbar__nav-item ${isActive ? "navbar__nav-item--active" : ""} ${
                  isOpen ? "navbar__nav-item--dropdown-open" : ""
                }`}
                aria-expanded={hasDropdown ? isOpen : undefined}
                aria-haspopup={hasDropdown ? "menu" : undefined}
              >
                <AnimatedIcon>
                  <span className="navbar__nav-icon">
                    <Icon size={16} strokeWidth={2} aria-hidden="true" />
                  </span>
                </AnimatedIcon>
                <span className="navbar__nav-label">{label}</span>
                {hasDropdown && (
                  <ChevronDown
                    size={13}
                    strokeWidth={1.8}
                    aria-hidden="true"
                    className={`navbar__nav-chevron ${
                      isOpen ? "navbar__nav-chevron--open" : ""
                    }`}
                  />
                )}
              </a>

              {/* Glassmorphism Dropdown Menu */}
              {hasDropdown && isOpen && dropdownItems && (
                <div className="navbar__dropdown navbar__dropdown--open" role="menu">
                  {dropdownItems.map((subItem) => (
                    <a
                      key={subItem.label}
                      href={subItem.href}
                      role="menuitem"
                      className="navbar__dropdown-item"
                      onClick={(e) => {
                        e.preventDefault();
                        setOpenDropdown(null);
                        onTabChange?.(tab);
                      }}
                    >
                      <span>{subItem.label}</span>
                    </a>
                  ))}
                </div>
              )}
            </div>
          );
        })}
      </div>
    </nav>
  );
}
import AnimatedIcon from "@/src/components/ui/animated-icon";
import {
  BlogIcon,
  BuySellIcon,
  ChevronDown,
  CityGuideIcon,
  CommunityIcon,
  ContactIcon,
  HomeIcon,
  MarketplaceIcon,
} from "@/src/components/ui/icons";
import type { LucideIcon } from "@/src/components/ui/icons";

type NavItem = {
  label: string;
  icon: LucideIcon;
  hasDropdown?: boolean;
};

const navItems: NavItem[] = [
  { label: "Home", icon: HomeIcon },
  { label: "City Guide", icon: CityGuideIcon, hasDropdown: true },
  { label: "Buy/Sell", icon: BuySellIcon, hasDropdown: true },
  { label: "Marketplace", icon: MarketplaceIcon, hasDropdown: true },
  { label: "Community", icon: CommunityIcon },
  { label: "Blog", icon: BlogIcon, hasDropdown: true },
  { label: "Contact", icon: ContactIcon },
];

export default function SecondaryNavbar() {
  return (
    <nav className="navbar__secondary">
      <div className="navbar__nav-inner">
        {navItems.map(({ label, icon: Icon, hasDropdown }, index) => (
          <a key={label} href="#" className={`navbar__nav-item ${index === 0 ? "navbar__nav-item--active" : ""}`}>
            <AnimatedIcon>
              <span className="navbar__nav-icon">
                <Icon size={16} strokeWidth={2} aria-hidden="true" />
              </span>
            </AnimatedIcon>
            <span>{label}</span>
            {hasDropdown ? <ChevronDown size={13} strokeWidth={1.7} aria-hidden="true" /> : null}
          </a>
        ))}
      </div>
    </nav>
  );
}

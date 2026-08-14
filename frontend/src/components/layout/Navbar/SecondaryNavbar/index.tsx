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

type TabType = "home" | "community" | "city-guide" | "buy-sell" | "marketplace" | "blog" | "contact";

type NavItem = {
  label: string;
  icon: LucideIcon;
  tab: TabType;
  hasDropdown?: boolean;
};

type SecondaryNavbarProps = {
  activeTab?: TabType;
  onTabChange?: (tab: TabType) => void;
};

const navItems: NavItem[] = [
  { label: "Home", icon: HomeIcon, tab: "home" },
  { label: "City Guide", icon: CityGuideIcon, tab: "city-guide", hasDropdown: true },
  { label: "Buy/Sell", icon: BuySellIcon, tab: "buy-sell", hasDropdown: true },
  { label: "Marketplace", icon: MarketplaceIcon, tab: "marketplace", hasDropdown: true },
  { label: "Community", icon: CommunityIcon, tab: "community" },
  { label: "Blog", icon: BlogIcon, tab: "blog", hasDropdown: true },
  { label: "Contact", icon: ContactIcon, tab: "contact" },
];

export default function SecondaryNavbar({ activeTab = "home", onTabChange }: SecondaryNavbarProps) {
  const handleTabClick = (e: React.MouseEvent, tab: TabType) => {
    e.preventDefault();
    onTabChange?.(tab);
  };

  return (
    <nav className="navbar__secondary">
      <div className="navbar__nav-inner">
        {navItems.map(({ label, icon: Icon, tab, hasDropdown }) => (
          <a
            key={tab}
            href="#"
            onClick={(e) => handleTabClick(e, tab)}
            className={`navbar__nav-item ${activeTab === tab ? "navbar__nav-item--active" : ""}`}
          >
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

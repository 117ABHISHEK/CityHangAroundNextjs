import PrimaryNavbar from "./PrimaryNavbar";
import SecondaryNavbar from "./SecondaryNavbar";

type TabType = "home" | "community" | "city-guide" | "buy-sell" | "marketplace" | "blog" | "contact";

type NavbarProps = {
  activeTab?: TabType;
  onTabChange?: (tab: TabType) => void;
};

export default function Navbar({ activeTab = "home", onTabChange }: NavbarProps) {
  return (
    <header className="navbar">
      <div className="navbar__primary-shell">
        <PrimaryNavbar />
      </div>
      <SecondaryNavbar activeTab={activeTab} onTabChange={onTabChange} />
    </header>
  );
}

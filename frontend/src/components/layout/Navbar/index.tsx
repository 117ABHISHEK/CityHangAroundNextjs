import PrimaryNavbar from "./PrimaryNavbar";
import SecondaryNavbar from "./SecondaryNavbar";

export default function Navbar() {
  return (
    <header className="navbar">
      <div className="navbar__primary-shell">
        <PrimaryNavbar />
      </div>
      <SecondaryNavbar />
    </header>
  );
}

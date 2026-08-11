import Image from "next/image";
import {
  AddIcon,
  CategoriesIcon,
  FavoritesIcon,
  LoginIcon,
  MenuIcon,
  SearchIcon,
} from "@/src/components/ui/icons";

export default function PrimaryNavbar() {
  return (
    <div className="navbar__primary">
      <div className="navbar__main">
        <button type="button" className="navbar__mobile-menu" aria-label="Open menu">
          <MenuIcon size={20} aria-hidden="true" />
        </button>

        <a href="#" className="navbar__logo" aria-label="CityHangaround home">
          <Image
            src="/images/cityhangaround-logo.png"
            alt="CityHangaround"
            width={208}
            height={51}
            className="navbar__logo-image"
          />
        </a>

        <div className="navbar__search">
          <button type="button" className="navbar__category">
            <CategoriesIcon size={16} strokeWidth={2.4} aria-hidden="true" />
            <span>All Categories</span>
            <span aria-hidden="true">⌄</span>
          </button>
          <label className="navbar__search-field">
            <SearchIcon size={17} strokeWidth={1.7} aria-hidden="true" />
            <span className="sr-only">Search</span>
            <input type="search" placeholder="Search for restaurants, services, events..." className="navbar__search-input" />
          </label>
          <button type="button" className="navbar__search-submit" aria-label="Search">
            <SearchIcon size={19} strokeWidth={2} aria-hidden="true" />
          </button>
        </div>

        <div className="navbar__actions">
          <button type="button" className="navbar__action" aria-label="Search">
            <SearchIcon size={16} strokeWidth={1.8} aria-hidden="true" />
            <span className="navbar__action-label">Search</span>
          </button>
          <a href="#" className="navbar__action">
            <FavoritesIcon size={16} strokeWidth={1.8} aria-hidden="true" />
            <span className="navbar__action-label">Favorites</span>
          </a>
          <a href="#" className="navbar__action">
            <LoginIcon size={16} strokeWidth={1.8} aria-hidden="true" />
            <span className="navbar__action-label">Login</span>
          </a>
          <button type="button" className="navbar__add-business">
            <AddIcon size={16} strokeWidth={2} aria-hidden="true" />
            <span>Add Business</span>
          </button>
        </div>
      </div>
    </div>
  );
}

"use client";

import { useEffect, useRef, useState } from "react";
import Image from "next/image";
import AuthModal, { type AuthMode } from "@/src/features/auth";
import AnimatedIcon from "@/src/components/ui/animated-icon";
import {
  AddIcon,
  ChevronDown,
  FavoritesIcon,
  LocationIcon,
  LoginIcon,
  MenuIcon,
  SearchIcon,
} from "@/src/components/ui/icons";

const CITIES = [
  "Kolkata",
  "Delhi",
  "Mumbai",
  "Bangalore",
  "Hyderabad",
  "Chennai",
  "Pune",
  "Ahmedabad",
  "Jaipur",
  "Surat",
  "Lucknow",
  "Chandigarh",
  "Indore",
  "Kochi",
  "Goa",
];

export default function PrimaryNavbar() {
  const [authOpen, setAuthOpen] = useState(false);
  const [authMode, setAuthMode] = useState<AuthMode>("login");
  const [cityDropdownOpen, setCityDropdownOpen] = useState(false);
  const [selectedCity, setSelectedCity] = useState<string>("Select City");
  const [citySearch, setCitySearch] = useState("");

  const cityDropdownRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (
        cityDropdownRef.current &&
        !cityDropdownRef.current.contains(event.target as Node)
      ) {
        setCityDropdownOpen(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const filteredCities = CITIES.filter((city) =>
    city.toLowerCase().includes(citySearch.toLowerCase())
  );

  const handleSelectCity = (city: string) => {
    setSelectedCity(city);
    setCityDropdownOpen(false);
    setCitySearch("");
  };

  return (
    <>
      <div className="navbar__primary">
        <div className="navbar__main">
          {/* Mobile Menu Button */}
          <button
            type="button"
            className="navbar__mobile-menu"
            aria-label="Open menu"
          >
            <MenuIcon size={20} aria-hidden="true" />
          </button>

          {/* Animated Logo */}
          <a href="#" className="navbar__logo" aria-label="CityHangaround home">
            <Image
              src="/images/cityhangaround-logo.png"
              alt="CityHangaround"
              width={208}
              height={51}
              className="navbar__logo-image"
              priority
            />
          </a>

          {/* Search Bar with Select City inside */}
          <div className="navbar__search">
            <div className="navbar__city-picker" ref={cityDropdownRef}>
              <button
                type="button"
                className={`navbar__category ${cityDropdownOpen ? "navbar__category--open" : ""}`}
                onClick={() => setCityDropdownOpen((prev) => !prev)}
                aria-expanded={cityDropdownOpen}
                aria-haspopup="listbox"
              >
                <AnimatedIcon>
                  <LocationIcon size={16} strokeWidth={2.4} aria-hidden="true" />
                </AnimatedIcon>
                <span className="navbar__category-text">{selectedCity}</span>
                <ChevronDown
                  size={14}
                  strokeWidth={2}
                  aria-hidden="true"
                  className={`navbar__category-chevron ${cityDropdownOpen ? "navbar__category-chevron--open" : ""}`}
                />
              </button>

              {/* Glassmorphism City Dropdown Panel */}
              {cityDropdownOpen && (
                <div className="navbar__city-menu" role="listbox">
                  <div className="navbar__city-search-box">
                    <SearchIcon size={14} strokeWidth={2} className="text-slate-400" />
                    <input
                      type="text"
                      placeholder="Search city..."
                      value={citySearch}
                      onChange={(e) => setCitySearch(e.target.value)}
                      className="navbar__city-search-input"
                      autoFocus
                    />
                  </div>
                  <div className="navbar__city-options">
                    {filteredCities.map((city) => (
                      <button
                        key={city}
                        type="button"
                        className={`navbar__city-item ${selectedCity === city ? "navbar__city-item--active" : ""}`}
                        onClick={() => handleSelectCity(city)}
                      >
                        <LocationIcon size={13} strokeWidth={2} />
                        <span>{city}</span>
                      </button>
                    ))}
                    {filteredCities.length === 0 && (
                      <div className="navbar__city-no-match">No city found</div>
                    )}
                  </div>
                </div>
              )}
            </div>

            <label className="navbar__search-field">
              <AnimatedIcon>
                <SearchIcon size={17} strokeWidth={1.7} aria-hidden="true" />
              </AnimatedIcon>
              <span className="sr-only">Search</span>
              <input
                type="search"
                placeholder="Search for restaurants, services, events..."
                className="navbar__search-input"
              />
            </label>
            <button
              type="button"
              className="navbar__search-submit"
              aria-label="Search"
            >
              <AnimatedIcon>
                <SearchIcon size={19} strokeWidth={2} aria-hidden="true" />
              </AnimatedIcon>
            </button>
          </div>

          {/* Action Links */}
          <div className="navbar__actions">
            <a href="#" className="navbar__action">
              <AnimatedIcon>
                <FavoritesIcon size={16} strokeWidth={1.8} aria-hidden="true" />
              </AnimatedIcon>
              <span className="navbar__action-label">Favorites</span>
            </a>
            <button
              type="button"
              className="navbar__action"
              onClick={() => {
                setAuthMode("login");
                setAuthOpen(true);
              }}
            >
              <AnimatedIcon>
                <LoginIcon size={16} strokeWidth={1.8} aria-hidden="true" />
              </AnimatedIcon>
              <span className="navbar__action-label">Login</span>
            </button>
            <button type="button" className="navbar__add-business">
              <AnimatedIcon>
                <AddIcon size={16} strokeWidth={2.2} aria-hidden="true" />
              </AnimatedIcon>
              <span>Add Business</span>
            </button>
          </div>
        </div>
      </div>

      {/* Authentication Modal */}
      <AuthModal
        isOpen={authOpen}
        onClose={() => setAuthOpen(false)}
        initialMode={authMode}
      />
    </>
  );
}
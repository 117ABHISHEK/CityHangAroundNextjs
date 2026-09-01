"use client";

import { useEffect, useRef, useState } from "react";
import Image from "next/image";
import AuthModal, { type AuthMode } from "@/src/features/auth";
import AnimatedIcon from "@/src/components/ui/animated-icon";
import { FALLBACK_CITIES, getCities } from "@/src/services/home";

type CityItem = { id: number; city_name: string; city_slug: string; city_image: string | null };
import {
  AddIcon,
  ChevronDown,
  FavoritesIcon,
  LocationIcon,
  LoginIcon,
  MenuIcon,
  SearchIcon,
} from "@/src/components/ui/icons";

const CITY_STORAGE_KEY = "cha_selected_city";

export default function PrimaryNavbar() {
  const [authOpen, setAuthOpen] = useState(false);
  const [authMode, setAuthMode] = useState<AuthMode>("login");
  const [cityDropdownOpen, setCityDropdownOpen] = useState(false);
  const [selectedCity, setSelectedCity] = useState<string>("Select City");
  const [citySearch, setCitySearch] = useState("");
  const [cities, setCities] = useState<CityItem[]>([]);

  const cityDropdownRef = useRef<HTMLDivElement>(null);
  const mobileCityRef = useRef<HTMLDivElement>(null);

  // Fetch cities from the Laravel backend API on mount
  useEffect(() => {
    const loadCities = async () => {
      try {
        const data = await getCities();
        setCities(data);

        // Restore previously selected city from localStorage
        if (typeof window !== "undefined") {
          const saved = localStorage.getItem(CITY_STORAGE_KEY);
          if (saved) {
            const match = data.find((c) => c.city_name === saved);
            if (match) setSelectedCity(match.city_name);
          }
        }
      } catch {
        setCities([...FALLBACK_CITIES]);
      }
    };

    void loadCities();
  }, []);

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      const target = event.target as Node;
      const clickedInsideDesktop = cityDropdownRef.current?.contains(target);
      const clickedInsideMobile = mobileCityRef.current?.contains(target);
      if (!clickedInsideDesktop && !clickedInsideMobile) {
        setCityDropdownOpen(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const filteredCities = cities.filter((city) =>
    city.city_name.toLowerCase().includes(citySearch.toLowerCase())
  );

  const handleSelectCity = (cityName: string) => {
    setSelectedCity(cityName);
    setCityDropdownOpen(false);
    setCitySearch("");
    localStorage.setItem(CITY_STORAGE_KEY, cityName);
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

          {/* Mobile City Selector (visible below 768px) */}
          <div className="navbar__mobile-city" ref={mobileCityRef}>
            <button
              type="button"
              className={`navbar__mobile-city-btn ${cityDropdownOpen ? "navbar__mobile-city-btn--open" : ""}`}
              onClick={() => setCityDropdownOpen((prev) => !prev)}
              aria-expanded={cityDropdownOpen}
              aria-haspopup="listbox"
            >
              <span className="navbar__mobile-city-text">
                {selectedCity === "Select City" ? "City" : selectedCity}
              </span>
              <ChevronDown
                size={12}
                strokeWidth={2}
                aria-hidden="true"
                className={`navbar__mobile-city-chevron ${cityDropdownOpen ? "navbar__mobile-city-chevron--open" : ""}`}
              />
            </button>

            {/* Mobile City Dropdown */}
            {cityDropdownOpen && (
              <div className="navbar__mobile-city-dropdown" role="listbox">
                <div className="navbar__mobile-city-search">
                  <SearchIcon size={14} strokeWidth={2} aria-hidden="true" />
                  <input
                    type="text"
                    placeholder="Search city..."
                    value={citySearch}
                    onChange={(e) => setCitySearch(e.target.value)}
                    className="navbar__mobile-city-search-input"
                    autoFocus
                  />
                </div>
                <div className="navbar__mobile-city-list">
                  {filteredCities.map((city) => (
                    <button
                      key={city.id}
                      type="button"
                      className={`navbar__mobile-city-item ${selectedCity === city.city_name ? "navbar__mobile-city-item--active" : ""}`}
                      onClick={() => handleSelectCity(city.city_name)}
                    >
                      <LocationIcon size={13} strokeWidth={2} />
                      <span>{city.city_name}</span>
                    </button>
                  ))}
                  {filteredCities.length === 0 && (
                    <div className="navbar__mobile-city-empty">No city found</div>
                  )}
                </div>
              </div>
            )}
          </div>

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
                        key={city.id}
                        type="button"
                        className={`navbar__city-item ${selectedCity === city.city_name ? "navbar__city-item--active" : ""}`}
                        onClick={() => handleSelectCity(city.city_name)}
                      >
                        <LocationIcon size={13} strokeWidth={2} />
                        <span>{city.city_name}</span>
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
            <a href="#" className="navbar__action navbar__mobile-fav">
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
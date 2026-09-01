"use client";

import { useState, useMemo } from "react";
import { SAMPLE_EVENTS } from "@/src/types/event";
import EventCard from "./components/EventCard";
import FeedAd from "./components/FeedAd";
import EventsSidebar from "./components/EventsSidebar";
import EventHeroBanner from "./components/EventHeroBanner";
import EventRightRail from "./components/EventRightRail";
import { FireIcon, ClockIcon, StarIcon } from "@/src/components/ui/icons";
import { X } from "lucide-react";
import "@/src/features/community/base.css";
import "@/src/features/community/components/CommunitySidebar/index.css";
import "@/src/features/community/components/FilterBar/index.css";
import "@/src/features/community/components/RightRail/index.css";
import "./index.css";

export default function EventHome() {
  const [selectedFeed, setSelectedFeed] = useState("all");
  const [activeFilter, setActiveFilter] = useState("hot");
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedCategory, setSelectedCategory] = useState("");
  const [selectedFormat, setSelectedFormat] = useState("");
  const [selectedPriceType, setSelectedPriceType] = useState("");
  const [selectedCity, setSelectedCity] = useState("");
  const [sortBy, setSortBy] = useState("hot");

  const hasActiveFilters = Boolean(
    searchQuery ||
    selectedCategory ||
    selectedFormat ||
    selectedPriceType ||
    selectedCity ||
    selectedFeed !== "all"
  );

  const handleResetFilters = () => {
    setSelectedFeed("all");
    setActiveFilter("hot");
    setSearchQuery("");
    setSelectedCategory("");
    setSelectedFormat("");
    setSelectedPriceType("");
    setSelectedCity("");
    setSortBy("hot");
  };

  const filteredEvents = useMemo(() => {
    const list = SAMPLE_EVENTS.filter((evt) => {
      // Feed filter
      if (selectedFeed === "featured" && !evt.featured) {
        return false;
      }
      if (selectedFeed === "weekend") {
        // e.g. food fest or upcoming
        if (evt.status !== "upcoming" && evt.status !== "live") {
          return false;
        }
      }

      // Search query
      if (searchQuery.trim()) {
        const q = searchQuery.toLowerCase();
        const matchesName = evt.name.toLowerCase().includes(q);
        const matchesVenue = evt.venueName.toLowerCase().includes(q);
        const matchesCity = evt.city.toLowerCase().includes(q);
        const matchesCategory =
          evt.parentCategory.toLowerCase().includes(q) ||
          evt.category.toLowerCase().includes(q);
        const matchesTags = evt.tags.some((t) => t.toLowerCase().includes(q));
        if (
          !matchesName &&
          !matchesVenue &&
          !matchesCity &&
          !matchesCategory &&
          !matchesTags
        ) {
          return false;
        }
      }

      // Category filter
      if (selectedCategory && evt.parentCategory !== selectedCategory) {
        return false;
      }

      // Format filter
      if (selectedFormat && evt.format !== selectedFormat) {
        return false;
      }

      // Price filter
      if (selectedPriceType === "free" && !evt.isFree) {
        return false;
      }
      if (selectedPriceType === "paid" && evt.isFree) {
        return false;
      }

      // City filter
      if (selectedCity) {
        if (selectedCity === "Online" && evt.format !== "online") {
          return false;
        }
        if (
          selectedCity !== "Online" &&
          !evt.city.toLowerCase().includes(selectedCity.toLowerCase())
        ) {
          return false;
        }
      }

      return true;
    });

    // Sorting
    return list.sort((a, b) => {
      if (sortBy === "price_asc") {
        return a.startingPrice - b.startingPrice;
      }
      if (sortBy === "price_desc") {
        return b.startingPrice - a.startingPrice;
      }
      if (sortBy === "popular") {
        return b.attendeesCount - a.attendeesCount;
      }
      if (sortBy === "top" || activeFilter === "top") {
        return b.attendeesCount - a.attendeesCount;
      }
      if (sortBy === "date" || activeFilter === "new") {
        return new Date(a.startDate).getTime() - new Date(b.startDate).getTime();
      }
      // default hot
      return (b.featured ? 1 : 0) - (a.featured ? 1 : 0);
    });
  }, [
    selectedFeed,
    searchQuery,
    selectedCategory,
    selectedFormat,
    selectedPriceType,
    selectedCity,
    sortBy,
    activeFilter,
  ]);

  return (
    <section className="community-page event-page-community">
      <div className="community-shell">
        {/* Left Side Filters Sidebar (Exact CommunitySidebar Style) */}
        <EventsSidebar
          selectedFeed={selectedFeed}
          onFeedChange={setSelectedFeed}
          selectedCategory={selectedCategory}
          onCategoryChange={setSelectedCategory}
          selectedFormat={selectedFormat}
          onFormatChange={setSelectedFormat}
          selectedPriceType={selectedPriceType}
          onPriceTypeChange={(price) => {
            setSelectedPriceType(price);
            if (price === "free") setActiveFilter("free");
          }}
          selectedCity={selectedCity}
          onCityChange={setSelectedCity}
          onResetFilters={handleResetFilters}
          hasActiveFilters={hasActiveFilters}
        />

        {/* Center Main Feed */}
        <main className="community-feed">
          {/* Compact Hero Banner with Background Image */}
          <EventHeroBanner
            searchQuery={searchQuery}
            onSearchChange={setSearchQuery}
          />

          {/* Exact Community Filter Bar */}
          <div className="community-filter-bar">
            <div className="community-filter-tabs">
              <button
                type="button"
                className={activeFilter === "hot" ? "is-active" : ""}
                onClick={() => {
                  setActiveFilter("hot");
                  setSelectedPriceType("");
                  setSortBy("hot");
                }}
              >
                <FireIcon size={14} />
                Hot
              </button>
              <button
                type="button"
                className={activeFilter === "new" ? "is-active" : ""}
                onClick={() => {
                  setActiveFilter("new");
                  setSelectedPriceType("");
                  setSortBy("date");
                }}
              >
                <ClockIcon size={14} />
                Upcoming
              </button>
              <button
                type="button"
                className={activeFilter === "top" ? "is-active" : ""}
                onClick={() => {
                  setActiveFilter("top");
                  setSelectedPriceType("");
                  setSortBy("popular");
                }}
              >
                <StarIcon size={14} />
                Top Rated
              </button>
              <button
                type="button"
                className={activeFilter === "free" ? "is-active" : ""}
                onClick={() => {
                  setActiveFilter("free");
                  setSelectedPriceType(selectedPriceType === "free" ? "" : "free");
                }}
              >
                🎟️ Free
              </button>
            </div>

            <select
              className="community-filter-select"
              value={sortBy}
              onChange={(e) => setSortBy(e.target.value)}
              aria-label="Sort events"
            >
              <option value="hot">Sort: Hot</option>
              <option value="date">Sort: Upcoming Date</option>
              <option value="price_asc">Sort: Price (Low to High)</option>
              <option value="price_desc">Sort: Price (High to Low)</option>
              <option value="popular">Sort: Most Popular</option>
            </select>
          </div>

          {/* Active Filter Chips */}
          {hasActiveFilters && (
            <div className="events-active-tags">
              {selectedCategory && (
                <button
                  type="button"
                  onClick={() => setSelectedCategory("")}
                  className="events-active-tag"
                >
                  Category: {selectedCategory} <X size={12} />
                </button>
              )}
              {selectedFormat && (
                <button
                  type="button"
                  onClick={() => setSelectedFormat("")}
                  className="events-active-tag"
                >
                  Format: {selectedFormat} <X size={12} />
                </button>
              )}
              {selectedPriceType && (
                <button
                  type="button"
                  onClick={() => {
                    setSelectedPriceType("");
                    if (activeFilter === "free") setActiveFilter("hot");
                  }}
                  className="events-active-tag"
                >
                  Pricing: {selectedPriceType === "free" ? "Free Only" : "Paid"} <X size={12} />
                </button>
              )}
              {selectedCity && (
                <button
                  type="button"
                  onClick={() => setSelectedCity("")}
                  className="events-active-tag"
                >
                  City: {selectedCity} <X size={12} />
                </button>
              )}
              {searchQuery && (
                <button
                  type="button"
                  onClick={() => setSearchQuery("")}
                  className="events-active-tag"
                >
                  "{searchQuery}" <X size={12} />
                </button>
              )}
              <button
                type="button"
                onClick={handleResetFilters}
                className="events-active-reset"
              >
                Clear all
              </button>
            </div>
          )}

          {/* Events Feed List */}
          {filteredEvents.length > 0 ? (
            <div className="events-cards-grid">
              {filteredEvents.map((event, idx) => (
                <div key={event.id}>
                  <EventCard event={event} />
                  {/* Insert an ad after every 2nd card */}
                  {(idx + 1) % 2 === 0 && idx < filteredEvents.length - 1 && (
                    <div className="feed-ad-slot">
                      <FeedAd index={Math.floor(idx / 2)} />
                    </div>
                  )}
                </div>
              ))}
            </div>
          ) : (
            <div className="events-empty-box">
              <div className="events-empty-box__icon">🔍</div>
              <h3 className="events-empty-box__title">No Events Found</h3>
              <p className="events-empty-box__desc">
                No events match your current filter selection. Try changing filters or searching with different keywords.
              </p>
              <button
                type="button"
                onClick={handleResetFilters}
                className="events-empty-box__btn"
              >
                Reset All Filters
              </button>
            </div>
          )}
        </main>

        {/* Right Rail (Exact Community RightRail Style) */}
        <EventRightRail />
      </div>
    </section>
  );
}

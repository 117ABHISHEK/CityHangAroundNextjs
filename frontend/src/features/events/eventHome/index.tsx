"use client";

import { useState, useMemo } from "react";
import Link from "next/link";
import { SAMPLE_EVENTS } from "@/src/types/event";
import EventCard from "./components/EventCard";
import EventsFilter from "./components/EventsFilter";
import { AddIcon, FireIcon, CalendarIcon } from "@/src/components/ui/icons";
import "./index.css";

const CATEGORY_CHIPS = [
  { label: "All Events", value: "" },
  { label: "🚀 Technology", value: "Technology" },
  { label: "🍔 Food & Drinks", value: "Food & Drinks" },
  { label: "🎶 Nightlife & Music", value: "Entertainment" },
  { label: "🎨 Workshops & Arts", value: "Workshops" },
  { label: "💼 Business & Startups", value: "Business" },
];

export default function EventHome() {
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedCategory, setSelectedCategory] = useState("");
  const [selectedFormat, setSelectedFormat] = useState("");
  const [selectedPriceType, setSelectedPriceType] = useState("");

  const filteredEvents = useMemo(() => {
    return SAMPLE_EVENTS.filter((evt) => {
      // Search query
      if (searchQuery.trim()) {
        const q = searchQuery.toLowerCase();
        const matchesName = evt.name.toLowerCase().includes(q);
        const matchesVenue = evt.venueName.toLowerCase().includes(q);
        const matchesCity = evt.city.toLowerCase().includes(q);
        const matchesTags = evt.tags.some((t) => t.toLowerCase().includes(q));
        if (!matchesName && !matchesVenue && !matchesCity && !matchesTags) {
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

      return true;
    });
  }, [searchQuery, selectedCategory, selectedFormat, selectedPriceType]);

  const featuredEvents = SAMPLE_EVENTS.filter((e) => e.featured);

  return (
    <div className="event-home">
      {/* Hero Section */}
      <section className="event-home__hero">
        <div className="event-home__container">
          <div className="event-home__hero-content">
            <div className="event-home__badge">
              <FireIcon size={16} />
              <span>Hyperlocal Experiences in Gujarat</span>
            </div>

            <h1 className="event-home__headline">
              Discover & Experience <span>Exciting Events</span> Near You
            </h1>

            <p className="event-home__subhead">
              From developer hackathons and tech summits to street food carnivals and sunset music festivals across Ahmedabad and Gandhinagar.
            </p>

            <div className="event-home__hero-actions">
              <Link href="/events/create" className="event-home__btn-primary">
                <AddIcon size={18} /> Host an Event
              </Link>
              <a href="#browse-events" className="event-home__btn-outline">
                Explore All Events ↓
              </a>
            </div>

            {/* Quick Category Chips */}
            <div className="event-home__pills-row">
              {CATEGORY_CHIPS.map((chip) => (
                <button
                  type="button"
                  key={chip.label}
                  onClick={() => setSelectedCategory(chip.value)}
                  className={`event-home__pill ${
                    selectedCategory === chip.value ? "event-home__pill--active" : ""
                  }`}
                >
                  {chip.label}
                </button>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Main Content Area */}
      <main id="browse-events" className="event-home__main">
        <div className="event-home__container">
          {/* Interactive Filters Bar */}
          <EventsFilter
            searchQuery={searchQuery}
            onSearchChange={setSearchQuery}
            selectedCategory={selectedCategory}
            onCategoryChange={setSelectedCategory}
            selectedFormat={selectedFormat}
            onFormatChange={setSelectedFormat}
            selectedPriceType={selectedPriceType}
            onPriceTypeChange={setSelectedPriceType}
          />

          {/* Results Summary */}
          <div className="event-home__results-head">
            <div>
              <h2 className="event-home__section-title">
                {selectedCategory ? `${selectedCategory} Events` : "Upcoming Events in Ahmedabad"}
              </h2>
              <p className="event-home__section-desc">
                Showing {filteredEvents.length} {filteredEvents.length === 1 ? "event" : "events"} matching your criteria
              </p>
            </div>

            <Link href="/events/create" className="event-home__host-link">
              + Post New Event
            </Link>
          </div>

          {/* Events Grid */}
          {filteredEvents.length > 0 ? (
            <div className="event-home__grid">
              {filteredEvents.map((event) => (
                <EventCard key={event.id} event={event} />
              ))}
            </div>
          ) : (
            <div className="event-home__empty">
              <div className="event-home__empty-icon">🔍</div>
              <h3 className="event-home__empty-title">No Events Found</h3>
              <p className="event-home__empty-desc">
                Try adjusting your search keywords or resetting your filter criteria.
              </p>
              <button
                type="button"
                onClick={() => {
                  setSearchQuery("");
                  setSelectedCategory("");
                  setSelectedFormat("");
                  setSelectedPriceType("");
                }}
                className="event-home__empty-btn"
              >
                Clear All Filters
              </button>
            </div>
          )}

          {/* Host an Event CTA Banner */}
          <section className="event-home__cta-banner">
            <div className="event-home__cta-content">
              <span className="event-home__cta-tag">FOR ORGANIZERS & BRANDS</span>
              <h3 className="event-home__cta-title">
                Planning a Meetup, Festival or Workshop?
              </h3>
              <p className="event-home__cta-desc">
                List your event on CityHangAround and reach thousands of enthusiastic locals with instant ticketing, QR check-ins, and verified analytics.
              </p>
              <Link href="/events/create" className="event-home__cta-btn">
                Launch Your Event in 5 Mins →
              </Link>
            </div>
          </section>
        </div>
      </main>
    </div>
  );
}

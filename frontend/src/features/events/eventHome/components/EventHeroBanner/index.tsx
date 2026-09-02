"use client";

import Link from "next/link";
import { SearchIcon, AddIcon } from "@/src/components/ui/icons";
import "./index.css";

interface EventHeroBannerProps {
  searchQuery: string;
  onSearchChange: (q: string) => void;
}

export default function EventHeroBanner({
  searchQuery,
  onSearchChange,
}: EventHeroBannerProps) {
  return (
    <div className="event-hero-banner">
      <div className="event-hero-banner__content">
        <div className="event-hero-banner__badge">
          <span>🔥 Hyperlocal Experiences</span>
        </div>

        <h1 className="event-hero-banner__title">
          Discover & Experience <span>Events</span> in Ahmedabad
        </h1>

        <p className="event-hero-banner__subhead">
          Tech summits, music nights, food fests, and workshops across Gujarat.
        </p>

        {/* Integrated compact search & host action bar */}
        <div className="event-hero-banner__search-row">
          <div className="event-hero-banner__input-wrap">
            <SearchIcon size={16} className="event-hero-banner__search-icon" />
            <input
              type="text"
              placeholder="Search by event title, venue, or category..."
              value={searchQuery}
              onChange={(e) => onSearchChange(e.target.value)}
              className="event-hero-banner__input"
            />
            {searchQuery && (
              <button
                type="button"
                onClick={() => onSearchChange("")}
                className="event-hero-banner__clear-btn"
              >
                ×
              </button>
            )}
          </div>

          <Link href="/events/create" className="event-hero-banner__host-btn">
            <AddIcon size={15} />
            <span>Host Event</span>
          </Link>
        </div>
      </div>
    </div>
  );
}

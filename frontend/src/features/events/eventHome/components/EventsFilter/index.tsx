"use client";

import { SearchIcon } from "@/src/components/ui/icons";
import "./index.css";

interface EventsFilterProps {
  searchQuery: string;
  onSearchChange: (q: string) => void;
  selectedCategory: string;
  onCategoryChange: (cat: string) => void;
  selectedFormat: string;
  onFormatChange: (fmt: string) => void;
  selectedPriceType: string;
  onPriceTypeChange: (priceType: string) => void;
}

const CATEGORIES = [
  "All Categories",
  "Technology",
  "Food & Drinks",
  "Entertainment",
  "Workshops",
  "Business",
];

export default function EventsFilter({
  searchQuery,
  onSearchChange,
  selectedCategory,
  onCategoryChange,
  selectedFormat,
  onFormatChange,
  selectedPriceType,
  onPriceTypeChange,
}: EventsFilterProps) {
  return (
    <div className="events-filter-bar">
      {/* Search Input */}
      <div className="events-filter-bar__search">
        <SearchIcon size={18} className="events-filter-bar__search-icon" />
        <input
          type="text"
          placeholder="Search by event title, keyword, or venue..."
          value={searchQuery}
          onChange={(e) => onSearchChange(e.target.value)}
          className="events-filter-bar__search-input"
        />
        {searchQuery && (
          <button
            type="button"
            onClick={() => onSearchChange("")}
            className="events-filter-bar__clear-btn"
          >
            ×
          </button>
        )}
      </div>

      {/* Dropdown Filters */}
      <div className="events-filter-bar__controls">
        {/* Category Filter */}
        <select
          value={selectedCategory}
          onChange={(e) => onCategoryChange(e.target.value)}
          className="events-filter-bar__select"
        >
          {CATEGORIES.map((c) => (
            <option key={c} value={c === "All Categories" ? "" : c}>
              {c}
            </option>
          ))}
        </select>

        {/* Format Filter */}
        <select
          value={selectedFormat}
          onChange={(e) => onFormatChange(e.target.value)}
          className="events-filter-bar__select"
        >
          <option value="">All Formats</option>
          <option value="in-person">📍 In-Person</option>
          <option value="online">🌐 Online / Virtual</option>
          <option value="hybrid">🔀 Hybrid</option>
        </select>

        {/* Price Filter */}
        <select
          value={selectedPriceType}
          onChange={(e) => onPriceTypeChange(e.target.value)}
          className="events-filter-bar__select"
        >
          <option value="">All Pricing</option>
          <option value="free">Free Only</option>
          <option value="paid">Paid Tickets</option>
        </select>
      </div>
    </div>
  );
}

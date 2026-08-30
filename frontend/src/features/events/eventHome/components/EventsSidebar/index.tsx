"use client";

import {
  Flame,
  Sparkles,
  Calendar,
  MapPin,
  Globe,
  Ticket,
  CreditCard,
  RotateCcw,
} from "lucide-react";
import AdSlot from "@/src/features/community/components/AdSlot";
import "./index.css";

interface EventsSidebarProps {
  selectedFeed: string;
  onFeedChange: (feed: string) => void;
  selectedCategory: string;
  onCategoryChange: (cat: string) => void;
  selectedFormat: string;
  onFormatChange: (fmt: string) => void;
  selectedPriceType: string;
  onPriceTypeChange: (price: string) => void;
  selectedCity: string;
  onCityChange: (city: string) => void;
  onResetFilters: () => void;
  hasActiveFilters: boolean;
}

const CATEGORIES = [
  { id: "Technology", icon: "🚀", name: "Tech & AI Summits", color: "#2563eb" },
  { id: "Food & Drinks", icon: "🍕", name: "Food & Dining Fests", color: "#f5720e" },
  { id: "Entertainment", icon: "🎶", name: "Music & Nightlife", color: "#ec4899" },
  { id: "Workshops", icon: "🎨", name: "Arts & Workshops", color: "#8b5cf6" },
  { id: "Business", icon: "💼", name: "Startups & Business", color: "#1fa672" },
];

const CITIES = [
  { id: "Ahmedabad", icon: "🏙️", name: "Ahmedabad", color: "#ef452f" },
  { id: "Gandhinagar", icon: "🏛️", name: "Gandhinagar", color: "#2563eb" },
  { id: "Online", icon: "🌐", name: "Global Virtual", color: "#8b5cf6" },
];

export default function EventsSidebar({
  selectedFeed,
  onFeedChange,
  selectedCategory,
  onCategoryChange,
  selectedFormat,
  onFormatChange,
  selectedPriceType,
  onPriceTypeChange,
  selectedCity,
  onCityChange,
  onResetFilters,
  hasActiveFilters,
}: EventsSidebarProps) {
  return (
    <aside className="community-sidebar">
      {/* Home / Feeds Group */}
      <div className="community-side-group">
        <div className="community-side-title-row">
          <div className="community-side-title">Feeds</div>
          {hasActiveFilters && (
            <button
              type="button"
              onClick={onResetFilters}
              className="community-side-reset-pill"
              title="Reset all filters"
            >
              <RotateCcw size={11} /> Reset
            </button>
          )}
        </div>
        <nav className="community-side-nav">
          <button
            type="button"
            onClick={() => {
              onFeedChange("all");
              onCategoryChange("");
            }}
            className={`community-side-link ${selectedFeed === "all" && !selectedCategory ? "is-active" : ""}`}
          >
            <span className="community-side-icon">
              <Flame size={18} />
            </span>
            All Events
          </button>
          <button
            type="button"
            onClick={() => onFeedChange("featured")}
            className={`community-side-link ${selectedFeed === "featured" ? "is-active" : ""}`}
          >
            <span className="community-side-icon">
              <Sparkles size={18} />
            </span>
            Featured
          </button>
          <button
            type="button"
            onClick={() => onFeedChange("weekend")}
            className={`community-side-link ${selectedFeed === "weekend" ? "is-active" : ""}`}
          >
            <span className="community-side-icon">
              <Calendar size={18} />
            </span>
            This Weekend
          </button>
        </nav>
      </div>

      {/* Event Categories Group (Exact Match with Community Page Your Communities) */}
      <div className="community-side-group">
        <div className="community-side-title">Event Categories</div>
        <nav className="community-side-nav">
          {CATEGORIES.map((cat) => (
            <button
              key={cat.id}
              type="button"
              onClick={() => onCategoryChange(selectedCategory === cat.id ? "" : cat.id)}
              className={`community-side-link ${selectedCategory === cat.id ? "is-active" : ""}`}
            >
              <span
                className="community-side-avatar"
                style={{ backgroundColor: cat.color }}
              >
                {cat.icon}
              </span>
              {cat.name}
            </button>
          ))}
        </nav>
      </div>

      {/* Left Sidebar Sponsored Ad Slot */}
      <div className="community-side-group">
        <AdSlot
          variant="landscape"
          title="Sunset Waves Music Night — 30% Off Early Bird"
          cta="Grab Pass"
          image="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=800&q=80"
        />
      </div>

      {/* Filter By Group */}
      <div className="community-side-group">
        <div className="community-side-title">Filter By</div>
        <nav className="community-side-nav">
          <button
            type="button"
            onClick={() => onFormatChange(selectedFormat === "in-person" ? "" : "in-person")}
            className={`community-side-link ${selectedFormat === "in-person" ? "is-active" : ""}`}
          >
            <span className="community-side-icon">
              <MapPin size={18} />
            </span>
            In-Person
          </button>
          <button
            type="button"
            onClick={() => onFormatChange(selectedFormat === "online" ? "" : "online")}
            className={`community-side-link ${selectedFormat === "online" ? "is-active" : ""}`}
          >
            <span className="community-side-icon">
              <Globe size={18} />
            </span>
            Virtual / Online
          </button>
          <button
            type="button"
            onClick={() => onPriceTypeChange(selectedPriceType === "free" ? "" : "free")}
            className={`community-side-link ${selectedPriceType === "free" ? "is-active" : ""}`}
          >
            <span className="community-side-icon">
              <Ticket size={18} />
            </span>
            Free Entry
          </button>
          <button
            type="button"
            onClick={() => onPriceTypeChange(selectedPriceType === "paid" ? "" : "paid")}
            className={`community-side-link ${selectedPriceType === "paid" ? "is-active" : ""}`}
          >
            <span className="community-side-icon">
              <CreditCard size={18} />
            </span>
            Paid Tickets
          </button>
        </nav>
      </div>

      {/* Cities Group */}
      <div className="community-side-group">
        <div className="community-side-title">Cities</div>
        <nav className="community-side-nav">
          {CITIES.map((c) => (
            <button
              key={c.id}
              type="button"
              onClick={() => onCityChange(selectedCity === c.id ? "" : c.id)}
              className={`community-side-link ${selectedCity === c.id ? "is-active" : ""}`}
            >
              <span
                className="community-side-avatar"
                style={{ backgroundColor: c.color }}
              >
                {c.icon}
              </span>
              {c.name}
            </button>
          ))}
        </nav>
      </div>

      {/* Left Sidebar Secondary Ad Slot */}
      <div className="community-side-group">
        <AdSlot
          variant="square"
          title="Heritage & Street Photography Walk"
          cta="Book Seat"
          image="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=600&q=80"
        />
      </div>
    </aside>
  );
}

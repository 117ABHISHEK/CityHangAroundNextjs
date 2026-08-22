"use client";

import { useState } from "react";
import type { EventFormData } from "@/src/types/event";
import EventImageUpload from "../EventImageUpload";
import EventStatus from "../EventStatus";
import "./index.css";

interface BasicInformationProps {
  formData: EventFormData;
  updateField: <K extends keyof EventFormData>(field: K, value: EventFormData[K]) => void;
}

const PARENT_CATEGORIES = [
  "Technology & Innovation",
  "Food & Drinks",
  "Entertainment & Nightlife",
  "Arts & Culture",
  "Business & Networking",
  "Sports & Fitness",
  "Workshops & Learning",
  "Community & Social",
];

const SUB_CATEGORIES: Record<string, string[]> = {
  "Technology & Innovation": ["Conferences & Summits", "Hackathons & Competitions", "Meetups & Tech Talks", "Web3 & AI"],
  "Food & Drinks": ["Food Festivals", "Wine & Dine", "Chef Masterclasses", "Street Food Carnivals"],
  "Entertainment & Nightlife": ["Concerts & Live Music", "DJ Nights & Clubbing", "Comedy Shows", "Theatre & Drama"],
  "Arts & Culture": ["Exhibitions", "Heritage Walks", "Photography", "Painting & Crafts"],
  "Business & Networking": ["Investor Mixers", "Startup Pitching", "Trade Expos", "Executive Roundtables"],
  "Sports & Fitness": ["Marathons & Runs", "Yoga & Wellness", "Tournaments", "Adventure Sports"],
  "Workshops & Learning": ["Skill Bootcamps", "Design Thinking", "Cooking Masterclasses", "Finance & Trading"],
  "Community & Social": ["Charity & Volunteering", "Eco & Green Drives", "Cultural Festivals", "Pet Meetups"],
};

export default function BasicInformation({
  formData,
  updateField,
}: BasicInformationProps) {
  const [tagInput, setTagInput] = useState("");

  const handleAddTag = (e: React.KeyboardEvent<HTMLInputElement>) => {
    if (e.key === "Enter" || e.key === ",") {
      e.preventDefault();
      const cleanTag = tagInput.trim().replace(/^#/, "");
      if (cleanTag && !formData.tags.includes(cleanTag) && formData.tags.length < 8) {
        updateField("tags", [...formData.tags, cleanTag]);
        setTagInput("");
      }
    }
  };

  const handleRemoveTag = (tagToRemove: string) => {
    updateField(
      "tags",
      formData.tags.filter((t) => t !== tagToRemove)
    );
  };

  const subCategories = formData.parentCategory
    ? SUB_CATEGORIES[formData.parentCategory] || []
    : [];

  return (
    <div className="basic-info">
      <div className="basic-info__intro">
        <h2 className="basic-info__heading">Basic Information</h2>
        <p className="basic-info__subheading">
          Set up the primary identity of your event to attract attendees and search discovery.
        </p>
      </div>

      {/* Event Title */}
      <div className="basic-info__form-group">
        <div className="basic-info__label-row">
          <label htmlFor="eventName" className="basic-info__label">
            Event Name <span className="basic-info__req">*</span>
          </label>
          <span className="basic-info__count">
            {formData.name.length}/100
          </span>
        </div>
        <input
          id="eventName"
          type="text"
          maxLength={100}
          placeholder="e.g., Gujarat Tech Summit 2026 or Sunset Waves Music Night"
          value={formData.name}
          onChange={(e) => updateField("name", e.target.value)}
          className="basic-info__input"
        />
      </div>

      {/* Category Dropdowns */}
      <div className="basic-info__grid-2">
        <div className="basic-info__form-group">
          <label htmlFor="parentCategory" className="basic-info__label">
            Parent Category <span className="basic-info__req">*</span>
          </label>
          <select
            id="parentCategory"
            value={formData.parentCategory}
            onChange={(e) => {
              updateField("parentCategory", e.target.value);
              updateField("category", "");
            }}
            className="basic-info__select"
          >
            <option value="">Select Parent Category</option>
            {PARENT_CATEGORIES.map((cat) => (
              <option key={cat} value={cat}>
                {cat}
              </option>
            ))}
          </select>
        </div>

        <div className="basic-info__form-group">
          <label htmlFor="subCategory" className="basic-info__label">
            Subcategory <span className="basic-info__req">*</span>
          </label>
          <select
            id="subCategory"
            value={formData.category}
            onChange={(e) => updateField("category", e.target.value)}
            disabled={!formData.parentCategory}
            className="basic-info__select"
          >
            <option value="">
              {formData.parentCategory ? "Select Subcategory" : "Select Parent Category first"}
            </option>
            {subCategories.map((sub) => (
              <option key={sub} value={sub}>
                {sub}
              </option>
            ))}
          </select>
        </div>
      </div>

      {/* Tags Input */}
      <div className="basic-info__form-group">
        <div className="basic-info__label-row">
          <label htmlFor="tagInput" className="basic-info__label">
            Event Tags <span className="basic-info__hint">(Up to 8 keywords)</span>
          </label>
          <span className="basic-info__count">{formData.tags.length}/8 tags</span>
        </div>
        <div className="basic-info__tags-container">
          {formData.tags.map((tag) => (
            <span key={tag} className="basic-info__tag-chip">
              #{tag}
              <button
                type="button"
                className="basic-info__tag-remove"
                onClick={() => handleRemoveTag(tag)}
                aria-label={`Remove tag ${tag}`}
              >
                ×
              </button>
            </span>
          ))}
          {formData.tags.length < 8 && (
            <input
              id="tagInput"
              type="text"
              placeholder="Type tag & press Enter (e.g. AI, LiveMusic)"
              value={tagInput}
              onChange={(e) => setTagInput(e.target.value)}
              onKeyDown={handleAddTag}
              className="basic-info__tag-input"
            />
          )}
        </div>
      </div>

      {/* Short Description */}
      <div className="basic-info__form-group">
        <div className="basic-info__label-row">
          <label htmlFor="shortDesc" className="basic-info__label">
            Short Summary / Catchphrase <span className="basic-info__req">*</span>
          </label>
          <span className="basic-info__count">
            {formData.shortDescription.length}/250
          </span>
        </div>
        <textarea
          id="shortDesc"
          rows={3}
          maxLength={250}
          placeholder="A catchy 1-2 sentence pitch summarizing what makes this event unmissable..."
          value={formData.shortDescription}
          onChange={(e) => updateField("shortDescription", e.target.value)}
          className="basic-info__textarea"
        />
      </div>

      {/* Cover Image Upload */}
      <EventImageUpload
        coverImage={formData.coverImage}
        onChange={(img) => updateField("coverImage", img)}
      />

      {/* Event Status Selector */}
      <EventStatus
        status={formData.status}
        onChange={(st) => updateField("status", st)}
      />
    </div>
  );
}

"use client";

import Image from "next/image";
import type { EventFormData } from "@/src/types/event";
import {
  CalendarIcon,
  LocationIcon,
  BadgeIndianRupeeIcon,
  StarIcon,
} from "@/src/components/ui/icons";
import { formatDate, formatDayMonth } from "@/src/utils/dateHelpers";
import "./index.css";

interface EventPreviewCardProps {
  formData: EventFormData;
}

export default function EventPreviewCard({ formData }: EventPreviewCardProps) {
  const minPrice = formData.isFree
    ? 0
    : formData.ticketTiers.length > 0
    ? Math.min(...formData.ticketTiers.map((t) => t.price))
    : 0;

  const dateBadge = formatDayMonth(formData.startDate || "2026-08-28");

  return (
    <div className="preview-card">
      <div className="preview-card__image-container">
        {formData.coverImage ? (
          <Image
            src={formData.coverImage}
            alt={formData.name || "Event preview"}
            fill
            className="preview-card__img"
            unoptimized
          />
        ) : (
          <div className="preview-card__placeholder">
            <span className="preview-card__placeholder-icon">🎪</span>
            <span className="preview-card__placeholder-text">
              Cover image will appear here
            </span>
          </div>
        )}

        {/* Floating Date Badge */}
        <div className="preview-card__date-badge">
          <span className="preview-card__date-month">{dateBadge.month}</span>
          <span className="preview-card__date-day">{dateBadge.day}</span>
        </div>

        {/* Floating Category Pill */}
        <div className="preview-card__cat-pill">
          {formData.category || formData.parentCategory || "Featured Event"}
        </div>
      </div>

      <div className="preview-card__body">
        {/* Status Chip */}
        <div className="preview-card__meta-top">
          <span
            className={`preview-card__status preview-card__status--${formData.status}`}
          >
            ● {formData.status.toUpperCase()}
          </span>
          <span className="preview-card__format-tag">
            {formData.format === "in-person"
              ? "📍 In-Person"
              : formData.format === "online"
              ? "🌐 Virtual / Online"
              : "🔀 Hybrid Event"}
          </span>
        </div>

        {/* Title */}
        <h4 className="preview-card__title">
          {formData.name.trim() || "Untitled Event Name"}
        </h4>

        {/* Short description */}
        <p className="preview-card__desc">
          {formData.shortDescription.trim() ||
            "A short description summarizing this event will be displayed to discoverers here."}
        </p>

        {/* Date & Location Row */}
        <div className="preview-card__info-list">
          <div className="preview-card__info-item">
            <CalendarIcon size={14} className="preview-card__info-icon" />
            <span>
              {formData.startDate ? formatDate(formData.startDate) : "Aug 28, 2026"}
              {formData.startTime ? ` • ${formData.startTime}` : ""}
            </span>
          </div>

          <div className="preview-card__info-item">
            <LocationIcon size={14} className="preview-card__info-icon" />
            <span className="preview-card__truncate">
              {formData.venueName || (formData.city ? `${formData.city}` : "Ahmedabad")}
            </span>
          </div>
        </div>

        {/* Footer with Price & Organizer */}
        <div className="preview-card__footer">
          <div className="preview-card__organizer">
            <div className="preview-card__org-avatar">
              {(formData.organizerName || "O").charAt(0).toUpperCase()}
            </div>
            <span className="preview-card__org-name">
              {formData.organizerName || "Organizer"}
            </span>
          </div>

          <div className="preview-card__price-badge">
            {formData.isFree || minPrice === 0 ? (
              <span className="preview-card__price-free">FREE</span>
            ) : (
              <span className="preview-card__price-val">
                From ₹{minPrice}
              </span>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}

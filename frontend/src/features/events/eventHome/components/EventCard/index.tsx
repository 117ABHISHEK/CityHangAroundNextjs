"use client";

import Link from "next/link";
import Image from "next/image";
import type { EventItem } from "@/src/types/event";
import {
  CalendarIcon,
  LocationIcon,
  BookmarkIcon,
  BadgeIndianRupeeIcon,
} from "@/src/components/ui/icons";
import { formatDate, formatDayMonth } from "@/src/utils/dateHelpers";
import "./index.css";

interface EventCardProps {
  event: EventItem;
}

export default function EventCard({ event }: EventCardProps) {
  const dateBadge = formatDayMonth(event.startDate);

  return (
    <article className="event-item-card">
      <div className="event-item-card__media">
        <Link href={`/events/${event.slug}`} className="event-item-card__media-link">
          <Image
            src={event.coverImage}
            alt={event.name}
            fill
            className="event-item-card__img"
            unoptimized
          />
        </Link>

        {/* Date Badge */}
        <div className="event-item-card__date-chip">
          <span className="event-item-card__date-month">{dateBadge.month}</span>
          <span className="event-item-card__date-day">{dateBadge.day}</span>
        </div>

        {/* Category Pill */}
        <span className="event-item-card__cat-tag">{event.category}</span>

        {/* Bookmark Button */}
        <button
          type="button"
          className="event-item-card__bookmark"
          aria-label="Bookmark event"
        >
          <BookmarkIcon size={16} />
        </button>
      </div>

      <div className="event-item-card__content">
        {/* Meta format */}
        <div className="event-item-card__meta">
          <span
            className={`event-item-card__status-dot event-item-card__status-dot--${event.status}`}
          />
          <span className="event-item-card__status-text">
            {event.status === "live" ? "LIVE NOW" : event.status.toUpperCase()}
          </span>
          <span className="event-item-card__dot">•</span>
          <span className="event-item-card__format">
            {event.format === "in-person"
              ? "In-Person"
              : event.format === "online"
              ? "Virtual"
              : "Hybrid"}
          </span>
        </div>

        {/* Title */}
        <h3 className="event-item-card__title">
          <Link href={`/events/${event.slug}`}>{event.name}</Link>
        </h3>

        {/* Short Description */}
        <p className="event-item-card__desc">{event.shortDescription}</p>

        {/* Details row */}
        <div className="event-item-card__info-group">
          <div className="event-item-card__info-row">
            <CalendarIcon size={14} className="event-item-card__icon" />
            <span>
              {formatDate(event.startDate)} • {event.startTime}
            </span>
          </div>

          <div className="event-item-card__info-row">
            <LocationIcon size={14} className="event-item-card__icon" />
            <span className="event-item-card__truncate">
              {event.venueName}, {event.city}
            </span>
          </div>
        </div>

        {/* Card Footer */}
        <div className="event-item-card__footer">
          <div className="event-item-card__pricing">
            <span className="event-item-card__price-label">Price</span>
            <div className="event-item-card__price-val">
              {event.isFree ? (
                <span className="event-item-card__free">FREE</span>
              ) : (
                <span className="event-item-card__paid">
                  ₹{event.startingPrice} <small>onwards</small>
                </span>
              )}
            </div>
          </div>

          <Link
            href={`/events/${event.slug}`}
            className="event-item-card__cta-btn"
          >
            Get Tickets
          </Link>
        </div>
      </div>
    </article>
  );
}

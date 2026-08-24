"use client";

import { useState } from "react";
import Image from "next/image";
import Link from "next/link";
import type { EventItem, TicketTier } from "@/src/types/event";
import {
  CalendarIcon,
  LocationIcon,
  ClockIcon,
  CheckIcon,
  Share2Icon,
  BookmarkIcon,
  BadgeIcon,
  ShieldIcon,
  BadgeIndianRupeeIcon,
} from "@/src/components/ui/icons";
import { formatDate, formatDayMonth } from "@/src/utils/dateHelpers";
import "./index.css";

interface EventDetailProps {
  event: EventItem;
}

export default function EventDetail({ event }: EventDetailProps) {
  const [selectedTierId, setSelectedTierId] = useState<string>(
    event.ticketTiers?.[0]?.id || ""
  );
  const [ticketQuantity, setTicketQuantity] = useState<number>(1);
  const [isBooked, setIsBooked] = useState(false);
  const [isBookmarked, setIsBookmarked] = useState(false);

  const selectedTier =
    event.ticketTiers?.find((t) => t.id === selectedTierId) ||
    event.ticketTiers?.[0];

  const unitPrice = selectedTier ? selectedTier.price : event.startingPrice;
  const totalPrice = unitPrice * ticketQuantity;
  const dateBadge = formatDayMonth(event.startDate);

  const handleShare = () => {
    if (navigator.clipboard) {
      navigator.clipboard.writeText(window.location.href);
      alert("Event link copied to clipboard!");
    }
  };

  return (
    <div className="event-detail-page">
      {/* Top Breadcrumb Header */}
      <div className="event-detail__top-nav">
        <div className="event-detail__container">
          <div className="event-detail__breadcrumbs">
            <Link href="/events">Events</Link>
            <span>/</span>
            <Link href={`/events?category=${event.parentCategory}`}>
              {event.parentCategory}
            </Link>
            <span>/</span>
            <span className="event-detail__breadcrumb-active">{event.name}</span>
          </div>
        </div>
      </div>

      {/* Hero Banner Section */}
      <section className="event-detail__hero">
        <div className="event-detail__container">
          <div className="event-detail__hero-grid">
            <div className="event-detail__hero-content">
              <div className="event-detail__hero-tags">
                <span className="event-detail__cat-badge">{event.category}</span>
                <span
                  className={`event-detail__status-badge event-detail__status-badge--${event.status}`}
                >
                  ● {event.status.toUpperCase()}
                </span>
                <span className="event-detail__format-badge">
                  {event.format === "in-person"
                    ? "📍 In-Person"
                    : event.format === "online"
                    ? "🌐 Virtual"
                    : "🔀 Hybrid"}
                </span>
              </div>

              <h1 className="event-detail__title">{event.name}</h1>
              <p className="event-detail__short-desc">{event.shortDescription}</p>

              {/* Quick Meta Chips */}
              <div className="event-detail__meta-chips">
                <div className="event-detail__meta-chip">
                  <CalendarIcon size={16} className="event-detail__chip-icon" />
                  <div>
                    <strong>{formatDate(event.startDate)}</strong>
                    <span>
                      {event.startTime} - {event.endTime}
                    </span>
                  </div>
                </div>

                <div className="event-detail__meta-chip">
                  <LocationIcon size={16} className="event-detail__chip-icon" />
                  <div>
                    <strong>{event.venueName}</strong>
                    <span>{event.city}</span>
                  </div>
                </div>
              </div>

              {/* Organizer Row */}
              <div className="event-detail__organizer-row">
                <div className="event-detail__org-avatar">
                  {event.organizerAvatar ? (
                    <Image
                      src={event.organizerAvatar}
                      alt={event.organizerName}
                      fill
                      className="event-detail__org-img"
                      unoptimized
                    />
                  ) : (
                    <span>{event.organizerName.charAt(0)}</span>
                  )}
                </div>
                <div className="event-detail__org-info">
                  <span className="event-detail__org-label">Organized by</span>
                  <div className="event-detail__org-name-wrap">
                    <span className="event-detail__org-name">
                      {event.organizerName}
                    </span>
                    {event.organizerVerified && (
                      <span className="event-detail__verified-badge" title="Verified Organizer">
                        <BadgeIcon size={14} /> Verified
                      </span>
                    )}
                  </div>
                </div>
              </div>
            </div>

            {/* Hero Cover Media */}
            <div className="event-detail__hero-media">
              <Image
                src={event.coverImage}
                alt={event.name}
                fill
                className="event-detail__hero-img"
                priority
                unoptimized
              />
              <div className="event-detail__hero-date-badge">
                <span className="event-detail__date-month">{dateBadge.month}</span>
                <span className="event-detail__date-day">{dateBadge.day}</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Main Body Layout: Left Details / Right Ticketing */}
      <div className="event-detail__container event-detail__body-layout">
        {/* Left Column: Details, Agenda, Venue */}
        <div className="event-detail__main-col">
          {/* About Event */}
          <section className="event-detail__section-box">
            <h2 className="event-detail__box-title">About this Event</h2>
            <div className="event-detail__description-text">
              {event.fullDescription.split("\n\n").map((para, i) => (
                <p key={i}>{para}</p>
              ))}
            </div>

            {/* Tags list */}
            {event.tags && event.tags.length > 0 && (
              <div className="event-detail__tags-row">
                {event.tags.map((t) => (
                  <span key={t} className="event-detail__tag-item">
                    #{t}
                  </span>
                ))}
              </div>
            )}
          </section>

          {/* Agenda & Schedule Timeline */}
          {event.agenda && event.agenda.length > 0 && (
            <section className="event-detail__section-box">
              <h2 className="event-detail__box-title">Event Schedule & Sessions</h2>
              <div className="event-detail__timeline">
                {event.agenda.map((slot) => (
                  <div key={slot.id} className="event-detail__timeline-item">
                    <div className="event-detail__timeline-time">{slot.time}</div>
                    <div className="event-detail__timeline-content">
                      <h4 className="event-detail__timeline-title">{slot.title}</h4>
                      {slot.speaker && (
                        <div className="event-detail__timeline-speaker">
                          🎤 {slot.speaker}
                        </div>
                      )}
                      {slot.description && (
                        <p className="event-detail__timeline-desc">
                          {slot.description}
                        </p>
                      )}
                    </div>
                  </div>
                ))}
              </div>
            </section>
          )}

          {/* Location & Map Section */}
          <section className="event-detail__section-box">
            <h2 className="event-detail__box-title">Location & Venue</h2>
            <div className="event-detail__venue-card">
              <div className="event-detail__venue-icon">
                <LocationIcon size={24} />
              </div>
              <div className="event-detail__venue-info">
                <h4 className="event-detail__venue-name">{event.venueName}</h4>
                <p className="event-detail__venue-address">{event.address}</p>
                <a
                  href={`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
                    `${event.venueName} ${event.address}`
                  )}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="event-detail__map-link"
                >
                  Open in Google Maps ↗
                </a>
              </div>
            </div>
          </section>

          {/* Gallery Images */}
          {event.galleryImages && event.galleryImages.length > 0 && (
            <section className="event-detail__section-box">
              <h2 className="event-detail__box-title">Event Gallery</h2>
              <div className="event-detail__gallery-grid">
                {event.galleryImages.map((imgUrl, idx) => (
                  <div key={idx} className="event-detail__gallery-item">
                    <Image
                      src={imgUrl}
                      alt={`Gallery photo ${idx + 1}`}
                      fill
                      className="event-detail__gallery-img"
                      unoptimized
                    />
                  </div>
                ))}
              </div>
            </section>
          )}
        </div>

        {/* Right Column: Sticky Ticket Booking Widget */}
        <div className="event-detail__side-col">
          <div className="event-detail__ticket-widget">
            <div className="event-detail__widget-header">
              <div>
                <span className="event-detail__widget-label">Registration</span>
                <h3 className="event-detail__widget-title">Get Tickets</h3>
              </div>
              <div className="event-detail__actions-group">
                <button
                  type="button"
                  onClick={handleShare}
                  className="event-detail__action-icon-btn"
                  title="Share event"
                >
                  <Share2Icon size={16} />
                </button>
                <button
                  type="button"
                  onClick={() => setIsBookmarked(!isBookmarked)}
                  className={`event-detail__action-icon-btn ${
                    isBookmarked ? "event-detail__action-icon-btn--active" : ""
                  }`}
                  title="Bookmark"
                >
                  <BookmarkIcon size={16} />
                </button>
              </div>
            </div>

            {/* Ticket Tier Selection */}
            {event.ticketTiers && event.ticketTiers.length > 0 ? (
              <div className="event-detail__tier-options">
                <label className="event-detail__tier-label">Select Ticket Tier:</label>
                {event.ticketTiers.map((tier) => {
                  const isSelected = tier.id === selectedTierId;
                  return (
                    <div
                      key={tier.id}
                      onClick={() => setSelectedTierId(tier.id)}
                      className={`event-detail__tier-radio ${
                        isSelected ? "event-detail__tier-radio--active" : ""
                      }`}
                    >
                      <div className="event-detail__tier-radio-top">
                        <span className="event-detail__tier-name">{tier.name}</span>
                        <span className="event-detail__tier-price">
                          {tier.price === 0 ? "FREE" : `₹${tier.price}`}
                        </span>
                      </div>
                      {tier.description && (
                        <p className="event-detail__tier-desc">{tier.description}</p>
                      )}
                      {tier.perks && tier.perks.length > 0 && (
                        <div className="event-detail__tier-perks">
                          {tier.perks.map((p) => (
                            <span key={p} className="event-detail__perk-pill">
                              ✓ {p}
                            </span>
                          ))}
                        </div>
                      )}
                    </div>
                  );
                })}
              </div>
            ) : null}

            {/* Quantity Selector */}
            <div className="event-detail__quantity-row">
              <span className="event-detail__qty-label">Quantity</span>
              <div className="event-detail__qty-controls">
                <button
                  type="button"
                  onClick={() => setTicketQuantity((q) => Math.max(1, q - 1))}
                  className="event-detail__qty-btn"
                >
                  -
                </button>
                <span className="event-detail__qty-val">{ticketQuantity}</span>
                <button
                  type="button"
                  onClick={() => setTicketQuantity((q) => Math.min(10, q + 1))}
                  className="event-detail__qty-btn"
                >
                  +
                </button>
              </div>
            </div>

            {/* Total Price Summary */}
            <div className="event-detail__price-summary">
              <div className="event-detail__summary-row">
                <span>Subtotal ({ticketQuantity} tickets)</span>
                <span>{totalPrice === 0 ? "FREE" : `₹${totalPrice}`}</span>
              </div>
              <div className="event-detail__summary-row event-detail__summary-row--total">
                <strong>Total Amount</strong>
                <strong>{totalPrice === 0 ? "FREE" : `₹${totalPrice}`}</strong>
              </div>
            </div>

            {/* Book Now Button */}
            <button
              type="button"
              onClick={() => setIsBooked(true)}
              className="event-detail__book-btn"
            >
              {totalPrice === 0 ? "Register for Free" : "Proceed to Checkout →"}
            </button>

            {/* Trust Assurance */}
            <div className="event-detail__trust-badge">
              <ShieldIcon size={14} />
              <span>Instant QR tickets delivered to email & SMS</span>
            </div>
          </div>
        </div>
      </div>

      {/* Booking Confirmation Modal */}
      {isBooked && (
        <div className="event-modal-overlay">
          <div className="event-modal">
            <div className="event-modal__icon">
              <CheckIcon size={32} />
            </div>
            <h3 className="event-modal__title">Registration Confirmed!</h3>
            <p className="event-modal__desc">
              You are all set for <strong>{event.name}</strong> ({ticketQuantity}x{" "}
              {selectedTier?.name || "Pass"}). Check your email for your digital entry pass.
            </p>
            <div className="event-modal__actions">
              <button
                type="button"
                onClick={() => setIsBooked(false)}
                className="event-modal__btn-primary"
              >
                Done
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

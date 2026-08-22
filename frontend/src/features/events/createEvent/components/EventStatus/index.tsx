"use client";

import type { EventStatusType } from "@/src/types/event";
import "./index.css";

interface EventStatusProps {
  status: EventStatusType;
  onChange: (status: EventStatusType) => void;
}

const STATUS_OPTIONS: {
  value: EventStatusType;
  label: string;
  badge: string;
  description: string;
  colorClass: string;
}[] = [
  {
    value: "upcoming",
    label: "Upcoming",
    badge: "Draft / Scheduled",
    description: "Event will be published and open for ticket registrations.",
    colorClass: "event-status__badge--upcoming",
  },
  {
    value: "live",
    label: "Live Now",
    badge: "Happening Today",
    description: "Highlighted with a pulsing LIVE badge on the discovery feed.",
    colorClass: "event-status__badge--live",
  },
  {
    value: "completed",
    label: "Completed",
    badge: "Archived",
    description: "Event has concluded. Ticket sales are closed.",
    colorClass: "event-status__badge--completed",
  },
  {
    value: "cancelled",
    label: "Cancelled",
    badge: "Inactive",
    description: "Marked as cancelled. Registrants are notified automatically.",
    colorClass: "event-status__badge--cancelled",
  },
];

export default function EventStatus({ status, onChange }: EventStatusProps) {
  return (
    <div className="event-status">
      <div className="event-status__header">
        <label className="event-status__title">Event Status</label>
        <span className="event-status__hint">Controls visibility on discovery feeds</span>
      </div>

      <div className="event-status__grid">
        {STATUS_OPTIONS.map((opt) => {
          const isSelected = status === opt.value;
          return (
            <button
              type="button"
              key={opt.value}
              onClick={() => onChange(opt.value)}
              className={`event-status__card ${
                isSelected ? "event-status__card--active" : ""
              }`}
            >
              <div className="event-status__card-top">
                <span className={`event-status__badge ${opt.colorClass}`}>
                  {opt.label}
                </span>
                <div
                  className={`event-status__radio ${
                    isSelected ? "event-status__radio--checked" : ""
                  }`}
                >
                  {isSelected && <span className="event-status__radio-inner" />}
                </div>
              </div>
              <p className="event-status__card-desc">{opt.description}</p>
            </button>
          );
        })}
      </div>
    </div>
  );
}

"use client";

import type { EventFormData, EventFormat } from "@/src/types/event";
import { LocationIcon, CalendarIcon } from "@/src/components/ui/icons";
import "./index.css";

interface DateLocationProps {
  formData: EventFormData;
  updateField: <K extends keyof EventFormData>(field: K, value: EventFormData[K]) => void;
}

const EVENT_FORMATS: { value: EventFormat; label: string; desc: string }[] = [
  { value: "in-person", label: "In-Person", desc: "Physical venue with specific address" },
  { value: "online", label: "Online Webinar / Stream", desc: "Virtual link or stream URL" },
  { value: "hybrid", label: "Hybrid", desc: "Both physical venue & livestream" },
];

const POPULAR_CITIES = [
  "Ahmedabad",
  "Gandhinagar",
  "Mumbai",
  "Delhi NCR",
  "Bengaluru",
  "Pune",
  "Hyderabad",
  "Jaipur",
];

export default function DateLocation({ formData, updateField }: DateLocationProps) {
  return (
    <div className="date-loc">
      <div className="date-loc__intro">
        <h2 className="date-loc__heading">Date & Location</h2>
        <p className="date-loc__subheading">
          Specify when and where your event takes place so attendees can plan ahead.
        </p>
      </div>

      {/* Format Selector */}
      <div className="date-loc__form-group">
        <label className="date-loc__label">
          Event Format <span className="date-loc__req">*</span>
        </label>
        <div className="date-loc__format-grid">
          {EVENT_FORMATS.map((fmt) => (
            <button
              type="button"
              key={fmt.value}
              onClick={() => updateField("format", fmt.value)}
              className={`date-loc__format-card ${
                formData.format === fmt.value ? "date-loc__format-card--active" : ""
              }`}
            >
              <span className="date-loc__format-title">{fmt.label}</span>
              <span className="date-loc__format-desc">{fmt.desc}</span>
            </button>
          ))}
        </div>
      </div>

      {/* Date & Time Grid */}
      <div className="date-loc__grid-2">
        {/* Start Date & Time */}
        <div className="date-loc__card-box">
          <div className="date-loc__box-header">
            <CalendarIcon size={16} />
            <span>Event Start</span>
          </div>
          <div className="date-loc__form-group">
            <label htmlFor="startDate" className="date-loc__sublabel">
              Start Date <span className="date-loc__req">*</span>
            </label>
            <input
              id="startDate"
              type="date"
              value={formData.startDate}
              onChange={(e) => updateField("startDate", e.target.value)}
              className="date-loc__input"
            />
          </div>
          <div className="date-loc__form-group">
            <label htmlFor="startTime" className="date-loc__sublabel">
              Start Time <span className="date-loc__req">*</span>
            </label>
            <input
              id="startTime"
              type="time"
              value={formData.startTime}
              onChange={(e) => updateField("startTime", e.target.value)}
              className="date-loc__input"
            />
          </div>
        </div>

        {/* End Date & Time */}
        <div className="date-loc__card-box">
          <div className="date-loc__box-header">
            <CalendarIcon size={16} />
            <span>Event End</span>
          </div>
          <div className="date-loc__form-group">
            <label htmlFor="endDate" className="date-loc__sublabel">
              End Date <span className="date-loc__req">*</span>
            </label>
            <input
              id="endDate"
              type="date"
              value={formData.endDate}
              onChange={(e) => updateField("endDate", e.target.value)}
              className="date-loc__input"
            />
          </div>
          <div className="date-loc__form-group">
            <label htmlFor="endTime" className="date-loc__sublabel">
              End Time <span className="date-loc__req">*</span>
            </label>
            <input
              id="endTime"
              type="time"
              value={formData.endTime}
              onChange={(e) => updateField("endTime", e.target.value)}
              className="date-loc__input"
            />
          </div>
        </div>
      </div>

      {/* Timezone */}
      <div className="date-loc__form-group">
        <label htmlFor="timezone" className="date-loc__label">Timezone</label>
        <select
          id="timezone"
          value={formData.timezone}
          onChange={(e) => updateField("timezone", e.target.value)}
          className="date-loc__select"
        >
          <option value="IST (UTC+5:30)">Indian Standard Time (IST - UTC+5:30)</option>
          <option value="GST (UTC+4:00)">Gulf Standard Time (GST - UTC+4:00)</option>
          <option value="GMT (UTC+0:00)">Greenwich Mean Time (GMT)</option>
          <option value="EST (UTC-5:00)">Eastern Standard Time (EST)</option>
          <option value="PST (UTC-8:00)">Pacific Standard Time (PST)</option>
        </select>
      </div>

      {/* Physical Venue Details */}
      {(formData.format === "in-person" || formData.format === "hybrid") && (
        <div className="date-loc__venue-section">
          <div className="date-loc__box-header">
            <LocationIcon size={16} />
            <span>Physical Venue Information</span>
          </div>

          <div className="date-loc__grid-2">
            <div className="date-loc__form-group">
              <label htmlFor="venueName" className="date-loc__label">
                Venue Name <span className="date-loc__req">*</span>
              </label>
              <input
                id="venueName"
                type="text"
                placeholder="e.g. GIFT City Convention Centre, Hyatt Regency"
                value={formData.venueName}
                onChange={(e) => updateField("venueName", e.target.value)}
                className="date-loc__input"
              />
            </div>

            <div className="date-loc__form-group">
              <label htmlFor="city" className="date-loc__label">
                City <span className="date-loc__req">*</span>
              </label>
              <select
                id="city"
                value={formData.city}
                onChange={(e) => updateField("city", e.target.value)}
                className="date-loc__select"
              >
                <option value="">Select City</option>
                {POPULAR_CITIES.map((c) => (
                  <option key={c} value={c}>
                    {c}
                  </option>
                ))}
              </select>
            </div>
          </div>

          <div className="date-loc__form-group">
            <label htmlFor="address" className="date-loc__label">
              Full Street Address & Landmark <span className="date-loc__req">*</span>
            </label>
            <input
              id="address"
              type="text"
              placeholder="e.g. Near SG Highway, Opp. Iscon Temple, Ahmedabad 380054"
              value={formData.address}
              onChange={(e) => updateField("address", e.target.value)}
              className="date-loc__input"
            />
          </div>
        </div>
      )}

      {/* Online Meeting URL */}
      {(formData.format === "online" || formData.format === "hybrid") && (
        <div className="date-loc__online-section">
          <div className="date-loc__form-group">
            <label htmlFor="onlineMeetingUrl" className="date-loc__label">
              Online Streaming / Meeting Link
            </label>
            <input
              id="onlineMeetingUrl"
              type="url"
              placeholder="https://meet.google.com/... or https://zoom.us/..."
              value={formData.onlineMeetingUrl}
              onChange={(e) => updateField("onlineMeetingUrl", e.target.value)}
              className="date-loc__input"
            />
            <span className="date-loc__help">
              This link will only be shared with confirmed registered attendees.
            </span>
          </div>
        </div>
      )}
    </div>
  );
}

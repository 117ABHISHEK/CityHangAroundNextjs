"use client";

import { useState } from "react";
import type { EventFormData, AgendaItem } from "@/src/types/event";
import { AddIcon } from "@/src/components/ui/icons";
import "./index.css";

interface EventDescriptionProps {
  formData: EventFormData;
  updateField: <K extends keyof EventFormData>(field: K, value: EventFormData[K]) => void;
}

export default function EventDescription({
  formData,
  updateField,
}: EventDescriptionProps) {
  const [newAgendaTime, setNewAgendaTime] = useState("");
  const [newAgendaTitle, setNewAgendaTitle] = useState("");
  const [newAgendaSpeaker, setNewAgendaSpeaker] = useState("");

  const handleAddAgendaItem = () => {
    if (newAgendaTime.trim() && newAgendaTitle.trim()) {
      const newItem: AgendaItem = {
        id: `agenda-${Date.now()}`,
        time: newAgendaTime.trim(),
        title: newAgendaTitle.trim(),
        speaker: newAgendaSpeaker.trim() || undefined,
      };
      updateField("agenda", [...formData.agenda, newItem]);
      setNewAgendaTime("");
      setNewAgendaTitle("");
      setNewAgendaSpeaker("");
    }
  };

  const handleRemoveAgendaItem = (id: string) => {
    updateField(
      "agenda",
      formData.agenda.filter((item) => item.id !== id)
    );
  };

  return (
    <div className="event-desc-step">
      <div className="event-desc-step__intro">
        <h2 className="event-desc-step__heading">Description & Schedule</h2>
        <p className="event-desc-step__subheading">
          Provide full event details, speaker line-ups, agenda breakdown, and organizer contacts.
        </p>
      </div>

      {/* Full Description */}
      <div className="event-desc-step__form-group">
        <label htmlFor="fullDescription" className="event-desc-step__label">
          Full Event Overview & Highlights <span className="event-desc-step__req">*</span>
        </label>
        <textarea
          id="fullDescription"
          rows={7}
          placeholder="Describe the entire experience, what attendees will learn or enjoy, guidelines, special guests, and takeaways..."
          value={formData.fullDescription}
          onChange={(e) => updateField("fullDescription", e.target.value)}
          className="event-desc-step__textarea"
        />
      </div>

      {/* Event Agenda Timeline Builder */}
      <div className="event-desc-step__agenda-section">
        <div className="event-desc-step__section-header">
          <h3 className="event-desc-step__title">Event Agenda & Timeline</h3>
          <p className="event-desc-step__hint">
            Break down the schedule with session times and speakers.
          </p>
        </div>

        {/* Existing Agenda Items */}
        <div className="event-desc-step__agenda-list">
          {formData.agenda.map((item) => (
            <div key={item.id} className="event-desc-step__agenda-item">
              <div className="event-desc-step__agenda-time">{item.time}</div>
              <div className="event-desc-step__agenda-details">
                <div className="event-desc-step__agenda-title">{item.title}</div>
                {item.speaker && (
                  <div className="event-desc-step__agenda-speaker">
                    🎤 {item.speaker}
                  </div>
                )}
              </div>
              <button
                type="button"
                onClick={() => handleRemoveAgendaItem(item.id)}
                className="event-desc-step__agenda-remove"
                aria-label="Remove agenda slot"
              >
                ×
              </button>
            </div>
          ))}
        </div>

        {/* Add Agenda Slot Form */}
        <div className="event-desc-step__agenda-add">
          <div className="event-desc-step__agenda-inputs">
            <input
              type="text"
              placeholder="Time (e.g. 10:00 AM)"
              value={newAgendaTime}
              onChange={(e) => setNewAgendaTime(e.target.value)}
              className="event-desc-step__input"
            />
            <input
              type="text"
              placeholder="Session Title (e.g. Keynote Speech)"
              value={newAgendaTitle}
              onChange={(e) => setNewAgendaTitle(e.target.value)}
              className="event-desc-step__input"
            />
            <input
              type="text"
              placeholder="Speaker / Host (Optional)"
              value={newAgendaSpeaker}
              onChange={(e) => setNewAgendaSpeaker(e.target.value)}
              className="event-desc-step__input"
            />
          </div>
          <button
            type="button"
            onClick={handleAddAgendaItem}
            className="event-desc-step__add-btn"
          >
            <AddIcon size={14} /> Add Agenda Slot
          </button>
        </div>
      </div>

      {/* Organizer Information */}
      <div className="event-desc-step__organizer-section">
        <div className="event-desc-step__section-header">
          <h3 className="event-desc-step__title">Organizer Contact Details</h3>
          <p className="event-desc-step__hint">
            Shown on ticket receipts and event page for support queries.
          </p>
        </div>

        <div className="event-desc-step__grid-3">
          <div className="event-desc-step__form-group">
            <label htmlFor="orgName" className="event-desc-step__sublabel">Organizer Name</label>
            <input
              id="orgName"
              type="text"
              placeholder="e.g. Tech Alliance"
              value={formData.organizerName}
              onChange={(e) => updateField("organizerName", e.target.value)}
              className="event-desc-step__input"
            />
          </div>

          <div className="event-desc-step__form-group">
            <label htmlFor="orgEmail" className="event-desc-step__sublabel">Support Email</label>
            <input
              id="orgEmail"
              type="email"
              placeholder="support@events.com"
              value={formData.organizerEmail}
              onChange={(e) => updateField("organizerEmail", e.target.value)}
              className="event-desc-step__input"
            />
          </div>

          <div className="event-desc-step__form-group">
            <label htmlFor="orgPhone" className="event-desc-step__sublabel">Phone / WhatsApp</label>
            <input
              id="orgPhone"
              type="tel"
              placeholder="+91 98765 43210"
              value={formData.organizerPhone}
              onChange={(e) => updateField("organizerPhone", e.target.value)}
              className="event-desc-step__input"
            />
          </div>
        </div>
      </div>

      {/* Terms & Conditions */}
      <div className="event-desc-step__form-group">
        <label htmlFor="terms" className="event-desc-step__label">
          Terms, Conditions & Refund Policy (Optional)
        </label>
        <textarea
          id="terms"
          rows={3}
          placeholder="e.g. Non-refundable within 48 hours of event. Entry requires valid government ID."
          value={formData.terms}
          onChange={(e) => updateField("terms", e.target.value)}
          className="event-desc-step__textarea"
        />
      </div>
    </div>
  );
}

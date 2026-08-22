"use client";

import type { EventFormData, TicketTier } from "@/src/types/event";
import { AddIcon, BadgeIndianRupeeIcon } from "@/src/components/ui/icons";
import "./index.css";

interface EventDetailsProps {
  formData: EventFormData;
  updateField: <K extends keyof EventFormData>(field: K, value: EventFormData[K]) => void;
}

export default function EventDetails({ formData, updateField }: EventDetailsProps) {
  const handleAddTier = () => {
    const newTier: TicketTier = {
      id: `tier-${Date.now()}`,
      name: formData.ticketTiers.length === 0 ? "General Admission" : "VIP Pass",
      price: formData.isFree ? 0 : 499,
      quantity: 100,
      description: "Standard event admission pass.",
    };
    updateField("ticketTiers", [...formData.ticketTiers, newTier]);
  };

  const handleUpdateTier = (id: string, updates: Partial<TicketTier>) => {
    updateField(
      "ticketTiers",
      formData.ticketTiers.map((t) => (t.id === id ? { ...t, ...updates } : t))
    );
  };

  const handleRemoveTier = (id: string) => {
    updateField(
      "ticketTiers",
      formData.ticketTiers.filter((t) => t.id !== id)
    );
  };

  return (
    <div className="event-details">
      <div className="event-details__intro">
        <h2 className="event-details__heading">Event Details & Ticketing</h2>
        <p className="event-details__subheading">
          Configure event capacity, age restrictions, and ticketing tiers for your attendees.
        </p>
      </div>

      {/* Free or Paid Mode */}
      <div className="event-details__type-selector">
        <label className="event-details__label">Ticket Pricing Model</label>
        <div className="event-details__type-grid">
          <button
            type="button"
            onClick={() => {
              updateField("isFree", true);
              updateField(
                "ticketTiers",
                formData.ticketTiers.map((t) => ({ ...t, price: 0 }))
              );
            }}
            className={`event-details__type-btn ${
              formData.isFree ? "event-details__type-btn--active" : ""
            }`}
          >
            <span className="event-details__type-title">🎉 Free Event</span>
            <span className="event-details__type-desc">Attendees register for free with 0 ticket fee</span>
          </button>

          <button
            type="button"
            onClick={() => updateField("isFree", false)}
            className={`event-details__type-btn ${
              !formData.isFree ? "event-details__type-btn--active" : ""
            }`}
          >
            <span className="event-details__type-title">💳 Paid Tickets</span>
            <span className="event-details__type-desc">Sell ticket tiers with custom INR prices</span>
          </button>
        </div>
      </div>

      {/* Capacity & Age Restriction Grid */}
      <div className="event-details__grid-2">
        <div className="event-details__form-group">
          <label htmlFor="capacity" className="event-details__label">
            Total Event Capacity (Seats / Max Attendees)
          </label>
          <input
            id="capacity"
            type="number"
            min={1}
            value={formData.capacity || ""}
            onChange={(e) => updateField("capacity", parseInt(e.target.value) || 0)}
            placeholder="e.g. 500"
            className="event-details__input"
          />
        </div>

        <div className="event-details__form-group">
          <label htmlFor="ageRestriction" className="event-details__label">
            Age Restriction
          </label>
          <select
            id="ageRestriction"
            value={formData.ageRestriction}
            onChange={(e) => updateField("ageRestriction", e.target.value)}
            className="event-details__select"
          >
            <option value="All Ages Welcome">All Ages Welcome</option>
            <option value="18+ Only">18+ Adults Only</option>
            <option value="21+ Only">21+ Only (Nightlife/Bars)</option>
            <option value="Kids & Families">Kids & Families Oriented</option>
          </select>
        </div>
      </div>

      {/* Ticket Tiers Section */}
      <div className="event-details__tiers-header">
        <div>
          <h3 className="event-details__tiers-title">Ticket Tiers</h3>
          <p className="event-details__tiers-hint">
            Create different ticket passes (e.g. Early Bird, Regular, VIP).
          </p>
        </div>
        <button
          type="button"
          onClick={handleAddTier}
          className="event-details__add-btn"
        >
          <AddIcon size={16} />
          <span>Add Ticket Tier</span>
        </button>
      </div>

      <div className="event-details__tiers-list">
        {formData.ticketTiers.map((tier, idx) => (
          <div key={tier.id} className="event-details__tier-card">
            <div className="event-details__tier-card-header">
              <div className="event-details__tier-index">Tier #{idx + 1}</div>
              {formData.ticketTiers.length > 1 && (
                <button
                  type="button"
                  onClick={() => handleRemoveTier(tier.id)}
                  className="event-details__tier-delete"
                >
                  Delete Tier
                </button>
              )}
            </div>

            <div className="event-details__grid-3">
              <div className="event-details__form-group">
                <label className="event-details__sublabel">Tier Name</label>
                <input
                  type="text"
                  placeholder="e.g. Early Bird Pass, VIP"
                  value={tier.name}
                  onChange={(e) => handleUpdateTier(tier.id, { name: e.target.value })}
                  className="event-details__input"
                />
              </div>

              <div className="event-details__form-group">
                <label className="event-details__sublabel">Price (₹ INR)</label>
                <div className="event-details__price-wrap">
                  <BadgeIndianRupeeIcon size={16} className="event-details__currency-icon" />
                  <input
                    type="number"
                    min={0}
                    disabled={formData.isFree}
                    placeholder="0"
                    value={formData.isFree ? 0 : tier.price}
                    onChange={(e) =>
                      handleUpdateTier(tier.id, { price: parseInt(e.target.value) || 0 })
                    }
                    className="event-details__input event-details__input--with-icon"
                  />
                </div>
              </div>

              <div className="event-details__form-group">
                <label className="event-details__sublabel">Available Quantity</label>
                <input
                  type="number"
                  min={1}
                  placeholder="100"
                  value={tier.quantity}
                  onChange={(e) =>
                    handleUpdateTier(tier.id, { quantity: parseInt(e.target.value) || 0 })
                  }
                  className="event-details__input"
                />
              </div>
            </div>

            <div className="event-details__form-group">
              <label className="event-details__sublabel">Tier Perks / Description</label>
              <input
                type="text"
                placeholder="e.g. Includes front-row seating, lunch buffet, and exclusive speaker access."
                value={tier.description || ""}
                onChange={(e) =>
                  handleUpdateTier(tier.id, { description: e.target.value })
                }
                className="event-details__input"
              />
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

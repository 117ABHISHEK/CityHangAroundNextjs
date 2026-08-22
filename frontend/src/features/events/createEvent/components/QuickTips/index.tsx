"use client";

import type { EventFormData } from "@/src/types/event";
import { CheckIcon } from "@/src/components/ui/icons";
import "./index.css";

interface QuickTipsProps {
  formData: EventFormData;
}

export default function QuickTips({ formData }: QuickTipsProps) {
  const tips = [
    {
      title: "Eye-catching Cover",
      desc: "Use high-resolution 16:9 images without cluttered text.",
      isDone: Boolean(formData.coverImage),
    },
    {
      title: "Specific Event Title",
      desc: "Include both the name and city/topic (at least 10 chars).",
      isDone: formData.name.trim().length >= 10,
    },
    {
      title: "Categorization & Tags",
      desc: "Pick both category and at least 2 relevant keyword tags.",
      isDone: Boolean(formData.category && formData.tags.length >= 2),
    },
    {
      title: "Schedule & Venue details",
      desc: "Specify exact start date, time and full street address.",
      isDone: Boolean(formData.startDate && (formData.venueName || formData.onlineMeetingUrl)),
    },
    {
      title: "Defined Ticket Tier",
      desc: "Add at least one clear ticket pass with perks or free RSVP.",
      isDone: formData.ticketTiers.length > 0,
    },
  ];

  const completedCount = tips.filter((t) => t.isDone).length;
  const progressPercent = Math.round((completedCount / tips.length) * 100);

  return (
    <div className="quick-tips">
      <div className="quick-tips__header">
        <div className="quick-tips__title-row">
          <h4 className="quick-tips__title">Event Readiness</h4>
          <span className="quick-tips__badge">{progressPercent}% Ready</span>
        </div>
        <div className="quick-tips__progress-bar">
          <div
            className="quick-tips__progress-fill"
            style={{ width: `${progressPercent}%` }}
          />
        </div>
      </div>

      <div className="quick-tips__list">
        {tips.map((tip, idx) => (
          <div
            key={idx}
            className={`quick-tips__item ${
              tip.isDone ? "quick-tips__item--done" : ""
            }`}
          >
            <div className="quick-tips__check-circle">
              {tip.isDone ? <CheckIcon size={12} strokeWidth={3} /> : <span>{idx + 1}</span>}
            </div>
            <div className="quick-tips__text">
              <span className="quick-tips__item-title">{tip.title}</span>
              <span className="quick-tips__item-desc">{tip.desc}</span>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

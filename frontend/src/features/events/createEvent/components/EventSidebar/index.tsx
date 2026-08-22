"use client";

import { CheckIcon, HelpIcon } from "@/src/components/ui/icons";
import "./index.css";

interface EventSidebarProps {
  currentStep: number;
  onStepClick: (step: number) => void;
  completedSteps: number[];
}

const FORM_STEPS = [
  {
    step: 1,
    title: "Basic Information",
    description: "Event name, category, tags & cover",
  },
  {
    step: 2,
    title: "Date & Location",
    description: "Schedule, venue & timezone",
  },
  {
    step: 3,
    title: "Event Details",
    description: "Ticketing tiers, capacity & age",
  },
  {
    step: 4,
    title: "Media & Images",
    description: "Gallery photos & video teaser",
  },
  {
    step: 5,
    title: "Description",
    description: "Full overview, agenda & contacts",
  },
];

export default function EventSidebar({
  currentStep,
  onStepClick,
  completedSteps,
}: EventSidebarProps) {
  return (
    <aside className="event-sidebar">
      <div className="event-sidebar__stepper">
        <h3 className="event-sidebar__header-title">Create Event</h3>
        <p className="event-sidebar__header-desc">
          Complete all 5 steps to publish your event to the city feed.
        </p>

        <div className="event-sidebar__steps-list">
          {FORM_STEPS.map((s) => {
            const isCurrent = currentStep === s.step;
            const isCompleted = completedSteps.includes(s.step);

            return (
              <button
                type="button"
                key={s.step}
                onClick={() => onStepClick(s.step)}
                className={`event-sidebar__step-item ${
                  isCurrent ? "event-sidebar__step-item--active" : ""
                } ${isCompleted ? "event-sidebar__step-item--completed" : ""}`}
              >
                <div className="event-sidebar__step-indicator">
                  {isCompleted ? (
                    <CheckIcon size={14} className="event-sidebar__check-icon" />
                  ) : (
                    <span>{s.step}</span>
                  )}
                </div>

                <div className="event-sidebar__step-content">
                  <span className="event-sidebar__step-title">{s.title}</span>
                  <span className="event-sidebar__step-desc">{s.description}</span>
                </div>
              </button>
            );
          })}
        </div>
      </div>

      {/* Need Help Support Card */}
      <div className="event-sidebar__help-card">
        <div className="event-sidebar__help-header">
          <HelpIcon size={20} className="event-sidebar__help-icon" />
          <h4 className="event-sidebar__help-title">Need Assistance?</h4>
        </div>
        <p className="event-sidebar__help-text">
          Have questions about ticket payouts, verification, or promotion campaigns?
        </p>
        <a
          href="mailto:events-support@cityhangaround.in"
          className="event-sidebar__help-link"
        >
          Contact Organizer Desk →
        </a>
      </div>
    </aside>
  );
}

"use client";

import { useRef, useState } from "react";
import type { EventFormData } from "@/src/types/event";
import EventSidebar from "../EventSidebar";
import LivePreview from "../LivePreview";
import BottomActionBar from "../BottomActionBar";
import BasicInformation from "../BasicInformation";
import DateLocation from "../DateLocation";
import EventDetails from "../EventDetails";
import MediaUpload from "../MediaUpload";
import EventDescription from "../EventDescription";
import { CheckIcon } from "@/src/components/ui/icons";
import Link from "next/link";
import "./index.css";

const INITIAL_FORM_DATA: EventFormData = {
  name: "Gujarat Tech & Startup Summit 2026",
  parentCategory: "Technology & Innovation",
  category: "Conferences & Summits",
  tags: ["AI", "Startups", "Web3", "Developer"],
  shortDescription:
    "Join 1,500+ builders, innovators, and investors for Gujarat's most anticipated tech gathering.",
  coverImage:
    "https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&auto=format&fit=crop&q=80",
  status: "upcoming",

  startDate: "2026-08-28",
  startTime: "09:00",
  endDate: "2026-08-29",
  endTime: "18:00",
  timezone: "IST (UTC+5:30)",
  format: "in-person",
  venueName: "GIFT City Convention Centre",
  address: "Block 12, GIFT City Road, Gandhinagar, Gujarat 382355",
  city: "Ahmedabad",
  onlineMeetingUrl: "",

  isFree: false,
  capacity: 1500,
  ageRestriction: "All Ages Welcome",
  ticketTiers: [
    {
      id: "tier-1",
      name: "Early Bird Attendee Pass",
      price: 499,
      quantity: 300,
      description: "Full day stage access, lunch voucher, and event kit.",
    },
    {
      id: "tier-2",
      name: "VIP Founder & Investor Pass",
      price: 1999,
      quantity: 50,
      description: "VIP networking lounge, speaker dinner, and priority seating.",
    },
  ],

  galleryImages: [
    "https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&auto=format&fit=crop&q=80",
  ],
  videoUrl: "https://www.youtube.com/watch?v=dQw4w9WgXcQ",

  fullDescription:
    "Gujarat Tech Summit brings developers, innovators, and founders together for 2 days of keynotes, developer workshops, and startup pitches.\n\nExperience high-impact sessions on AI agents, edge computing, and venture investing.",
  agenda: [
    {
      id: "ag-1",
      time: "09:30 AM",
      title: "Opening Keynote: Building for 1 Billion Users",
      speaker: "Dr. Arvind Mehta",
    },
    {
      id: "ag-2",
      time: "11:30 AM",
      title: "The Agentic AI Revolution & Realtime Web",
      speaker: "Priya Sharma",
    },
  ],
  organizerName: "Gujarat Tech Alliance",
  organizerEmail: "team@gujaratechsummit.in",
  organizerPhone: "+91 98765 43210",
  terms: "Standard ticket cancellation policy applies. Entry requires valid photo ID.",
};

export default function EventForm() {
  const [currentStep, setCurrentStep] = useState(1);
  const [formData, setFormData] = useState<EventFormData>(INITIAL_FORM_DATA);
  const [completedSteps, setCompletedSteps] = useState<number[]>([1]);
  const [isSaving, setIsSaving] = useState(false);
  const [lastSaved, setLastSaved] = useState<Date>(new Date());
  const [isPublished, setIsPublished] = useState(false);
  const saveTimerRef = useRef<number | null>(null);

  const updateField = <K extends keyof EventFormData>(
    field: K,
    value: EventFormData[K]
  ) => {
    setFormData((prev) => ({ ...prev, [field]: value }));
    setIsSaving(true);

    if (saveTimerRef.current) {
      window.clearTimeout(saveTimerRef.current);
    }

    saveTimerRef.current = window.setTimeout(() => {
      setIsSaving(false);
      setLastSaved(new Date());
    }, 800);
  };

  const handleNext = () => {
    if (!completedSteps.includes(currentStep)) {
      setCompletedSteps((prev) => [...prev, currentStep]);
    }
    if (currentStep < 5) {
      setCurrentStep((prev) => prev + 1);
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  };

  const handlePrev = () => {
    if (currentStep > 1) {
      setCurrentStep((prev) => prev - 1);
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  };

  const handleSaveDraft = () => {
    setIsSaving(true);
    setTimeout(() => {
      setIsSaving(false);
      setLastSaved(new Date());
      alert("Draft saved successfully to local storage!");
    }, 400);
  };

  const handleSubmit = () => {
    setIsPublished(true);
  };

  const renderActiveStep = () => {
    switch (currentStep) {
      case 1:
        return (
          <BasicInformation
            formData={formData}
            updateField={updateField}
          />
        );
      case 2:
        return (
          <DateLocation
            formData={formData}
            updateField={updateField}
          />
        );
      case 3:
        return (
          <EventDetails
            formData={formData}
            updateField={updateField}
          />
        );
      case 4:
        return (
          <MediaUpload
            formData={formData}
            updateField={updateField}
          />
        );
      case 5:
        return (
          <EventDescription
            formData={formData}
            updateField={updateField}
          />
        );
      default:
        return null;
    }
  };

  return (
    <div className="event-creator">
      {/* Top Banner Header */}
      <div className="event-creator__top-bar">
        <div className="event-creator__container">
          <div className="event-creator__top-content">
            <div>
              <span className="event-creator__breadcrumbs">
                <Link href="/events">Events</Link> / <span>Create New Event</span>
              </span>
              <h1 className="event-creator__main-title">
                Publish an Event in Ahmedabad
              </h1>
            </div>
            <div className="event-creator__top-actions">
              <Link href="/events" className="event-creator__btn-outline">
                Cancel & Exit
              </Link>
            </div>
          </div>
        </div>
      </div>

      {/* 3-Column Creator Layout */}
      <div className="event-creator__container event-creator__layout">
        {/* Left Column: Stepper & Support */}
        <div className="event-creator__col-sidebar">
          <EventSidebar
            currentStep={currentStep}
            onStepClick={(step) => setCurrentStep(step)}
            completedSteps={completedSteps}
          />
        </div>

        {/* Center Column: Active Step Form */}
        <div className="event-creator__col-form">
          {renderActiveStep()}
        </div>

        {/* Right Column: Live Feed Preview */}
        <div className="event-creator__col-preview">
          <LivePreview formData={formData} />
        </div>
      </div>

      {/* Bottom Sticky Action Bar */}
      <BottomActionBar
        currentStep={currentStep}
        totalSteps={5}
        onPrev={handlePrev}
        onNext={handleNext}
        onSaveDraft={handleSaveDraft}
        onSubmit={handleSubmit}
        isSaving={isSaving}
        lastSaved={lastSaved}
      />

      {/* Success Modal */}
      {isPublished && (
        <div className="event-modal-overlay">
          <div className="event-modal">
            <div className="event-modal__icon">
              <CheckIcon size={32} />
            </div>
            <h3 className="event-modal__title">Event Published Successfully!</h3>
            <p className="event-modal__desc">
              <strong>{formData.name}</strong> is now live and listed in the city event feed.
            </p>
            <div className="event-modal__actions">
              <Link href="/events" className="event-modal__btn-primary">
                View in Event Discovery
              </Link>
              <button
                type="button"
                onClick={() => setIsPublished(false)}
                className="event-modal__btn-secondary"
              >
                Continue Editing
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

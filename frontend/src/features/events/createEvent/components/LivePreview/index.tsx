"use client";

import type { EventFormData } from "@/src/types/event";
import EventPreviewCard from "../EventPreviewCard";
import QuickTips from "../QuickTips";
import "./index.css";

interface LivePreviewProps {
  formData: EventFormData;
}

export default function LivePreview({ formData }: LivePreviewProps) {
  return (
    <aside className="live-preview">
      <div className="live-preview__header">
        <div className="live-preview__title-row">
          <span className="live-preview__dot" />
          <h3 className="live-preview__title">Real-Time Feed Preview</h3>
        </div>
        <span className="live-preview__subtitle">
          How your event card appears to users
        </span>
      </div>

      <div className="live-preview__card-wrapper">
        <EventPreviewCard formData={formData} />
      </div>

      <div className="live-preview__tips-wrapper">
        <QuickTips formData={formData} />
      </div>
    </aside>
  );
}

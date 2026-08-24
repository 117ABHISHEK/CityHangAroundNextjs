"use client";

import { useEffect, useState } from "react";
import "./index.css";

interface AutoSaveStatusProps {
  lastSaved?: Date;
  isSaving?: boolean;
}

export default function AutoSaveStatus({
  lastSaved,
  isSaving = false,
}: AutoSaveStatusProps) {
  const [timeAgo, setTimeAgo] = useState<string>("Just now");

  useEffect(() => {
    if (!lastSaved) return;

    const updateInterval = setInterval(() => {
      const seconds = Math.floor(
        (new Date().getTime() - lastSaved.getTime()) / 1000
      );
      if (seconds < 10) {
        setTimeAgo("Just now");
      } else if (seconds < 60) {
        setTimeAgo(`${seconds}s ago`);
      } else {
        const mins = Math.floor(seconds / 60);
        setTimeAgo(`${mins}m ago`);
      }
    }, 5000);

    return () => clearInterval(updateInterval);
  }, [lastSaved]);

  return (
    <div className="autosave-status">
      <span
        className={`autosave-status__dot ${
          isSaving ? "autosave-status__dot--saving" : "autosave-status__dot--saved"
        }`}
        aria-hidden="true"
      />
      <span className="autosave-status__text">
        {isSaving ? (
          "Saving changes..."
        ) : (
          <>
            Auto-save is <strong>ON</strong>
            {lastSaved && <span className="autosave-status__time"> • Saved {timeAgo}</span>}
          </>
        )}
      </span>
    </div>
  );
}

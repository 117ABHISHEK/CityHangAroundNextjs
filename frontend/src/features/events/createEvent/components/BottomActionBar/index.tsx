"use client";

import { ChevronLeft, ChevronRight, CheckIcon } from "@/src/components/ui/icons";
import AutoSaveStatus from "../AutoSaveStatus";
import "./index.css";

interface BottomActionBarProps {
  currentStep: number;
  totalSteps: number;
  onPrev: () => void;
  onNext: () => void;
  onSaveDraft: () => void;
  onSubmit: () => void;
  isSaving?: boolean;
  lastSaved?: Date;
}

export default function BottomActionBar({
  currentStep,
  totalSteps,
  onPrev,
  onNext,
  onSaveDraft,
  onSubmit,
  isSaving,
  lastSaved,
}: BottomActionBarProps) {
  const isLastStep = currentStep === totalSteps;

  return (
    <div className="bottom-action-bar">
      <div className="bottom-action-bar__inner">
        {/* Left: AutoSave Status */}
        <div className="bottom-action-bar__left">
          <AutoSaveStatus isSaving={isSaving} lastSaved={lastSaved} />
        </div>

        {/* Right: Actions */}
        <div className="bottom-action-bar__right">
          {currentStep > 1 && (
            <button
              type="button"
              onClick={onPrev}
              className="bottom-action-bar__btn-back"
            >
              <ChevronLeft size={16} /> Back
            </button>
          )}

          <button
            type="button"
            onClick={onSaveDraft}
            className="bottom-action-bar__btn-draft"
          >
            Save Draft
          </button>

          {isLastStep ? (
            <button
              type="button"
              onClick={onSubmit}
              className="bottom-action-bar__btn-publish"
            >
              <CheckIcon size={16} /> Publish Event
            </button>
          ) : (
            <button
              type="button"
              onClick={onNext}
              className="bottom-action-bar__btn-next"
            >
              Save & Continue <ChevronRight size={16} />
            </button>
          )}
        </div>
      </div>
    </div>
  );
}

"use client";

import { useRef, useState } from "react";
import Image from "next/image";
import { ImageIcon } from "@/src/components/ui/icons";
import "./index.css";

interface EventImageUploadProps {
  coverImage: string | null;
  onChange: (image: string | null) => void;
}

const SAMPLE_PRESET_COVERS = [
  {
    title: "Conference & Tech",
    url: "https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&auto=format&fit=crop&q=80",
  },
  {
    title: "Concert & Music",
    url: "https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=1200&auto=format&fit=crop&q=80",
  },
  {
    title: "Food & Drinks",
    url: "https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=1200&auto=format&fit=crop&q=80",
  },
  {
    title: "Workshop & Art",
    url: "https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=1200&auto=format&fit=crop&q=80",
  },
];

export default function EventImageUpload({
  coverImage,
  onChange,
}: EventImageUploadProps) {
  const fileInputRef = useRef<HTMLInputElement>(null);
  const [isDragging, setIsDragging] = useState(false);
  const [customUrl, setCustomUrl] = useState("");
  const [showUrlInput, setShowUrlInput] = useState(false);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = () => {
        if (typeof reader.result === "string") {
          onChange(reader.result);
        }
      };
      reader.readAsDataURL(file);
    }
  };

  const handleDragOver = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(true);
  };

  const handleDragLeave = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(false);
  };

  const handleDrop = (e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(false);
    const file = e.dataTransfer.files?.[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = () => {
        if (typeof reader.result === "string") {
          onChange(reader.result);
        }
      };
      reader.readAsDataURL(file);
    }
  };

  const handleApplyUrl = () => {
    if (customUrl.trim()) {
      onChange(customUrl.trim());
      setShowUrlInput(false);
      setCustomUrl("");
    }
  };

  return (
    <div className="event-upload">
      <div className="event-upload__header">
        <label className="event-upload__title">
          Cover Image <span className="event-upload__required">*</span>
        </label>
        <span className="event-upload__hint">16:9 ratio recommended (1200 x 675px)</span>
      </div>

      {coverImage ? (
        <div className="event-upload__preview-box">
          <div className="event-upload__preview-media">
            <Image
              src={coverImage}
              alt="Event Cover Preview"
              fill
              className="event-upload__preview-img"
              unoptimized
            />
            <div className="event-upload__preview-badge">Cover Preview</div>
          </div>
          <div className="event-upload__preview-actions">
            <button
              type="button"
              className="event-upload__btn-secondary"
              onClick={() => fileInputRef.current?.click()}
            >
              Replace Image
            </button>
            <button
              type="button"
              className="event-upload__btn-danger"
              onClick={() => onChange(null)}
            >
              Remove
            </button>
          </div>
        </div>
      ) : (
        <div
          className={`event-upload__dropzone ${
            isDragging ? "event-upload__dropzone--active" : ""
          }`}
          onDragOver={handleDragOver}
          onDragLeave={handleDragLeave}
          onDrop={handleDrop}
          onClick={() => fileInputRef.current?.click()}
        >
          <div className="event-upload__icon-circle">
            <ImageIcon size={28} />
          </div>
          <p className="event-upload__primary-text">
            <strong>Click to upload</strong> or drag and drop
          </p>
          <p className="event-upload__sub-text">
            PNG, JPG, WEBP or GIF (Max file size: 5MB)
          </p>

          <div
            className="event-upload__url-toggle"
            onClick={(e) => {
              e.stopPropagation();
              setShowUrlInput(!showUrlInput);
            }}
          >
            Or paste image URL
          </div>
        </div>
      )}

      {showUrlInput && !coverImage && (
        <div className="event-upload__url-box">
          <input
            type="url"
            placeholder="https://images.unsplash.com/photo-..."
            value={customUrl}
            onChange={(e) => setCustomUrl(e.target.value)}
            className="event-upload__url-input"
            onKeyDown={(e) => {
              if (e.key === "Enter") {
                e.preventDefault();
                handleApplyUrl();
              }
            }}
          />
          <button
            type="button"
            className="event-upload__url-apply"
            onClick={handleApplyUrl}
          >
            Apply
          </button>
        </div>
      )}

      {/* Quick Preset Images */}
      <div className="event-upload__presets">
        <span className="event-upload__presets-label">Or choose a curated preset:</span>
        <div className="event-upload__presets-list">
          {SAMPLE_PRESET_COVERS.map((preset) => (
            <button
              type="button"
              key={preset.title}
              className="event-upload__preset-chip"
              onClick={() => onChange(preset.url)}
            >
              {preset.title}
            </button>
          ))}
        </div>
      </div>

      <input
        ref={fileInputRef}
        type="file"
        accept="image/*"
        onChange={handleFileChange}
        className="event-upload__hidden-input"
      />
    </div>
  );
}

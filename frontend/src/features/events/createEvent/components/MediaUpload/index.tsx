"use client";

import { useState } from "react";
import Image from "next/image";
import type { EventFormData } from "@/src/types/event";
import { AddIcon, VideoIcon, ImageIcon } from "@/src/components/ui/icons";
import "./index.css";

interface MediaUploadProps {
  formData: EventFormData;
  updateField: <K extends keyof EventFormData>(field: K, value: EventFormData[K]) => void;
}

const PRESET_GALLERY_IMAGES = [
  "https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&auto=format&fit=crop&q=80",
  "https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&auto=format&fit=crop&q=80",
  "https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=800&auto=format&fit=crop&q=80",
];

export default function MediaUpload({ formData, updateField }: MediaUploadProps) {
  const [newImageUrl, setNewImageUrl] = useState("");

  const handleAddImage = (url: string) => {
    if (url.trim() && !formData.galleryImages.includes(url.trim())) {
      updateField("galleryImages", [...formData.galleryImages, url.trim()]);
      setNewImageUrl("");
    }
  };

  const handleRemoveImage = (index: number) => {
    updateField(
      "galleryImages",
      formData.galleryImages.filter((_, i) => i !== index)
    );
  };

  return (
    <div className="media-upload">
      <div className="media-upload__intro">
        <h2 className="media-upload__heading">Media & Gallery</h2>
        <p className="media-upload__subheading">
          Showcase past event highlights, venue photos, and video teasers to boost engagement.
        </p>
      </div>

      {/* Gallery Images */}
      <div className="media-upload__section">
        <div className="media-upload__section-header">
          <div>
            <h3 className="media-upload__title">Event Photos & Gallery</h3>
            <span className="media-upload__hint">
              Upload or add URLs of venue images and stage photos ({formData.galleryImages.length}/6)
            </span>
          </div>
        </div>

        {/* Gallery Grid */}
        <div className="media-upload__grid">
          {formData.galleryImages.map((img, idx) => (
            <div key={idx} className="media-upload__item">
              <Image
                src={img}
                alt={`Gallery ${idx + 1}`}
                fill
                className="media-upload__img"
                unoptimized
              />
              <button
                type="button"
                onClick={() => handleRemoveImage(idx)}
                className="media-upload__delete-btn"
                aria-label="Remove image"
              >
                ×
              </button>
            </div>
          ))}

          {formData.galleryImages.length < 6 && (
            <div className="media-upload__add-box">
              <ImageIcon size={24} className="media-upload__icon" />
              <input
                type="url"
                placeholder="Paste image URL..."
                value={newImageUrl}
                onChange={(e) => setNewImageUrl(e.target.value)}
                className="media-upload__input"
                onKeyDown={(e) => {
                  if (e.key === "Enter") {
                    e.preventDefault();
                    handleAddImage(newImageUrl);
                  }
                }}
              />
              <button
                type="button"
                onClick={() => handleAddImage(newImageUrl)}
                className="media-upload__add-action"
              >
                <AddIcon size={14} /> Add Image
              </button>
            </div>
          )}
        </div>

        {/* Quick Presets */}
        <div className="media-upload__presets">
          <span className="media-upload__presets-title">Add sample photos:</span>
          {PRESET_GALLERY_IMAGES.map((presetUrl, idx) => (
            <button
              type="button"
              key={idx}
              disabled={formData.galleryImages.includes(presetUrl)}
              onClick={() => handleAddImage(presetUrl)}
              className="media-upload__preset-btn"
            >
              + Sample #{idx + 1}
            </button>
          ))}
        </div>
      </div>

      {/* Video Teaser Link */}
      <div className="media-upload__video-section">
        <div className="media-upload__section-header">
          <div className="media-upload__title-with-icon">
            <VideoIcon size={18} />
            <h3 className="media-upload__title">Promo Video / Teaser</h3>
          </div>
        </div>

        <div className="media-upload__form-group">
          <label htmlFor="videoUrl" className="media-upload__label">
            YouTube or Vimeo Link (Optional)
          </label>
          <input
            id="videoUrl"
            type="url"
            placeholder="https://www.youtube.com/watch?v=..."
            value={formData.videoUrl}
            onChange={(e) => updateField("videoUrl", e.target.value)}
            className="media-upload__input-full"
          />
          <span className="media-upload__hint">
            The video teaser will be embedded directly in the event overview section.
          </span>
        </div>
      </div>
    </div>
  );
}

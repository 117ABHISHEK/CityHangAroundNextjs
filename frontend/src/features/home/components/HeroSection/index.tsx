"use client";

import { useEffect, useState } from "react";

import {
  BusinessIcon,
  CategoriesIcon,
  CityGuideIcon,
  CommunityIcon,
  LocationIcon,
  SearchIcon
} from "@/src/components/ui/icons";

import { LetterSwap3D } from "@/src/components/ui/letter-swap-3d";
import MagicCard from "@/src/components/ui/magic-card";

import "./index.css";

/* =========================================================
   HERO SLIDES
   Remote images - nothing stored in src/ or public/
========================================================= */

const slides = [
  {
    image:
      "https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1800&q=85",
    category: "Shopping",
    title: "Urban Lifestyle Store",
    location: "Mumbai",
    rating: "4.7",
  },
  {
    image:
      "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1800&q=85",
    category: "Restaurant",
    title: "Best Restaurants Nearby",
    location: "Pune",
    rating: "4.8",
  },
  {
    image:
      "https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1800&q=85",
    category: "Events",
    title: "Events Happening Near You",
    location: "Bangalore",
    rating: "4.9",
  },
  {
    image:
      "https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=1800&q=85",
    category: "Business",
    title: "Discover Local Businesses",
    location: "Delhi",
    rating: "4.7",
  },
];

/* =========================================================
   STATS
========================================================= */

const stats = [
  {
    value: "5,000+",
    label: "Businesses Listed",
    icon: BusinessIcon,
  },
  {
    value: "10,000+",
    label: "Happy Users",
    icon: CommunityIcon,
  },
  {
    value: "350+",
    label: "Cities Covered",
    icon: LocationIcon,
  },
  {
    value: "20+",
    label: "Categories",
    icon: CategoriesIcon,
  },
];

/* =========================================================
   POPULAR SEARCHES
========================================================= */

const popularSearches = [
  "Restaurants",
  "Cafes",
  "Events",
  "Gyms",
  "Salons",
  "Doctors",
  "Hotels",
  "Shops",
];

/* =========================================================
   HERO SECTION
========================================================= */

export default function HeroSection() {
  const [activeSlide, setActiveSlide] = useState(0);
  const [isPaused, setIsPaused] = useState(false);

  /* =======================================================
     NEXT SLIDE
  ======================================================= */

  const nextSlide = () => {
    setActiveSlide((current) =>
      current === slides.length - 1 ? 0 : current + 1,
    );
  };

  /* =======================================================
     PREVIOUS SLIDE
  ======================================================= */

  const previousSlide = () => {
    setActiveSlide((current) =>
      current === 0 ? slides.length - 1 : current - 1,
    );
  };

  /* =======================================================
     AUTO SLIDER
  ======================================================= */

  useEffect(() => {
    if (isPaused) {
      return;
    }

    const timer = window.setInterval(() => {
      setActiveSlide((current) =>
        current === slides.length - 1 ? 0 : current + 1,
      );
    }, 5000);

    return () => window.clearInterval(timer);
  }, [isPaused]);

  const currentSlide = slides[activeSlide];

  return (
    <section className="home-hero">
      {/* =====================================================
          RIGHT IMAGE SLIDER
      ===================================================== */}

      <div
        className="home-hero__visual"
        onMouseEnter={() => setIsPaused(true)}
        onMouseLeave={() => setIsPaused(false)}
      >
        {slides.map((slide, index) => (
          <div
            key={`${slide.category}-${index}`}
            className={`home-hero__visual-slide ${
              index === activeSlide ? "is-active" : ""
            }`}
          >
            <img
              src={slide.image}
              alt={slide.title}
              className="home-hero__visual-image"
              loading={index === 0 ? "eager" : "lazy"}
              fetchPriority={index === 0 ? "high" : "auto"}
            />

            <div className="home-hero__visual-overlay" />
          </div>
        ))}

        {/* ===================================================
            IMAGE INFORMATION
        =================================================== */}

        <div
          key={currentSlide.title}
          className="home-hero__visual-info"
        >
          <span className="home-hero__visual-category">
            {currentSlide.category}
          </span>

          <h2>{currentSlide.title}</h2>

          <div className="home-hero__visual-meta">
            <span>
              <LocationIcon size={12} aria-hidden="true" />
              {currentSlide.location}
            </span>

            <span>★ {currentSlide.rating}</span>
          </div>
        </div>

        {/* ===================================================
            SLIDER CONTROLS
        =================================================== */}

        <div className="home-hero__visual-navigation">
          <button
            type="button"
            className="home-hero__slider-button"
            aria-label="Previous image"
            onClick={previousSlide}
          >
            ←
          </button>

          <div className="home-hero__visual-counter">
            <strong>
              {String(activeSlide + 1).padStart(2, "0")}
            </strong>

            <span>/</span>

            <span>
              {String(slides.length).padStart(2, "0")}
            </span>
          </div>

          <button
            type="button"
            className="home-hero__slider-button"
            aria-label="Next image"
            onClick={nextSlide}
          >
            →
          </button>
        </div>

        {/* ===================================================
            SLIDER PROGRESS
        =================================================== */}

        <div className="home-hero__visual-progress">
          {slides.map((slide, index) => (
            <button
              key={slide.title}
              type="button"
              className={
                index === activeSlide ? "is-active" : ""
              }
              aria-label={`Show ${slide.title}`}
              aria-current={
                index === activeSlide ? "true" : undefined
              }
              onClick={() => setActiveSlide(index)}
            />
          ))}
        </div>
      </div>

      {/* =====================================================
          DIAGONAL DIVIDER
      ===================================================== */}

      <div
        className="home-hero__tile-line"
        aria-hidden="true"
      />

      {/* =====================================================
          LEFT CONTENT
      ===================================================== */}

      <div className="home-container home-hero__content">
        <div className="home-hero__copy">
          {/* =================================================
              TRUST BADGE
          ================================================= */}

          <div className="home-hero__badge">
            <span className="home-hero__badge-dot" />

            <span>
              Trusted by 10,000+ users across India
            </span>
          </div>

          {/* =================================================
              HEADLINE
          ================================================= */}

          <h1 className="home-hero__title">
            Discover What&apos;s

            <span className="home-hero__accent">
              <LetterSwap3D
                  className="home-hero__letter-swap"
                  frontFaceClassName="home-hero__letter-swap-front"
                  backFaceClassName="home-hero__letter-swap-back"
                  staggerInterval={0.055}
                  staggerOrigin="first"
                  flipDirection="top"
                  duration={0.42}
                  blur
                  blurAmount={3}
                  respectReducedMotion
                  autoPlay
                  interval={4500}
                >
                  Nearby.
                </LetterSwap3D>
            </span>

            <span className="home-hero__animated-line">
              Businesses,{" "}

              <span className="home-hero__animated-word">
                <LetterSwap3D
                  className="home-hero__letter-swap"
                  frontFaceClassName="home-hero__letter-swap-front"
                  backFaceClassName="home-hero__letter-swap-back"
                  staggerInterval={0.055}
                  staggerOrigin="first"
                  flipDirection="top"
                  duration={0.42}
                  blur
                  blurAmount={3}
                  respectReducedMotion
                  autoPlay
                  interval={4500}
                >
                  Deals
                </LetterSwap3D>
              </span>

              {" "}&amp;
            </span>

            <span className="home-hero__accent">
              <LetterSwap3D
                  className="home-hero__letter-swap"
                  frontFaceClassName="home-hero__letter-swap-front"
                  backFaceClassName="home-hero__letter-swap-back"
                  staggerInterval={0.055}
                  staggerOrigin="first"
                  flipDirection="top"
                  duration={0.42}
                  blur
                  blurAmount={3}
                  respectReducedMotion
                  autoPlay
                  interval={4500}
                >
                  Events.
                </LetterSwap3D>
            </span>
          </h1>

          {/* =================================================
              ACCENT LINE
          ================================================= */}

          <div className="home-hero__accent-line" />

          {/* =================================================
              DESCRIPTION
          ================================================= */}

          <p className="home-hero__description">
            Discover restaurants, services, events, and
            exclusive offers happening near you — all on a
            single platform.
          </p>

          {/* =================================================
              MAGIC SEARCH CARD
          ================================================= */}

          <MagicCard className="home-hero__search-magic">
            <div className="home-hero__search">
              {/* LOCATION */}

              <label className="home-hero__field">
                <span className="home-hero__field-icon">
                  <LocationIcon
                    size={20}
                    aria-hidden="true"
                  />
                </span>

                <span className="home-hero__field-content">
                  <span className="home-hero__field-label">
                    Location
                  </span>

                  <input
                    type="search"
                    placeholder="City, area or location"
                    aria-label="Search city, area or location"
                  />
                </span>
              </label>

              {/* DIVIDER */}

              <span
                className="home-hero__search-divider"
                aria-hidden="true"
              />

              {/* DISCOVER */}

              <label className="home-hero__field">
                <span className="home-hero__field-icon">
                  <CityGuideIcon
                    size={20}
                    aria-hidden="true"
                  />
                </span>

                <span className="home-hero__field-content">
                  <span className="home-hero__field-label">
                    Discover
                  </span>

                  <input
                    type="search"
                    placeholder="Restaurants, events, services..."
                    aria-label="Search businesses and events"
                  />
                </span>
              </label>

              {/* SEARCH */}

              <button
                type="button"
                className="home-button home-button--search"
              >
                <SearchIcon
                  size={20}
                  aria-hidden="true"
                />

                <span>Search</span>
              </button>
            </div>
          </MagicCard>

          {/* =================================================
              POPULAR SEARCHES
          ================================================= */}

          <div className="home-hero__tags">
            <span className="home-hero__tags-label">
              Popular
            </span>

            <span
              className="home-hero__tags-divider"
              aria-hidden="true"
            />

            {popularSearches.map((tag) => (
              <button
                key={tag}
                type="button"
                className="home-hero__tag"
              >
                {tag}
              </button>
            ))}
          </div>

          {/* =================================================
              CTA
          ================================================= */}

          <div className="home-hero__actions">
            <button
              type="button"
              className="home-button home-button--primary"
            >
              <span>Start Exploring Free</span>

              <span
                className="home-hero__cta-arrow"
                aria-hidden="true"
              >
                →
              </span>
            </button>
          </div>

          {/* =================================================
              STATS
          ================================================= */}

          <div className="home-stats">
            {stats.map(
              ({ value, label, icon: Icon }, index) => (
                <div
                  key={label}
                  className={`home-stat home-stat--${index + 1}`}
                >
                  <span className="home-stat__icon">
                    <Icon
                      size={19}
                      aria-hidden="true"
                    />
                  </span>

                  <span className="home-stat__content">
                    <strong className="home-stat__value">
                      {value}
                    </strong>

                    <span className="home-stat__label">
                      {label}
                    </span>
                  </span>
                </div>
              ),
            )}
          </div>
        </div>
      </div>

      {/* =====================================================
          BOTTOM LINE
      ===================================================== */}

      <div
        className="home-hero__bottom-line"
        aria-hidden="true"
      />
    </section>
  );
}
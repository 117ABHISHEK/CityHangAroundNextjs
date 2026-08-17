"use client";

import { useEffect, useRef, useState } from "react";
import { ChevronLeft, ChevronRight } from "@/src/components/ui/icons";
import "./index.css";

type City = {
  id: number;
  name: string;
  count: string;
  badge?: string;
  description: string;
  image: string;
};

const cities: City[] = [
  {
    id: 1,
    name: "Kolkata",
    count: "1200+ Listings",
    badge: "Popular Now",
    description:
      "Discover trending restaurants, events and local offers around Kolkata.",
    image:
      "https://images.unsplash.com/photo-1558431382-27e303142255?auto=format&fit=crop&w=900&q=85",
  },
  {
    id: 2,
    name: "Delhi",
    count: "1050+ Listings",
    description:
      "Explore the latest places, events and experiences around Delhi.",
    image:
      "https://images.unsplash.com/photo-1587474260584-136574528ed5?auto=format&fit=crop&w=900&q=85",
  },
  {
    id: 3,
    name: "Mumbai",
    count: "1000+ Listings",
    badge: "Hot",
    description: "Find what's trending across Mumbai right now.",
    image:
      "https://images.unsplash.com/photo-1570168007204-dfb528c6958f?auto=format&fit=crop&w=900&q=85",
  },
  {
    id: 4,
    name: "Bangalore",
    count: "950+ Listings",
    description:
      "Discover popular cafes, events and experiences around Bangalore.",
    image:
      "https://images.unsplash.com/photo-1596176530529-78163a4f7af2?auto=format&fit=crop&w=900&q=85",
  },
  {
    id: 5,
    name: "Hyderabad",
    count: "800+ Listings",
    description:
      "Explore local favourites, hidden gems and trending spots.",
    image:
      "https://images.unsplash.com/photo-1578991624414-276ef23a534f?auto=format&fit=crop&w=900&q=85",
  },
  {
    id: 6,
    name: "Pune",
    count: "700+ Listings",
    description: "See what people are discovering across Pune this week.",
    image:
      "https://images.unsplash.com/photo-1570168007204-dfb528c6958f?auto=format&fit=crop&w=900&q=85",
  },
  {
    id: 7,
    name: "Chennai",
    count: "650+ Listings",
    description:
      "Explore the best local places, events and experiences in Chennai.",
    image:
      "https://images.unsplash.com/photo-1582510003544-4d00b7f74220?auto=format&fit=crop&w=900&q=85",
  },
  {
    id: 8,
    name: "Ahmedabad",
    count: "600+ Listings",
    description: "Discover what's happening around Ahmedabad right now.",
    image:
      "https://images.unsplash.com/photo-1602643163983-ed0babc39797?auto=format&fit=crop&w=900&q=85",
  },
  {
    id: 9,
    name: "Jaipur",
    count: "550+ Listings",
    badge: "Trending",
    description: "Find popular destinations, events and offers around Jaipur.",
    image:
      "https://images.unsplash.com/photo-1477587458883-47145ed94245?auto=format&fit=crop&w=900&q=85",
  },
  {
    id: 10,
    name: "Surat",
    count: "500+ Listings",
    description: "Explore local experiences and trending spots around Surat.",
    image:
      "https://images.unsplash.com/photo-1595658658481-d53d3f999875?auto=format&fit=crop&w=900&q=85",
  },
];

export default function TrendingCities() {
  const viewportRef = useRef<HTMLDivElement>(null);

  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);

  const updateScrollButtons = () => {
    const viewport = viewportRef.current;

    if (!viewport) return;

    const maxScrollLeft = viewport.scrollWidth - viewport.clientWidth;

    setCanScrollLeft(viewport.scrollLeft > 5);
    setCanScrollRight(viewport.scrollLeft < maxScrollLeft - 5);
  };

  useEffect(() => {
    const viewport = viewportRef.current;

    if (!viewport) return;

    updateScrollButtons();

    viewport.addEventListener("scroll", updateScrollButtons, { passive: true });

    window.addEventListener("resize", updateScrollButtons);

    return () => {
      viewport.removeEventListener("scroll", updateScrollButtons);

      window.removeEventListener("resize", updateScrollButtons);
    };
  }, []);

  const getScrollAmount = () => {
    const viewport = viewportRef.current;

    if (!viewport) return 0;

    const firstCard = viewport.querySelector(
      ".home-city-card",
    ) as HTMLElement | null;

    if (!firstCard) {
      return viewport.clientWidth;
    }

    const track = firstCard.parentElement;

    if (!track) {
      return firstCard.offsetWidth;
    }

    const styles = window.getComputedStyle(track);

    const gap = parseFloat(styles.columnGap || styles.gap || "0") || 0;

    return firstCard.offsetWidth + gap;
  };

  const scrollPrevious = () => {
    viewportRef.current?.scrollBy({
      left: -getScrollAmount(),
      behavior: "smooth",
    });
  };

  const scrollNext = () => {
    viewportRef.current?.scrollBy({
      left: getScrollAmount(),
      behavior: "smooth",
    });
  };

  return (
    <section className="home-section home-cities">
      <div className="home-container">
        {/* Section Heading */}

        <div className="home-section__heading">
          <p className="home-eyebrow">Explore locally</p>

          <h2>
            Top Cities <span>Trending</span> This Week
          </h2>

          <p>
            See where people are discovering new restaurants, events &amp;
            offers.
          </p>
        </div>

        {/* City Carousel */}

        <div className="home-cities__carousel">
          {/* Previous Button */}

          <button
            type="button"
            className="home-cities__arrow"
            aria-label="Previous cities"
            onClick={scrollPrevious}
            disabled={!canScrollLeft}
          >
            <ChevronLeft size={20} />
          </button>

          {/* Carousel Viewport */}

          <div ref={viewportRef} className="home-cities__viewport">
            <div className="home-cities__track">
              {cities.map((city) => (
                <article key={city.id} className="home-city-card">
                  {/* Dark Offset */}

                  <div className="home-city-card__shadow" aria-hidden="true" />

                  {/* Main Card */}

                  <div className="home-city-card__inner">
                    {/* City Image */}

                    <div
                      className="home-city-card__image"
                      style={{
                        backgroundImage: `url("${city.image}")`,
                      }}
                      aria-hidden="true"
                    />

                    {/* Image Overlay */}

                    <div
                      className="home-city-card__image-overlay"
                      aria-hidden="true"
                    />

                    {/* ==========================
                        DEFAULT CARD
                    =========================== */}

                    <div className="home-city-card__default">
                      <div className="home-city-card__top">
                        <span className="home-city-card__number">
                          {String(city.id).padStart(2, "0")}
                        </span>

                        {city.badge && (
                          <span className="home-city-card__badge">
                            {city.badge}
                          </span>
                        )}
                      </div>

                      {/* Ghost Number */}

                      <span
                        className="home-city-card__ghost-number"
                        aria-hidden="true"
                      >
                        {String(city.id).padStart(2, "0")}
                      </span>

                      {/* City Information */}

                      <div className="home-city-card__bottom">
                        <h3>{city.name}</h3>

                        <p>{city.count}</p>
                      </div>
                    </div>

                    {/* ==========================
                        HOVER CARD
                    =========================== */}

                    <div className="home-city-card__hover">
                      <span className="home-city-card__hover-label">
                        EXPLORE CITY
                      </span>

                      <h3>{city.name}</h3>

                      <p>{city.description}</p>

                      <div className="home-city-card__explore">
                        <span>Explore</span>

                        <ChevronRight size={18} />
                      </div>
                    </div>
                  </div>
                </article>
              ))}
            </div>
          </div>

          {/* Next Button */}

          <button
            type="button"
            className="home-cities__arrow"
            aria-label="Next cities"
            onClick={scrollNext}
            disabled={!canScrollRight}
          >
            <ChevronRight size={20} />
          </button>
        </div>

        {/* View All Cities */}

        <div className="home-centered">
          <button
            type="button"
            className="home-button home-button--outline home-button--outline-dark"
          >
            View All Cities
          </button>
        </div>
      </div>
    </section>
  );
}

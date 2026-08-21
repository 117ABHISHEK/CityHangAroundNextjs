"use client";

import { useEffect, useRef, useState } from "react";
import {
  EducationIcon,
  EventIcons,
  FoodIcon,
  CommunityIcon,
  BadgeIndianRupeeIcon,
  ShoppingIcon,
  HealthIcon,
  WrenchOffIcon
} from "@/src/components/ui/icons";
import type { LucideIcon } from "@/src/components/ui/icons";
import { LetterSwap3D } from "@/src/components/ui/letter-swap-3d";

import "./index.css";

type Category = {
  icon: LucideIcon;
  title: string;
  badge: string;
  description: string;
  pills: string[];
};

const categories: Category[] = [
  {
    icon: FoodIcon,
    title: "Food & Dining",
    badge: "1.8K+ Places",
    description:
      "Restaurants, Cafes, Bakeries & Street Food",
    pills: ["Cafes", "Pizzas", "Buffets"],
  },

  {
    icon: HealthIcon,
    title: "Health & Medical",
    badge: "620+ Doctors",
    description:
      "Hospitals, Clinics, Dental & Diagnostic Labs",
    pills: ["Doctors", "Clinics", "Gyms"],
  },

  {
    icon: EducationIcon,
    title: "Education",
    badge: "850+ Institutes",
    description:
      "Schools, Colleges, Coaching & Skill Tutors",
    pills: ["Colleges", "Classes", "Skill IT"],
  },

  {
    icon: ShoppingIcon,
    title: "Shopping & Retail",
    badge: "2.4K+ Stores",
    description:
      "Fashion Boutiques, Electronics & Malls",
    pills: ["Apparel", "Malls", "Gadgets"],
  },

  {
    icon: EventIcons,
    title: "Events & Nightlife",
    badge: "290+ Live",
    description:
      "Concerts, Shows, Workshops & Meetups",
    pills: ["Music", "Nightlife", "Sports"],
  },

  {
    icon: CommunityIcon,
    title: "City Community",
    badge: "50+ Groups",
    description:
      "Discussion Forums, Local Groups & Q&A",
    pills: ["Foodies", "Jobs", "Real Estate"],
  },

  {
    icon: BadgeIndianRupeeIcon,
    title: "Trending & Deals",
    badge: "Hot Deals",
    description:
      "Hyperlocal Offers, Discounts & Top Places",
    pills: ["50% Off", "Top Rated", "Offers"],
  },

  {
    icon: WrenchOffIcon,
    title: "Auto & Car Repair",
    badge: "410+ Garages",
    description:
      "Auto Service, Garages, Car Wash & Parts",
    pills: ["Garages", "Wash", "EV Repair"],
  },
];

export default function Categories() {
  const [activeIndex, setActiveIndex] = useState<number | null>(null);
  const gridRef = useRef<HTMLDivElement>(null);
  const cardRefs = useRef<(HTMLElement | null)[]>([]);

  /* ----------------------------------------------------------
     IntersectionObserver: auto-activate the most-visible card
     in the mobile carousel.
  ---------------------------------------------------------- */
  useEffect(() => {
    const grid = gridRef.current;
    if (!grid) return;

    const observer = new IntersectionObserver(
      (entries) => {
        /* Find the entry with the largest intersection ratio */
        let best: IntersectionObserverEntry | null = null;
        for (const entry of entries) {
          if (
            entry.isIntersecting &&
            (!best || entry.intersectionRatio > best.intersectionRatio)
          ) {
            best = entry;
          }
        }

        if (best) {
          const idx = cardRefs.current.indexOf(
            best.target as HTMLElement,
          );
          if (idx !== -1) setActiveIndex(idx);
        }
      },
      {
        root: grid,          /* scroll container itself */
        rootMargin: "0px",
        threshold: [0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1],
      },
    );

    for (const el of cardRefs.current) {
      if (el) observer.observe(el);
    }

    return () => observer.disconnect();
  }, []);

  return (
    <section className="home-section home-categories">

      <div className="home-container">

        {/* =================================================
            SECTION HEADING
        ================================================= */}

        <div className="home-section__heading">

          <p className="home-eyebrow">
            Find your next favorite
          </p>

          <h2>
            What Are You {" "}

             <LetterSwap3D
                className="home-growth__letter-swap"
                frontFaceClassName="home-growth__letter-swap-front"
                backFaceClassName="home-growth__letter-swap-back"
                staggerInterval={0.055}
                staggerOrigin="first"
                flipDirection="top"
                duration={0.42}
                blur={true}
                blurAmount={3}
                respectReducedMotion={true}
                autoPlay={true}
                interval={5000}
              >
                Looking
              </LetterSwap3D>

              {" "}
            For Today?
          </h2>

          <p>
            Choose a category to find trusted local businesses.
          </p>

        </div>


        {/* =================================================
            CATEGORY GRID
        ================================================= */}

        <div className="home-category-grid" ref={gridRef}>

          {categories.map((category, index) => {

            const Icon = category.icon;

            return (
              <article
                key={category.title}
                ref={(el) => { cardRefs.current[index] = el; }}
                className={`home-category${activeIndex === index ? " is-active" : ""}`}
              >

                {/* =================================================
                    TOP
                ================================================= */}

                <div className="home-category__top">

                  <span className="home-category__icon">
                    <Icon size={22} />
                  </span>

                  <small>
                    {category.badge}
                  </small>

                </div>


                {/* =================================================
                    CONTENT
                ================================================= */}

                <div className="home-category__content">

                  <h3>
                    {category.title}
                  </h3>

                  <p>
                    {category.description}
                  </p>

                </div>


                {/* =================================================
                    PILLS
                ================================================= */}

                <div className="home-category__pills">

                  {category.pills.map((pill) => (

                    <span key={pill}>
                      {pill}
                    </span>

                  ))}

                </div>


                {/* =================================================
                    ACTION
                ================================================= */}

                <a
                  href="#"
                  className="home-category__action"
                >
                  <span>
                    Explore Category
                  </span>

                  <span className="home-category__arrow">
                    →
                  </span>
                </a>

              </article>
            );
          })}

        </div>

      </div>

    </section>
  );
}
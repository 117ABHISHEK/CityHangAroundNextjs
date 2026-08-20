import Image from "next/image";

import {
  ArrowRightIcon,
  CalendarIcon,
  ChartIcon,
  LocationIcon,
  ShieldIcon,
  StarIcon,
  SupportIcon,
  CommunityIcon,
} from "@/src/components/ui/icons";

import { LetterSwap3D } from "@/src/components/ui/letter-swap-3d";
import MagicCard from "@/src/components/ui/magic-card";

import "./index.css";


/* =========================================================
   BUSINESS GROWTH STEPS
========================================================= */

const steps = [
  {
    number: "1",
    title: "List Your Business",
    description: "Create your free listing in minutes.",
  },
  {
    number: "2",
    title: "Get Seen by Local Customers",
    description: "People discover you when they search.",
  },
  {
    number: "3",
    title: "Grow Revenue & Build Reputation",
    description: "Get more leads, calls and customers.",
  },
];


/* =========================================================
   REACH FEATURES
========================================================= */

const reachFeatures = [
  {
    icon: LocationIcon,
    title: "Get discovered locally",
    description: "Appear when nearby customers search.",
  },
  {
    icon: CalendarIcon,
    title: "Share offers instantly",
    description: "Promote deals, events and updates.",
  },
  {
    icon: StarIcon,
    title: "Build customer trust",
    description: "Collect reviews and showcase your reputation.",
  },
  {
    icon: ChartIcon,
    title: "Grow your business",
    description: "Turn local discovery into real customers.",
  },
];


/* =========================================================
   BUSINESS GROWTH COMPONENT
========================================================= */

export default function BusinessGrowth() {
  return (
    <>
      {/* =====================================================
          GET FOUND SECTION
      ===================================================== */}

      <section className="home-section home-growth">

        <div className="home-container home-growth__grid">

          {/* =================================================
              LEFT CONTENT
          ================================================= */}

          <div className="home-growth__content">

            <p className="home-eyebrow home-eyebrow--light">
              Get found
            </p>


            <h2>
              Get{" "}

              <LetterSwap3D
                className="home-growth__letter-swap"
                frontFaceClassName="home-growth__letter-swap-front"
                backFaceClassName="home-growth__letter-swap-back"
                staggerInterval={0.055}
                staggerOrigin="first"
                flipDirection="top"
                duration={0.42}
                blur
                blurAmount={3}
                respectReducedMotion
                autoPlay
                interval={5000}
              >
                Found
              </LetterSwap3D>

              {" "}by People in Your City
            </h2>


            <p className="home-section__intro">
              We help small businesses like yours get more
              visibility, leads &amp; revenue without spending big.
            </p>


            {/* =================================================
                STEPS
            ================================================= */}

            <div className="home-steps">

              {steps.map((step) => (

                <div
                  key={step.number}
                  className="home-step"
                >

                  <span className="home-step__number">
                    {step.number}
                  </span>


                  <h3>
                    {step.title}
                  </h3>


                  <p>
                    {step.description}
                  </p>

                </div>

              ))}

            </div>


            {/* =================================================
                CTA
            ================================================= */}

            <button
              type="button"
              className="
                home-button
                home-button--primary
                home-growth__cta
              "
            >

              <span>
                Start Your Free Listing Today
              </span>

              <ArrowRightIcon size={16} />

            </button>

          </div>


          {/* =================================================
              FACELESS BUSINESSMAN
          ================================================= */}

          <div
            className="home-growth__art"
            aria-hidden="true"
          >

            <span className="home-growth__art-glow" />

            <span
              className="
                home-growth__art-orbit
                home-growth__art-orbit--one
              "
            />

            <span
              className="
                home-growth__art-orbit
                home-growth__art-orbit--two
              "
            />


            {/* =================================================
                PERSON
            ================================================= */}

            <div className="home-growth__person">

              {/* HEAD */}

              <div className="home-growth__head">

                <div className="home-growth__hair" />

                <div className="home-growth__head-highlight" />

              </div>


              {/* NECK */}

              <div className="home-growth__neck" />


              {/* COLLAR */}

              <div className="home-growth__collar">

                <span className="home-growth__collar-left" />

                <span className="home-growth__collar-right" />

              </div>


              {/* TIE */}

              <div className="home-growth__tie">

                <span className="home-growth__tie-knot" />

                <span className="home-growth__tie-body" />

              </div>


              {/* BODY */}

              <div className="home-growth__body">

                <span className="home-growth__body-highlight" />

                <span
                  className="
                    home-growth__body-lapel
                    home-growth__body-lapel--left
                  "
                />

                <span
                  className="
                    home-growth__body-lapel
                    home-growth__body-lapel--right
                  "
                />

              </div>

            </div>


            {/* =================================================
                RESULT CARD
            ================================================= */}

            <div className="home-growth__result">

              <span className="home-growth__result-icon">
                <ChartIcon size={18} />
              </span>


              <div>

                <strong>
                  Avg 47% more leads
                </strong>

                <small>
                  in 30 days
                </small>

              </div>

            </div>

          </div>

        </div>

      </section>


      {/* =====================================================
          REACH MORE CUSTOMERS
      ===================================================== */}

      <section className="home-section home-reach">

        <div className="home-container home-reach__grid">

          {/* =================================================
              PHONE SHOWCASE
          ================================================= */}

          <div className="home-reach__visual">

            {/* =================================================
                PHONE
            ================================================= */}

            <div className="home-phone home-phone--light">

              <div className="home-phone__frame">

                {/* CAMERA */}

                <div className="home-phone__camera">
                  <span />
                </div>


                {/* SCREEN */}

                <div
                  className="
                    home-phone__screen
                    home-phone__screen--light
                  "
                >

                  {/* APP HEADER */}

                  <div className="home-phone__header">

                    <div className="home-phone__brand">

                      <Image
                        src="/images/cityhangaround-logo.png"
                        alt="CityHangaround"
                        width={135}
                        height={32}
                        className="home-phone__brand-logo"
                        priority
                      />

                    </div>


                    <span className="home-phone__header-dot" />

                  </div>


                  {/* SEARCH */}

                  <div className="home-phone__search">

                    <LocationIcon size={13} />

                    <span>
                      Search near you
                    </span>

                  </div>


                  {/* LOCATION */}

                  <div className="home-phone__location">

                    <div>

                      <small>
                        NEAR YOU
                      </small>

                      <strong>
                        Local businesses
                      </strong>

                    </div>


                    <span>
                      2.4 km
                    </span>

                  </div>


                  {/* BUSINESS CARD */}

                  <div className="home-phone__business-card">

                    <div className="home-phone__business-image">

                      <span className="home-phone__business-image-icon">
                        ☕
                      </span>

                    </div>


                    <div className="home-phone__business-info">

                      <span className="home-phone__verified">
                        VERIFIED
                      </span>

                      <strong>
                        Local Cafe
                      </strong>

                      <small>
                        Cafe · Coffee · Bakery
                      </small>


                      <div className="home-phone__rating">

                        <span>
                          ★
                        </span>

                        <b>
                          4.9
                        </b>

                        <em>
                          (128)
                        </em>

                      </div>

                    </div>

                  </div>


                  {/* OFFER */}

                  <div className="home-phone__offer">

                    <div className="home-phone__offer-icon">
                      %
                    </div>


                    <div>

                      <span>
                        TODAY&apos;S OFFER
                      </span>

                      <strong>
                        20% OFF your first visit
                      </strong>

                    </div>


                    <ArrowRightIcon size={14} />

                  </div>


                  {/* MAP */}

                  <div className="home-phone__map">

                    <span
                      className="
                        home-phone__map-line
                        home-phone__map-line--one
                      "
                    />

                    <span
                      className="
                        home-phone__map-line
                        home-phone__map-line--two
                      "
                    />

                    <span
                      className="
                        home-phone__map-line
                        home-phone__map-line--three
                      "
                    />

                    <span className="home-phone__map-pin">
                      <LocationIcon size={12} />
                    </span>

                  </div>


                  {/* BOTTOM NAV */}

                  <div className="home-phone__nav">

                    <span
                      className="
                        home-phone__nav-item
                        home-phone__nav-item--active
                      "
                    >

                      <LocationIcon size={15} />

                      <small>
                        Explore
                      </small>

                    </span>


                    <span className="home-phone__nav-item">

                      <CalendarIcon size={15} />

                      <small>
                        Deals
                      </small>

                    </span>


                    <span className="home-phone__nav-item">

                      <StarIcon size={15} />

                      <small>
                        Saved
                      </small>

                    </span>

                  </div>

                </div>

              </div>

            </div>


            {/* =================================================
                CUSTOMER BADGE
            ================================================= */}

            <div
              className="
                home-reach__floating-card
                home-reach__floating-card--customers
              "
            >

              <span className="home-reach__floating-icon">
                <ChartIcon size={16} />
              </span>


              <div>

                <strong>
                  +12 new customers
                </strong>

                <small>
                  this week
                </small>

              </div>

            </div>


            {/* =================================================
                RATING BADGE
            ================================================= */}

            <div
              className="
                home-reach__floating-card
                home-reach__floating-card--rating
              "
            >

              <span className="home-reach__floating-star">
                ★
              </span>


              <div>

                <strong>
                  4.9
                </strong>

                <small>
                  customer rating
                </small>

              </div>

            </div>

          </div>


          {/* =================================================
              RIGHT CONTENT
          ================================================= */}

          <div className="home-reach__content">

            <p className="home-eyebrow">
              For local businesses
            </p>


            {/* =================================================
                HEADING

                IMPORTANT:
                The animated phrase is wrapped in its own
                inline-block so "Grow Faster" can NEVER
                split into "Gro / w Faster".
            ================================================= */}

            <h2 className="home-reach__heading">

              <span className="home-reach__heading-main">
                Reach More Customers,
              </span>

              {" "}

              <span className="home-reach__animated-phrase">

                <LetterSwap3D
                  className="home-reach__letter-swap"
                  frontFaceClassName="home-reach__letter-swap-front"
                  backFaceClassName="home-reach__letter-swap-back"
                  staggerInterval={0.055}
                  staggerOrigin="first"
                  flipDirection="top"
                  duration={0.42}
                  blur
                  blurAmount={3}
                  respectReducedMotion
                  autoPlay
                  interval={6500}
                >
                  Grow Faster
                </LetterSwap3D>

              </span>

            </h2>


            <p className="home-section__intro">
              Thousands of small businesses use CityHangaround
              to attract local customers every day.
            </p>


            {/* =================================================
                FEATURES
            ================================================= */}

            <div className="home-reach__features">

              {reachFeatures.map(
                ({
                  icon: Icon,
                  title,
                  description,
                }) => (

                  <MagicCard key={title}>

                    <div className="home-reach__feature">

                      <span className="home-reach__feature-icon">

                        <Icon size={16} />

                      </span>


                      <div>

                        <strong>
                          {title}
                        </strong>

                        <p>
                          {description}
                        </p>

                      </div>

                    </div>

                  </MagicCard>

                )
              )}

            </div>


            {/* =================================================
                ACTIONS
            ================================================= */}

            <div className="home-reach__actions">

              <button
                type="button"
                className="
                  home-button
                  home-button--primary
                  home-reach__primary-button
                "
              >

                <span>
                  Claim Your Free Profile
                </span>

                <ArrowRightIcon size={16} />

              </button>


              <button
                type="button"
                className="
                  home-button
                  home-button--outline
                  home-button--outline-dark
                  home-reach__secondary-button
                "
              >
                Learn More
              </button>

            </div>


            {/* =================================================
                TRUST
            ================================================= */}

            <div className="home-reach__trust">

              <span>

                <CommunityIcon size={14} />

                Local reach

              </span>


              <span>

                <ShieldIcon size={14} />

                Verified listings

              </span>


              <span>

                <SupportIcon size={14} />

                24/7 support

              </span>

            </div>

          </div>

        </div>

      </section>
    </>
  );
}
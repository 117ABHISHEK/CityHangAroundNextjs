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

          <div>

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
                blur={true}
                blurAmount={3}
                respectReducedMotion={true}
                autoPlay={true}
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
              className="home-button home-button--primary"
            >

              <span>
                Start Your Free Listing Today
              </span>

              <ArrowRightIcon size={16} />

            </button>

          </div>


          {/* =================================================
              ILLUSTRATION
          ================================================= */}

          <div
            className="home-growth__art"
            aria-hidden="true"
          >

            {/* =================================================
                PERSON
            ================================================= */}

            <div className="home-growth__person">

              {/* =================================================
                  HEAD
              ================================================= */}

              <div className="home-growth__head">

                <div className="home-growth__face">

                  <span
                    className="
                      home-growth__eye
                      home-growth__eye--left
                    "
                  />

                  <span
                    className="
                      home-growth__eye
                      home-growth__eye--right
                    "
                  />

                  <span className="home-growth__nose" />

                  <span className="home-growth__mouth" />

                </div>

              </div>


              {/* =================================================
                  NECK
              ================================================= */}

              <div className="home-growth__neck" />


              {/* =================================================
                  TIE
              ================================================= */}

              <div className="home-growth__tie">

                <span className="home-growth__tie-knot" />

                <span className="home-growth__tie-body" />

              </div>


              {/* =================================================
                  BODY
              ================================================= */}

              <div className="home-growth__body" />

            </div>


            {/* =================================================
                RESULT CARD
            ================================================= */}

            <div className="home-growth__result">

              <span className="home-growth__result-icon">

                <ChartIcon size={18} />

              </span>


              <strong>
                Avg 47% more leads
              </strong>


              <small>
                in 30 days
              </small>

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
              PHONE
          ================================================= */}

          <div
            className="
              home-phone
              home-phone--light
            "
          >

            <div
              className="
                home-phone__screen
                home-phone__screen--light
              "
            />

          </div>


          {/* =================================================
              RIGHT CONTENT
          ================================================= */}

          <div>

            <p className="home-eyebrow">
              For local businesses
            </p>


            <h2>
              Reach More Customers,{" "}
              <span>Grow Faster</span>
            </h2>


            <p className="home-section__intro">
              Thousands of small businesses use CityHangaround
              to attract local customers every day.
            </p>


            {/* =================================================
                CHECKLIST
            ================================================= */}

            <ul className="home-checklist">

              {[
                [
                  LocationIcon,
                  "Get discovered by people searching near you",
                ],
                [
                  CalendarIcon,
                  "Share deals, offers & updates instantly",
                ],
                [
                  StarIcon,
                  "Build trust with reviews & ratings",
                ],
                [
                  ChartIcon,
                  "Increase footfall and grow your business",
                ],
              ].map(([Icon, label]) => {

                const IconComponent = Icon;

                return (

                  <li key={label as string}>

                    <span>
                      <IconComponent size={14} />
                    </span>

                    {label as string}

                  </li>

                );

              })}

            </ul>


            {/* =================================================
                ACTIONS
            ================================================= */}

            <div className="home-hero__actions">

              <button
                type="button"
                className="
                  home-button
                  home-button--primary
                "
              >
                Claim Your Free Profile Now
              </button>


              <button
                type="button"
                className="
                  home-button
                  home-button--outline
                  home-button--outline-dark
                "
              >
                Learn More
              </button>

            </div>


            {/* =================================================
                TRUST LIST
            ================================================= */}

            <div className="home-trust-list">

              <span>

                <CommunityIcon size={15} />

                Local reach

              </span>


              <span>

                <ShieldIcon size={15} />

                Verified listings

              </span>


              <span>

                <SupportIcon size={15} />

                24/7 support

              </span>

            </div>

          </div>

        </div>

      </section>
    </>
  );
}
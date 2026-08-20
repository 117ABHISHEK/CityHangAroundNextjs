import {
  ArrowRightIcon,
  CheckIcon,
  ChevronDown,
  LockIcon,
  ShieldIcon,
  StarIcon,
  SupportIcon,
} from "@/src/components/ui/icons";

import { LetterSwap3D } from "@/src/components/ui/letter-swap-3d";
import MagicCard from "@/src/components/ui/magic-card";

import "./index.css";

const faqs = [
  [
    "How can I get free leads for my business?",
    "List your business for free and start appearing in local searches.",
  ],
  [
    "Is CityHangaround available in my city?",
    "We currently cover 350+ cities and are adding new cities every month.",
  ],
  [
    "How do I promote my listing?",
    "Use Deals, Events and Promotions to boost visibility near you.",
  ],
  [
    "Can I track views and leads?",
    "Yes. Analytics shows profile views, leads and customer engagement.",
  ],
];

const partners = [
  {
    name: "Zomato",
    logo: "https://cdn.simpleicons.org/zomato/E23744",
    className: "home-partner__logo--zomato",
  },
  {
    name: "Paytm",
    logo: "https://cdn.simpleicons.org/paytm/00BAF2",
    className: "home-partner__logo--paytm",
  },
  {
    name: "Uber",
    logo: "https://cdn.simpleicons.org/uber/000000",
    className: "home-partner__logo--uber",
  },
  {
    name: "MakeMyTrip",
    logo: "https://commons.wikimedia.org/wiki/Special:Redirect/file/Makemytrip_logo.svg",
    className: "home-partner__logo--makemytrip",
  },
  {
    name: "OYO",
    logo: "https://cdn.simpleicons.org/oyo/EE2E24",
    className: "home-partner__logo--oyo",
  },
  {
    name: "Cleartrip",
    logo: "https://commons.wikimedia.org/wiki/Special:Redirect/file/Cleartrip_Original.svg",
    className: "home-partner__logo--cleartrip",
  },
];

export default function TrustAndFaq() {
  return (
    <>
      {/* =====================================================
          TRUST
      ====================================================== */}

      <section className="home-section home-trust">
        <div className="home-container home-trust__grid">

          <div className="home-trust__content">

            <p className="home-eyebrow">
              Trusted locally
            </p>

            <h2>
              Why Local Businesses Choose{" "}
              <span>CityHangaround</span>
            </h2>

            <p className="home-section__intro">
              Grow with our high-traffic local network built to
              connect small businesses with real nearby customers.
            </p>

            <div className="home-trust__stats">

              <MagicCard>
                <div className="home-trust__stat">
                  <strong>10,000+</strong>
                  <small>Daily Visitors</small>
                </div>
              </MagicCard>

              <MagicCard>
                <div className="home-trust__stat">
                  <strong>5,000+</strong>
                  <small>Businesses Listed</small>
                </div>
              </MagicCard>

              <MagicCard>
                <div className="home-trust__stat">
                  <strong>350+</strong>
                  <small>Cities Covered</small>
                </div>
              </MagicCard>

            </div>

            <button
              type="button"
              className="home-button home-button--primary"
            >
              Join Free
              <ArrowRightIcon size={16} />
            </button>

            <div className="home-trust-list">

              <span>
                <LockIcon size={15} />
                Secure payments
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

          {/* TRUST VISUAL */}

          <div className="home-trust__visual">

            <div className="home-trust__blob home-trust__blob--one" />
            <div className="home-trust__blob home-trust__blob--two" />

            <div className="home-trust__dashboard">

              <div className="home-trust__dashboard-top">

                <div>
                  <span>Business Performance</span>
                  <strong>+34.8%</strong>
                </div>

                <span className="home-trust__status">
                  Growing
                </span>

              </div>

              <div className="home-trust__chart">
                <span style={{ height: "32%" }} />
                <span style={{ height: "48%" }} />
                <span style={{ height: "42%" }} />
                <span style={{ height: "66%" }} />
                <span style={{ height: "58%" }} />
                <span style={{ height: "79%" }} />
                <span style={{ height: "92%" }} />
              </div>

              <div className="home-trust__dashboard-footer">

                <div>
                  <small>Profile Views</small>
                  <strong>8.4K</strong>
                </div>

                <div>
                  <small>Leads</small>
                  <strong>1.2K</strong>
                </div>

                <div>
                  <small>Rating</small>
                  <strong>4.9</strong>
                </div>

              </div>

            </div>

            <div className="home-trust__floating home-trust__floating--customers">

              <span className="home-trust__floating-icon">
                <ArrowRightIcon size={14} />
              </span>

              <div>
                <strong>+248 Customers</strong>
                <small>This month</small>
              </div>

            </div>

            <div className="home-trust__floating home-trust__floating--rating">

              <div className="home-trust__rating-stars">
                <StarIcon size={11} fill="currentColor" />
                <StarIcon size={11} fill="currentColor" />
                <StarIcon size={11} fill="currentColor" />
                <StarIcon size={11} fill="currentColor" />
                <StarIcon size={11} fill="currentColor" />
              </div>

              <strong>4.9 Rating</strong>
              <small>From local customers</small>

            </div>

          </div>

        </div>
      </section>


      {/* =====================================================
          TESTIMONIALS
      ====================================================== */}

      <section className="home-section home-testimonials">

        <div className="home-container home-testimonials__grid">

          {/* PARTNERS */}

          <div>

            <p className="home-eyebrow">
              Trusted ecosystem
            </p>

            <h2>
              Trusted by Local Businesses &amp; Partners Nationwide
            </h2>

            <p className="home-testimonials__intro">
              Built to connect local businesses with the platforms
              and customers that matter most.
            </p>

            <div className="home-partners">

              {partners.map((partner) => (
                <MagicCard key={partner.name}>
                  <div className="home-partner-card">
                    <div className="home-partner">

                      {/* eslint-disable-next-line @next/next/no-img-element */}
                      <img
                        src={partner.logo}
                        alt={`${partner.name} logo`}
                        className={`home-partner__logo ${partner.className}`}
                      />

                    </div>
                  </div>
                </MagicCard>
              ))}

            </div>

          </div>


          {/* TESTIMONIAL */}

          <div className="home-testimonial-section">

            <p className="home-eyebrow">
              Real businesses. Real results.
            </p>

            <h2>
              What Local Business Owners Say
            </h2>

            {/* MAGIC CARD */}

            <MagicCard
              className="home-testimonial"
            >

              <div className="home-stars">

                <StarIcon size={14} fill="currentColor" />
                <StarIcon size={14} fill="currentColor" />
                <StarIcon size={14} fill="currentColor" />
                <StarIcon size={14} fill="currentColor" />
                <StarIcon size={14} fill="currentColor" />

              </div>

              <blockquote>
                “CityHangaround helped us get more local customers
                than any other platform we tried.”
              </blockquote>

              <div className="home-testimonial__person">

                <span className="home-testimonial__avatar">
                  RS
                </span>

                <div>
                  <strong>Ravi Sharma</strong>
                  <small>Café Owner, Kolkata</small>
                </div>

              </div>

            </MagicCard>

          </div>

        </div>

      </section>


      {/* =====================================================
          FAQ + CTA
      ====================================================== */}

      <section className="home-section home-faq">

        <div className="home-container home-faq__grid">

          {/* FAQ */}

          <div className="home-faq__content">

            <p className="home-eyebrow">
              Need to know
            </p>

            <h2>
              Frequently Asked Questions
            </h2>

            <div className="home-faq__list">

              {faqs.map(([question, answer]) => (
                <details key={question}>

                  <summary>

                    <span>
                      {question}
                    </span>

                    <span className="home-faq__chevron">
                      <ChevronDown size={16} />
                    </span>

                  </summary>

                  <p>
                    {answer}
                  </p>

                </details>
              ))}

            </div>

          </div>


          {/* CTA */}

          <div className="home-cta">

            <div className="home-cta__pattern" />

            <div className="home-cta__orb home-cta__orb--one" />
            <div className="home-cta__orb home-cta__orb--two" />

            <div className="home-cta__rings">
              <span />
              <span />
              <span />
            </div>

            <div className="home-cta__content">

              <span className="home-cta__badge">
                <CheckIcon size={13} />
                Free forever
              </span>

              {/* =================================================
                  3D LETTER ANIMATION
              ================================================== */}

              <h2>

                Ready to{" "}

                <LetterSwap3D
                  className="home-cta__letter-swap"
                  frontFaceClassName="home-cta__letter-swap-front"
                  backFaceClassName="home-cta__letter-swap-back"
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
                  Grow
                </LetterSwap3D>

                <br />

                Your Business?

              </h2>

              <p>
                Get listed in minutes and connect with thousands
                of local customers. Start growing your local
                presence today.
              </p>

              <button
                type="button"
                className="home-button home-button--light home-cta__button"
              >
                List Your Business Free Now
                <ArrowRightIcon size={16} />
              </button>

              <div className="home-cta__benefits">

                <span>
                  <CheckIcon size={12} />
                  No credit card
                </span>

                <span>
                  <CheckIcon size={12} />
                  Setup in minutes
                </span>

              </div>

            </div>


            {/* FLOATING CARDS */}

            <div className="home-cta__mini-card home-cta__mini-card--one">

              <span className="home-cta__mini-icon">
                <StarIcon
                  size={13}
                  fill="currentColor"
                />
              </span>

              <div>
                <strong>4.9/5</strong>
                <small>Business rating</small>
              </div>

            </div>


            <div className="home-cta__mini-card home-cta__mini-card--two">

              <span className="home-cta__mini-icon">
                <ArrowRightIcon size={13} />
              </span>

              <div>
                <strong>+34%</strong>
                <small>More visibility</small>
              </div>

            </div>

          </div>

        </div>

      </section>
    </>
  );
}
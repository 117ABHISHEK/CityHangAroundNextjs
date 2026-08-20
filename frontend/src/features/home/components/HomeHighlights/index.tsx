import {
  BusinessIcon,
  CalendarIcon,
  ChartIcon,
  CommunityIcon,
  ShoppingIcon,
  ShieldIcon,
  StarIcon,
  MarketplaceIcon,
} from "@/src/components/ui/icons";

import { LetterSwap3D } from "@/src/components/ui/letter-swap-3d";
import MagicCard from "@/src/components/ui/magic-card";

import "./index.css";

const highlights = [
  [BusinessIcon, "Add Business", "Get listed and reach local customers"],
  [
    MarketplaceIcon,
    "Deals & Offers",
    "Promote offers and attract more customers",
  ],
  [CalendarIcon, "Events", "Create and promote events in your city"],
  [StarIcon, "Reviews & Ratings", "Build trust with genuine ratings"],
  [CommunityIcon, "Community", "Connect with local customers"],
  [ShieldIcon, "Verified Listing", "Build credibility with a trusted badge"],
  [ChartIcon, "Analytics", "Track views, leads and customer insights"],
  [ShoppingIcon, "Promotions", "Promote your business with smart tools"],
];

export default function HomeHighlights() {
  return (
    <section className="home-section home-highlights">
      <div className="home-container">

        {/* =====================================================
            HEADING
        ===================================================== */}

        <div className="home-section__heading home-highlights__heading">
          <p className="home-eyebrow">
            Built to help you grow
          </p>

          <h2>
            Everything You Need to{" "}

            <span className="home-highlights__animated-word">
              <LetterSwap3D
                className="home-highlights__letter-swap"
                frontFaceClassName="home-highlights__letter-swap-front"
                backFaceClassName="home-highlights__letter-swap-back"
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
            </span>

            {" "}Your Business
          </h2>

          <p>
            One simple dashboard to manage your listing,
            engage customers, and track results.
          </p>
        </div>


        {/* =====================================================
            HIGHLIGHT CARDS
        ===================================================== */}

        <div className="home-highlight-grid">
          {highlights.map(([Icon, title, description]) => (
            <MagicCard key={title as string}>

              <article className="home-highlight">

                {/* TOP */}
                <div className="home-highlight__top">

                  <span className="home-highlight__icon">
                    <Icon size={20} />
                  </span>

                  <span className="home-highlight__arrow">
                    ↗
                  </span>

                </div>


                {/* CONTENT */}
                <div className="home-highlight__content">

                  <h3>
                    {title as string}
                  </h3>

                  <p>
                    {description as string}
                  </p>

                </div>

              </article>

            </MagicCard>
          ))}
        </div>


        {/* =====================================================
            CTA
        ===================================================== */}

        <div className="home-centered home-highlights__action">
          <button
            type="button"
            className="
              home-button
              home-button--outline
              home-button--outline-dark
            "
          >
            See How It Works

            <ChartIcon size={16} />
          </button>
        </div>

      </div>
    </section>
  );
}
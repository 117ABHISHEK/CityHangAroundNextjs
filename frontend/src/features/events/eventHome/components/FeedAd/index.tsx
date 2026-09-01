"use client";

import Link from "next/link";
import "./index.css";

export interface FeedAdData {
  id: string;
  type: "banner" | "sponsor" | "cta";
  title: string;
  description: string;
  cta: string;
  ctaLink: string;
  image: string;
  badge?: string;
  gradient: string;
}

const FEED_ADS: FeedAdData[] = [
  {
    id: "ad-food-fest",
    type: "banner",
    title: "Ahmedabad Street Food Festival 2026",
    description:
      "50+ food stalls, live cooking battles, celebrity chefs & unlimited tastings. Early bird passes starting ₹149.",
    cta: "Get Early Bird Pass →",
    ctaLink: "/events",
    image:
      "https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=1200&q=80",
    badge: "🔥 Trending",
    gradient: "linear-gradient(135deg, #ff6b35, #f7931e)",
  },
  {
    id: "ad-host-event",
    type: "cta",
    title: "Planning a Meetup, Festival, or Workshop?",
    description:
      "List your event on CityHangAround — instant ticketing, QR check-ins, and verified analytics. Reach 50K+ locals.",
    cta: "Host Your Event Free →",
    ctaLink: "/events/create",
    image:
      "https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80",
    badge: "FOR ORGANIZERS",
    gradient: "linear-gradient(135deg, #667eea, #764ba2)",
  },
  {
    id: "ad-tech-summit",
    type: "sponsor",
    title: "Tech Summit Gujarat 2026 @ GIFT City",
    description:
      "AI, Blockchain & Cloud — 2 days, 40+ speakers, 3000+ attendees. Powered by Google & NASSCOM.",
    cta: "Reserve Your Seat →",
    ctaLink: "/events",
    image:
      "https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80",
    badge: "⚡ Sponsored",
    gradient: "linear-gradient(135deg, #2563eb, #3b82f6)",
  },
  {
    id: "ad-music-night",
    type: "banner",
    title: "Sunset Waves — Live DJ & Acoustic Night",
    description:
      "An unforgettable rooftop experience with India's top DJs. Limited 200 passes only. Don't miss out!",
    cta: "Grab 30% Off Pass →",
    ctaLink: "/events",
    image:
      "https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80",
    badge: "🎶 Music",
    gradient: "linear-gradient(135deg, #ec4899, #f43f5e)",
  },
  {
    id: "ad-photography",
    type: "sponsor",
    title: "Heritage Photography Walk — Old Ahmedabad",
    description:
      "Walk through UNESCO heritage pols with National Geographic contributor Rahul Dave. Limited 25 spots.",
    cta: "Book ₹299 Seat →",
    ctaLink: "/events",
    image:
      "https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=1200&q=80",
    badge: "📸 Workshop",
    gradient: "linear-gradient(135deg, #8b5cf6, #a78bfa)",
  },
  {
    id: "ad-startup-mixer",
    type: "cta",
    title: "Founders & Investors Mixer — Startup Night",
    description:
      "Network with 100+ founders and angel investors. Pitch your idea in 60 seconds. Free for first 50 registrations.",
    cta: "Register Free →",
    ctaLink: "/events",
    image:
      "https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&w=1200&q=80",
    badge: "💼 Business",
    gradient: "linear-gradient(135deg, #059669, #10b981)",
  },
];

interface FeedAdProps {
  index: number;
}

export default function FeedAd({ index }: FeedAdProps) {
  const ad = FEED_ADS[index % FEED_ADS.length];

  return (
    <div className={`feed-ad feed-ad--${ad.type}`}>
      <div className="feed-ad__inner">
        <div
          className="feed-ad__image"
          style={{ backgroundImage: `url(${ad.image})` }}
        >
          <div className="feed-ad__overlay" style={{ background: ad.gradient }} />
          {ad.badge && <span className="feed-ad__badge">{ad.badge}</span>}
        </div>

        <div className="feed-ad__body">
          <span className="feed-ad__label">
            {ad.type === "sponsor" ? "Sponsored" : ad.type === "cta" ? "CityHangAround" : "Promoted"}
          </span>
          <h3 className="feed-ad__title">{ad.title}</h3>
          <p className="feed-ad__desc">{ad.description}</p>
          <Link href={ad.ctaLink} className="feed-ad__cta" style={{ background: ad.gradient }}>
            {ad.cta}
          </Link>
        </div>
      </div>
    </div>
  );
}

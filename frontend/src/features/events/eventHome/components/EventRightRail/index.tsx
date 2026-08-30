"use client";

import Link from "next/link";
import { PlusCircle, ShieldCheck } from "lucide-react";
import AdSlot from "@/src/features/community/components/AdSlot";
import "./index.css";

interface Organizer {
  initials: string;
  name: string;
  meta: string;
  color: string;
}

const TOP_ORGANIZERS: Organizer[] = [
  { initials: "GT", name: "Gujarat Tech Alliance", meta: "Verified Organizer • 3 Events", color: "#2563eb" },
  { initials: "AF", name: "Amdavad Food Collective", meta: "6 upcoming food fests", color: "#f5720e" },
  { initials: "VC", name: "VibeCity Productions", meta: "Live DJ & Sunset Acts", color: "#ec4899" },
  { initials: "SB", name: "SBR Founders Circle", meta: "Founder & Investor Mixers", color: "#1fa672" },
];

export default function EventRightRail() {
  return (
    <aside className="community-right-rail">
      {/* Top Organizers Group */}
      <div className="community-side-group">
        <div className="community-side-title">Top Event Organizers</div>
        <div className="community-people-list">
          {TOP_ORGANIZERS.map((org) => (
            <div key={org.name} className="community-person-item">
              <div
                className="community-person-avatar"
                style={{ backgroundColor: org.color }}
              >
                {org.initials}
              </div>
              <div className="community-person-info">
                <div className="community-person-name">{org.name}</div>
                <div className="community-person-meta">{org.meta}</div>
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* AdSlot 1: Food Fest */}
      <div className="community-side-group">
        <AdSlot
          variant="landscape"
          title="Ahmedabad Street & Gourmet Food Fest 2026"
          cta="Book ₹149 Pass"
          image="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80"
        />
      </div>

      {/* Host Event Highlight Card */}
      <div className="community-side-group">
        <div className="community-highlight-card">
          <div
            className="community-highlight-card__image"
            style={{
              backgroundImage:
                'linear-gradient(135deg, rgba(15, 23, 42, 0.75), rgba(30, 41, 59, 0.6)), url("https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=900&q=80")',
            }}
          />
          <div className="community-highlight-card__content">
            <h4>Host your event in Ahmedabad</h4>
            <p className="event-rail__desc">
              Get direct ticketing, QR check-ins, and reach thousands of enthusiastic locals.
            </p>
            <Link href="/events/create" className="event-rail__btn">
              <PlusCircle size={14} />
              <span>Launch Your Event</span>
            </Link>
          </div>
        </div>
      </div>

      {/* AdSlot 2: AI Hackathon */}
      <div className="community-side-group">
        <AdSlot
          variant="portrait"
          title="Agentic AI 48-Hour Virtual Hackathon — ₹2.5L Prize Pool"
          cta="Register Team"
          image="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=900&q=80"
        />
      </div>

      {/* Event Guidelines */}
      <div className="community-side-group">
        <div className="community-side-title">Event Safety & Guarantee</div>
        <p className="community-guidelines">
          <ShieldCheck size={16} style={{ display: "inline", verticalAlign: "text-bottom", color: "#16a34a", marginRight: "4px" }} />
          All bookings on CityHangAround include 100% verified QR entry, buyer protection, and organizer authenticity.
        </p>
      </div>

      {/* AdSlot 3: Tech Summit */}
      <div className="community-side-group">
        <AdSlot
          variant="landscape"
          title="Tech Summit Gujarat 2026 @ GIFT City"
          cta="Explore Tickets"
          image="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80"
        />
      </div>
    </aside>
  );
}

"use client";

import { useState } from "react";
import CommunitySidebar from "./components/CommunitySidebar";
import FeedComposer from "./components/FeedComposer";
import FilterBar from "./components/FilterBar";
import PostCard from "./components/PostCard";
import RightRail from "./components/RightRail";

const communities = [
  { id: "foodies", icon: "🍕", name: "Ahmedabad Foodies", color: "#f5720e" },
  { id: "jobs", icon: "💼", name: "Ahmedabad Jobs", color: "#2f6fed" },
  { id: "real-estate", icon: "🏠", name: "Real Estate Ahmedabad", color: "#1fa672" },
  { id: "startup", icon: "🚀", name: "Startup Ahmedabad", color: "#8b5cf6" },
  { id: "dog-lovers", icon: "🐾", name: "Dog Lovers", color: "#2b2f3a" },
];

const posts = [
  {
    id: 1,
    community: "AhmedabadFoodies",
    icon: "🍕",
    color: "#f5720e",
    title: "Best pizza places in Ahmedabad? 🍕",
    author: "u/PriyaShah",
    time: "2h ago",
    body:
      "Looking for some really good pizza places in Ahmedabad. Prefer places with good ambience and thin crust options. Your suggestions?",
    image: "https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=1200&q=80",
    tags: ["🍕 Recommendation", "Food & Dining", "Ahmedabad"],
    votes: 142,
    comments: 32,
    shares: 8,
  },
  {
    id: 2,
    community: "AhmedabadJobs",
    icon: "💼",
    color: "#2f6fed",
    title: "PHP Developer needed for exciting project in GIFT City 💻",
    author: "u/RahulMehta",
    time: "4h ago",
    body:
      "We are hiring a PHP Developer with 2+ years of experience in Laravel & Vue. Work from home option available. Competitive salary package.",
    tags: ["💼 Hiring / Job", "Laravel", "Remote"],
    votes: 89,
    comments: 18,
    shares: 11,
  },
  {
    id: 3,
    community: "RealEstateAhmedabad",
    icon: "🏠",
    color: "#1fa672",
    title: "Which area is best for investment in Ahmedabad? 🏘️",
    author: "u/NehaPatel",
    time: "6h ago",
    body:
      "Looking for investment opportunities in Ahmedabad. Which areas are growing fast?",
    tags: ["🏘️ Investment", "Real Estate", "Ahmedabad"],
    votes: 32,
    comments: 24,
    shares: 6,
  },
];

const people = [
  { initials: "AS", name: "Asha Shah", meta: "7 mutual groups", color: "#e8432c" },
  { initials: "RK", name: "Ravi Kumar", meta: "New follower", color: "#2f6fed" },
  { initials: "NP", name: "Nisha Patel", meta: "Commented recently", color: "#1fa672" },
];

export default function CommunitySection() {
  const [activeFilter, setActiveFilter] = useState("hot");

  return (
    <section className="community-page">
      <div className="community-shell">
        <CommunitySidebar communities={communities} />

        <main className="community-feed">
          <FeedComposer />
          <FilterBar activeFilter={activeFilter} onChange={setActiveFilter} />
          {posts.map((post) => (
            <PostCard key={post.id} post={post} />
          ))}
        </main>

        <RightRail people={people} />
      </div>
    </section>
  );
}

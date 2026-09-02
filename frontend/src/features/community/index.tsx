"use client";

import { useState } from "react";
import CommunitySidebar from "./components/CommunitySidebar";
import FeedComposer from "./components/FeedComposer";
import FilterBar from "./components/FilterBar";
import PostCard from "./components/PostCard";
import RightRail from "./components/RightRail";
import { communities, communityPosts, people } from "./data";
import "./base.css";

export default function CommunitySection() {
  const [activeFilter, setActiveFilter] = useState("hot");

  return (
    <section className="community-page">
      <div className="community-shell">
        <CommunitySidebar communities={communities} />

        <main className="community-feed">
          <FeedComposer />
          <FilterBar activeFilter={activeFilter} onChange={setActiveFilter} />
          {communityPosts.map((post) => (
            <PostCard key={post.id} post={post} />
          ))}
        </main>

        <RightRail people={people} />
      </div>
    </section>
  );
}

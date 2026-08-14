"use client";

import { useState } from "react";
import {
  Heart,
  MessageCircle,
  Share2,
  Bookmark,
  MoreHorizontal,
  Fire,
  Clock,
  Star,
  Image,
  Video,
  Share,
} from "lucide-react";

const communities = [
  { icon: "🍕", name: "Ahmedabad Foodies", color: "#f5720e" },
  { icon: "💼", name: "Ahmedabad Jobs", color: "#2f6fed" },
  { icon: "🏠", name: "Real Estate Ahmedabad", color: "#1fa672" },
  { icon: "🚀", name: "Startup Ahmedabad", color: "#8b5cf6" },
  { icon: "🐾", name: "Dog Lovers", color: "#2b2f3a" },
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
    body: "Looking for some really good pizza places in Ahmedabad. Prefer places with good ambience and thin crust options. Your suggestions?",
    image: "https://picsum.photos/seed/pizzaahd1/800/450",
    tags: ["🍕 Recommendation", "Food & Dining", "Ahmedabad"],
    votes: 142,
    comments: 32,
  },
  {
    id: 2,
    community: "AhmedabadJobs",
    icon: "💼",
    color: "#2f6fed",
    title: "PHP Developer needed for exciting project in GIFT City 💻",
    author: "u/RahulMehta",
    time: "4h ago",
    body: "We are hiring a PHP Developer with 2+ years of experience in Laravel & Vue. Work from home option available. Competitive salary package.",
    tags: ["💼 Hiring / Job", "Laravel", "Remote"],
    votes: 89,
    comments: 18,
  },
  {
    id: 3,
    community: "RealEstateAhmedabad",
    icon: "🏠",
    color: "#1fa672",
    title: "Which area is best for investment in Ahmedabad? 🏘️",
    author: "u/NehaPatel",
    time: "6h ago",
    body: "Looking for investment opportunities in Ahmedabad. Which areas are growing fast?",
    tags: ["🏘️ Investment", "Real Estate", "Ahmedabad"],
    votes: 32,
    comments: 24,
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
    <section className="section community-panel tab-panel">
      <div className="layout">
        {/* LEFT SIDEBAR */}
        <aside className="side-left">
          <div className="side-group">
            <div className="side-group-title">Home</div>
            <div className="side-nav-list">
              <a href="#" className="active">
                <span className="side-icon">🏠</span>Home
              </a>
              <a href="#">
                <span className="side-icon">🔥</span>Popular
              </a>
              <a href="#">
                <span className="side-icon">🧭</span>Explore
              </a>
            </div>
          </div>

          <div className="side-group">
            <div className="side-group-title">Your Communities</div>
            <div className="side-nav-list">
              {communities.map((comm) => (
                <a key={comm.name} href="#">
                  <span
                    className="side-avatar"
                    style={{ backgroundColor: comm.color }}
                  >
                    {comm.icon}
                  </span>
                  {comm.name}
                </a>
              ))}
            </div>
          </div>

          <div className="side-group">
            <div className="side-group-title">Recent</div>
            <div className="side-nav-list">
              <a href="#">
                <span className="side-icon">🔖</span>Saved
              </a>
              <a href="#">
                <span className="side-icon">🕐</span>History
              </a>
              <a href="#">
                <span className="side-icon">📑</span>Bookmarks
              </a>
            </div>
          </div>

          <div className="side-group">
            <div className="side-group-title">Custom Feeds</div>
            <div className="side-nav-list">
              <a href="#">
                <span className="side-icon">💻</span>Technology
              </a>
              <a href="#">
                <span className="side-icon">🎮</span>Gaming
              </a>
              <a href="#">
                <span className="side-icon">📷</span>Photography
              </a>
              <a href="#">
                <span className="side-icon">⚡</span>Programming
              </a>
            </div>
          </div>
        </aside>

        {/* CENTER FEED */}
        <main className="feed">
          {/* CREATE POST */}
          <div className="composer">
            <div className="avatar">C</div>
            <div className="composer-main">
              <input
                type="text"
                className="composer-input"
                placeholder="Create Post"
              />
              <div className="composer-quick-actions">
                <button title="Add Photo">
                  <Image size={16} />
                </button>
                <button title="Add Video">
                  <Video size={16} />
                </button>
                <button title="Add Link">
                  <Share size={16} />
                </button>
              </div>
              <button className="btn-post">Post</button>
            </div>
          </div>

          {/* FILTER BAR */}
          <div className="filter-bar">
            <div className="filter-tabs">
              <button
                className={activeFilter === "hot" ? "active" : ""}
                onClick={() => setActiveFilter("hot")}
              >
                <Fire size={14} /> Hot
              </button>
              <button
                className={activeFilter === "new" ? "active" : ""}
                onClick={() => setActiveFilter("new")}
              >
                <Clock size={14} /> New
              </button>
              <button
                className={activeFilter === "top" ? "active" : ""}
                onClick={() => setActiveFilter("top")}
              >
                <Star size={14} /> Top
              </button>
            </div>
            <select className="filter-sort-select">
              <option value="hot">Sort: Hot</option>
              <option value="new">Sort: New</option>
              <option value="top_today">Sort: Top Today</option>
              <option value="top_week">Sort: Top This Week</option>
              <option value="top_all">Sort: Top All Time</option>
            </select>
          </div>

          {/* POSTS FEED */}
          {posts.map((post) => (
            <article key={post.id} className="post">
              <div className="post-head">
                <div
                  className="comm-tag"
                  style={{ borderLeftColor: post.color }}
                >
                  <span className="dot" style={{ background: post.color }}>
                    {post.icon}
                  </span>
                  <a href="#" className="comm-link">
                    r/{post.community}
                  </a>
                </div>
                <span className="time">
                  • Posted by <strong>{post.author}</strong> • {post.time}
                </span>
                <button className="more">
                  <MoreHorizontal size={16} />
                </button>
              </div>

              <h3>{post.title}</h3>
              <p className="body">{post.body}</p>

              {post.image && (
                <div className="post-image">
                  <img 
                    src={post.image} 
                    alt={post.title}
                    loading="lazy" 
                    width={800}
                    height={450}
                  />
                </div>
              )}

              <div className="tags">
                {post.tags.map((tag, idx) => (
                  <span key={idx} className="tag">
                    {tag}
                  </span>
                ))}
              </div>

              <div className="post-footer">
                <div className="vote">
                  <button className="up" title="Upvote">
                    <Heart size={14} />
                  </button>
                  <span className="count">{post.votes}</span>
                  <button className="down" title="Downvote">
                    <Heart size={14} className="rotate-180" />
                  </button>
                </div>
                <button className="action">
                  <MessageCircle size={14} /> {post.comments} Comments
                </button>
                <button className="action">
                  <Share2 size={14} /> Share
                </button>
                <button className="bookmark-btn" title="Save post">
                  <Bookmark size={14} />
                </button>
                <button className="more-dots">
                  <MoreHorizontal size={14} />
                </button>
              </div>
            </article>
          ))}
        </main>

        {/* RIGHT SIDEBAR */}
        <aside className="side-right">
          <div className="side-group">
            <div className="side-group-title">People You Know</div>
            <div className="people-list">
              {people.map((person, idx) => (
                <div key={idx} className="person">
                  <div
                    className="person-avatar"
                    style={{ backgroundColor: person.color }}
                  >
                    {person.initials}
                  </div>
                  <div className="person-info">
                    <div className="name">{person.name}</div>
                    <div className="meta">{person.meta}</div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          <div className="side-group">
            <div className="side-group-title">Community Guidelines</div>
            <p style={{ fontSize: "13px", lineHeight: "1.6", color: "#5b6472" }}>
              Be respectful, follow community rules, and engage positively. No spam, harassment, or adult content.
            </p>
          </div>

          <div className="side-group">
            <div className="side-group-title">Hot Communities</div>
            <div className="small-block">
              <p>Join local communities to discover events, jobs, and connect with people near you.</p>
            </div>
          </div>
        </aside>
      </div>
    </section>
  );
}

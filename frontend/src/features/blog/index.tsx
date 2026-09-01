"use client";

import { useState } from "react";
import BlogCard from "./components/BlogCard";
import "./index.css";

const categories = ["All", "City Stories", "Local News", "Travel Tips", "Food & Dining", "Events"];

const blogs = [
  {
    id: 1,
    title: "Top 10 Hidden Gems in Ahmedabad You Must Visit",
    excerpt: "Ahmedabad is full of surprises. From secret rooftop cafes to ancient stepwells, discover the city's best-kept secrets that most tourists miss.",
    author: "Priya Shah",
    date: "Aug 28, 2026",
    readTime: "5 min read",
    category: "Travel Tips",
    image: "https://images.unsplash.com/photo-1599661489829-8e7e25adf8e7?auto=format&fit=crop&w=800&q=80",
    featured: true,
  },
  {
    id: 2,
    title: "Ahmedabad's Street Food Revolution: A Complete Guide",
    excerpt: "From the iconic khaman to the evolving fusion street food scene, here's everything you need to know about Ahmedabad's culinary streets.",
    author: "Ravi Kumar",
    date: "Aug 25, 2026",
    readTime: "7 min read",
    category: "Food & Dining",
    image: "https://images.unsplash.com/photo-1567337710282-00832b415979?auto=format&fit=crop&w=800&q=80",
  },
  {
    id: 3,
    title: "How Ahmedabad's Startup Scene is Changing in 2026",
    excerpt: "GIFT City and SG Highway are becoming hubs for tech startups. We explore the key players and what's driving growth.",
    author: "Neha Patel",
    date: "Aug 22, 2026",
    readTime: "6 min read",
    category: "Local News",
    image: "https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&w=800&q=80",
  },
  {
    id: 4,
    title: "Heritage Walks: Exploring the Old City of Ahmedabad",
    excerpt: "Walk through centuries of history in the walled city. From intricate jharokhas to the stunning Jama Masjid, experience Ahmedabad's soul.",
    author: "Asha Shah",
    date: "Aug 19, 2026",
    readTime: "8 min read",
    category: "City Stories",
    image: "https://images.unsplash.com/photo-1570168007204-dfb528c6958f?auto=format&fit=crop&w=800&q=80",
  },
  {
    id: 5,
    title: "Navratri 2026: Best Garba Events in Ahmedabad",
    excerpt: "The biggest Garba festival is around the corner. Here are the top events, venues, and tips to make the most of Navratri this year.",
    author: "Ravi Kumar",
    date: "Aug 16, 2026",
    readTime: "4 min read",
    category: "Events",
    image: "https://images.unsplash.com/photo-1545128485-c480b3e8e12f?auto=format&fit=crop&w=800&q=80",
  },
  {
    id: 6,
    title: "Best Cafes for Remote Work in Ahmedabad",
    excerpt: "Looking for a productive workspace outside the office? These cafes offer great WiFi, coffee, and the perfect ambience for working remotely.",
    author: "Nisha Patel",
    date: "Aug 12, 2026",
    readTime: "5 min read",
    category: "Food & Dining",
    image: "https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=800&q=80",
  },
];

export default function BlogSection() {
  const [activeCategory, setActiveCategory] = useState("All");

  const filteredBlogs =
    activeCategory === "All"
      ? blogs
      : blogs.filter((blog) => blog.category === activeCategory);

  return (
    <section className="blog-page">
      <div className="blog-container">
        {/* Heading */}
        <div className="blog-heading">
          <p className="blog-eyebrow">Read & Discover</p>
          <h1>
            City <span>Blog</span> & Stories
          </h1>
          <p>
            Explore stories, guides, and updates from the heart of the city.
          </p>
        </div>

        {/* Category Filter */}
        <div className="blog-filters">
          {categories.map((cat) => (
            <button
              key={cat}
              type="button"
              className={`blog-filter-btn${activeCategory === cat ? " is-active" : ""}`}
              onClick={() => setActiveCategory(cat)}
            >
              {cat}
            </button>
          ))}
        </div>

        {/* Blog Grid */}
        <div className="blog-grid">
          {filteredBlogs.map((blog) => (
            <BlogCard key={blog.id} blog={blog} />
          ))}
        </div>

        {filteredBlogs.length === 0 && (
          <div className="blog-empty">
            <p>No articles found in this category.</p>
          </div>
        )}

        {/* Load More */}
        <div className="blog-load-more">
          <button type="button" className="blog-load-more-btn">
            Load More Articles
          </button>
        </div>
      </div>
    </section>
  );
}

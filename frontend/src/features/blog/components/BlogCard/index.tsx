"use client";

import "./index.css";
import Image from "next/image";
import MagicCard from "@/src/components/ui/magic-card";
import { ClockIcon } from "@/src/components/ui/icons";

type Blog = {
  id: number;
  title: string;
  excerpt: string;
  author: string;
  date: string;
  readTime: string;
  category: string;
  image: string;
  featured?: boolean;
};

type BlogCardProps = {
  blog: Blog;
};

export default function BlogCard({ blog }: BlogCardProps) {
  return (
    <MagicCard>
      <article className={`blog-card${blog.featured ? " blog-card--featured" : ""}`}>
        <div className="blog-card__image-wrap">
          <Image
            src={blog.image}
            alt={blog.title}
            width={800}
            height={450}
            className="blog-card__image"
            priority={blog.featured}
            unoptimized
          />
          <div className="blog-card__image-overlay" aria-hidden="true" />
          <span className="blog-card__category">{blog.category}</span>
        </div>

        <div className="blog-card__content">
          <h3 className="blog-card__title">{blog.title}</h3>
          <p className="blog-card__excerpt">{blog.excerpt}</p>

          <div className="blog-card__meta">
            <div className="blog-card__author">
              <span className="blog-card__author-avatar">
                {blog.author.split(" ").map((n) => n[0]).join("")}
              </span>
              <span className="blog-card__author-name">{blog.author}</span>
            </div>

            <span className="blog-card__dot">·</span>
            <span className="blog-card__date">{blog.date}</span>
            <span className="blog-card__dot">·</span>
            <span className="blog-card__read-time">
              <ClockIcon size={12} />
              {blog.readTime}
            </span>
          </div>
        </div>
      </article>
    </MagicCard>
  );
}

"use client";

import "./index.css";
import Image from "next/image";
import { FormEvent, useState } from "react";
import {
  BookmarkIcon,
  HeartIcon,
  MessageIcon,
  MoreIcon,
  Share2Icon,
} from "@/src/components/ui/icons";

type PostCardProps = {
  post: {
    id: number;
    community: string;
    icon: string;
    color: string;
    title: string;
    author: string;
    time: string;
    body: string;
    image?: string;
    tags: string[];
    votes: number;
    comments: number;
    shares: number;
  };
};

export default function PostCard({ post }: PostCardProps) {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isCommentsOpen, setIsCommentsOpen] = useState(false);
  const [commentText, setCommentText] = useState("");
  const [comments, setComments] = useState<string[]>([]);

  const handleCopyLink = async () => {
    const postLink = `${window.location.origin}${window.location.pathname}#post-${post.id}`;

    await navigator.clipboard.writeText(postLink);
    setIsMenuOpen(false);
    alert("Post link copied");
  };

  const handleReport = () => {
    setIsMenuOpen(false);
    alert("Post reported");
  };

  const handleAddComment = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();

    const newComment = commentText.trim();
    if (!newComment) return;

    setComments((currentComments) => [...currentComments, newComment]);
    setCommentText("");
  };

  return (
    <article id={`post-${post.id}`} className="community-post-card">
      <div className="community-post-head">
        <div className="community-post-community" style={{ borderLeftColor: post.color }}>
          <span className="community-post-community-dot" style={{ background: post.color }}>
            {post.icon}
          </span>
          <a href="#" className="community-post-community-name">
            r/{post.community}
          </a>
        </div>

        <span className="community-post-meta">
          • Posted by <strong>{post.author}</strong> • {post.time}
        </span>

        <div className="community-post-menu">
          <button
            type="button"
            className="community-post-more"
            aria-label="More options"
            aria-expanded={isMenuOpen}
            onClick={() => setIsMenuOpen((open) => !open)}
          >
            <MoreIcon size={16} />
          </button>

          {isMenuOpen && (
            <div className="community-post-menu__dropdown">
              <button type="button" onClick={handleCopyLink}>
                Copy link
              </button>
              <button
                type="button"
                className="community-post-menu__report"
                onClick={handleReport}
              >
                Report
              </button>
            </div>
          )}
        </div>
      </div>

      <h3 className="community-post-title">{post.title}</h3>
      <p className="community-post-body">{post.body}</p>

      {post.image ? (
        <div className="community-post-image-wrap">
          <Image
            src={post.image}
            alt={post.title}
            width={1200}
            height={450}
            className="community-post-image"
            priority={false}
            unoptimized
          />
        </div>
      ) : null}

      <div className="community-post-tags">
        {post.tags.map((tag, index) => (
          <span key={`${tag}-${index}`} className="community-post-tag">
            {tag}
          </span>
        ))}
      </div>

      <div className="community-post-footer">
        <div className="community-post-votes" aria-label={`Votes: ${post.votes}`}>
          <button type="button" className="community-vote-button" aria-label="Upvote">
            <HeartIcon size={14} />
          </button>
          <span>{post.votes}</span>
          <button type="button" className="community-vote-button is-down" aria-label="Downvote">
            <HeartIcon size={14} />
          </button>
        </div>

        <button
          type="button"
          className="community-post-action"
          aria-expanded={isCommentsOpen}
          onClick={() => setIsCommentsOpen((open) => !open)}
        >
          <MessageIcon size={14} />
          {post.comments + comments.length} Comments
        </button>

        <button type="button" className="community-post-action">
          <Share2Icon size={14} />
          Share
        </button>

        <button type="button" className="community-post-bookmark" aria-label="Save post">
          <BookmarkIcon size={14} />
        </button>
      </div>

      {isCommentsOpen && (
        <section className="community-comments" aria-label="Comments">
          {comments.length > 0 && (
            <div className="community-comments__list">
              {comments.map((comment, index) => (
                <div key={`${comment}-${index}`} className="community-comments__item">
                  <strong>You</strong>
                  <p>{comment}</p>
                </div>
              ))}
            </div>
          )}

          <form className="community-comments__form" onSubmit={handleAddComment}>
            <input
              type="text"
              value={commentText}
              onChange={(event) => setCommentText(event.target.value)}
              placeholder="Write a comment..."
              aria-label="Write a comment"
            />
            <button type="submit">Comment</button>
          </form>
        </section>
      )}
    </article>
  );
}

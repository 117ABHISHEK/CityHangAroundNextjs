"use client";

import "./index.css";
import Image from "next/image";
import { FormEvent, useState } from "react";
import MagicCard from "@/src/components/ui/magic-card";
import {
  ArrowDownIcon,
  ArrowUpIcon,
  BookmarkIcon,
<<<<<<< HEAD
=======
  ArrowUpIcon,
  ArrowDownIcon,
>>>>>>> origin/main
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
  const [vote, setVote] = useState<"up" | "down" | null>(null);
  const [isSaved, setIsSaved] = useState(false);
  const [isShared, setIsShared] = useState(false);
  const [selectedTags, setSelectedTags] = useState<string[]>([post.tags[0]]);

  const voteCount = post.votes + (vote === "up" ? 1 : vote === "down" ? -1 : 0);

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

  const handleVote = (nextVote: "up" | "down") => {
    setVote((currentVote) => (currentVote === nextVote ? null : nextVote));
  };

  const handleTagToggle = (tag: string) => {
    setSelectedTags((currentTags) =>
      currentTags.includes(tag)
        ? currentTags.filter((currentTag) => currentTag !== tag)
        : [...currentTags, tag],
    );
  };

  const handleShare = async () => {
    const postLink = `${window.location.origin}${window.location.pathname}#post-${post.id}`;

    try {
      if (navigator.share) {
        await navigator.share({ title: post.title, text: post.body, url: postLink });
      } else {
        await navigator.clipboard.writeText(postLink);
        alert("Post link copied");
      }
      setIsShared(true);
      setTimeout(() => setIsShared(false), 1500);
    } catch {
      // Sharing can be cancelled by the user.
    }
  };

  const handleAddComment = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();

    const newComment = commentText.trim();
    if (!newComment) return;

    setComments((currentComments) => [...currentComments, newComment]);
    setCommentText("");
  };

  return (
    <MagicCard>
      <article id={`post-${post.id}`} className="community-post-card">
        <div className="community-post-head">
          <div className="community-post-community">
            <span
              className="community-post-community-icon"
              style={{ background: `linear-gradient(135deg, ${post.color}, ${post.color}dd)` }}
            >
              {post.icon}
            </span>
            <a href="#" className="community-post-community-name">
              r/{post.community}
            </a>
          </div>

          <span className="community-post-meta">
            Posted by <strong>{post.author}</strong> · {post.time}
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

<<<<<<< HEAD
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
          <button
            key={`${tag}-${index}`}
            type="button"
            className={`community-post-tag${selectedTags.includes(tag) ? " is-active" : ""}`}
            aria-pressed={selectedTags.includes(tag)}
            onClick={() => handleTagToggle(tag)}
          >
            {tag}
          </button>
        ))}
      </div>

      <div className="community-post-footer">
        <div className="community-post-votes" aria-label={`Votes: ${voteCount}`}>
          <button
            type="button"
            className={`community-vote-button${vote === "up" ? " is-active" : ""}`}
            aria-label="Upvote"
            aria-pressed={vote === "up"}
            onClick={() => handleVote("up")}
          >
            <ArrowUpIcon size={14} />
          </button>
          <span>{voteCount}</span>
          <button
            type="button"
            className={`community-vote-button is-down${vote === "down" ? " is-active" : ""}`}
            aria-label="Downvote"
            aria-pressed={vote === "down"}
            onClick={() => handleVote("down")}
          >
            <ArrowDownIcon size={14} />
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

        <button type="button" className="community-post-action" onClick={handleShare}>
          <Share2Icon size={14} />
          Share
        </button>

        <button
          type="button"
          className={`community-post-bookmark${isSaved ? " is-active" : ""}`}
          aria-label={isSaved ? "Unsave post" : "Save post"}
          aria-pressed={isSaved}
          onClick={() => setIsSaved((saved) => !saved)}
        >
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
=======
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
>>>>>>> origin/main
            />
          </div>
        ) : null}

        <div className="community-post-tags">
          {post.tags.map((tag, index) => (
            <button
              key={`${tag}-${index}`}
              type="button"
              className={`community-post-tag${selectedTags.includes(tag) ? " is-active" : ""}`}
              aria-pressed={selectedTags.includes(tag)}
              onClick={() => handleTagToggle(tag)}
            >
              {tag}
            </button>
          ))}
        </div>

        <div className="community-post-footer">
          <div className="community-post-votes" aria-label={`Votes: ${voteCount}`}>
            <button
              type="button"
              className={`community-vote-button community-vote-button--up${vote === "up" ? " is-active" : ""}`}
              aria-label="Upvote"
              aria-pressed={vote === "up"}
              onClick={() => handleVote("up")}
            >
              <ArrowUpIcon size={15} />
            </button>
            <span>{voteCount}</span>
            <button
              type="button"
              className={`community-vote-button community-vote-button--down${vote === "down" ? " is-active" : ""}`}
              aria-label="Downvote"
              aria-pressed={vote === "down"}
              onClick={() => handleVote("down")}
            >
              <ArrowDownIcon size={15} />
            </button>
          </div>

          <button
            type="button"
            className={`community-post-action${isCommentsOpen ? " is-active" : ""}`}
            aria-expanded={isCommentsOpen}
            onClick={() => setIsCommentsOpen((open) => !open)}
          >
            <MessageIcon size={14} />
            {post.comments + comments.length} Comments
          </button>

          <button
            type="button"
            className={`community-post-action${isShared ? " is-active" : ""}`}
            onClick={handleShare}
          >
            <Share2Icon size={14} />
            Share
          </button>

          <button
            type="button"
            className={`community-post-bookmark${isSaved ? " is-active" : ""}`}
            aria-label={isSaved ? "Unsave post" : "Save post"}
            aria-pressed={isSaved}
            onClick={() => setIsSaved((saved) => !saved)}
          >
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
    </MagicCard>
  );
}

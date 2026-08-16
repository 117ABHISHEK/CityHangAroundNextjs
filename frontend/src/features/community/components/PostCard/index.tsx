import "./index.css";
import Image from "next/image";
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
  return (
    <article className="community-post-card">
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

        <button type="button" className="community-post-more" aria-label="More options">
          <MoreIcon size={16} />
        </button>
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

        <button type="button" className="community-post-action">
          <MessageIcon size={14} />
          {post.comments} Comments
        </button>

        <button type="button" className="community-post-action">
          <Share2Icon size={14} />
          Share
        </button>

        <button type="button" className="community-post-bookmark" aria-label="Save post">
          <BookmarkIcon size={14} />
        </button>
      </div>
    </article>
  );
}

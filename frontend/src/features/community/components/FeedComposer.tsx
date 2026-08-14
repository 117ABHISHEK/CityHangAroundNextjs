import { ImageIcon, VideoIcon, Share2Icon } from "@/src/components/ui/icons";

export default function FeedComposer() {
  return (
    <div className="community-composer">
      <div className="community-composer__avatar">C</div>

      <div className="community-composer__main">
        <input
          type="text"
          className="community-composer__input"
          placeholder="Create Post"
          aria-label="Create post"
        />

        <div className="community-composer__actions">
          <button type="button" title="Add Photo" aria-label="Add Photo">
            <ImageIcon size={16} />
          </button>
          <button type="button" title="Add Video" aria-label="Add Video">
            <VideoIcon size={16} />
          </button>
          <button type="button" title="Share" aria-label="Share">
            <Share2Icon size={16} />
          </button>
        </div>

        <button type="button" className="community-composer__submit">
          Post
        </button>
      </div>
    </div>
  );
}

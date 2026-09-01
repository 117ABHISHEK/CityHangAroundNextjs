"use client";

import Link from "next/link";
import PostCard from "@/src/features/community/components/PostCard";

type PostDetailViewProps = {
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

export default function PostDetailView({ post }: PostDetailViewProps) {
  return (
    <div style={{ maxWidth: 900, margin: "32px auto", padding: "0 20px" }}>
      <div style={{ marginBottom: 18 }}>
        <Link href="/community" style={{ color: "#ef452f", fontWeight: 700, textDecoration: "none" }}>
          ← Back to community
        </Link>
      </div>

      <PostCard post={post} detailView />

    </div>
  );
}

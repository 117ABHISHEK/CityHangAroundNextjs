import type { Metadata } from "next";
import { notFound } from "next/navigation";
import PostDetailView from "@/src/features/community/components/PostDetailView";
import { communityPosts } from "@/src/features/community/data";

type Props = {
  params: Promise<{ id: string }>;
};

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { id } = await params;
  const post = communityPosts.find((item) => item.id === Number(id));

  if (!post) {
    return {
      title: "Post not found | Community",
    };
  }

  return {
    title: `${post.title} | Community`,
    description: post.body,
    openGraph: {
      title: post.title,
      description: post.body,
      images: post.image ? [post.image] : undefined,
    },
  };
}

export default async function CommunityPostDetailPage({ params }: Props) {
  const { id } = await params;
  const post = communityPosts.find((item) => item.id === Number(id));

  if (!post) {
    notFound();
  }

  return <PostDetailView post={post} />;
}

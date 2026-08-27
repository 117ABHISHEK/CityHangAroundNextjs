import { api } from "./client";

// ── Post types ───────────────────────────────────────────────────────

export interface Post {
  id: number;
  content: string;
  images: string[];
  privacy: "public" | "friends" | "private";
  user: { id: number; name: string; avatar: string | null };
  group_id: number | null;
  reactions_count: number;
  comments_count: number;
  created_at: string;
}

export interface Comment {
  id: number;
  content: string;
  user: { id: number; name: string; avatar: string | null };
  created_at: string;
}

export interface Story {
  id: number;
  content: string;
  image: string | null;
  user: { id: number; name: string; avatar: string | null };
  created_at: string;
}

// ── Posts ────────────────────────────────────────────────────────────

/**
 * Backend route: POST /create_post
 */
export function createPost(payload: FormData): Promise<{ id: number }> {
  return api.post<{ id: number }>("/create_post", payload);
}

/**
 * Backend route: POST /edit_post/{id}
 */
export function updatePost(id: number, payload: FormData): Promise<void> {
  return api.post<void>(`/edit_post/${id}`, payload);
}

/**
 * Backend route: GET /load_post_by_scrolling?offset={offset}&limit={limit}
 */
export function loadPostsByScrolling(
  offset: number,
  limit: number,
): Promise<Post[]> {
  return api.get<Post[]>("/load_post_by_scrolling", {
    params: { offset, limit },
  });
}

/**
 * Backend route: GET /view/single/post/{id}
 */
export function getSinglePost(id: number): Promise<Post> {
  return api.get<Post>(`/view/single/post/${id}`);
}

/**
 * Backend route: GET /delete/my/post?id={id}
 */
export function deletePost(id: number): Promise<void> {
  return api.get<void>("/delete/my/post", { params: { id } });
}

// ── Comments ─────────────────────────────────────────────────────────

/**
 * Backend route: GET /post_comment?post_id={id}&comment={text}
 */
export function addComment(postId: number, comment: string): Promise<void> {
  return api.get<void>("/post_comment", {
    params: { post_id: postId, comment },
  });
}

/**
 * Backend route: GET /load_post_comments?post_id={id}&offset={offset}&limit={limit}
 */
export function loadComments(
  postId: number,
  offset: number,
  limit: number,
): Promise<Comment[]> {
  return api.get<Comment[]>("/load_post_comments", {
    params: { post_id: postId, offset, limit },
  });
}

// ── Reactions ────────────────────────────────────────────────────────

/**
 * Backend route: POST /my_react
 */
export function reactToPost(payload: {
  post_id: number;
  reaction: string;
}): Promise<void> {
  return api.post<void>("/my_react", payload);
}

// ── Share ────────────────────────────────────────────────────────────

/**
 * Backend route: POST /share/on/my/timeline
 */
export function shareToTimeline(postId: number): Promise<void> {
  return api.post<void>("/share/on/my/timeline", { post_id: postId });
}

/**
 * Backend route: POST /share/on/group
 */
export function shareToGroup(payload: {
  post_id: number;
  group_id: number;
}): Promise<void> {
  return api.post<void>("/share/on/group", payload);
}

// ── Stories ──────────────────────────────────────────────────────────

/**
 * Backend route: POST /create_story
 */
export function createStory(payload: FormData): Promise<{ id: number }> {
  return api.post<{ id: number }>("/create_story", payload);
}

/**
 * Backend route: GET /stories/{offset}/{limit}
 */
export function getStories(offset: number, limit: number): Promise<Story[]> {
  return api.get<Story[]>(`/stories/${offset}/${limit}`);
}

/**
 * Backend route: GET /story_details/{story_id}/{offset}/{limit}
 */
export function getStoryDetails(
  storyId: number,
  offset: number,
  limit: number,
): Promise<Story> {
  return api.get<Story>(`/story_details/${storyId}/${offset}/${limit}`);
}

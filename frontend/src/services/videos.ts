import { api } from "./client";

// ── Video type ───────────────────────────────────────────────────────

export interface Video {
  id: number;
  title: string;
  video_url: string;
  description: string | null;
  type: "video" | "short";
  approved: boolean;
  created_at: string;
}

// ── Public ───────────────────────────────────────────────────────────

/**
 * Backend route: GET /videos
 */
export function getAllVideos(): Promise<Video[]> {
  return api.get<Video[]>("/videos");
}

/**
 * Backend route: GET /shorts
 */
export function getAllShorts(): Promise<Video[]> {
  return api.get<Video[]>("/shorts");
}

/**
 * Backend route: GET /load_videos_by_scrolling
 */
export function loadVideosByScrolling(
  offset: number,
  limit: number,
): Promise<Video[]> {
  return api.get<Video[]>("/load_videos_by_scrolling", {
    params: { offset, limit },
  });
}

/**
 * Backend route: GET /load_shorts_by_scrolling
 */
export function loadShortsByScrolling(
  offset: number,
  limit: number,
): Promise<Video[]> {
  return api.get<Video[]>("/load_shorts_by_scrolling", {
    params: { offset, limit },
  });
}

// ── Auth required ────────────────────────────────────────────────────

/**
 * Backend route: POST /videos/sorts/store
 */
export function createVideo(payload: {
  title: string;
  video_url: string;
  description?: string;
  type: "video" | "short";
}): Promise<{ id: number }> {
  return api.post<{ id: number }>("/videos/sorts/store", payload);
}

/**
 * Backend route: GET /video/delete?id={id}
 */
export function deleteVideo(id: number): Promise<void> {
  return api.get<void>("/video/delete", { params: { id } });
}

/**
 * Backend route: GET /save/video/short/{id}
 */
export function saveShort(id: number): Promise<void> {
  return api.get<void>(`/save/video/short/${id}`);
}

/**
 * Backend route: GET /unsave/video/short/{id}
 */
export function unsaveShort(id: number): Promise<void> {
  return api.get<void>(`/unsave/video/short/${id}`);
}

/**
 * Backend route: GET /saved/video/view
 */
export function getSavedVideos(): Promise<Video[]> {
  return api.get<Video[]>("/saved/video/view");
}

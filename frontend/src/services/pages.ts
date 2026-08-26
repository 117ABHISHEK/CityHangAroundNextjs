import { api } from "./client";

// ── Page type ────────────────────────────────────────────────────────

export interface Page {
  id: number;
  page_name: string;
  item_slug: string;
  description: string;
  phone: string | null;
  email: string | null;
  website: string | null;
  address: string | null;
  cover_image: string | null;
  images: string[];
  city_id: number;
  area_id: number | null;
  category_id: number;
  item_status: number;
  view: number;
  created_at: string;
}

// ── Public (no auth) ─────────────────────────────────────────────────

/**
 * Fetch all active pages.
 *
 * Backend: GET /api/public/pages
 */
export function getAllPages(): Promise<Page[]> {
  return api.get<Page[]>("/api/public/pages");
}

/**
 * Fetch pages for a specific city.
 *
 * Backend: GET /api/public/pages/{city_slug}
 */
export function getPagesByCity(citySlug: string): Promise<Page[]> {
  return api.get<Page[]>(`/api/public/pages/${citySlug}`);
}

/**
 * Infinite-scroll loader for pages.
 *
 * Backend: GET /api/public/pages/scroll?offset=0&limit=20
 */
export function loadPagesByScrolling(
  offset: number,
  limit: number,
): Promise<Page[]> {
  return api.get<Page[]>("/api/public/pages/scroll", {
    params: { offset, limit },
  });
}

/**
 * Backend: GET /page/suggestions?q={query} (auth required)
 */
export function getPageSuggestions(query: string): Promise<Page[]> {
  return api.get<Page[]>("/page/suggestions", { params: { q: query } });
}

// ── Auth required ────────────────────────────────────────────────────

/**
 * Backend: POST /page/store (auth required)
 */
export function createPage(payload: FormData): Promise<{ id: number }> {
  return api.post<{ id: number }>("/page/store", payload);
}

/**
 * Backend: POST /update/page/{id} (auth required)
 */
export function updatePage(id: number, payload: FormData): Promise<void> {
  return api.post<void>(`/update/page/${id}`, payload);
}

/**
 * Backend: GET /pages/delete?id={id} (auth required)
 */
export function deletePage(id: number): Promise<void> {
  return api.get<void>("/pages/delete", { params: { id } });
}

/**
 * Backend: POST /claim-listing/submit (auth required)
 */
export function claimListing(payload: {
  page_id: number;
  name: string;
  email: string;
  phone: string;
}): Promise<void> {
  return api.post<void>("/claim-listing/submit", payload);
}

// ── Page media ───────────────────────────────────────────────────────

/**
 * Backend: GET /page/load_videos?page_id={id} (auth required)
 */
export function loadPageVideos(
  pageId: number,
): Promise<{ id: number; title: string; video_url: string }[]> {
  return api.get("/page/load_videos", { params: { page_id: pageId } });
}

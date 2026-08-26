import { api } from "./client";

// ── Search result types ──────────────────────────────────────────────

export interface SearchResult {
  type: "page" | "event" | "product" | "blog" | "group" | "video" | "post" | "user";
  id: number;
  title: string;
  slug: string;
  image: string | null;
  city: string | null;
}

// ── Global search (public) ───────────────────────────────────────────

/**
 * Global search across all content types.
 *
 * Backend: GET /search-globally?q={query} (public, web route)
 */
export function searchGlobally(query: string): Promise<SearchResult[]> {
  return api.get<SearchResult[]>("/search-globally", {
    params: { q: query },
  });
}

// ── Specific entity search (auth required) ───────────────────────────

/**
 * Backend: GET /search/people/?q={query} (auth required)
 */
export function searchPeople(query: string): Promise<unknown[]> {
  return api.get<unknown[]>("/search/people/", { params: { q: query } });
}

/**
 * Backend: GET /search/post/?q={query} (auth required)
 */
export function searchPosts(query: string): Promise<unknown[]> {
  return api.get<unknown[]>("/search/post/", { params: { q: query } });
}

/**
 * Backend: GET /search/video/?q={query} (auth required)
 */
export function searchVideos(query: string): Promise<unknown[]> {
  return api.get<unknown[]>("/search/video/", { params: { q: query } });
}

/**
 * Backend: GET /search/product/?q={query} (auth required)
 */
export function searchProducts(query: string): Promise<unknown[]> {
  return api.get<unknown[]>("/search/product/", { params: { q: query } });
}

/**
 * Backend: GET /search/page/?q={query} (auth required)
 */
export function searchPages(query: string): Promise<unknown[]> {
  return api.get<unknown[]>("/search/page/", { params: { q: query } });
}

/**
 * Backend: GET /search/group/?q={query} (auth required)
 */
export function searchGroups(query: string): Promise<unknown[]> {
  return api.get<unknown[]>("/search/group/", { params: { q: query } });
}

/**
 * Backend: GET /search/event/?q={query} (auth required)
 */
export function searchEvents(query: string): Promise<unknown[]> {
  return api.get<unknown[]>("/search/event/", { params: { q: query } });
}

import { api } from "./client";

// ── Blog type ────────────────────────────────────────────────────────

export interface Blog {
  id: number;
  title: string;
  blog_slug: string;
  content: string;
  featured_image: string | null;
  category_id: number;
  tags: string[];
  created_at: string;
}

// ── Public (no auth) ─────────────────────────────────────────────────

/**
 * Fetch all blogs.
 *
 * Backend: GET /api/public/blogs
 */
export function getAllBlogs(): Promise<Blog[]> {
  return api.get<Blog[]>("/api/public/blogs");
}

/**
 * Fetch blogs for a specific city.
 *
 * Backend: GET /api/public/blogs/{city_slug}
 */
export function getBlogsByCity(citySlug: string): Promise<Blog[]> {
  return api.get<Blog[]>(`/api/public/blogs/${citySlug}`);
}

/**
 * Infinite-scroll loader for blogs.
 *
 * Backend: GET /api/public/blogs/scroll?offset=0&limit=20
 */
export function loadBlogsByScrolling(
  offset: number,
  limit: number,
): Promise<Blog[]> {
  return api.get<Blog[]>("/api/public/blogs/scroll", {
    params: { offset, limit },
  });
}

// ── Auth required ────────────────────────────────────────────────────

/**
 * Backend: POST /blog/store (auth required)
 */
export function createBlog(payload: FormData): Promise<{ id: number }> {
  return api.post<{ id: number }>("/blog/store", payload);
}

/**
 * Backend: POST /update/blog/{id} (auth required)
 */
export function updateBlog(id: number, payload: FormData): Promise<void> {
  return api.post<void>(`/update/blog/${id}`, payload);
}

/**
 * Backend: GET /blog/delete?id={id} (auth required)
 */
export function deleteBlog(id: number): Promise<void> {
  return api.get<void>("/blog/delete", { params: { id } });
}

/**
 * Backend: GET /blog/search/?q={query} (auth required)
 */
export function searchBlogs(query: string): Promise<Blog[]> {
  return api.get<Blog[]>("/blog/search/", { params: { q: query } });
}

// ── Blog Categories ──────────────────────────────────────────────────

/**
 * Backend: GET /api/public/blog-categories
 */
export function getBlogCategories(): Promise<
  { id: number; category_name: string; parent?: string }[]
> {
  return api.get("/api/public/blog-categories");
}

/**
 * Backend: GET /api/public/blog-categories/parent
 */
export function getBlogParentCategories(): Promise<
  { id: number; category_name: string }[]
> {
  return api.get("/api/public/blog-categories/parent");
}

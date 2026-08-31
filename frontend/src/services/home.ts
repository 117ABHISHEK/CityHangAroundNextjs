import { api } from "./client";

// ── Category ────────────────────────────────────────────────────────

/**
 * Category shape used across home, pages, events, marketplace, etc.
 */
export interface Category {
  id: number;
  category_name: string;
  category_parent_id: number | null;
  parent?: string;
}

// ── Cities ───────────────────────────────────────────────────────────

export const DEFAULT_CITIES = [
  { id: 1, city_name: "Ahmedabad", city_slug: "ahmedabad", city_image: null },
  { id: 2, city_name: "Gandhinagar", city_slug: "gandhinagar", city_image: null },
  { id: 3, city_name: "Surat", city_slug: "surat", city_image: null },
  { id: 4, city_name: "Vadodara", city_slug: "vadodara", city_image: null },
  { id: 5, city_name: "Rajkot", city_slug: "rajkot", city_image: null },
  { id: 6, city_name: "Mumbai", city_slug: "mumbai", city_image: null },
];

/**
 * Fetch all active cities (those with approved pages).
 *
 * Backend: GET /api/public/cities
 */
export async function getCities(): Promise<
  { id: number; city_name: string; city_slug: string; city_image: string | null }[]
> {
  try {
    const res = await api.get<{ id: number; city_name: string; city_slug: string; city_image: string | null }[]>("/api/public/cities");
    if (Array.isArray(res) && res.length > 0) {
      return res;
    }
    return DEFAULT_CITIES;
  } catch {
    return DEFAULT_CITIES;
  }
}

/**
 * Search cities by filter string (for the city picker search box).
 *
 * Backend: POST /api/public/cities/search
 */
export function searchCities(filter: string): Promise<
  { id: number; city_name: string; city_slug: string }[]
> {
  return api.post("/api/public/cities/search", { filter });
}

/**
 * Fetch cities belonging to a specific state.
 *
 * Backend: GET /api/public/cities/{state_id}
 */
export function getCitiesByState(stateId: number): Promise<
  { id: number; city_name: string; city_slug: string }[]
> {
  return api.get(`/api/public/cities/${stateId}`);
}

// ── Areas ────────────────────────────────────────────────────────────

/**
 * Fetch areas for a given city.
 *
 * Backend: GET /api/public/areas/{city_id}
 */
export function getAreasByCity(cityId: number): Promise<
  { id: number; area_name: string; city_id: number }[]
> {
  return api.get(`/api/public/areas/${cityId}`);
}

// ── Page Categories ──────────────────────────────────────────────────

/**
 * Fetch all page categories with parent info.
 *
 * Backend: GET /api/public/categories
 */
export function getCategories(): Promise<Category[]> {
  return api.get<Category[]>("/api/public/categories");
}

/**
 * Fetch parent page categories only.
 *
 * Backend: GET /api/public/categories/parent
 */
export function getParentCategories(): Promise<Category[]> {
  return api.get<Category[]>("/api/public/categories/parent");
}

/**
 * Fetch page categories filtered by city.
 *
 * Backend: GET /api/public/categories/{city_id}
 */
export function getCategoriesByCity(cityId: number): Promise<Category[]> {
  return api.get<Category[]>(`/api/public/categories/${cityId}`);
}

/**
 * Fetch subcategories for a given parent category.
 *
 * Backend: GET /api/public/subcategories/{category_id}
 */
export function getSubcategories(categoryId: number): Promise<Category[]> {
  return api.get<Category[]>(`/api/public/subcategories/${categoryId}`);
}

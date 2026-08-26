/**
 * Shared API types used across feature service modules.
 *
 * These mirror the JSON shapes returned by the Laravel backend.
 */

// ── Pagination ───────────────────────────────────────────────────────

export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
}

// ── Location ─────────────────────────────────────────────────────────

export interface City {
  id: number;
  city_name: string;
  city_slug: string;
  city_image: string | null;
  city_state: string | null;
  state_id: number | null;
  city_about: string | null;
  city_lat: string | null;
  city_lng: string | null;
}

export interface Area {
  id: number;
  area_name: string;
  city_id: number;
}

export interface State {
  id: number;
  state_name: string;
  state_abbr: string;
}

export interface Country {
  id: number;
  country_name: string;
}

// ── User ─────────────────────────────────────────────────────────────

export interface User {
  id: number;
  name: string;
  email: string;
  avatar: string | null;
  cover_photo: string | null;
  bio: string | null;
  phone: string | null;
  gender: string | null;
  dob: string | null;
}

// ── Auth ─────────────────────────────────────────────────────────────

export interface LoginPayload {
  email: string;
  password: string;
  remember?: boolean;
}

export interface RegisterPayload {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export interface AuthResponse {
  user: User;
  token: string;
}

// ── Health ───────────────────────────────────────────────────────────

export interface HealthResponse {
  status: string;
  timestamp: string;
  message: string;
}

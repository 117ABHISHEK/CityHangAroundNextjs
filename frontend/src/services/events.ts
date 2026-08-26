import { api } from "./client";

// ── Event type ───────────────────────────────────────────────────────

export interface Event {
  id: number;
  event_name: string;
  event_slug: string;
  short_description: string;
  event_date: string;
  event_time: string;
  event_status: "upcoming" | "live" | "completed" | "cancelled";
  venue: string;
  address: string;
  city_id: number;
  area_id: number | null;
  category_id: number;
  cover_image: string | null;
  view: number;
  created_at: string;
}

export interface EventCategory {
  id: number;
  category_name: string;
  parent?: string;
}

// ── Public (no auth) ─────────────────────────────────────────────────

/**
 * Fetch all upcoming public events.
 *
 * Backend: GET /api/public/events
 */
export function getAllEvents(): Promise<Event[]> {
  return api.get<Event[]>("/api/public/events");
}

/**
 * Fetch events for a specific city (by slug).
 *
 * Backend: GET /api/public/events/{city_slug}
 */
export function getEventsByCity(citySlug: string): Promise<Event[]> {
  return api.get<Event[]>(`/api/public/events/${citySlug}`);
}

/**
 * Fetch events by category slug.
 *
 * Backend: GET /api/public/events/category/{category_slug}
 */
export function getEventsByCategory(categorySlug: string): Promise<Event[]> {
  return api.get<Event[]>(`/api/public/events/category/${categorySlug}`);
}

/**
 * Fetch events by category in a specific city.
 *
 * Backend: GET /api/public/events/{category_slug}-in-{city_slug}
 */
export function getEventsByCategoryInCity(
  categorySlug: string,
  citySlug: string,
): Promise<Event[]> {
  return api.get<Event[]>(
    `/api/public/events/${categorySlug}-in-${citySlug}`,
  );
}

/**
 * Infinite-scroll loader for events.
 *
 * Backend: GET /api/public/events/scroll?offset=0&limit=20
 */
export function loadEventsByScrolling(
  offset: number,
  limit: number,
): Promise<Event[]> {
  return api.get<Event[]>("/api/public/events/scroll", {
    params: { offset, limit },
  });
}

// ── Auth required (Laravel Blade routes) ─────────────────────────────

/**
 * Create a new event.
 *
 * Backend: POST /event/store (auth required)
 */
export function createEvent(payload: FormData): Promise<{ id: number }> {
  return api.post<{ id: number }>("/event/store", payload);
}

/**
 * Update an existing event.
 *
 * Backend: POST /event/update/{id} (auth required)
 */
export function updateEvent(id: number, payload: FormData): Promise<void> {
  return api.post<void>(`/event/update/${id}`, payload);
}

/**
 * Delete an event.
 *
 * Backend: GET /event/delete?id={id} (auth required)
 */
export function deleteEvent(id: number): Promise<void> {
  return api.get<void>("/event/delete", { params: { id } });
}

// ── RSVP (auth required) ────────────────────────────────────────────

export function markGoing(eventId: number): Promise<void> {
  return api.get<void>(`/event/going/${eventId}`);
}

export function markNotGoing(eventId: number): Promise<void> {
  return api.get<void>(`/event/notgoing/${eventId}`);
}

export function markInterested(eventId: number): Promise<void> {
  return api.get<void>(`/event/interested/${eventId}`);
}

export function markNotInterested(eventId: number): Promise<void> {
  return api.get<void>(`/event/notinterested/${eventId}`);
}

// ── Event Categories ─────────────────────────────────────────────────

/**
 * Fetch all event categories (public).
 *
 * Backend: GET /api/public/event-categories
 */
export function getEventCategories(): Promise<EventCategory[]> {
  return api.get<EventCategory[]>("/api/public/event-categories");
}

/**
 * Fetch parent event categories (public).
 *
 * Backend: GET /api/public/event-categories/parent
 */
export function getEventParentCategories(): Promise<EventCategory[]> {
  return api.get<EventCategory[]>("/api/public/event-categories/parent");
}

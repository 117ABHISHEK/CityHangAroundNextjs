import { api } from "./client";

// ── Group type ───────────────────────────────────────────────────────

export interface Group {
  id: number;
  group_name: string;
  group_slug: string;
  description: string;
  cover_image: string | null;
  privacy: "public" | "private";
  category_id: number;
  city_id: number;
  created_at: string;
}

// ── Public (no auth) ─────────────────────────────────────────────────

/**
 * Fetch all public groups.
 *
 * Backend: GET /api/public/groups
 */
export function getAllGroups(): Promise<Group[]> {
  return api.get<Group[]>("/api/public/groups");
}

/**
 * Fetch groups for a specific city.
 *
 * Backend: GET /api/public/groups/{city_slug}
 */
export function getGroupsByCity(citySlug: string): Promise<Group[]> {
  return api.get<Group[]>(`/api/public/groups/${citySlug}`);
}

/**
 * Infinite-scroll loader for groups.
 *
 * Backend: GET /api/public/groups/scroll?offset=0&limit=20
 */
export function loadGroupsByScrolling(
  offset: number,
  limit: number,
): Promise<Group[]> {
  return api.get<Group[]>("/api/public/groups/scroll", {
    params: { offset, limit },
  });
}

// ── Auth required ────────────────────────────────────────────────────

/**
 * Backend: POST /group/store (auth required)
 */
export function createGroup(payload: FormData): Promise<{ id: number }> {
  return api.post<{ id: number }>("/group/store", payload);
}

/**
 * Backend: POST /update/group/{id} (auth required)
 */
export function updateGroup(id: number, payload: FormData): Promise<void> {
  return api.post<void>(`/update/group/${id}`, payload);
}

/**
 * Backend: GET /group/join/{id} (auth required)
 */
export function joinGroup(groupId: number): Promise<void> {
  return api.get<void>(`/group/join/${groupId}`);
}

/**
 * Backend: GET /group/rjoin/{id} (auth required — request to join private group)
 */
export function requestJoinGroup(groupId: number): Promise<void> {
  return api.get<void>(`/group/rjoin/${groupId}`);
}

/**
 * Backend: POST /group/invites/sent (auth required)
 */
export function sendGroupInvites(payload: {
  group_id: number;
  friend_ids: number[];
}): Promise<void> {
  return api.post<void>("/group/invites/sent", payload);
}

/**
 * Backend: POST /report/group (auth required)
 */
export function reportGroup(payload: {
  group_id: number;
  reason: string;
}): Promise<void> {
  return api.post<void>("/report/group", payload);
}

// ── Group Categories ─────────────────────────────────────────────────

/**
 * Backend: GET /api/public/group-categories
 */
export function getGroupCategories(): Promise<
  { id: number; category_name: string; parent?: string }[]
> {
  return api.get("/api/public/group-categories");
}

/**
 * Backend: GET /api/public/group-categories/parent
 */
export function getGroupParentCategories(): Promise<
  { id: number; category_name: string }[]
> {
  return api.get("/api/public/group-categories/parent");
}

import { api } from "./client";

// ── Notification type ────────────────────────────────────────────────

export interface Notification {
  id: number;
  type: string;
  message: string;
  is_read: boolean;
  created_at: string;
  data?: {
    user_id?: number;
    group_id?: number;
    event_id?: number;
    [key: string]: unknown;
  };
}

// ── List ─────────────────────────────────────────────────────────────

/**
 * Backend route: GET /all/notification
 */
export function getNotifications(): Promise<Notification[]> {
  return api.get<Notification[]>("/all/notification");
}

/**
 * Backend route: GET /mark/as/read/notification/{id}
 */
export function markAsRead(id: number): Promise<void> {
  return api.get<void>(`/mark/as/read/notification/${id}`);
}

// ── Friend requests ──────────────────────────────────────────────────

/**
 * Backend route: GET /accept/friend/request/notification/{id}
 */
export function acceptFriendRequest(id: number): Promise<void> {
  return api.get<void>(`/accept/friend/request/notification/${id}`);
}

/**
 * Backend route: GET /decline/friend/request/notification/{id}
 */
export function declineFriendRequest(id: number): Promise<void> {
  return api.get<void>(`/decline/friend/request/notification/${id}`);
}

// ── Group requests ───────────────────────────────────────────────────

/**
 * Backend route: GET /accept/group/request/notification/{id}/{group_id}
 */
export function acceptGroupRequest(
  id: number,
  groupId: number,
): Promise<void> {
  return api.get<void>(
    `/accept/group/request/notification/${id}/${groupId}`,
  );
}

/**
 * Backend route: GET /decline/group/request/notification/{id}/{group_id}
 */
export function declineGroupRequest(
  id: number,
  groupId: number,
): Promise<void> {
  return api.get<void>(
    `/decline/group/request/notification/${id}/${groupId}`,
  );
}

// ── Event requests ───────────────────────────────────────────────────

/**
 * Backend route: GET /accept/event/request/notification/{id}/{event_id}
 */
export function acceptEventRequest(
  id: number,
  eventId: number,
): Promise<void> {
  return api.get<void>(
    `/accept/event/request/notification/${id}/${eventId}`,
  );
}

/**
 * Backend route: GET /decline/event/request/notification/{id}/{event_id}
 */
export function declineEventRequest(
  id: number,
  eventId: number,
): Promise<void> {
  return api.get<void>(
    `/decline/event/request/notification/${id}/${eventId}`,
  );
}

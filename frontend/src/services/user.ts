import { api } from "./client";
import type { User } from "./types";

// ── Profile ──────────────────────────────────────────────────────────

/**
 * Backend route: GET /profile
 */
export function getProfile(): Promise<User> {
  return api.get<User>("/profile");
}

/**
 * Backend route: GET /user/view-profile/{id}
 */
export function getViewProfile(userId: number): Promise<User> {
  return api.get<User>(`/user/view-profile/${userId}`);
}

/**
 * Backend route: POST /profile/update_profile/
 */
export function updateProfile(payload: FormData): Promise<void> {
  return api.post<void>("/profile/update_profile/", payload);
}

/**
 * Backend route: POST /profile/upload_photo/{photo_type}
 */
export function uploadPhoto(
  photoType: "avatar" | "cover",
  file: File,
): Promise<{ url: string }> {
  const fd = new FormData();
  fd.append("photo", file);
  return api.post<{ url: string }>(`/profile/upload_photo/${photoType}`, fd);
}

/**
 * Backend route: POST /profile/about/{action_type?}
 */
export function updateAbout(
  payload: Record<string, unknown>,
  actionType?: string,
): Promise<void> {
  const path = actionType
    ? `/profile/about/${actionType}`
    : "/profile/about";
  return api.post<void>(path, payload);
}

// ── Friends ──────────────────────────────────────────────────────────

/**
 * Backend route: GET /profile/friends
 */
export function getFriends(): Promise<User[]> {
  return api.get<User[]>("/profile/friends");
}

/**
 * Backend route: GET /user/friend/{id}
 */
export function addFriend(userId: number): Promise<void> {
  return api.get<void>(`/user/friend/${userId}`);
}

/**
 * Backend route: GET /user/unfriend/{id}
 */
export function unfriend(userId: number): Promise<void> {
  return api.get<void>(`/user/unfriend/${userId}`);
}

// ── Follow ───────────────────────────────────────────────────────────

/**
 * Backend route: GET /user/account/follow/{id}
 */
export function followUser(userId: number): Promise<void> {
  return api.get<void>(`/user/account/follow/${userId}`);
}

/**
 * Backend route: GET /user/account/unfollow/{id}
 */
export function unfollowUser(userId: number): Promise<void> {
  return api.get<void>(`/user/account/unfollow/${userId}`);
}

// ── Password ─────────────────────────────────────────────────────────

/**
 * Backend route: POST /user/password/update
 */
export function changePassword(payload: {
  current_password: string;
  password: string;
  password_confirmation: string;
}): Promise<void> {
  return api.post<void>("/user/password/update", payload);
}

// ── Dashboard ────────────────────────────────────────────────────────

/**
 * Backend route: GET /user/dashboard/view
 */
export function getDashboard(): Promise<unknown> {
  return api.get<unknown>("/user/dashboard/view");
}

// ── Ads ──────────────────────────────────────────────────────────────

/**
 * Backend route: GET /user/view/ads
 */
export function getUserAds(): Promise<unknown[]> {
  return api.get<unknown[]>("/user/view/ads");
}

/**
 * Backend route: POST /user/ad/store
 */
export function createAd(payload: FormData): Promise<{ id: number }> {
  return api.post<{ id: number }>("/user/ad/store", payload);
}

// ── Wallet ───────────────────────────────────────────────────────────

/**
 * Backend route: GET /user/wallet/view
 */
export function getWalletBalance(): Promise<{ balance: number }> {
  return api.get<{ balance: number }>("/user/wallet/view");
}

/**
 * Backend route: POST /user/wallet/add
 */
export function addWalletMoney(amount: number): Promise<void> {
  return api.post<void>("/user/wallet/add", { amount });
}

// ── Subscriptions ────────────────────────────────────────────────────

/**
 * Backend route: GET /user/subscriptions/view
 */
export function getSubscriptionPlans(): Promise<unknown[]> {
  return api.get<unknown[]>("/user/subscriptions/view");
}

/**
 * Backend route: POST /user/subscribe
 */
export function subscribe(payload: {
  plan_id: number;
  payment_method: "razorpay" | "wallet" | "free";
}): Promise<void> {
  return api.post<void>("/user/subscribe", payload);
}

// ── Reviews ──────────────────────────────────────────────────────────

/**
 * Backend route: POST /user/pages/reviews
 */
export function reviewPage(payload: {
  reviewable_id: number;
  rating: number;
  comment: string;
}): Promise<void> {
  return api.post<void>("/user/pages/reviews", payload);
}

/**
 * Backend route: POST /user/marketplace/reviews
 */
export function reviewProduct(payload: {
  reviewable_id: number;
  rating: number;
  comment: string;
}): Promise<void> {
  return api.post<void>("/user/marketplace/reviews", payload);
}

// ── Leads ────────────────────────────────────────────────────────────

/**
 * Backend route: GET /user/leads/view
 */
export function getLeads(): Promise<unknown[]> {
  return api.get<unknown[]>("/user/leads/view");
}

/**
 * Backend route: POST /user/leads/buy/{id}
 */
export function buyLead(leadId: number): Promise<void> {
  return api.post<void>(`/user/leads/buy/${leadId}`);
}

// ── Tickets ──────────────────────────────────────────────────────────

/**
 * Backend route: POST /user/support/tickets
 */
export function createTicket(payload: {
  subject: string;
  message: string;
  priority?: "low" | "medium" | "high";
}): Promise<{ id: number }> {
  return api.post<{ id: number }>("/user/support/tickets", payload);
}

/**
 * Backend route: GET /user/support/tickets-view/
 */
export function getTickets(): Promise<unknown[]> {
  return api.get<unknown[]>("/user/support/tickets-view/");
}

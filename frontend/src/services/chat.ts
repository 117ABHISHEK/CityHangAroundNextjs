import { api } from "./client";

// ── Chat types ───────────────────────────────────────────────────────

export interface ChatMessage {
  id: number;
  sender_id: number;
  receiver_id: number;
  message: string;
  product_id: number | null;
  is_read: boolean;
  created_at: string;
}

export interface Conversation {
  id: number;
  user: { id: number; name: string; avatar: string | null };
  last_message: string;
  unread_count: number;
  updated_at: string;
}

// ── User Chat ────────────────────────────────────────────────────────

/**
 * Backend route: POST /chat/save
 */
export function sendMessage(payload: {
  receiver_id: number;
  message: string;
  product_id?: number;
}): Promise<ChatMessage> {
  return api.post<ChatMessage>("/chat/save", payload);
}

/**
 * Backend route: GET /chat/inbox/load/data/ajax/
 */
export function loadChatData(): Promise<Conversation[]> {
  return api.get<Conversation[]>("/chat/inbox/load/data/ajax/");
}

/**
 * Backend route: GET /chat/inbox/read/message/ajax/
 */
export function markMessagesRead(): Promise<void> {
  return api.get<void>("/chat/inbox/read/message/ajax/");
}

/**
 * Backend route: GET /chat/own/remove/{id}
 */
export function deleteMessage(id: number): Promise<void> {
  return api.get<void>(`/chat/own/remove/${id}`);
}

/**
 * Backend route: GET /chat/profile/search/?q={query}
 */
export function searchChatProfiles(
  query: string,
): Promise<{ id: number; name: string; avatar: string | null }[]> {
  return api.get("/chat/profile/search/", { params: { q: query } });
}

/**
 * Backend route: POST /my_message_react
 */
export function reactToMessage(payload: {
  message_id: number;
  reaction: string;
}): Promise<void> {
  return api.post<void>("/my_message_react", payload);
}

// ── Chat with support ────────────────────────────────────────────────

/**
 * Backend route: POST /chat/with-us
 */
export function chatWithSupport(payload: {
  name: string;
  email: string;
  message: string;
}): Promise<void> {
  return api.post<void>("/chat/with-us", payload);
}

// ── Page Chat ────────────────────────────────────────────────────────

/**
 * Backend route: POST /chat/send
 */
export function sendPageMessage(payload: {
  page_id: number;
  message: string;
}): Promise<ChatMessage> {
  return api.post<ChatMessage>("/chat/send", payload);
}

/**
 * Backend route: GET /chat/fetch/{id}
 */
export function fetchPageChatMessages(
  conversationId: number,
): Promise<ChatMessage[]> {
  return api.get<ChatMessage[]>(`/chat/fetch/${conversationId}`);
}

// ── Marketplace Chat ─────────────────────────────────────────────────

/**
 * Backend route: POST /chat/marketplace/send
 */
export function sendMarketplaceMessage(payload: {
  marketplace_id: number;
  message: string;
}): Promise<ChatMessage> {
  return api.post<ChatMessage>("/chat/marketplace/send", payload);
}

/**
 * Backend route: GET /chat/marketplace/fetch/{id}
 */
export function fetchMarketplaceMessages(
  conversationId: number,
): Promise<ChatMessage[]> {
  return api.get<ChatMessage[]>(
    `/chat/marketplace/fetch/${conversationId}`,
  );
}

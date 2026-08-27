import { api } from "./client";
import type {
  AuthResponse,
  LoginPayload,
  RegisterPayload,
  User,
} from "./types";

/**
 * Register a new user.
 *
 * Backend route: POST /register
 */
export function register(payload: RegisterPayload): Promise<AuthResponse> {
  return api.post<AuthResponse>("/register", payload);
}

/**
 * Log in an existing user.
 *
 * Backend route: POST /login
 */
export function login(payload: LoginPayload): Promise<AuthResponse> {
  return api.post<AuthResponse>("/login", payload);
}

/**
 * Log out the current user and revoke the token.
 *
 * Backend route: POST /logout
 */
export function logout(): Promise<void> {
  return api.post<void>("/logout");
}

/**
 * Request a password-reset email.
 *
 * Backend route: POST /forgot-password
 */
export function forgotPassword(email: string): Promise<{ message: string }> {
  return api.post<{ message: string }>("/forgot-password", { email });
}

/**
 * Reset password with a valid token.
 *
 * Backend route: POST /reset-password
 */
export function resetPassword(payload: {
  token: string;
  email: string;
  password: string;
  password_confirmation: string;
}): Promise<{ message: string }> {
  return api.post<{ message: string }>("/reset-password", payload);
}

// ── Social Login ─────────────────────────────────────────────────────

/** Redirect URLs — call these via `window.location.href` */
export const GOOGLE_AUTH_URL = `${process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000"}/auth/google`;
export const FACEBOOK_AUTH_URL = `${process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000"}/auth/facebook`;

// ── Helpers ──────────────────────────────────────────────────────────

/** Store the auth token in localStorage after login/register. */
export function setAuthToken(token: string): void {
  localStorage.setItem("auth_token", token);
}

/** Remove the stored auth token. */
export function clearAuthToken(): void {
  localStorage.removeItem("auth_token");
}

/** Check whether a token is stored (client-side only). */
export function isAuthenticated(): boolean {
  return typeof window !== "undefined" && !!localStorage.getItem("auth_token");
}

const API_BASE_URL =
  process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000";

interface RequestOptions extends Omit<RequestInit, "method" | "body"> {
  params?: Record<string, string | number | boolean | undefined>;
}

/**
 * Core HTTP client used by all feature API modules.
 *
 * - Prepends the Laravel backend base URL.
 * - Attaches the Sanctum bearer token when present in localStorage.
 * - Serialises `params` as query-string entries.
 * - Returns parsed JSON or throws a descriptive Error.
 */
async function request<T>(
  path: string,
  method: string,
  body?: unknown,
  options: RequestOptions = {},
): Promise<T> {
  const { params, headers: extraHeaders, ...rest } = options;

  // Build URL with optional query params
  let url = `${API_BASE_URL}${path}`;
  if (params) {
    const qs = new URLSearchParams();
    for (const [key, value] of Object.entries(params)) {
      if (value !== undefined && value !== null) {
        qs.append(key, String(value));
      }
    }
    const qsStr = qs.toString();
    if (qsStr) url += `?${qsStr}`;
  }

  // Auth token from localStorage (Laravel Sanctum)
  const token =
    typeof window !== "undefined" ? localStorage.getItem("auth_token") : null;

  const headers: Record<string, string> = {
    Accept: "application/json",
    ...(extraHeaders as Record<string, string>),
  };

  if (token) {
    headers["Authorization"] = `Bearer ${token}`;
  }

  const init: RequestInit = { method, headers, ...rest };

  if (body !== undefined) {
    if (body instanceof FormData) {
      // Let the browser set the multipart boundary
      delete headers["Content-Type"];
      init.body = body;
    } else {
      headers["Content-Type"] = "application/json";
      init.body = JSON.stringify(body);
    }
  }

  const response = await fetch(url, init);

  if (!response.ok) {
    const text = await response.text().catch(() => "");
    throw new Error(
      `API ${method} ${path} failed (${response.status}): ${text || response.statusText}`,
    );
  }

  // Handle 204 No Content
  if (response.status === 204) return undefined as T;

  return response.json() as Promise<T>;
}

// ── Convenience helpers ──────────────────────────────────────────────

export const api = {
  get: <T>(path: string, opts?: RequestOptions) =>
    request<T>(path, "GET", undefined, opts),

  post: <T>(path: string, body?: unknown, opts?: RequestOptions) =>
    request<T>(path, "POST", body, opts),

  put: <T>(path: string, body?: unknown, opts?: RequestOptions) =>
    request<T>(path, "PUT", body, opts),

  patch: <T>(path: string, body?: unknown, opts?: RequestOptions) =>
    request<T>(path, "PATCH", body, opts),

  delete: <T>(path: string, opts?: RequestOptions) =>
    request<T>(path, "DELETE", undefined, opts),
};

export { API_BASE_URL };

// ── Health check ─────────────────────────────────────────────────────

interface HealthResponse {
  status: string;
  timestamp: string;
  message: string;
}

/**
 * Check whether the Laravel backend is reachable.
 *
 * Backend route: GET /api/health
 */
export async function checkHealth(): Promise<HealthResponse> {
  const response = await fetch(`${API_BASE_URL}/api/health`);

  if (!response.ok) {
    throw new Error(`Health check failed with status ${response.status}`);
  }

  return response.json();
}

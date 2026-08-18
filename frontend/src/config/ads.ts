/**
 * Google Ads configuration for the application.
 */

// Google Ads Client Publisher ID (e.g. ca-pub-xxxxxxxxxxxxxxxx)
// Reads from environment variables, falls back to a dummy placeholder
export const GOOGLE_ADS_CLIENT_ID = 
  process.env.NEXT_PUBLIC_GOOGLE_ADS_CLIENT_ID || "ca-pub-1234567890123456";

// Routes where Google Ads should not be shown
export const BLOCKED_ROUTES = [
  "/",         // Home page
  "/profile",  // Profile page prefix
  "/user",     // User profile prefix
];

/**
 * Checks if a given pathname should have ads disabled.
 * Handles exact matches as well as route prefixes (e.g. /profile/settings).
 */
export function isRouteBlocked(pathname: string | null): boolean {
  if (!pathname) return false;

  // Normalise the pathname (remove trailing slash except for root)
  const normalizedPath = pathname.length > 1 && pathname.endsWith("/") 
    ? pathname.slice(0, -1) 
    : pathname;

  return BLOCKED_ROUTES.some((blockedRoute) => {
    if (blockedRoute === "/") {
      return normalizedPath === "/";
    }
    // Block the route and any sub-routes (e.g. /profile blocks /profile/edit)
    return normalizedPath === blockedRoute || normalizedPath.startsWith(blockedRoute + "/");
  });
}

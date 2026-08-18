"use client";

import Script from "next/script";
import { usePathname } from "next/navigation";
import { GOOGLE_ADS_CLIENT_ID, isRouteBlocked } from "@/src/config/ads";

export default function GoogleAdScript() {
  const pathname = usePathname();

  // If the current route is blocked, do not load the Google Ads script
  if (isRouteBlocked(pathname)) {
    return null;
  }

  // Also skip loading in development mode to prevent console clutter,
  // since we render visual placeholders during development.
  if (process.env.NODE_ENV === "development") {
    return null;
  }

  const scriptUrl = `https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=${GOOGLE_ADS_CLIENT_ID}`;

  return (
    <Script
      async
      src={scriptUrl}
      crossOrigin="anonymous"
      strategy="afterInteractive"
    />
  );
}

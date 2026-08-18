"use client";

import React, { useEffect, useSyncExternalStore } from "react";
import { usePathname } from "next/navigation";
import { GOOGLE_ADS_CLIENT_ID, isRouteBlocked } from "@/src/config/ads";
import AdPlaceholder from "./AdPlaceholder";

// Proper type for the adsbygoogle window property instead of `any`
interface WindowWithAds extends Window {
  adsbygoogle: Array<Record<string, unknown>>;
}

type GoogleAdSlotProps = {
  slotId: string;
  format?: "auto" | "fluid" | "rectangle" | "horizontal" | "vertical";
  responsive?: boolean;
  style?: React.CSSProperties;
  className?: string;
  label?: string;
};

// useSyncExternalStore-based client-only hook avoids setState in useEffect
function useIsClient(): boolean {
  return useSyncExternalStore(
    () => () => {},           // subscribe: no-op (no external store changes)
    () => true,               // getSnapshot (client): mounted
    () => false               // getServerSnapshot: not mounted
  );
}

export default function GoogleAdSlot({
  slotId,
  format = "auto",
  responsive = true,
  style,
  className = "",
  label,
}: GoogleAdSlotProps) {
  const pathname = usePathname();
  const isMounted = useIsClient();

  // Check if route is blocked
  const isBlocked = isRouteBlocked(pathname);

  useEffect(() => {
    // Only push if we are on client, not blocked, and NOT in dev mode
    if (!isMounted || isBlocked || process.env.NODE_ENV === "development") {
      return;
    }

    try {
      // Initialize the ad slot using the properly typed window property
      const win = window as unknown as WindowWithAds;
      win.adsbygoogle = win.adsbygoogle || [];
      win.adsbygoogle.push({});
    } catch (err) {
      console.error("Google AdSense initialization error for slot:", slotId, err);
    }
  }, [isMounted, pathname, isBlocked, slotId]);

  // Don't render anything during SSR to prevent hydration mismatches
  if (!isMounted) {
    return <div style={{ minHeight: style?.height || "100px", width: "100%" }} className={className} />;
  }

  // If the page/route is blocked, render nothing
  if (isBlocked) {
    return null;
  }

  // Render visual placeholder in development
  if (process.env.NODE_ENV === "development") {
    return (
      <AdPlaceholder
        slotId={slotId}
        format={format}
        className={className}
        style={style}
        label={label}
      />
    );
  }

  // Render the Google Ads tag in production
  // We use a key based on pathname + slotId to force a clean remount when navigating
  return (
    <div
      key={`${pathname}-${slotId}`}
      className={`google-ad-container ${className}`.trim()}
    >
      <ins
        className="adsbygoogle"
        style={{ display: "block", ...style }}
        data-ad-client={GOOGLE_ADS_CLIENT_ID}
        data-ad-slot={slotId}
        data-ad-format={format}
        data-full-width-responsive={responsive ? "true" : "false"}
      />
    </div>
  );
}

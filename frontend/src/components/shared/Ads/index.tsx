import GoogleAdSlot from "./GoogleAdSlot";
import GoogleAdScript from "./GoogleAdScript";

export { GoogleAdSlot, GoogleAdScript };

type PresetAdProps = {
  slotId?: string;
  className?: string;
};

/**
 * Banner Ad - Typically a horizontal leaderboard (728x90).
 * Ideal for headers, footers, or between sections.
 */
export function BannerAd({ slotId = "1111111111", className = "" }: PresetAdProps) {
  return (
    <GoogleAdSlot
      slotId={slotId}
      format="horizontal"
      style={{ minHeight: "90px" }}
      className={`w-full my-4 flex justify-center ${className}`.trim()}
      label="Leaderboard Banner Ad"
    />
  );
}

/**
 * Square Ad - Typically a medium rectangle (300x250).
 * Ideal for sidebars, within grids, or embedded inside articles.
 */
export function SquareAd({ slotId = "2222222222", className = "" }: PresetAdProps) {
  return (
    <GoogleAdSlot
      slotId={slotId}
      format="rectangle"
      style={{ width: "300px", height: "250px" }}
      className={`mx-auto my-4 flex justify-center ${className}`.trim()}
      label="Square Box Ad"
    />
  );
}

/**
 * Sidebar Ad - Typically a vertical skyscraper (160x600 or 300x600).
 * Ideal for page side columns.
 */
export function SidebarAd({ slotId = "3333333333", className = "" }: PresetAdProps) {
  return (
    <GoogleAdSlot
      slotId={slotId}
      format="vertical"
      style={{ width: "160px", height: "600px" }}
      className={`mx-auto my-4 flex justify-center ${className}`.trim()}
      label="Vertical Sidebar Ad"
    />
  );
}

/**
 * Responsive Ad - Automatically configures the size based on layout container.
 * Best for general use when grid/flex column width varies.
 */
export function ResponsiveAd({ slotId = "4444444444", className = "" }: PresetAdProps) {
  return (
    <GoogleAdSlot
      slotId={slotId}
      format="auto"
      className={`w-full my-4 ${className}`.trim()}
      label="Responsive Google Ad"
    />
  );
}

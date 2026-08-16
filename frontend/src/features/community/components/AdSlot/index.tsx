import "./index.css";

type AdVariant = "landscape" | "square" | "portrait";

type AdSlotProps = {
  variant?: AdVariant;
  title: string;
  cta?: string;
  image?: string;
  className?: string;
};

const variantLabels: Record<AdVariant, string> = {
  landscape: "Landscape ad",
  square: "Square ad",
  portrait: "Portrait ad",
};

export default function AdSlot({
  variant = "landscape",
  title,
  cta = "Learn more",
  image,
  className = "",
}: AdSlotProps) {
  return (
    <div className={`community-ad-slot community-ad-slot--${variant} ${className}`.trim()}>
      <span className="community-ad-slot__label">{variantLabels[variant]}</span>
      <div
        className="community-ad-slot__media"
        style={
          image
            ? {
                backgroundImage: `linear-gradient(135deg, rgba(17, 24, 39, 0.22), rgba(17, 24, 39, 0.12)), url(${image})`,
              }
            : undefined
        }
      />
      <div className="community-ad-slot__content">
        <h4>{title}</h4>
        <button type="button">{cta}</button>
      </div>
    </div>
  );
}

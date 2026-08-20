import type { ReactNode, MouseEvent } from "react";
import "./index.css";

interface MagicCardProps {
  children: ReactNode;
  className?: string;
}

export default function MagicCard({
  children,
  className = "",
}: MagicCardProps) {
  const handleMouseMove = (event: MouseEvent<HTMLDivElement>) => {
    const card = event.currentTarget;
    const rect = card.getBoundingClientRect();

    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;

    card.style.setProperty("--magic-x", `${x}px`);
    card.style.setProperty("--magic-y", `${y}px`);
  };

  const handleMouseLeave = (event: MouseEvent<HTMLDivElement>) => {
    const card = event.currentTarget;

    card.style.setProperty("--magic-x", "50%");
    card.style.setProperty("--magic-y", "50%");
  };

  return (
    <div
      className={`magic-card ${className}`}
      onMouseMove={handleMouseMove}
      onMouseLeave={handleMouseLeave}
    >
      <div className="magic-card__glow" />
      <div className="magic-card__content">
        {children}
      </div>
    </div>
  );
}
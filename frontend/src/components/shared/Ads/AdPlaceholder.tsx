import React from "react";

type AdPlaceholderProps = {
  slotId: string;
  format?: string;
  className?: string;
  style?: React.CSSProperties;
  label?: string;
};

export default function AdPlaceholder({
  slotId,
  format = "auto",
  className = "",
  style,
  label = "Google Ad",
}: AdPlaceholderProps) {
  // Determine default sizing classes based on style/format
  const isHorizontal = format === "horizontal" || (style?.height && Number(style.height) <= 100);
  const isVertical = format === "vertical" || (style?.width && Number(style.width) <= 200);

  const placeholderClasses = "flex flex-col items-center justify-center border-2 border-dashed border-slate-300 bg-slate-100/80 rounded-lg text-slate-500 font-medium p-4 select-none transition-all hover:bg-slate-100 hover:border-slate-400";
  
  // Set dimensions based on styles if present, or use default sizing classes
  const appliedStyle: React.CSSProperties = {
    minHeight: style?.height || (isHorizontal ? "90px" : isVertical ? "600px" : "250px"),
    width: style?.width || "100%",
    ...style,
  };

  return (
    <div 
      className={`${placeholderClasses} ${className}`}
      style={appliedStyle}
    >
      <div className="flex items-center space-x-2 text-xs font-semibold uppercase tracking-wider text-slate-400 bg-white px-2 py-0.5 rounded shadow-sm border border-slate-200">
        <span>{label}</span>
      </div>
      
      <div className="mt-2 text-center">
        <p className="text-sm font-semibold text-slate-600">
          Format: <span className="font-mono text-xs bg-slate-200/60 px-1 rounded text-red-600">{format}</span>
        </p>
        <p className="text-xs text-slate-400 mt-0.5 font-mono">
          Slot ID: {slotId}
        </p>
        {style?.width && style?.height && (
          <p className="text-[10px] text-slate-400 font-mono mt-0.5">
            Dimensions: {String(style.width)}x{String(style.height)}
          </p>
        )}
      </div>
      
      <div className="mt-2.5 text-[10px] text-slate-400 italic">
        (Dev Mode Placeholder - Hidden in Production)
      </div>
    </div>
  );
}

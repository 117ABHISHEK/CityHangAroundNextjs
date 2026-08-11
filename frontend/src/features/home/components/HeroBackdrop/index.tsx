"use client";

import { useEffect, useState } from "react";
import type { CSSProperties } from "react";

const buildings = [
  ["0%", "74%", "7%"], ["7%", "62%", "5%"], ["12%", "78%", "8%"],
  ["21%", "48%", "6%"], ["28%", "68%", "7%"], ["36%", "42%", "6%"],
  ["43%", "58%", "9%"], ["53%", "38%", "6%"], ["60%", "64%", "8%"],
  ["69%", "51%", "7%"], ["77%", "70%", "8%"], ["86%", "45%", "6%"],
  ["93%", "63%", "7%"],
];

const stars = [
  ["12%", "24%"], ["24%", "14%"], ["36%", "29%"], ["49%", "12%"],
  ["61%", "22%"], ["73%", "10%"], ["84%", "28%"], ["94%", "17%"],
];

const seededValue = (input: number) => {
  const value = Math.sin(input * 12.9898 + 78.233) * 43758.5453123;
  return value - Math.floor(value);
};

const stableDuration = (index: number) => {
  const base = 5000 + Math.floor(seededValue(index + 1) * 5000);
  return base;
};

const stableDelay = (index: number, duration: number) => {
  const raw = Math.floor(seededValue(index + 101) * duration);
  return raw - duration;
};

export default function HeroBackdrop() {
  const [isNight, setIsNight] = useState(false);
  const [lightTimings] = useState<number[]>(() =>
    Array.from({ length: buildings.length * 12 }, (_, index) => stableDuration(index)),
  );
  const [morningGlowMap] = useState<number[]>(() =>
    buildings.flatMap((_, buildingIndex) => {
      const litCount = 1 + Math.floor(seededValue(buildingIndex + 9) * 2);
      const activeIndexes = new Set<number>();

      while (activeIndexes.size < litCount) {
        activeIndexes.add(Math.floor(seededValue(buildingIndex * 17 + activeIndexes.size + 31) * 12));
      }

      return Array.from({ length: 12 }, (_, index) => (activeIndexes.has(index) ? 1 : 0));
    }),
  );

  useEffect(() => {
    const updateTime = () => {
      const hour = new Date().getHours();
      setIsNight(hour < 6 || hour >= 18);
    };

    updateTime();
    const timer = window.setInterval(updateTime, 60_000);
    return () => window.clearInterval(timer);
  }, []);

  return (
    <div className={`hero-backdrop ${isNight ? "hero-backdrop--night" : "hero-backdrop--day"}`} aria-hidden="true">
      <div className="hero-backdrop__stars">{stars.map(([left, top]) => <i key={`${left}-${top}`} style={{ left, top }} />)}</div>
      <div className="hero-backdrop__celestial" />
      <div className="hero-backdrop__buildings">
        {buildings.map(([left, height, width], buildingIndex) => (
          <span key={left} style={{ left, height, width }}>
            {Array.from({ length: 6 }, (_, rowIndex) => (
              <b key={rowIndex}>
                {[0, 1].map((columnIndex) => {
                  const timingIndex = buildingIndex * 12 + rowIndex * 2 + columnIndex;
                  const duration = lightTimings[timingIndex] ?? 5000;
                  const isMorningGlow = !isNight && morningGlowMap[timingIndex] === 1;
                  const lightDelay = stableDelay(timingIndex, duration);

                  return (
                    <i
                      key={columnIndex}
                      className={isMorningGlow ? "is-morning-glow" : ""}
                      style={{
                        "--light-duration": `${duration}ms`,
                        "--light-delay": `${lightDelay}ms`,
                      } as CSSProperties}
                    />
                  );
                })}
              </b>
            ))}
          </span>
        ))}
      </div>
    </div>
  );
}

"use client";

import { useEffect, useState } from "react";

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

export default function HeroBackdrop() {
  const [isNight, setIsNight] = useState(false);

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
        {buildings.map(([left, height, width]) => (
          <span key={left} style={{ left, height, width }}>
            <i /><i /><i /><i /><i /><i />
          </span>
        ))}
      </div>
    </div>
  );
}

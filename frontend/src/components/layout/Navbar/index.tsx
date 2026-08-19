"use client";

import { useEffect, useRef, useState } from "react";
import PrimaryNavbar from "./PrimaryNavbar";
import SecondaryNavbar from "./SecondaryNavbar";

export type TabType =
  | "home"
  | "community"
  | "city-guide"
  | "buy-sell"
  | "marketplace"
  | "blog"
  | "contact";

type NavbarProps = {
  activeTab?: TabType;
  onTabChange?: (tab: TabType) => void;
};

export default function Navbar({
  activeTab = "home",
  onTabChange,
}: NavbarProps) {
  const [isVisible, setIsVisible] = useState(true);
  const [isScrolled, setIsScrolled] = useState(false);
  const lastScrollY = useRef(0);

  useEffect(() => {
    lastScrollY.current = window.scrollY;

    const handleScroll = () => {
      const currentScrollY = window.scrollY;
      const prevScrollY = lastScrollY.current;
      const delta = currentScrollY - prevScrollY;

      setIsScrolled(currentScrollY > 10);

      // Always visible at the top of page
      if (currentScrollY <= 15) {
        setIsVisible(true);
        lastScrollY.current = currentScrollY;
        return;
      }

      // Ignore micro jitter
      if (Math.abs(delta) < 4) {
        return;
      }

      // Scrolling DOWN -> hide entire navbar
      if (delta > 0 && currentScrollY > 60) {
        setIsVisible(false);
      }
      // Scrolling UP -> reveal entire navbar
      else if (delta < 0) {
        setIsVisible(true);
      }

      lastScrollY.current = currentScrollY;
    };

    window.addEventListener("scroll", handleScroll, { passive: true });
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <header
      className={`navbar ${
        isVisible ? "navbar--visible" : "navbar--hidden"
      } ${isScrolled ? "navbar--scrolled" : ""}`}
    >
      {/* Primary Navbar Shell */}
      <div className="navbar__primary-shell">
        <PrimaryNavbar />
      </div>

      {/* Secondary Navbar Shell */}
      <div className="navbar__secondary-shell">
        <SecondaryNavbar
          activeTab={activeTab}
          onTabChange={onTabChange}
        />
      </div>
    </header>
  );
}
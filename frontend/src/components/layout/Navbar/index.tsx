"use client";

import { useEffect, useState } from "react";
import PrimaryNavbar from "./PrimaryNavbar";
import SecondaryNavbar from "./SecondaryNavbar";
import type { TabType } from "@/src/routes";

export type { TabType };

type NavbarProps = {
  activeTab?: TabType;
  onTabChange?: (tab: TabType) => void;
};

export default function Navbar({
  activeTab = "home",
  onTabChange,
}: NavbarProps) {
  const [isScrolled, setIsScrolled] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 10);
    };

    window.addEventListener("scroll", handleScroll, { passive: true });
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <header
      className={`navbar ${isScrolled ? "navbar--scrolled" : ""}`}
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
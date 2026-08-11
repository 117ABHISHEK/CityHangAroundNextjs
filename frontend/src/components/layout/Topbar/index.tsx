"use client";

import { useEffect, useRef, useState } from "react";
import {
  AdvertiseIcon,
  BusinessIcon,
  ChevronDown,
  DownloadIcon,
  HelpIcon,
  LocationIcon,
} from "@/src/components/ui/icons";
import AnimatedIcon from "@/src/components/ui/animated-icon";

export default function Topbar() {
  const [isHidden, setIsHidden] = useState(false);
  const previousScrollY = useRef(0);

  useEffect(() => {
    const handleScroll = () => {
      const currentScrollY = window.scrollY;

      if (currentScrollY <= 8 || currentScrollY < previousScrollY.current) {
        setIsHidden(false);
      } else if (currentScrollY > previousScrollY.current) {
        setIsHidden(true);
      }

      previousScrollY.current = currentScrollY;
    };

    window.addEventListener("scroll", handleScroll, { passive: true });
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <div className={`topbar text-[13px] text-[#34465f] ${isHidden ? "topbar--hidden" : ""}`}>
      <div className="container mx-auto flex h-9 min-w-max items-center justify-between gap-8 overflow-x-auto px-4 sm:px-6">
        <a href="#" className="flex shrink-0 items-center gap-1.5 hover:text-red-600">
          <AnimatedIcon>
            <LocationIcon size={14} strokeWidth={2.4} aria-hidden="true" />
          </AnimatedIcon>
          <span>Select City</span>
          <ChevronDown size={13} strokeWidth={1.8} aria-hidden="true" />
        </a>
        <div className="flex shrink-0 items-center gap-5">
          <a href="#" className="flex items-center gap-1.5 hover:text-red-600">
            <AnimatedIcon>
              <BusinessIcon size={14} strokeWidth={2} aria-hidden="true" />
            </AnimatedIcon>
            <span>For Businesses</span>
            <ChevronDown size={13} strokeWidth={1.8} aria-hidden="true" />
          </a>
          <a href="#" className="flex items-center gap-1.5 font-medium text-[#e74732] hover:text-red-700">
            <AnimatedIcon>
              <AdvertiseIcon size={14} strokeWidth={2} aria-hidden="true" />
            </AnimatedIcon>
            <span>Advertise</span>
            <ChevronDown size={13} strokeWidth={1.8} aria-hidden="true" />
          </a>
          <a href="#" className="flex items-center gap-1.5 hover:text-red-600">
            <AnimatedIcon>
              <DownloadIcon size={14} strokeWidth={1.8} aria-hidden="true" />
            </AnimatedIcon>
            <span>Download App</span>
          </a>
          <a href="#" className="flex items-center gap-1.5 hover:text-red-600">
            <AnimatedIcon>
              <HelpIcon size={14} strokeWidth={1.8} aria-hidden="true" />
            </AnimatedIcon>
            <span>Help &amp; Support</span>
          </a>
        </div>
      </div>
    </div>
  );
}

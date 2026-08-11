"use client";

import type { ReactNode } from "react";
import { motion } from "motion/react";

type AnimatedIconProps = {
  children: ReactNode;
};

export default function AnimatedIcon({ children }: AnimatedIconProps) {
  return (
    <motion.span
      className="inline-flex shrink-0"
      whileHover={{ y: -1, scale: 1.04 }}
      transition={{ duration: 0.18 }}
    >
      {children}
    </motion.span>
  );
}

"use client";

import React, {
  ElementType,
  ReactNode,
  useCallback,
  useEffect,
  useMemo,
  useRef,
  useState,
} from "react";

import "./index.css";


/* =========================================================
   TYPES
========================================================= */

export interface LetterSwap3DProps {
  children: ReactNode;

  as?: ElementType;

  className?: string;

  frontFaceClassName?: string;

  backFaceClassName?: string;

  staggerInterval?: number;

  staggerOrigin?:
    | "first"
    | "last"
    | "center"
    | "random"
    | number;

  flipDirection?: "top" | "bottom";

  blur?: boolean;

  blurAmount?: number;

  duration?: number;

  respectReducedMotion?: boolean;

  /**
   * Automatically animate the letters.
   */
  autoPlay?: boolean;

  /**
   * Delay between the END of one animation
   * and the START of the next animation.
   *
   * 5000 = 5 seconds.
   */
  interval?: number;

  onAnimationStart?: () => void;

  onAnimationComplete?: () => void;
}


/* =========================================================
   TEXT EXTRACTION
========================================================= */

function extractText(node: ReactNode): string {
  if (
    typeof node === "string" ||
    typeof node === "number"
  ) {
    return String(node);
  }

  if (Array.isArray(node)) {
    return node
      .map((child) => extractText(child))
      .join("");
  }

  if (
    React.isValidElement<{
      children?: ReactNode;
    }>(node)
  ) {
    return extractText(node.props.children);
  }

  return "";
}


/* =========================================================
   DETERMINISTIC RANDOM
========================================================= */

function pseudoRandom(index: number): number {
  const value =
    Math.sin(index * 12.9898) *
    43758.5453;

  return value - Math.floor(value);
}


/* =========================================================
   COMPONENT
========================================================= */

export function LetterSwap3D({
  children,

  as: Component = "span",

  className = "",

  frontFaceClassName = "",

  backFaceClassName = "",

  staggerInterval = 0.055,

  staggerOrigin = "first",

  flipDirection = "top",

  blur = true,

  blurAmount = 3,

  duration = 0.42,

  respectReducedMotion = true,

  autoPlay = false,

  interval = 5000,

  onAnimationStart,

  onAnimationComplete,
}: LetterSwap3DProps) {


  /* =======================================================
     STATE
  ======================================================= */

  const [isActive, setIsActive] =
    useState(false);


  /* =======================================================
     REFS
  ======================================================= */

  /*
   * Browser timers return numbers.
   *
   * Using number explicitly avoids the NodeJS.Timeout
   * vs browser number conflict.
   */

  const animationTimerRef =
    useRef<number | null>(null);

  const autoplayTimerRef =
    useRef<number | null>(null);

  const isAnimatingRef =
    useRef(false);

  const isMountedRef =
    useRef(true);


  /* =======================================================
     TEXT
  ======================================================= */

  const text = useMemo(
    () => extractText(children),
    [children]
  );


  /* =======================================================
     LETTERS
  ======================================================= */

  const letters = useMemo(
    () => Array.from(text),
    [text]
  );


  /* =======================================================
     STAGGER DELAY
  ======================================================= */

  const getDelay = useCallback(
    (index: number) => {
      const total = letters.length;

      if (total === 0) {
        return 0;
      }


      /* ---------------------------------------------------
         NUMERIC
      --------------------------------------------------- */

      if (
        typeof staggerOrigin === "number"
      ) {
        return (
          Math.abs(
            index - staggerOrigin
          ) * staggerInterval
        );
      }


      /* ---------------------------------------------------
         LAST
      --------------------------------------------------- */

      if (
        staggerOrigin === "last"
      ) {
        return (
          (total - 1 - index) *
          staggerInterval
        );
      }


      /* ---------------------------------------------------
         CENTER
      --------------------------------------------------- */

      if (
        staggerOrigin === "center"
      ) {
        const center =
          (total - 1) / 2;

        return (
          Math.abs(
            index - center
          ) * staggerInterval
        );
      }


      /* ---------------------------------------------------
         RANDOM
      --------------------------------------------------- */

      if (
        staggerOrigin === "random"
      ) {
        return (
          pseudoRandom(index) *
          total *
          staggerInterval
        );
      }


      /* ---------------------------------------------------
         FIRST
      --------------------------------------------------- */

      return (
        index * staggerInterval
      );
    },
    [
      letters.length,
      staggerOrigin,
      staggerInterval,
    ]
  );


  /* =======================================================
     TOTAL ANIMATION DURATION
  ======================================================= */

  const animationDuration =
    duration +
    Math.max(
      0,
      letters.length - 1
    ) *
      staggerInterval;


  /*
   * Convert seconds → milliseconds.
   */

  const animationDurationMs =
    animationDuration * 1000;


  /* =======================================================
     CLEAR ANIMATION TIMER
  ======================================================= */

  const clearAnimationTimer =
    useCallback(() => {

      if (
        animationTimerRef.current !== null
      ) {
        window.clearTimeout(
          animationTimerRef.current
        );

        animationTimerRef.current =
          null;
      }

    }, []);


  /* =======================================================
     CLEAR AUTOPLAY TIMER
  ======================================================= */

  const clearAutoplayTimer =
    useCallback(() => {

      if (
        autoplayTimerRef.current !== null
      ) {
        window.clearTimeout(
          autoplayTimerRef.current
        );

        autoplayTimerRef.current =
          null;
      }

    }, []);


  /* =======================================================
     TRIGGER ANIMATION
     
     This function is intentionally independent
     from the autoplay scheduler.
     
     That prevents circular callback dependencies.
  ======================================================= */

  const triggerAnimation =
    useCallback(() => {

      /* -----------------------------------------------
         Don't animate after unmount
      ----------------------------------------------- */

      if (!isMountedRef.current) {
        return;
      }


      /* -----------------------------------------------
         Don't overlap animations
      ----------------------------------------------- */

      if (isAnimatingRef.current) {
        return;
      }


      /* -----------------------------------------------
         Mark animation as running
      ----------------------------------------------- */

      isAnimatingRef.current =
        true;


      /* -----------------------------------------------
         Start CSS animation
      ----------------------------------------------- */

      setIsActive(true);


      /* -----------------------------------------------
         Callback
      ----------------------------------------------- */

      onAnimationStart?.();


      /* -----------------------------------------------
         Clear previous animation timer
      ----------------------------------------------- */

      clearAnimationTimer();


      /* -----------------------------------------------
         Finish animation
      ----------------------------------------------- */

      animationTimerRef.current =
        window.setTimeout(() => {

          if (!isMountedRef.current) {
            return;
          }


          /* -----------------------------------------
             Reset animation state
          ----------------------------------------- */

          setIsActive(false);

          isAnimatingRef.current =
            false;


          /* -----------------------------------------
             Callback
          ----------------------------------------- */

          onAnimationComplete?.();


        }, animationDurationMs);

    }, [
      animationDurationMs,
      clearAnimationTimer,
      onAnimationComplete,
      onAnimationStart,
    ]);


  /* =======================================================
     AUTOPLAY
     
     IMPORTANT:
     
     First animation:
       wait 5 seconds
     
     Next animation:
       animation finishes
       ↓
       wait 5 seconds
       ↓
       animation starts
     
     We achieve this without recursively referencing
     triggerAnimation from inside itself.
  ======================================================= */

  useEffect(() => {

    isMountedRef.current = true;


    /* -----------------------------------------------
       Autoplay disabled
    ----------------------------------------------- */

    if (!autoPlay) {
      return;
    }


    /* -----------------------------------------------
       First animation
    ----------------------------------------------- */

    clearAutoplayTimer();

    autoplayTimerRef.current =
      window.setTimeout(() => {

        if (!isMountedRef.current) {
          return;
        }


        triggerAnimation();


        /*
         * Schedule subsequent animations.
         *
         * interval = delay AFTER animation
         *
         * So:
         *
         * animation duration
         * +
         * interval
         *
         * = next animation start.
         */

        autoplayTimerRef.current =
          window.setInterval(() => {

            if (
              !isMountedRef.current
            ) {
              return;
            }


            /*
             * triggerAnimation already protects
             * against overlapping animations.
             */

            triggerAnimation();

          }, animationDurationMs + interval);

      }, interval);


    /* -----------------------------------------------
       Cleanup
    ----------------------------------------------- */

    return () => {

      isMountedRef.current =
        false;

      clearAutoplayTimer();

      clearAnimationTimer();

    };

  }, [
    autoPlay,
    interval,
    animationDurationMs,
    triggerAnimation,
    clearAutoplayTimer,
    clearAnimationTimer,
  ]);


  /* =======================================================
     HOVER
  ======================================================= */

  const handleMouseEnter =
    useCallback(() => {

      triggerAnimation();

    }, [
      triggerAnimation,
    ]);


  /* =======================================================
     RENDER
  ======================================================= */

  return React.createElement(
    Component,
    {
      className: [
        "letter-swap-3d",
        className,
      ]
        .filter(Boolean)
        .join(" "),

      onMouseEnter:
        handleMouseEnter,

      "aria-label": text,

      "data-active":
        isActive
          ? "true"
          : "false",

      "data-respect-reduced-motion":
        respectReducedMotion
          ? "true"
          : "false",
    },


    /* =====================================================
       INNER
    ===================================================== */

    React.createElement(
      "span",
      {
        className:
          "letter-swap-3d__inner",
      },


      /* ===================================================
         LETTERS
      =================================================== */

      letters.map(
        (letter, index) => {

          const delay =
            getDelay(index);

          const isSpace =
            letter === " ";


          /* -----------------------------------------------
             SPACE
          ----------------------------------------------- */

          if (isSpace) {

            return React.createElement(
              "span",
              {
                key:
                  `space-${index}`,

                className:
                  "letter-swap-3d__letter",

                "aria-hidden": true,
              },

              "\u00A0"
            );
          }


          /* -----------------------------------------------
             LETTER
          ----------------------------------------------- */

          return React.createElement(
            "span",
            {
              key:
                `${letter}-${index}`,

              className:
                "letter-swap-3d__letter",

              style: {
                "--letter-delay":
                  `${delay}s`,

                "--letter-duration":
                  `${duration}s`,

                "--letter-blur":
                  blur
                    ? `${blurAmount}px`
                    : "0px",

                "--swap-distance":
                  flipDirection ===
                  "bottom"
                    ? "115%"
                    : "-115%",
              } as React.CSSProperties,
            },


            /* -------------------------------------------
               ORIGINAL LETTER
            ------------------------------------------- */

            React.createElement(
              "span",
              {
                className: [
                  "letter-swap-3d__face",
                  "letter-swap-3d__face--front",
                  frontFaceClassName,
                ]
                  .filter(Boolean)
                  .join(" "),
              },

              letter
            ),


            /* -------------------------------------------
               INCOMING LETTER
            ------------------------------------------- */

            React.createElement(
              "span",
              {
                className: [
                  "letter-swap-3d__face",
                  "letter-swap-3d__face--back",
                  backFaceClassName,
                ]
                  .filter(Boolean)
                  .join(" "),
              },

              letter
            )
          );
        }
      )
    )
  );
}


export default LetterSwap3D;
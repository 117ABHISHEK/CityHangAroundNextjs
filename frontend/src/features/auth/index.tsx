"use client";

import { useEffect, useRef, useState } from "react";
import { createPortal } from "react-dom";
import {
  ArrowRight,
  Check,
  Eye,
  EyeOff,
  Lock,
  Mail,
  User,
  X,
} from "lucide-react";
import { FaFacebookF, FaGoogle } from "react-icons/fa6";

export type AuthMode = "login" | "signup";

type AuthModalProps = {
  isOpen: boolean;
  onClose: () => void;
  initialMode?: AuthMode;
};

export default function AuthModal({
  isOpen,
  onClose,
  initialMode = "login",
}: AuthModalProps) {
  const [mode, setMode] = useState<AuthMode>(initialMode);
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [mounted, setMounted] = useState(false);
  const prevIsOpenRef = useRef(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  useEffect(() => {
    if (!isOpen) return;

    const handleEscape = (event: KeyboardEvent) => {
      if (event.key === "Escape") onClose();
    };

    document.addEventListener("keydown", handleEscape);

    return () => {
      document.removeEventListener("keydown", handleEscape);
    };
  }, [isOpen, onClose]);

  useEffect(() => {
    // Only reset form state when transitioning from closed to open
    if (isOpen && !prevIsOpenRef.current) {
      setMode(initialMode);
      setShowPassword(false);
      setShowConfirmPassword(false);
    }
    prevIsOpenRef.current = isOpen;
  }, [isOpen, initialMode]);

  if (!isOpen || !mounted) return null;

  const isLogin = mode === "login";

  const inputWrapper =
    "group flex h-11 items-center gap-2.5 rounded-lg border border-slate-200 bg-white px-3 transition-all duration-200 hover:-translate-y-[1px] hover:border-slate-300 hover:shadow-sm focus-within:-translate-y-[1px] focus-within:border-[#ef4444] focus-within:ring-4 focus-within:ring-red-500/10 focus-within:shadow-[0_4px_15px_rgba(239,68,68,0.07)]";

  const iconWrapper =
    "flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-red-50 text-[#ef4444] transition-all duration-200 group-hover:scale-105 group-focus-within:scale-105 group-focus-within:bg-red-100";

  return createPortal(
    <div
      className="fixed inset-0 z-[99999] flex items-center justify-center overflow-y-auto bg-slate-950/45 p-4 backdrop-blur-md animate-[fadeIn_180ms_ease-out]"
      onClick={onClose}
      role="presentation"
    >
      <div
        className="relative my-auto w-full max-w-[440px] overflow-hidden rounded-2xl border border-white/70 bg-[#f8f9fb] shadow-[0_20px_60px_rgba(15,23,42,0.30)] animate-[modalIn_260ms_cubic-bezier(0.16,1,0.3,1)]"
        onClick={(event) => event.stopPropagation()}
        role="dialog"
        aria-modal="true"
        aria-label={isLogin ? "Log in form" : "Sign up form"}
      >
        {/* Close Button */}
        <button
          type="button"
          onClick={onClose}
          aria-label="Close authentication form"
          className="group absolute right-3.5 top-3.5 z-20 flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 bg-white/95 text-slate-400 shadow-sm transition-all duration-200 hover:rotate-90 hover:border-red-200 hover:bg-red-50 hover:text-[#ef4444] hover:shadow-md active:scale-90"
        >
          <X size={16} strokeWidth={2.3} />
        </button>

        {/* Auth Switcher */}
        <div className="bg-white px-4 pt-4 sm:px-5 sm:pt-5">
          <div className="relative rounded-xl bg-slate-100 p-1">
            {/* Sliding Active Background */}
            <div
              className={`absolute bottom-1 top-1 w-[calc(50%-4px)] rounded-lg bg-white shadow-[0_2px_8px_rgba(15,23,42,0.08)] transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] ${
                isLogin
                  ? "left-1"
                  : "left-[calc(50%+1px)]"
              }`}
            />

            <div className="relative grid grid-cols-2">
              {/* Login */}
              <button
                type="button"
                onClick={() => setMode("login")}
                className={`group relative z-10 flex h-10 items-center justify-center gap-2 rounded-lg text-[13px] font-semibold transition-all duration-300 ${
                  isLogin
                    ? "text-[#ef4444]"
                    : "text-slate-500 hover:text-slate-800"
                }`}
              >
                <span
                  className={`flex h-7 w-7 items-center justify-center rounded-md transition-all duration-300 ${
                    isLogin
                      ? "scale-100 bg-red-50 text-[#ef4444]"
                      : "scale-90 bg-transparent text-slate-400 group-hover:scale-100"
                  }`}
                >
                  <ArrowRight
                    size={15}
                    strokeWidth={2.5}
                    className="-rotate-45 transition-transform duration-300"
                  />
                </span>

                <span>Log In</span>
              </button>

              {/* Sign Up */}
              <button
                type="button"
                onClick={() => setMode("signup")}
                className={`group relative z-10 flex h-10 items-center justify-center gap-2 rounded-lg text-[13px] font-semibold transition-all duration-300 ${
                  !isLogin
                    ? "text-[#ef4444]"
                    : "text-slate-500 hover:text-slate-800"
                }`}
              >
                <span
                  className={`flex h-7 w-7 items-center justify-center rounded-md transition-all duration-300 ${
                    !isLogin
                      ? "scale-100 bg-red-50 text-[#ef4444]"
                      : "scale-90 bg-transparent text-slate-400 group-hover:scale-100"
                  }`}
                >
                  <User
                    size={15}
                    strokeWidth={2.3}
                    className="transition-transform duration-300"
                  />
                </span>

                <span>Sign Up</span>
              </button>
            </div>
          </div>
        </div>

        {/* Form Content */}
        <div className="px-4 pb-5 pt-5 sm:px-5 sm:pb-6">
          <form className="space-y-4">
            {/* Full Name */}
            {!isLogin && (
              <div>
                <label
                  htmlFor="name"
                  className="mb-1.5 block text-[13px] font-semibold text-slate-700"
                >
                  Full Name
                </label>

                <div className={inputWrapper}>
                  <span className={iconWrapper}>
                    <User size={15} strokeWidth={2.3} />
                  </span>

                  <input
                    id="name"
                    type="text"
                    placeholder="Enter your full name"
                    className="min-w-0 flex-1 bg-transparent text-[13px] text-slate-900 outline-none placeholder:text-slate-400"
                  />
                </div>
              </div>
            )}

            {/* Email */}
            <div>
              <label
                htmlFor="email"
                className="mb-1.5 block text-[13px] font-semibold text-slate-700"
              >
                Email Address
              </label>

              <div className={inputWrapper}>
                <span className={iconWrapper}>
                  <Mail size={15} strokeWidth={2.2} />
                </span>

                <input
                  id="email"
                  type="email"
                  placeholder="Enter your email address"
                  className="min-w-0 flex-1 bg-transparent text-[13px] text-slate-900 outline-none placeholder:text-slate-400"
                />
              </div>
            </div>

            {/* Password */}
            <div>
              <label
                htmlFor="password"
                className="mb-1.5 block text-[13px] font-semibold text-slate-700"
              >
                Password
              </label>

              <div className={inputWrapper}>
                <span className={iconWrapper}>
                  <Lock size={15} strokeWidth={2.2} />
                </span>

                <input
                  id="password"
                  type={showPassword ? "text" : "password"}
                  placeholder={
                    isLogin ? "Enter your password" : "Create your password"
                  }
                  className="min-w-0 flex-1 bg-transparent text-[13px] text-slate-900 outline-none placeholder:text-slate-400"
                />

                <button
                  type="button"
                  aria-label={
                    showPassword ? "Hide password" : "Show password"
                  }
                  onClick={() => setShowPassword((value) => !value)}
                  className="flex h-7 w-7 shrink-0 items-center justify-center rounded-md text-slate-400 transition-all duration-200 hover:bg-slate-100 hover:text-slate-700 active:scale-90"
                >
                  {showPassword ? (
                    <EyeOff size={16} />
                  ) : (
                    <Eye size={16} />
                  )}
                </button>
              </div>
            </div>

            {/* Confirm Password */}
            {!isLogin && (
              <div>
                <label
                  htmlFor="confirmPassword"
                  className="mb-1.5 block text-[13px] font-semibold text-slate-700"
                >
                  Confirm Password
                </label>

                <div className={inputWrapper}>
                  <span className={iconWrapper}>
                    <Lock size={15} strokeWidth={2.2} />
                  </span>

                  <input
                    id="confirmPassword"
                    type={showConfirmPassword ? "text" : "password"}
                    placeholder="Re-enter your password"
                    className="min-w-0 flex-1 bg-transparent text-[13px] text-slate-900 outline-none placeholder:text-slate-400"
                  />

                  <button
                    type="button"
                    aria-label={
                      showConfirmPassword
                        ? "Hide confirm password"
                        : "Show confirm password"
                    }
                    onClick={() =>
                      setShowConfirmPassword((value) => !value)
                    }
                    className="flex h-7 w-7 shrink-0 items-center justify-center rounded-md text-slate-400 transition-all duration-200 hover:bg-slate-100 hover:text-slate-700 active:scale-90"
                  >
                    {showConfirmPassword ? (
                      <EyeOff size={16} />
                    ) : (
                      <Eye size={16} />
                    )}
                  </button>
                </div>
              </div>
            )}

            {/* Login Options */}
            {isLogin && (
              <div className="flex items-center justify-between gap-3 pt-0.5 text-[12px]">
                <label className="flex cursor-pointer items-center gap-1.5 text-slate-600">
                  <input
                    type="checkbox"
                    className="h-3.5 w-3.5 cursor-pointer rounded border-slate-300 accent-[#ef4444] transition-transform active:scale-90"
                  />
                  <span>Remember me</span>
                </label>

                <button
                  type="button"
                  className="font-semibold text-[#ef4444] transition-all hover:text-[#dc2626] hover:underline"
                >
                  Forgot password?
                </button>
              </div>
            )}

            {/* Terms */}
            {!isLogin && (
              <label className="flex cursor-pointer items-start gap-2 text-[12px] text-slate-600">
                <input
                  type="checkbox"
                  className="mt-0.5 h-3.5 w-3.5 shrink-0 cursor-pointer rounded border-slate-300 accent-[#ef4444] transition-transform active:scale-90"
                />

                <span>
                  I agree to the{" "}
                  <button
                    type="button"
                    className="font-semibold text-[#ef4444] transition-colors hover:text-[#dc2626] hover:underline"
                  >
                    Terms & Conditions
                  </button>
                </span>
              </label>
            )}

            {/* Primary Button */}
            <button
              type="submit"
              className="group relative flex h-11 w-full items-center justify-center gap-2 overflow-hidden rounded-lg bg-[#ef4444] px-4 text-[13px] font-bold text-white shadow-[0_8px_20px_rgba(239,68,68,0.18)] transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#dc3d3d] hover:shadow-[0_12px_25px_rgba(239,68,68,0.27)] active:translate-y-0 active:scale-[0.98]"
            >
              {/* Shine Animation */}
              <span className="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition-transform duration-700 group-hover:translate-x-full" />

              <Check
                size={15}
                strokeWidth={2.8}
                className="relative transition-transform duration-200 group-hover:scale-110"
              />

              <span className="relative">
                {isLogin ? "Log In" : "Create Account"}
              </span>
            </button>

            {/* Divider */}
            <div className="flex items-center gap-2.5 py-0.5">
              <span className="h-px flex-1 bg-slate-200" />

              <span className="text-[9px] font-medium uppercase tracking-[0.18em] text-slate-400">
                Or continue with
              </span>

              <span className="h-px flex-1 bg-slate-200" />
            </div>

            {/* Social Login */}
            <div className="grid grid-cols-2 gap-2.5">
              {/* Google */}
              <button
                type="button"
                className="group flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 text-[12px] font-semibold text-slate-700 transition-all duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 hover:shadow-[0_6px_16px_rgba(15,23,42,0.07)] active:translate-y-0 active:scale-[0.98]"
              >
                <span className="flex h-6 w-6 items-center justify-center rounded-full bg-white transition-transform duration-200 group-hover:scale-110">
                  <FaGoogle className="text-[14px] text-[#4285F4]" />
                </span>

                <span>Google</span>
              </button>

              {/* Facebook */}
              <button
                type="button"
                className="group flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 text-[12px] font-semibold text-slate-700 transition-all duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 hover:shadow-[0_6px_16px_rgba(15,23,42,0.07)] active:translate-y-0 active:scale-[0.98]"
              >
                <span className="flex h-6 w-6 items-center justify-center rounded-full bg-[#1877f2] transition-transform duration-200 group-hover:scale-110">
                  <FaFacebookF className="text-[12px] text-white" />
                </span>

                <span>Facebook</span>
              </button>
            </div>

            {/* Switch Auth Mode */}
            <div className="pt-0.5 text-center text-[12px] text-slate-500">
              {isLogin
                ? "Don't have an account?"
                : "Already have an account?"}{" "}
              <button
                type="button"
                onClick={() => setMode(isLogin ? "signup" : "login")}
                className="font-semibold text-[#ef4444] transition-all hover:text-[#dc2626] hover:underline"
              >
                {isLogin ? "Sign Up" : "Log In"}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>,
    document.body
  );
}
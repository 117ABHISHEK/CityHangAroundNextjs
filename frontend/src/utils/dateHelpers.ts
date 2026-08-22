export function formatDate(dateString: string): string {
  if (!dateString) return "";
  try {
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return dateString;
    return date.toLocaleDateString("en-IN", {
      day: "numeric",
      month: "short",
      year: "numeric",
    });
  } catch {
    return dateString;
  }
}

export function formatDayMonth(dateString: string): { day: string; month: string } {
  if (!dateString) return { day: "--", month: "---" };
  try {
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return { day: "--", month: "---" };
    return {
      day: date.getDate().toString().padStart(2, "0"),
      month: date.toLocaleDateString("en-IN", { month: "short" }).toUpperCase(),
    };
  } catch {
    return { day: "--", month: "---" };
  }
}

export function formatTime(timeString: string): string {
  return timeString || "";
}

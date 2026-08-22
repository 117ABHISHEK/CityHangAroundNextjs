import type { Metadata } from "next";
import EventHome from "@/src/features/events/eventHome";

export const metadata: Metadata = {
  title: "Events in Ahmedabad & Gujarat | CityHangAround",
  description:
    "Discover upcoming tech summits, food festivals, music concerts, and workshops happening in Ahmedabad and Gandhinagar.",
};

export default function EventsPage() {
  return <EventHome />;
}

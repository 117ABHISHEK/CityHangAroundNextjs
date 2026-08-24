import type { Metadata } from "next";
import CreateEvent from "@/src/features/events/createEvent";

export const metadata: Metadata = {
  title: "Publish New Event | CityHangAround",
  description:
    "List and publish your workshop, conference, festival, or meetup to reach thousands of attendees across the city.",
};

export default function CreateEventRoutePage() {
  return <CreateEvent />;
}

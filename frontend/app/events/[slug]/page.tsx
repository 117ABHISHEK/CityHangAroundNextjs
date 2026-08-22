import type { Metadata } from "next";
import { notFound } from "next/navigation";
import EventDetail from "@/src/features/events/eventDetail";
import { SAMPLE_EVENTS } from "@/src/types/event";

type Props = {
  params: Promise<{ slug: string }>;
};

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { slug } = await params;
  const event = SAMPLE_EVENTS.find((e) => e.slug === slug) || SAMPLE_EVENTS[0];

  return {
    title: `${event.name} | CityHangAround Events`,
    description: event.shortDescription,
    openGraph: {
      title: event.name,
      description: event.shortDescription,
      images: [event.coverImage],
    },
  };
}

export default async function EventDetailPage({ params }: Props) {
  const { slug } = await params;
  const event =
    SAMPLE_EVENTS.find((e) => e.slug === slug) ||
    SAMPLE_EVENTS.find((e) => e.id === slug) ||
    SAMPLE_EVENTS[0];

  if (!event) {
    notFound();
  }

  return <EventDetail event={event} />;
}

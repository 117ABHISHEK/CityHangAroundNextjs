export type EventStatusType = "upcoming" | "live" | "completed" | "cancelled";

export type EventFormat = "in-person" | "online" | "hybrid";

export type TicketTier = {
  id: string;
  name: string;
  price: number;
  quantity: number;
  description?: string;
  perks?: string[];
};

export type AgendaItem = {
  id: string;
  time: string;
  title: string;
  speaker?: string;
  description?: string;
};

export type EventFormData = {
  // Step 1: Basic Information
  name: string;
  parentCategory: string;
  category: string;
  tags: string[];
  shortDescription: string;
  coverImage: string | null;
  status: EventStatusType;

  // Step 2: Date & Location
  startDate: string;
  startTime: string;
  endDate: string;
  endTime: string;
  timezone: string;
  format: EventFormat;
  venueName: string;
  address: string;
  city: string;
  onlineMeetingUrl: string;

  // Step 3: Event Details & Tickets
  isFree: boolean;
  ticketTiers: TicketTier[];
  capacity: number;
  ageRestriction: string;

  // Step 4: Media & Images
  galleryImages: string[];
  videoUrl: string;

  // Step 5: Description & Schedule
  fullDescription: string;
  agenda: AgendaItem[];
  organizerName: string;
  organizerEmail: string;
  organizerPhone: string;
  terms: string;
};

export interface EventItem {
  id: string;
  slug: string;
  name: string;
  parentCategory: string;
  category: string;
  tags: string[];
  shortDescription: string;
  fullDescription: string;
  coverImage: string;
  galleryImages?: string[];
  status: EventStatusType;
  startDate: string;
  startTime: string;
  endDate: string;
  endTime: string;
  timezone: string;
  format: EventFormat;
  venueName: string;
  address: string;
  city: string;
  isFree: boolean;
  startingPrice: number;
  ticketTiers: TicketTier[];
  capacity: number;
  attendeesCount: number;
  organizerName: string;
  organizerAvatar?: string;
  organizerVerified?: boolean;
  agenda?: AgendaItem[];
  videoUrl?: string;
  featured?: boolean;
}

export const SAMPLE_EVENTS: EventItem[] = [
  {
    id: "evt-1",
    slug: "tech-summit-2026",
    name: "Tech Summit Gujarat 2026",
    parentCategory: "Technology",
    category: "Conferences & Summits",
    tags: ["AI", "Cloud", "Next.js", "Web3", "Startups"],
    shortDescription: "Join 1,500+ developers, tech leaders, and founders exploring the next decade of AI, cloud infrastructure, and modern web.",
    fullDescription: "Tech Summit Gujarat 2026 brings together the country's most passionate tech innovators, software engineers, and founders. Experience keynotes from Silicon Valley and Indian tech pioneers, hands-on developer workshops, pitch stages, and exclusive VIP networking dinners.\n\nWhether you're scaling an AI startup or refining your web architecture, this summit delivers actionable insights and connections that matter.",
    coverImage: "https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&auto=format&fit=crop&q=80",
    galleryImages: [
      "https://images.unsplash.com/photo-1511578314322-379afb476865?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&auto=format&fit=crop&q=80"
    ],
    status: "upcoming",
    startDate: "2026-08-28",
    startTime: "09:00 AM",
    endDate: "2026-08-29",
    endTime: "06:00 PM",
    timezone: "IST (UTC+5:30)",
    format: "in-person",
    venueName: "GIFT City Grand Convention Hall",
    address: "Block 12, GIFT City Road, Gandhinagar, Gujarat 382355",
    city: "Ahmedabad",
    isFree: false,
    startingPrice: 499,
    ticketTiers: [
      { id: "t1", name: "General Attendee Pass", price: 499, quantity: 500, description: "Access to all keynotes, breakout stages, expo hall, and coffee lounges.", perks: ["All-Stage Access", "Lunch & Refreshments", "Attendee Badge & Kit"] },
      { id: "t2", name: "Developer Pro Pass", price: 999, quantity: 200, description: "Includes hands-on AI & Web workshops and direct Q&A with speakers.", perks: ["All-Stage Access", "AI Workshop Pass", "Priority Seating", "Exclusive Swag Bag"] },
      { id: "t3", name: "VIP Executive Pass", price: 2499, quantity: 50, description: "VIP lounge access, speaker dinner invite, and 1-on-1 investor matchmaking.", perks: ["VIP Lounge", "Speaker Dinner", "Investor Matchmaking", "Dedicated Valet"] }
    ],
    capacity: 1500,
    attendeesCount: 840,
    organizerName: "Gujarat Tech Alliance",
    organizerAvatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80",
    organizerVerified: true,
    featured: true,
    agenda: [
      { id: "a1", time: "09:00 AM", title: "Keynote: The Sovereign AI Architecture", speaker: "Dr. Arvind Mehta (AI Research Director)", description: "How distributed AI models are reshaping regional enterprise infrastructure." },
      { id: "a2", time: "11:30 AM", title: "Building Hyper-Fast Realtime Web Apps", speaker: "Priya Sharma (Principal Engineer)", description: "Deep dive into server components, edge streams, and websocket state." },
      { id: "a3", time: "02:30 PM", title: "Founder Pitch Stage: Top 10 Indian Startups", speaker: "Panel of 5 Venture Partners", description: "Live pitch competition with $500k in non-dilutive cloud credits." }
    ]
  },
  {
    id: "evt-2",
    slug: "ahmedabad-food-festival-2026",
    name: "Ahmedabad Street & Gourmet Food Fest",
    parentCategory: "Food & Drinks",
    category: "Festivals & Fairs",
    tags: ["Foodie", "Live Music", "Street Food", "Family Friendly"],
    shortDescription: "Taste over 120+ iconic food stalls, artisanal desserts, fusion bites, and live indie band performances at Sabarmati Riverfront.",
    fullDescription: "Experience Gujarat's grandest food carnival! From legendary Manek Chowk midnight delicacies to gourmet sushi and artisanal sourdough, explore flavors curated by over 60 celebrity chefs and home-cook masters.",
    coverImage: "https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=1200&auto=format&fit=crop&q=80",
    status: "upcoming",
    startDate: "2026-09-05",
    startTime: "04:00 PM",
    endDate: "2026-09-07",
    endTime: "11:30 PM",
    timezone: "IST (UTC+5:30)",
    format: "in-person",
    venueName: "Riverfront Event Ground (East)",
    address: "Behind Ellis Bridge, Sabarmati Riverfront, Ahmedabad 380006",
    city: "Ahmedabad",
    isFree: false,
    startingPrice: 149,
    ticketTiers: [
      { id: "t1", name: "Day Pass", price: 149, quantity: 2000, description: "Entry to festival grounds + ₹50 food tasting coupon." },
      { id: "t2", name: "Weekend Tasting Pass (3 Days)", price: 349, quantity: 800, description: "Unlimited entry for all 3 days + ₹150 tasting credit." }
    ],
    capacity: 5000,
    attendeesCount: 3200,
    organizerName: "Amdavad Food Collective",
    organizerAvatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80",
    organizerVerified: true,
    featured: true
  },
  {
    id: "evt-3",
    slug: "sundowner-music-carnival",
    name: "Sunset Waves: Indie & Electronic Music Night",
    parentCategory: "Entertainment",
    category: "Concerts & Live Music",
    tags: ["Nightlife", "Electronic", "Indie", "Live DJ"],
    shortDescription: "An electrifying open-air musical evening featuring 6 top indie acts, DJ sets, laser shows, and craft cocktails.",
    fullDescription: "Dance under the stars with breathtaking open-sky acoustics, visual projections, food stalls, and mesmerizing sunset beats.",
    coverImage: "https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=1200&auto=format&fit=crop&q=80",
    status: "upcoming",
    startDate: "2026-09-12",
    startTime: "06:00 PM",
    endDate: "2026-09-12",
    endTime: "11:45 PM",
    timezone: "IST (UTC+5:30)",
    format: "in-person",
    venueName: "The Gulmohar Amphitheatre",
    address: "SG Highway, Near Iscon Circle, Ahmedabad 380054",
    city: "Ahmedabad",
    isFree: false,
    startingPrice: 699,
    ticketTiers: [
      { id: "t1", name: "Phase 1 Early Bird", price: 699, quantity: 400, description: "Standard entry to concert zone." },
      { id: "t2", name: "Fan Pit VIP", price: 1499, quantity: 150, description: "Front stage access + 2 complimentary drinks." }
    ],
    capacity: 1200,
    attendeesCount: 680,
    organizerName: "VibeCity Productions",
    organizerVerified: true,
    featured: true
  },
  {
    id: "evt-4",
    slug: "urban-photography-masterclass",
    name: "Heritage & Street Photography Walk",
    parentCategory: "Workshops",
    category: "Arts & Photography",
    tags: ["Photography", "Old City", "Heritage", "Workshop"],
    shortDescription: "A hands-on walking masterclass through Ahmedabad's UNESCO heritage pols with National Geographic contributor Rahul Dave.",
    fullDescription: "Capture light, architecture, and untold stories of Ahmedabad's centuries-old pols. Learn manual exposure control, street framing ethics, and mobile editing workflows.",
    coverImage: "https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=1200&auto=format&fit=crop&q=80",
    status: "upcoming",
    startDate: "2026-09-19",
    startTime: "06:30 AM",
    endDate: "2026-09-19",
    endTime: "10:30 AM",
    timezone: "IST (UTC+5:30)",
    format: "in-person",
    venueName: "Swaminarayan Temple, Kalupur",
    address: "Old City, Kalupur, Ahmedabad 380001",
    city: "Ahmedabad",
    isFree: false,
    startingPrice: 299,
    ticketTiers: [
      { id: "t1", name: "Masterclass Seat", price: 299, quantity: 25, description: "Includes guided photowalk, heritage breakfast, and 1-on-1 portfolio review." }
    ],
    capacity: 25,
    attendeesCount: 19,
    organizerName: "Heritage Photowalks Guild",
    organizerVerified: false,
    featured: false
  },
  {
    id: "evt-5",
    slug: "startup-founder-networking-mixer",
    name: "Founder & Angel Investor Breakfast Mixer",
    parentCategory: "Business",
    category: "Networking & Meetups",
    tags: ["Startups", "Founders", "Angel Investors", "Networking"],
    shortDescription: "Curated networking breakfast connecting 40 selected early-stage founders with active angel syndicates and mentors.",
    fullDescription: "High-density, low-noise networking over gourmet breakfast. Share learnings on PMF, fundraising, and hiring in India's booming tier-1 and tier-2 startup ecosystems.",
    coverImage: "https://images.unsplash.com/photo-1515187029135-18ee286d815b?w=1200&auto=format&fit=crop&q=80",
    status: "upcoming",
    startDate: "2026-09-24",
    startTime: "08:30 AM",
    endDate: "2026-09-24",
    endTime: "11:30 AM",
    timezone: "IST (UTC+5:30)",
    format: "in-person",
    venueName: "Roastery Coffee House",
    address: "Bodakdev, Sindhu Bhavan Marg, Ahmedabad 380054",
    city: "Ahmedabad",
    isFree: true,
    startingPrice: 0,
    ticketTiers: [
      { id: "t1", name: "Curated Founder Invite", price: 0, quantity: 40, description: "Application-based RSVP. Includes coffee and artisanal breakfast." }
    ],
    capacity: 40,
    attendeesCount: 36,
    organizerName: "SBR Founders Circle",
    organizerVerified: true,
    featured: false
  },
  {
    id: "evt-6",
    slug: "online-global-ai-hackathon",
    name: "Agentic AI 48-Hour Virtual Hackathon",
    parentCategory: "Technology",
    category: "Hackathons & Competitions",
    tags: ["Hackathon", "Virtual", "AI Agents", "Coding", "Prize Pool"],
    shortDescription: "Build next-generation autonomous AI workflows and agents. ₹2,50,000 prize pool and direct fast-track interviews.",
    fullDescription: "Global online hackathon dedicated to multi-agent architectures, MCP protocol, and autonomous developer workflows.",
    coverImage: "https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1200&auto=format&fit=crop&q=80",
    status: "live",
    startDate: "2026-08-22",
    startTime: "10:00 AM",
    endDate: "2026-08-24",
    endTime: "10:00 AM",
    timezone: "IST (UTC+5:30)",
    format: "online",
    venueName: "Online (Discord & Google Meet)",
    address: "Global Virtual",
    city: "Online",
    isFree: true,
    startingPrice: 0,
    ticketTiers: [
      { id: "t1", name: "Hacker Team Pass (1-4 members)", price: 0, quantity: 1000, description: "Free team registration with cloud compute API keys provided." }
    ],
    capacity: 2000,
    attendeesCount: 1420,
    organizerName: "Antigravity Open Lab",
    organizerVerified: true,
    featured: true
  }
];

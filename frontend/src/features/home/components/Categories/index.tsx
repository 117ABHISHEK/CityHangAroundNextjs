import {
  BusinessIcon,
  CalendarIcon,
  CityGuideIcon,
  CommunityIcon,
  FavoritesIcon,
  MarketplaceIcon,
  ShoppingIcon,
  StarIcon,
} from "@/src/components/ui/icons";
import type { LucideIcon } from "@/src/components/ui/icons";

const categories: [LucideIcon, string, string, string, string[]][] = [
  [CityGuideIcon, "Food & Dining", "1.8K+ Places", "Restaurants, Cafes, Bakeries & Street Food", ["Cafes", "Pizzas", "Buffets"]],
  [FavoritesIcon, "Health & Medical", "620+ Doctors", "Hospitals, Clinics, Dental & Diagnostic Labs", ["Doctors", "Clinics", "Gyms"]],
  [BusinessIcon, "Education", "850+ Institutes", "Schools, Colleges, Coaching & Skill Tutors", ["Colleges", "Classes", "Skill IT"]],
  [ShoppingIcon, "Shopping & Retail", "2.4K+ Stores", "Fashion Boutiques, Electronics & Malls", ["Apparel", "Malls", "Gadgets"]],
  [CalendarIcon, "Events & Nightlife", "290+ Live", "Concerts, Shows, Workshops & Meetups", ["Music", "Nightlife", "Sports"]],
  [CommunityIcon, "City Community", "50+ Groups", "Discussion Forums, Local Groups & Q&A", ["Foodies", "Jobs", "Real Estate"]],
  [MarketplaceIcon, "Trending & Deals", "Hot Deals", "Hyperlocal Offers, Discounts & Top Places", ["50% Off", "Top Rated", "Offers"]],
  [StarIcon, "Auto & Car Repair", "410+ Garages", "Auto Service, Garages, Car Wash & Parts", ["Garages", "Wash", "EV Repair"]],
];

export default function Categories() {
  return (
    <section className="home-section home-categories"><div className="home-container"><div className="home-section__heading"><p className="home-eyebrow">Find your next favorite</p><h2>What Are You <span>Looking</span> For Today?</h2><p>Choose a category to find trusted local businesses.</p></div><div className="home-category-grid">{categories.map(([Icon, title, badge, description, pills]) => <article key={title as string} className="home-category"><div className="home-category__top"><span className="home-category__icon"><Icon size={22} /></span><small>{badge as string}</small></div><h3>{title as string}</h3><p>{description as string}</p><div className="home-category__pills">{(pills as string[]).map((pill) => <span key={pill}>{pill}</span>)}</div><a href="#">Explore Category <span>→</span></a></article>)}</div></div></section>
  );
}

import {
  BusinessIcon,
  CategoriesIcon,
  CityGuideIcon,
  LocationIcon,
  SearchIcon,
  CommunityIcon,
} from "@/src/components/ui/icons";
import HeroBackdrop from "../HeroBackdrop";
import "./index.css";

const stats = [
  { value: "5,000+", label: "Businesses Listed", icon: BusinessIcon },
  { value: "10,000+", label: "Happy Users", icon: CommunityIcon },
  { value: "350+", label: "Cities Covered", icon: LocationIcon },
  { value: "20+", label: "Categories", icon: CategoriesIcon },
];

export default function HeroSection() {
  return (
    <section className="home-hero">
      <HeroBackdrop />
      <div className="home-container home-hero__content">
        <div className="home-hero__copy">
          <p className="home-eyebrow">Local discovery</p>
          <h1>Find the Best Local Deals, Businesses &amp; Events<span>All in One Place</span></h1>
          <p className="home-hero__description">Join thousands of people discovering what&apos;s happening around them every day.</p>
          <div className="home-hero__search">
            <label className="home-hero__field">
              <LocationIcon size={17} aria-hidden="true" />
              <span className="sr-only">Search city, area or location</span>
              <input type="search" placeholder="Search city, area or location" />
            </label>
            <label className="home-hero__field">
              <CityGuideIcon size={17} aria-hidden="true" />
              <span className="sr-only">Search businesses and events</span>
              <input type="search" placeholder="Search for restaurants, services, events..." />
            </label>
            <button type="button" className="home-button home-button--search"><SearchIcon size={16} aria-hidden="true" />Search</button>
          </div>
          <div className="home-hero__actions">
            <button type="button" className="home-button home-button--primary">Start Exploring Free</button>
            <button type="button" className="home-button home-button--outline">List Your Business &amp; Get Leads</button>
          </div>
          <div className="home-stats">
            {stats.map(({ value, label, icon: Icon }) => (
              <div key={label} className="home-stat">
                <span className="home-stat__icon"><Icon size={17} aria-hidden="true" /></span>
                <span><strong className="home-stat__value">{value}</strong><span className="home-stat__label">{label}</span></span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}

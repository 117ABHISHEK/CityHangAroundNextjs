import {
  BusinessIcon,
  CalendarIcon,
  ChartIcon,
  CommunityIcon,
  ShoppingIcon,
  ShieldIcon,
  StarIcon,
  MarketplaceIcon,
} from "@/src/components/ui/icons";
import "./index.css";

const highlights = [
  [BusinessIcon, "Add Business", "Get listed and reach local customers"],
  [MarketplaceIcon, "Deals & Offers", "Promote offers and attract more customers"],
  [CalendarIcon, "Events", "Create and promote events in your city"],
  [StarIcon, "Reviews & Ratings", "Build trust with genuine ratings"],
  [CommunityIcon, "Community", "Connect with local customers"],
  [ShieldIcon, "Verified Listing", "Build credibility with a trusted badge"],
  [ChartIcon, "Analytics", "Track views, leads and customer insights"],
  [ShoppingIcon, "Promotions", "Promote your business with smart tools"],
];

export default function HomeHighlights() {
  return (
    <section className="home-section home-highlights">
      <div className="home-container">
        <div className="home-section__heading"><p className="home-eyebrow">Built to help you grow</p><h2>Everything You Need to <span>Grow</span> Your Business</h2><p>One simple dashboard to manage your listing, engage customers, and track results.</p></div>
        <div className="home-highlight-grid">
          {highlights.map(([Icon, title, description]) => <article key={title as string} className="home-highlight"><span className="home-highlight__icon"><Icon size={21} /></span><div><h3>{title as string}</h3><p>{description as string}</p></div></article>)}
        </div>
        <div className="home-centered"><button type="button" className="home-button home-button--outline home-button--outline-dark">See How It Works <ChartIcon size={16} /></button></div>
      </div>
    </section>
  );
}

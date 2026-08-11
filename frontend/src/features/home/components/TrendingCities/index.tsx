import { ChevronLeft, ChevronRight } from "@/src/components/ui/icons";

const cities = [
  ["Kolkata", "1200+ Listings", "Popular Now"],
  ["Delhi", "1050+ Listings", ""],
  ["Mumbai", "1000+ Listings", "Hot"],
  ["Bangalore", "950+ Listings", ""],
  ["Hyderabad", "800+ Listings", ""],
  ["Pune", "700+ Listings", ""],
];

export default function TrendingCities() {
  return (
    <section className="home-section home-cities">
      <div className="home-container">
        <div className="home-section__heading"><p className="home-eyebrow">Explore locally</p><h2>Top Cities <span>Trending</span> This Week</h2><p>See where people are discovering new restaurants, events &amp; offers.</p></div>
        <div className="home-cities__track-wrap"><button type="button" className="home-cities__arrow" aria-label="Previous cities"><ChevronLeft size={18} /></button><div className="home-cities__track">{cities.map(([name, count, badge]) => <article key={name} className="home-city-card"><div className="home-city-card__art" /><div className="home-city-card__content">{badge && <span>{badge}</span>}<h3>{name}</h3><p>{count}</p></div></article>)}</div><button type="button" className="home-cities__arrow" aria-label="Next cities"><ChevronRight size={18} /></button></div>
        <div className="home-centered"><button type="button" className="home-button home-button--outline home-button--outline-dark">View All Cities</button></div>
      </div>
    </section>
  );
}

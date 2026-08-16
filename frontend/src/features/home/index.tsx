import HeroSection from "./components/HeroSection";
import TrendingCities from "./components/TrendingCities";
import Categories from "./components/Categories";
import BusinessGrowth from "./components/BusinessGrowth";
import HomeHighlights from "./components/HomeHighlights";
import TrustAndFaq from "./components/TrustAndFaq";
import "./base.css";

export default function HomeFeature() {
  return (
    <main className="home-page">
      <HeroSection />
      <TrendingCities />
      <Categories />
      <BusinessGrowth />
      <HomeHighlights />
      <TrustAndFaq />
    </main>
  );
}

import HeroSection from "./components/HeroSection";
import TrendingCities from "./components/TrendingCities";
import Categories from "./components/Categories";
import SecondarySection from "./components/SecondarySection";

export default function HomeFeature() {
  return (
    <main>
      <HeroSection />
      <TrendingCities />
      <Categories />
      <SecondarySection />
    </main>
  );
}

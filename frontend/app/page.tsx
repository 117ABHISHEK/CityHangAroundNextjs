import HeroSection from "@/src/components/HeroSection";
import Topbar from "@/src/components/Topbar";
import Navbar from "@/src/components/Navbar";
import TrendingCities from "@/src/components/TrendingCities";
import Categories from "@/src/components/Categories";
import SecondarySection from "@/src/components/SecondarySection";

export default function Home() {
  return (
    <div className="min-h-screen bg-slate-50 text-slate-950">
      <Topbar />
      <Navbar />
      <main>
        <HeroSection />
        <TrendingCities />
        <Categories />
        <SecondarySection />
      </main>
    </div>
  );
}

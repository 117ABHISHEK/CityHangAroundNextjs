import Topbar from "@/src/components/layout/Topbar/index";
import Navbar from "@/src/components/layout/Navbar/index";
import HomeFeature from "@/src/features/home";

export default function Home() {
  return (
    <div className="min-h-screen bg-slate-50 text-slate-950">
      <Topbar />
      <Navbar />
      <HomeFeature />
    </div>
  );
}

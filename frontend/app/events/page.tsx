import { BannerAd, SquareAd } from "@/src/components/shared/Ads";

export default function EventsPage() {
  return (
    <div className="max-w-4xl mx-auto p-10">
      <h1 className="text-3xl font-bold mb-6 text-slate-900">Events in Ahmedabad</h1>
      <p className="text-slate-600 mb-8">
        Discover upcoming events, workshops, networking meetups, and local celebrations in the city.
      </p>

      {/* Example Horizontal Banner Ad placement */}
      <div className="my-8">
        <BannerAd slotId="1234567890" />
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div className="md:col-span-2 space-y-4">
          <div className="border border-slate-200 rounded-lg p-6 bg-white shadow-sm">
            <h2 className="text-xl font-semibold text-slate-800">Tech Summit 2026</h2>
            <p className="text-sm text-slate-400 font-medium">Aug 28 • GIFT City</p>
            <p className="text-slate-600 mt-2">
              Join leading experts and developers to discuss next-generation web technologies, cloud computing, and AI systems.
            </p>
          </div>
          
          <div className="border border-slate-200 rounded-lg p-6 bg-white shadow-sm">
            <h2 className="text-xl font-semibold text-slate-800">Ahmedabad Food Festival</h2>
            <p className="text-sm text-slate-400 font-medium">Sep 05 • Riverfront Park</p>
            <p className="text-slate-600 mt-2">
              Experience the best cuisines, food trucks, chef masterclasses, and live musical performances.
            </p>
          </div>
        </div>

        <div className="space-y-6">
          <div className="bg-slate-50 border border-slate-200 rounded-lg p-4">
            <h3 className="font-semibold text-slate-700 mb-2">Sponsored Link</h3>
            
            {/* Example Square Ad in sidebar column */}
            <SquareAd slotId="0987654321" />
          </div>
        </div>
      </div>
    </div>
  );
}

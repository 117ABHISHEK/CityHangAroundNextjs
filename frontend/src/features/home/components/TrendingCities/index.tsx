const cities = [
  { name: 'Kolkata', count: '1200+ Listings', badge: 'Popular Now' },
  { name: 'Delhi', count: '1050+ Listings' },
  { name: 'Mumbai', count: '1000+ Listings', badge: 'Hot' },
  { name: 'Bangalore', count: '950+ Listings' },
  { name: 'Hyderabad', count: '800+ Listings' },
  { name: 'Pune', count: '700+ Listings' },
];

export default function TrendingCities() {
  return (
    <section className="py-20 bg-white">
      <div className="container mx-auto px-6">
        <div className="section-head text-center max-w-2xl mx-auto mb-12">
          <h2 className="text-3xl font-extrabold text-slate-950 sm:text-4xl">
            Top Cities <span className="text-red-600">Trending</span> This Week
          </h2>
          <p className="mt-4 text-base text-slate-500">
            See where people are discovering new restaurants, events & offers.
          </p>
        </div>

        <div className="relative">
          <div className="flex items-center justify-between gap-3 overflow-hidden">
            <button className="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-700 shadow-sm hover:bg-slate-50">←</button>
            <div className="grid flex-1 grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
              {cities.map((city) => (
                <div key={city.name} className="overflow-hidden rounded-[28px] border border-slate-200 bg-gradient-to-br from-slate-950 to-slate-800 text-white shadow-xl">
                  <div className="p-6">
                    <div className="mb-4 rounded-3xl bg-white/10 p-4 text-sm font-semibold text-white opacity-90">{city.badge ?? 'Featured'}</div>
                    <div className="text-2xl font-bold">{city.name}</div>
                    <div className="mt-2 text-sm text-slate-200">{city.count}</div>
                  </div>
                </div>
              ))}
            </div>
            <button className="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-700 shadow-sm hover:bg-slate-50">→</button>
          </div>

          <div className="mt-6 text-center">
            <button className="rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:border-red-600 hover:text-red-600">
              View All Cities
            </button>
          </div>
        </div>
      </div>
    </section>
  );
}

export default function HeroSection() {
  return (
    <section className="relative overflow-hidden bg-white">
      <div className="absolute inset-x-0 top-0 -z-10 h-[420px] bg-gradient-to-br from-red-50 via-white to-white" />
      <div className="absolute left-1/2 top-10 -z-10 h-48 w-48 -translate-x-1/2 rounded-full bg-orange-200/40 blur-3xl" />
      <div className="container mx-auto px-6 py-20">
        <div className="grid gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
          <div className="max-w-2xl">
            <p className="text-sm font-semibold uppercase tracking-[0.24em] text-red-600">Local discovery</p>
            <h1 className="mt-6 text-4xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">
              Find the Best Local Deals, Businesses & Events <span className="text-red-600">All in One Place</span>
            </h1>
            <p className="mt-6 max-w-xl text-base leading-8 text-slate-600">
              Join thousands of people discovering what&apos;s happening around them every day.
            </p>
            <div className="mt-10 space-y-4 rounded-[32px] border border-slate-200 bg-slate-50 p-5 shadow-sm sm:space-y-0 sm:flex sm:items-center sm:gap-3">
              <div className="flex flex-1 items-center gap-3 rounded-3xl bg-white px-4 py-3 shadow-sm">
                <span>📍</span>
                <input type="text" placeholder="Search city, area or location" className="w-full bg-transparent text-sm outline-none" />
              </div>
              <div className="flex flex-1 items-center gap-3 rounded-3xl bg-white px-4 py-3 shadow-sm">
                <span>🗂️</span>
                <input type="text" placeholder="Search for restaurants, services, events..." className="w-full bg-transparent text-sm outline-none" />
              </div>
              <button className="shrink-0 rounded-3xl bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-red-200/30 hover:bg-red-700">
                🔎 Search
              </button>
            </div>
            <div className="mt-10 flex flex-col gap-3 sm:flex-row">
              <button className="rounded-3xl bg-red-600 px-6 py-3 text-sm font-semibold text-white hover:bg-red-700">Start Exploring Free</button>
              <button className="rounded-3xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:border-red-600 hover:text-red-600">List Your Business & Get Leads</button>
            </div>
            <div className="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
              {[
                { label: 'Businesses Listed', value: '5,000+' },
                { label: 'Happy Users', value: '10,000+' },
                { label: 'Cities Covered', value: '350+' },
                { label: 'Categories', value: '20+' },
              ].map((item) => (
                <div key={item.label} className="rounded-3xl border border-slate-200 bg-white px-5 py-5 text-center shadow-sm">
                  <div className="text-2xl font-bold text-slate-950">{item.value}</div>
                  <div className="mt-2 text-sm text-slate-500">{item.label}</div>
                </div>
              ))}
            </div>
          </div>

          <div className="relative overflow-hidden rounded-[32px] bg-gradient-to-br from-red-50 via-white to-white p-6 shadow-xl">
            <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(254,231,200,0.7),_transparent_40%)]" />
            <div className="relative rounded-[32px] bg-white p-8 shadow-2xl">
              <div className="mb-6 h-[360px] rounded-[28px] bg-slate-900 p-4 text-white">
                <div className="mb-4 flex items-center justify-between text-sm text-slate-300">
                  <span>CityHangaround</span>
                  <span>Live Now</span>
                </div>
                <div className="h-full rounded-[24px] bg-gradient-to-b from-red-500 to-orange-300"></div>
              </div>
              <div className="flex flex-wrap gap-3 text-sm text-slate-600">
                <span className="rounded-full bg-slate-100 px-4 py-2">Popular Pick</span>
                <span className="rounded-full bg-slate-100 px-4 py-2">Top Rated</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

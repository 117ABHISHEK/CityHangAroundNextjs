export default function SecondarySection() {
  return (
    <section className="py-20 bg-white">
      <div className="container mx-auto px-6">
        <div className="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
          <div className="rounded-[32px] bg-slate-950 p-8 text-white shadow-2xl">
            <div className="inline-flex rounded-3xl bg-red-600/15 px-4 py-2 text-sm font-semibold text-red-200">Get Found</div>
            <h2 className="mt-6 text-3xl font-extrabold tracking-tight">
              Get <span className="text-red-400">Found</span> by People in Your City
            </h2>
            <p className="mt-4 text-base leading-8 text-slate-200">
              We help small businesses like yours get more visibility, leads & revenue — without spending big.
            </p>
            <div className="mt-10 grid gap-4 sm:grid-cols-3">
              {[
                { step: '1', title: 'List Your Business', desc: 'Create your free listing in minutes.' },
                { step: '2', title: 'Get Seen by Local Customers', desc: 'People discover you when they search.' },
                { step: '3', title: 'Grow Revenue & Build Reputation', desc: 'Get more leads, calls and customers.' },
              ].map((item) => (
                <div key={item.step} className="rounded-3xl bg-slate-900/80 p-6">
                  <div className="flex h-12 w-12 items-center justify-center rounded-3xl bg-red-600 text-lg font-bold text-white">{item.step}</div>
                  <h3 className="mt-4 text-lg font-semibold text-white">{item.title}</h3>
                  <p className="mt-2 text-sm text-slate-300">{item.desc}</p>
                </div>
              ))}
            </div>
            <button className="mt-10 rounded-3xl bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-red-200/20 hover:bg-red-700">
              Start Your Free Listing Today
            </button>
          </div>
          <div className="relative rounded-[32px] bg-red-50 p-8 shadow-xl">
            <div className="h-[420px] rounded-[32px] bg-slate-950" />
            <div className="absolute bottom-10 left-10 rounded-3xl bg-white p-5 shadow-xl">
              <div className="text-sm font-semibold text-slate-900">Avg 47% more leads</div>
              <div className="text-xs text-slate-500">in 30 days</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

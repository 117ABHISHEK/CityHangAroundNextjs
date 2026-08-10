import styles from "./index.module.css";

export default function Navbar() {
  return (
    <div className={`${styles.navbar} sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200`}>
      <div className="container mx-auto flex flex-col gap-4 px-6 py-4 md:flex-row md:items-center md:justify-between">
        <div className="flex items-center gap-4">
          <div className="flex items-center justify-center w-11 h-11 rounded-2xl bg-red-600 text-white text-lg font-bold">C</div>
          <div>
            <div className="text-sm font-bold text-slate-950">CityHangaround</div>
            <div className="text-xs text-slate-500">Find local deals, businesses & events</div>
          </div>
        </div>

        <div className="flex flex-1 flex-col gap-3 md:flex-row md:items-center md:justify-between">
          <div className="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-2 shadow-sm md:max-w-2xl">
            <div className="flex items-center gap-2 rounded-2xl bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm">
              <span>📂</span>
              All Categories
            </div>
            <div className="flex flex-1 items-center gap-2 px-3 text-sm text-slate-500">
              <span>🔎</span>
              <input
                type="text"
                placeholder="Search for restaurants, services, events..."
                className="w-full bg-transparent outline-none"
              />
            </div>
            <button className="rounded-2xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
              Search
            </button>
          </div>

          <div className="flex flex-wrap items-center gap-3 justify-end">
            <a href="#" className="text-sm font-semibold text-slate-700 hover:text-red-600">❤️ Favorites</a>
            <a href="#" className="text-sm font-semibold text-slate-700 hover:text-red-600">👤 Login</a>
            <button className="rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700">
              + Add Business
            </button>
          </div>
        </div>
      </div>

      <div className="border-t border-slate-200 bg-white shadow-sm">
        <div className="container mx-auto flex items-center gap-4 overflow-x-auto px-6 py-3 text-sm text-slate-600">
          {['Home', 'City Guide', 'Buy/Sell', 'Marketplace', 'Community', 'Blog', 'Contact'].map((label, index) => (
            <a
              key={label}
              href="#"
              className={`flex items-center gap-2 rounded-full px-3 py-2 transition hover:bg-slate-100 ${index === 0 ? 'text-red-600 font-semibold' : ''}`}
            >
              {index === 0 ? '🏠' : index === 1 ? '🧭' : index === 2 ? '🛒' : index === 3 ? '🏷️' : index === 4 ? '👥' : index === 5 ? '📰' : '✉️'}
              {label}
            </a>
          ))}
        </div>
      </div>
    </div>
  );
}

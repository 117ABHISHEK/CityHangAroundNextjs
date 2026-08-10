import styles from "./index.module.css";

export default function Topbar() {
  return (
    <div className={`${styles.topbar} bg-slate-50 border-b border-slate-200 text-slate-500 text-[13px]`}>
      <div className="container mx-auto flex items-center justify-between h-9 px-6">
        <div className="flex items-center gap-4">
          <a href="#" className="flex items-center gap-1 hover:text-red-600">
            <span>📍</span>
            <span>Select City</span>
          </a>
        </div>
        <div className="flex items-center gap-4">
          <a href="#" className="flex items-center gap-1 hover:text-red-600">🏙️ For Businesses</a>
          <a href="#" className="flex items-center gap-1 text-red-600 font-semibold">📣 Advertise</a>
          <a href="#" className="flex items-center gap-1">⬇️ Download App</a>
          <a href="#" className="flex items-center gap-1">❓ Help & Support</a>
        </div>
      </div>
    </div>
  );
}

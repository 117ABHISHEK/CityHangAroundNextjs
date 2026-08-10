export default function Topbar() {
  return (
    <div className="border-b border-slate-200 bg-slate-50 text-[13px] text-slate-500">
      <div className="container mx-auto flex h-9 items-center justify-between px-6">
        <a href="#" className="flex items-center gap-1 hover:text-red-600">
          <span>📍</span>
          <span>Select City</span>
        </a>
        <div className="flex items-center gap-4">
          <a href="#" className="hover:text-red-600">🏙️ For Businesses</a>
          <a href="#" className="font-semibold text-red-600">📣 Advertise</a>
          <a href="#">⬇️ Download App</a>
          <a href="#">❓ Help & Support</a>
        </div>
      </div>
    </div>
  );
}

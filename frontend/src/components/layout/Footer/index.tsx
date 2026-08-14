import {
  FacebookIcon,
  InstagramIcon,
  LinkedInIcon,
  XIcon,
} from "@/src/components/ui/icons";

const socialLinks = [
  {
    label: "Facebook",
    href: "#",
    icon: FacebookIcon,
    hoverClass: "hover:border-[#1877F2] hover:bg-[#1877F2]",
  },
  {
    label: "Instagram",
    href: "#",
    icon: InstagramIcon,
    hoverClass:
      "hover:border-transparent hover:bg-gradient-to-br hover:from-[#f09433] hover:via-[#e6683c] hover:to-[#bc1888]",
  },
  {
    label: "X",
    href: "#",
    icon: XIcon,
    hoverClass: "hover:border-[#333333] hover:bg-[#111111]",
  },
  {
    label: "LinkedIn",
    href: "#",
    icon: LinkedInIcon,
    hoverClass: "hover:border-[#0A66C2] hover:bg-[#0A66C2]",
  },
];

export default function Footer() {
  return (
    <footer className="bg-slate-950 text-slate-100">
      <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="grid gap-12 lg:grid-cols-4">
          <div className="space-y-5">
            <div className="font-semibold text-white">CityHangaround</div>
            <p className="max-w-sm text-sm text-slate-400">
              Helping people discover the best local businesses, deals and events — and
              helping businesses get found.
            </p>
            <div className="flex items-center gap-3">
              {socialLinks.map(({ label, href, icon: Icon, hoverClass }) => (
                <a
                  key={label}
                  href={href}
                  aria-label={label}
                  className={`group relative flex h-9 w-9 items-center justify-center rounded-full border border-slate-700 bg-white/5 text-slate-300 transition-all duration-300 ease-out hover:-translate-y-1 hover:scale-110 hover:shadow-[0_8px_20px_rgba(0,0,0,0.25)] ${hoverClass}`}
                >
                  <Icon className="text-base transition-transform duration-300 group-hover:scale-110 group-hover:text-white" aria-hidden="true" />
                </a>
              ))}
            </div>
          </div>

          <div>
            <h3 className="mb-5 text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">
              Explore
            </h3>
            <ul className="space-y-3 text-sm text-slate-300">
              <li>
                <a href="#" className="hover:text-white">Top Cities</a>
              </li>
              <li>
                <a href="#" className="hover:text-white">Categories</a>
              </li>
              <li>
                <a href="#" className="hover:text-white">Deals</a>
              </li>
              <li>
                <a href="#" className="hover:text-white">Events</a>
              </li>
            </ul>
          </div>

          <div>
            <h3 className="mb-5 text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">
              Company
            </h3>
            <ul className="space-y-3 text-sm text-slate-300">
              <li>
                <a href="#" className="hover:text-white">About Us</a>
              </li>
              <li>
                <a href="#" className="hover:text-white">Careers</a>
              </li>
              <li>
                <a href="#" className="hover:text-white">Blog</a>
              </li>
              <li>
                <a href="#" className="hover:text-white">Contact</a>
              </li>
            </ul>
          </div>

          <div>
            <h3 className="mb-5 text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">
              For Businesses
            </h3>
            <ul className="space-y-3 text-sm text-slate-300">
              <li>
                <a href="#" className="hover:text-white">List Your Business</a>
              </li>
              <li>
                <a href="#" className="hover:text-white">Advertise With Us</a>
              </li>
              <li>
                <a href="#" className="hover:text-white">Business Dashboard</a>
              </li>
              <li>
                <a href="#" className="hover:text-white">Success Stories</a>
              </li>
            </ul>
          </div>
        </div>

        <div className="mt-12 border-t border-slate-800 pt-8 text-sm text-slate-500 sm:flex sm:items-center sm:justify-between">
          <p>© 2026 CityHangaround. All rights reserved.</p>
          <div className="mt-4 flex flex-wrap gap-4 sm:mt-0">
            <a href="#" className="hover:text-white">Privacy Policy</a>
            <a href="#" className="hover:text-white">Terms of Service</a>
            <a href="#" className="hover:text-white">Sitemap</a>
          </div>
        </div>
      </div>
    </footer>
  );
}

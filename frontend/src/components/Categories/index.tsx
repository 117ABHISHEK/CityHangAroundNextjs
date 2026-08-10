import styles from "./index.module.css";

const categories = [
  {
    title: 'Food & Dining',
    badge: '1.8K+ Places',
    desc: 'Restaurants, Cafes, Bakeries & Street Food',
    pills: ['Cafes', 'Pizzas', 'Buffets'],
  },
  {
    title: 'Health & Medical',
    badge: '620+ Doctors',
    desc: 'Hospitals, Clinics, Dental & Diagnostic Labs',
    pills: ['Doctors', 'Clinics', 'Gyms'],
  },
  {
    title: 'Education',
    badge: '850+ Institutes',
    desc: 'Schools, Colleges, Coaching & Skill Tutors',
    pills: ['Colleges', 'Classes', 'Skill IT'],
  },
  {
    title: 'Auto & Car Repair',
    badge: '410+ Garages',
    desc: 'Auto Service, Garages, Car Wash & Parts',
    pills: ['Garages', 'Wash', 'EV Repair'],
  },
  {
    title: 'Shopping & Retail',
    badge: '2.4K+ Stores',
    desc: 'Fashion Boutiques, Electronics & Malls',
    pills: ['Apparel', 'Malls', 'Gadgets'],
  },
  {
    title: 'Events & Nightlife',
    badge: '290+ Live',
    desc: 'Concerts, Shows, Workshops & Meetups',
    pills: ['Music', 'Nightlife', 'Sports'],
  },
  {
    title: 'City Community',
    badge: '50+ Groups',
    desc: 'Discussion Forums, Local Groups & Q&A',
    pills: ['Foodies', 'Jobs', 'Real Estate'],
  },
  {
    title: 'Trending & Deals',
    badge: 'Hot Deals 🔥',
    desc: 'Hyperlocal Offers, Discounts & Top Places',
    pills: ['50% Off', 'Top Rated', 'Offers'],
  },
];

export default function Categories() {
  return (
    <section className={`${styles.categoriesSection} py-20 bg-slate-50`}>
      <div className="container mx-auto px-6">
        <div className="section-head text-center max-w-2xl mx-auto mb-12">
          <h2 className="text-3xl font-extrabold text-slate-950 sm:text-4xl">
            What Are You <span className="text-red-600">Looking</span> For Today?
          </h2>
          <p className="mt-4 text-base text-slate-500">
            Choose a category to find trusted local businesses.
          </p>
        </div>

        <div className="grid gap-6 lg:grid-cols-2 xl:grid-cols-4">
          {categories.map((category) => (
            <div key={category.title} className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
              <div className="mb-4 flex items-center justify-between gap-4">
                <div className="rounded-3xl bg-red-50 px-3 py-3 text-red-600">🏙️</div>
                <span className="rounded-full bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">
                  {category.badge}
                </span>
              </div>
              <h3 className="text-xl font-semibold text-slate-950">{category.title}</h3>
              <p className="mt-3 text-sm text-slate-500">{category.desc}</p>
              <div className="mt-4 flex flex-wrap gap-2">
                {category.pills.map((pill) => (
                  <span key={pill} className="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                    {pill}
                  </span>
                ))}
              </div>
              <div className="mt-6 text-sm font-semibold text-red-600">Explore Category →</div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

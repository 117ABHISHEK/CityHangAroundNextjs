import { ArrowRightIcon, CheckIcon, ChevronDown, LockIcon, ShieldIcon, StarIcon, SupportIcon } from "@/src/components/ui/icons";
import "./index.css";

const faqs = [
  ["How can I get free leads for my business?", "List your business for free and start appearing in local searches."],
  ["Is CityHangaround available in my city?", "We currently cover 350+ cities and are adding new cities every month."],
  ["How do I promote my listing?", "Use Deals, Events and Promotions to boost visibility near you."],
  ["Can I track views and leads?", "Yes. Analytics shows profile views, leads and customer engagement."],
];

export default function TrustAndFaq() {
  return (
    <>
      <section className="home-section home-trust">
        <div className="home-container home-trust__grid">
          <div><p className="home-eyebrow">Trusted locally</p><h2>Why Local Businesses Choose <span>CityHangaround</span></h2><p className="home-section__intro">Grow with our high-traffic local network built to connect small businesses with real nearby customers.</p><div className="home-trust__stats"><strong>10,000+<small>Daily Visitors</small></strong><strong>5,000+<small>Businesses Listed</small></strong><strong>350+<small>Cities Covered</small></strong></div><button type="button" className="home-button home-button--primary">Join Free <ArrowRightIcon size={16} /></button><div className="home-trust-list"><span><LockIcon size={15} /> Secure payments</span><span><ShieldIcon size={15} /> Verified listings</span><span><SupportIcon size={15} /> 24/7 support</span></div></div>
          <div className="home-trust__illustration"><div /><div /><div /></div>
        </div>
      </section>

      <section className="home-section home-testimonials">
        <div className="home-container home-testimonials__grid"><div><h2>Trusted by Local Businesses &amp; Partners Nationwide</h2><div className="home-partners"><span>Zomato</span><span>paytm</span><span>Uber</span><span>MakeMyTrip</span><span>OYO</span><span>cleartrip</span></div></div><div><h2>What Local Business Owners Say</h2><article className="home-testimonial"><div className="home-stars"><StarIcon size={14} fill="currentColor" /><StarIcon size={14} fill="currentColor" /><StarIcon size={14} fill="currentColor" /><StarIcon size={14} fill="currentColor" /><StarIcon size={14} fill="currentColor" /></div><p>CityHangaround helped us get more local customers than any other platform we tried.</p><strong>Ravi Sharma</strong><small>Café Owner, Kolkata</small></article></div></div>
      </section>

      <section className="home-section home-faq"><div className="home-container home-faq__grid"><div><p className="home-eyebrow">Need to know</p><h2>Frequently Asked Questions</h2><div className="home-faq__list">{faqs.map(([question, answer]) => <details key={question}><summary>{question}<ChevronDown size={16} /></summary><p>{answer}</p></details>)}</div></div><div className="home-cta"><h2>Ready to Grow Your Business?</h2><p>Get listed in minutes and connect with thousands of local customers. Free forever.</p><button type="button" className="home-button home-button--light">List Your Business Free Now <CheckIcon size={16} /></button></div></div></section>
    </>
  );
}

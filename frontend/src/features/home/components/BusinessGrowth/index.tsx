import {
  ArrowRightIcon,
  CalendarIcon,
  ChartIcon,
  LocationIcon,
  ShieldIcon,
  StarIcon,
  SupportIcon,
  CommunityIcon,
} from "@/src/components/ui/icons";

const steps = [
  ["1", "List Your Business", "Create your free listing in minutes."],
  ["2", "Get Seen by Local Customers", "People discover you when they search."],
  ["3", "Grow Revenue & Build Reputation", "Get more leads, calls and customers."],
];

export default function BusinessGrowth() {
  return (
    <>
      <section className="home-section home-growth">
        <div className="home-container home-growth__grid">
          <div>
            <p className="home-eyebrow home-eyebrow--light">Get found</p>
            <h2>Get <span>Found</span> by People in Your City</h2>
            <p className="home-section__intro">We help small businesses like yours get more visibility, leads &amp; revenue without spending big.</p>
            <div className="home-steps">
              {steps.map(([number, title, description]) => (
                <div key={number} className="home-step">
                  <span className="home-step__number">{number}</span>
                  <h3>{title}</h3>
                  <p>{description}</p>
                </div>
              ))}
            </div>
            <button type="button" className="home-button home-button--primary">Start Your Free Listing Today<ArrowRightIcon size={16} /></button>
          </div>
          <div className="home-growth__art" aria-hidden="true">
            <div className="home-growth__person"><div className="home-growth__head" /><div className="home-growth__body" /></div>
            <div className="home-growth__result"><ChartIcon size={17} /><strong>Avg 47% more leads</strong><small>in 30 days</small></div>
          </div>
        </div>
      </section>

      <section className="home-section home-reach">
        <div className="home-container home-reach__grid">
          <div className="home-phone home-phone--light"><div className="home-phone__screen home-phone__screen--light" /></div>
          <div>
            <p className="home-eyebrow">For local businesses</p>
            <h2>Reach More Customers, <span>Grow Faster</span></h2>
            <p className="home-section__intro">Thousands of small businesses use CityHangaround to attract local customers every day.</p>
            <ul className="home-checklist">
              {[
                [LocationIcon, "Get discovered by people searching near you"],
                [CalendarIcon, "Share deals, offers & updates instantly"],
                [StarIcon, "Build trust with reviews & ratings"],
                [ChartIcon, "Increase footfall and grow your business"],
              ].map(([Icon, label]) => <li key={label as string}><span><Icon size={14} /></span>{label as string}</li>)}
            </ul>
            <div className="home-hero__actions"><button type="button" className="home-button home-button--primary">Claim Your Free Profile Now</button><button type="button" className="home-button home-button--outline home-button--outline-dark">Learn More</button></div>
            <div className="home-trust-list"><span><CommunityIcon size={15} /> Local reach</span><span><ShieldIcon size={15} /> Verified listings</span><span><SupportIcon size={15} /> 24/7 support</span></div>
          </div>
        </div>
      </section>
    </>
  );
}

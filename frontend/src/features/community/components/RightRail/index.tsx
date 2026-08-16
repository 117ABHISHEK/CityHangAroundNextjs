import "./index.css";
import AdSlot from "../AdSlot";

type Person = {
  initials: string;
  name: string;
  meta: string;
  color: string;
};

type RightRailProps = {
  people: Person[];
};

export default function RightRail({ people }: RightRailProps) {
  return (
    <aside className="community-right-rail">
      <div className="community-side-group">
        <div className="community-side-title">People you know</div>
        <div className="community-people-list">
          {people.map((person) => (
            <div key={person.name} className="community-person-item">
              <div className="community-person-avatar" style={{ backgroundColor: person.color }}>
                {person.initials}
              </div>
              <div className="community-person-info">
                <div className="community-person-name">{person.name}</div>
                <div className="community-person-meta">{person.meta}</div>
              </div>
            </div>
          ))}
        </div>
      </div>

      <div className="community-side-group">
        <AdSlot
          variant="landscape"
          title="Find your dream home in Ahmedabad"
          cta="Explore now"
          image="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80"
        />
      </div>

      <div className="community-side-group">
        <div className="community-side-title">Community guidelines</div>
        <p className="community-guidelines">
          Be respectful, follow community rules, and engage positively. No spam, harassment,
          or adult content.
        </p>
      </div>

      <div className="community-side-group">
        <AdSlot
          variant="portrait"
          title="Invest in Ahmedabad’s future"
          cta="View projects"
          image="https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=900&q=80"
        />
      </div>

      <div className="community-side-group">
        <div className="community-side-title">Latest blog</div>
        <div className="community-highlight-card">
          <div className="community-highlight-card__image" aria-hidden="true" />
          <div className="community-highlight-card__content">
            <h4>Invest in Ahmedabad’s future</h4>
            <button type="button">View Projects</button>
          </div>
        </div>
      </div>
    </aside>
  );
}

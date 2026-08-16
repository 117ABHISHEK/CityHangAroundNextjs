import {
  Home,
  Flame,
  Compass,
  PartyPopper,
  Briefcase,
  Users,
  Store,
  ShoppingBag
} from 'lucide-react';

type CommunityItem = {
  id: string;
  icon: string;
  name: string;
  color: string;
};

type CommunitySidebarProps = {
  communities: CommunityItem[];
};

export default function CommunitySidebar({ communities }: CommunitySidebarProps) {
  return (
    <aside className="community-sidebar">
      <div className="community-side-group">
        <div className="community-side-title">Home</div>
        <nav className="community-side-nav">
                    <a href="#" className="community-side-link is-active">
            <span className="community-side-icon"><Home size={20} /></span>
            Home
          </a>
          <a href="#" className="community-side-link">
            <span className="community-side-icon"><Flame size={20} /></span>
            Popular
          </a>
          <a href="#" className="community-side-link">
            <span className="community-side-icon"><Compass size={20} /></span>
            History
          </a>
        </nav>
      </div>

      <div className="community-side-group">
        <div className="community-side-title">Your communities</div>
        <nav className="community-side-nav">
          {communities.map((community) => (
            <a key={community.id} href="#" className="community-side-link">
              <span
                className="community-side-avatar"
                style={{ backgroundColor: community.color }}
              >
                {community.icon}
              </span>
              {community.name}
            </a>
          ))}
        </nav>
      </div>

      <div className="community-side-group">
        <div className="community-side-title">Explore</div>
        <nav className="community-side-nav">
                    <a href="#" className="community-side-link">
            <span className="community-side-icon"><PartyPopper size={20} /></span>
            Events
          </a>
          <a href="#" className="community-side-link">
            <span className="community-side-icon"><Briefcase size={20} /></span>
            Jobs
          </a>
          <a href="#" className="community-side-link">
            <span className="community-side-icon"><Users size={20} /></span>
            Groups
          </a>
          <a href="#" className="community-side-link">
            <span className="community-side-icon"><Store size={20} /></span>
            Businesses
          </a>
          <a href="#" className="community-side-link">
            <span className="community-side-icon"><ShoppingBag size={20} /></span>
            Products
          </a>
        </nav>
      </div>
    </aside>
  );
}

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
            <span className="community-side-icon">🏠</span>
            Home
          </a>
          <a href="#" className="community-side-link">
            <span className="community-side-icon">🔥</span>
            Popular
          </a>
          <a href="#" className="community-side-link">
            <span className="community-side-icon">🧭</span>
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
            <span className="community-side-icon">🎉</span>
            Events
          </a>
          <a href="#" className="community-side-link">
            <span className="community-side-icon">💼</span>
            Jobs
          </a>
          <a href="#" className="community-side-link">
            <span className="community-side-icon">👥</span>
            Groups
          </a>
          <a href="#" className="community-side-link">
            <span className="community-side-icon">🏪</span>
            Businesses
          </a>
          <a href="#" className="community-side-link">
            <span className="community-side-icon">🛍️</span>
            Products
          </a>
        </nav>
      </div>
    </aside>
  );
}

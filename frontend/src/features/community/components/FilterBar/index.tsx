import "./index.css";
import { ClockIcon, FireIcon, StarIcon } from "@/src/components/ui/icons";

type FilterBarProps = {
  activeFilter: string;
  onChange: (value: string) => void;
};

const tabs = [
  { value: "hot", label: "Hot", icon: FireIcon },
  { value: "new", label: "New", icon: ClockIcon },
  { value: "top", label: "Top", icon: StarIcon },
];

export default function FilterBar({ activeFilter, onChange }: FilterBarProps) {
  return (
    <div className="community-filter-bar">
      <div className="community-filter-tabs">
        {tabs.map(({ value, label, icon: Icon }) => (
          <button
            key={value}
            type="button"
            className={activeFilter === value ? "is-active" : ""}
            onClick={() => onChange(value)}
          >
            <Icon size={14} />
            {label}
          </button>
        ))}
      </div>

      <select className="community-filter-select" defaultValue="hot" aria-label="Sort posts">
        <option value="hot">Sort: Hot</option>
        <option value="new">Sort: New</option>
        <option value="top_today">Sort: Top Today</option>
        <option value="top_week">Sort: Top This Week</option>
      </select>
    </div>
  );
}

import { cn } from "../../lib/utils.js";

export default function StatsCard({ title, value, trend, trendValue }) {
  return (
    <div className="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-sm p-6 shadow-sm hover:shadow-md transition-shadow">
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-sm font-medium text-zinc-500 dark:text-zinc-400">{title}</h3>
      </div>
      <div className="flex items-baseline gap-3">
        <div className="text-3xl font-bold text-zinc-900 dark:text-zinc-50">{value}</div>
        {trend && trendValue && (
          <div className="text-xs font-semibold px-2 py-1 rounded-sm border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-700 dark:text-zinc-300 shadow-sm">
            {trend === 'up' ? '↑' : trend === 'down' ? '↓' : ''} {trendValue}
          </div>
        )}
      </div>
    </div>
  );
}

import { useHealth } from '@/hooks/useHealth';

export function HealthStatus() {
  const health = useHealth();

  return (
    <div className="p-4 rounded-lg border">
      <div className="flex items-center gap-2">
        <div
          className={`w-3 h-3 rounded-full ${
            health.isHealthy ? 'bg-green-500' : 'bg-red-500'
          }`}
        />
        <span className="font-semibold">
          {health.isHealthy ? 'API Healthy' : 'API Unavailable'}
        </span>
      </div>
      {!health.loading && (
        <p className="text-sm text-gray-600 mt-2">{health.message}</p>
      )}
      {health.loading && <p className="text-sm text-gray-500 mt-2">Checking...</p>}
      {health.error && (
        <p className="text-sm text-red-600 mt-2">Error: {health.error}</p>
      )}
    </div>
  );
}

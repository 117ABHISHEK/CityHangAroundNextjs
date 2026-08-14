import { useState, useEffect } from 'react';
import { checkHealth } from '@/services/api';

interface HealthStatus {
  isHealthy: boolean;
  message: string;
  loading: boolean;
  error: string | null;
}

export function useHealth() {
  const [health, setHealth] = useState<HealthStatus>({
    isHealthy: false,
    message: 'Checking...',
    loading: true,
    error: null,
  });

  useEffect(() => {
    const fetchHealth = async () => {
      try {
        const result = await checkHealth();
        setHealth({
          isHealthy: result.status === 'healthy',
          message: result.message,
          loading: false,
          error: null,
        });
      } catch (err) {
        setHealth({
          isHealthy: false,
          message: 'API is unavailable',
          loading: false,
          error: err instanceof Error ? err.message : 'Unknown error',
        });
      }
    };

    fetchHealth();
  }, []);

  return health;
}

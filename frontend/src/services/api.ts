const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';

interface HealthResponse {
  status: string;
  timestamp: string;
  message: string;
}

export interface City {
  id: number;
  city_name: string;
  city_slug: string;
  city_image: string | null;
  city_state: string | null;
  state_id: number | null;
  city_about: string | null;
  city_lat: string | null;
  city_lng: string | null;
}

export async function checkHealth(): Promise<HealthResponse> {
  try {
    const response = await fetch(`${API_BASE_URL}/api/health`);
    
    if (!response.ok) {
      throw new Error(`Health check failed with status ${response.status}`);
    }
    
    return await response.json();
  } catch (error) {
    console.error('Health check error:', error);
    throw error;
  }
}

/**
 * Fetch all active cities from the Laravel backend.
 * Public endpoint — no auth required.
 */
export async function getCities(): Promise<City[]> {
  try {
    const response = await fetch(`${API_BASE_URL}/ajax/cities`, {
      next: { revalidate: 3600 }, // cache for 1 hour
    });

    if (!response.ok) {
      throw new Error(`Failed to fetch cities: ${response.status}`);
    }

    return await response.json();
  } catch (error) {
    console.error('Error fetching cities:', error);
    return [];
  }
}

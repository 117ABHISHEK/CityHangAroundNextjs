<div class="container py-5">
    <div class="mb-5 text-center">
        <h2 class="fw-bold text-primary-emphasis">📊 City-wise Impact Report</h2>
        <p class="text-muted">Track your contributions across different cities</p>
    </div>

    <div class="table-responsive rounded-3 shadow-sm border">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-body-tertiary text-secondary text-uppercase small border-bottom">
                <tr>
                    <th scope="col" class="ps-4">#</th>
                    <th scope="col">🏙️ City Name</th>
                    <th scope="col">⭐ Total Score</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cityScores as $index => $score)
                    <tr class="border-top">
                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                        <td class="fw-medium text-dark">
                            <a href="{{ route('city.activity.report', ['cityId' => $score['id']]) }}" class="text-decoration-none fw-semibold text-primary">
        {{ $score['city_name'] ?? 'Unknown City' }}
    </a>
                        </td>
                        <td class="fw-bold text-success">{{ $score['total_score'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            <i class="bi bi-emoji-frown me-1"></i> No activity data found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

{{-- pagination links --}}
{{ $cityScores->links() }}
    </div>
</div>

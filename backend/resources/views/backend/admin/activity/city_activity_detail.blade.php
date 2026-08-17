<div class="container py-5">
    <h2 class="mb-4 fw-bold text-dark">📍 Activity in {{ $cityName }}</h2>

    @php
        $groupedLogs = $cityLogs->groupBy('date');
    @endphp

    @forelse($groupedLogs as $date => $activities)
        <div class="mb-5">
            <h5 class="text-primary mb-3">{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</h5>

            <div class="list-group shadow-sm rounded">
                @foreach($activities as $activity)
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-dark">
                                {{ ucfirst($activity['event']) }}
                                <small class="text-muted">({{ $activity['type'] }})</small>
                            </div>
                            @if(!empty($activity['details']))
                                <div class="text-muted small">
                                    {!! $activity['details'] !!}
                                </div>
                            @endif
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success rounded-pill">{{ $activity['score'] }}</span><br>
                            <small class="text-muted">{{ $activity['time'] }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="alert alert-warning">No activity found in this city.</div>
    @endforelse

    <div class="mt-4">
        {{ $cityLogs->links() }}
    </div>
</div>

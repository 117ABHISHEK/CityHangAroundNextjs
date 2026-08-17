
<style>
    .dashboard-card {
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: 0.3s ease-in-out;
        background: #fff;
    }

    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    .icon-badge {
        background: #e6f2ff;
        padding: 18px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .icon-badge i {
        color: #0d6efd;
        font-size: 28px;
    }

    .score-text {
        font-size: 40px;
        font-weight: bold;
        color: #212529;
    }

    .card-label {
        font-size: 18px;
        color: #6c757d;
    }

    .view-btn {
        border-radius: 25px;
        padding: 10px 30px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <!-- Welcome Card -->
            <div class="dashboard-card mb-4 p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-2 text-dark">👋 Hello, {{ $user->name }}</h4>
                    <p class="text-muted mb-0">Welcome back to your activity dashboard.</p>
                </div>
                <div class="icon-badge">
                    <i class="fas fa-user-circle"></i>
                </div>
            </div>

            <!-- Score Card -->
            <div class="dashboard-card p-4 d-flex justify-content-between align-items-center mb-4">
                <div>
                    <div class="card-label mb-1">Your Total Score</div>
                    <div class="score-text">{{ $score }}</div>
                </div>
                <div class="icon-badge bg-light">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>

            <!-- Next Button -->
            <div class="text-end">
                <a href="{{ route('user.activity.cities') }}" class="btn btn-primary view-btn">
                    View City-wise Breakdown
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0"><i class="fas fa-file-alt"></i> Lead Details</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <tr>
                    <th><i class="fas fa-user"></i> Name:</th>
                    <td>
                        @php
                            $isPurchased = $lead->purchases()->where('user_id', auth()->id())->exists();
                        @endphp
                        @if($isPurchased)
                            {{ $lead->name }}
                        @else
                            <span class="blur-text">{{ substr($lead->name, 0, 2) . str_repeat('*', strlen($lead->name) - 4) . substr($lead->name, -2) }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th><i class="fas fa-phone-alt"></i> Mobile No:</th>
                    <td>
                        @if($isPurchased)
                            {{ $lead->mobileno }}
                        @else
                            <span class="blur-text">
                                {{ substr($lead->mobileno, 0, 2) . str_repeat('*', strlen($lead->mobileno) - 4) . substr($lead->mobileno, -2) }}
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th><i class="fas fa-city"></i> City:</th>
                    <td>{{ optional($lead->marketplace->page->city)->city_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-box"></i> Product:</th>
                    <td>{{ optional($lead->marketplace)->title ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-tags"></i> Category:</th>
                    <td>
                        @php
                            $categoryIds = explode(',', $lead->marketplace->category);
                            $categories = App\Models\Category::whereIn('id', $categoryIds)->get();
                        @endphp
                        @foreach($categories as $category)
                            <span class="badge bg-info">{{ $category->product_category_name }}</span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th><i class="fas fa-money-bill-wave"></i> Lead Price:</th>
                    <td class="text-success fw-bold">
                        @if($lead->marketplace)
                            @php
                                $categories = $lead->marketplace->category_objects ?? collect();
                                $masterCategory = $categories->first();
                            @endphp
                            ₹{{ $masterCategory->lead_price ?? 'Not Set' }}
                        @else
                            No Price
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('leads.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Leads
            </a>
        </div>
    </div>
</div>

<!-- FontAwesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Custom CSS to blur text -->
<style>
    .blur-text {
        filter: blur(5px);
        cursor: pointer;
    }
    .blur-text:hover {
        filter: blur(0);
        transition: 0.3s ease-in-out;
    }
</style>

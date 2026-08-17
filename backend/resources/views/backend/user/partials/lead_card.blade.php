@php
    $isPurchased = $lead->purchases()->where('user_id', auth()->id())->exists();
    $categoryIds = explode(',', $lead->marketplace->category);
    $leadCategories = App\Models\Category::whereIn('id', $categoryIds)->get();
    $leadPrice = $lead->marketplace->master_category_lead_price ?? 0;
@endphp

<div class="col-md-12">
    <div class="table-responsive shadow-lg p-3 mb-4 bg-white rounded">
        <table class="table table-bordered table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Product</th>
                    <th>Categories</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <i class="fas fa-user-circle"></i> 
                        {{ $isPurchased ? $lead->name : str_repeat('*', strlen($lead->name) - 2) . substr($lead->name, -2) }}
                    </td>
                    <td>
                        <i class="fas fa-phone-alt text-success"></i> 
                        {{ $isPurchased ? $lead->mobileno : substr($lead->mobileno, 0, 2) . str_repeat('*', strlen($lead->mobileno) - 4) . substr($lead->mobileno, -2) }}
                    </td>
                    <td><i class="fas fa-city text-warning"></i> {{ optional($lead->marketplace->page->city)->city_name ?? 'N/A' }}</td>
                    <td><i class="fas fa-box text-info"></i> {{ optional($lead->marketplace)->title ?? 'N/A' }}</td>
                    <td>
                        <i class="fas fa-tags text-danger"></i>
                        @foreach($leadCategories as $category)
                            <span class="badge bg-secondary">{{ $category->product_category_name }}</span>
                        @endforeach
                    </td>
                    <td class="text-success">₹{{ $lead->marketplace->master_category_lead_price ?? 'Not Set' }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('leads.view', $lead->id) }}" class="btn btn-info btn-sm d-flex align-items-center">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                            @if(!$isPurchased)
                                <button type="button" class="btn btn-success btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $lead->id }}">
                                    <i class="fas fa-shopping-cart me-1"></i> Buy
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm d-flex align-items-center" disabled>
                                    <i class="fas fa-check-circle me-1"></i> Purchased
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal{{ $lead->id }}" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Choose Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>How would you like to pay for this lead?</p>
                <form id="paymentForm{{ $lead->id }}" method="POST">
                    @csrf
                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                    <input type="hidden" id="paymentMethod{{ $lead->id }}" name="payment_method" value="wallet">
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <div>
                <button onclick="buyLead({{ $lead->id }}, {{ $leadPrice }}, 'wallet')" class="btn btn-primary me-2">
                        <i class="fas fa-wallet"></i> Wallet
                    </button>
                    <button onclick="buyLead({{ $lead->id }}, {{ $leadPrice }}, 'online')" class="btn btn-success">
                        <i class="fas fa-credit-card"></i> Online
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
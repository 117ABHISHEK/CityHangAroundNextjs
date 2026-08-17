<div class="main_content">
    <!-- Main section header and breadcrumb -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <div class="d-flex flex-column">
                        <h4 class="fw-bold text-primary">{{ get_phrase('All Enquiry') }}</h4>
                        <small class="text-muted">Manage and track all user enquiries</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm p-3 mb-4">
        <form method="GET" action="{{ route('user.product.enquiry') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">City</label>
                    <select name="city" id="city" class="selectpicker eForm-control select2 @error('parent') is-invalid @enderror">
                        <option value="">Select City</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                {{ $city->city_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Area</label>
                    <select name="area" id="area" class="selectpicker eForm-control select2 @error('parent') is-invalid @enderror">
                        <option value="">Select Area</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category" class="selectpicker eForm-control select2 @error('parent') is-invalid @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->product_category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <a href="{{ route('user.product.enquiry') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Enquiries Table -->
    <div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User</th>
                            <th scope="col">City</th>
                            <th scope="col">Product</th>
                            <th scope="col">Date of Enquiry</th> <!-- Added Creation Date Column -->
                            <th scope="col">Lead Status</th>
        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
    @foreach ($enquiries as $key => $enquiry)
        @php
            // Fetch the page details
            $pages = DB::table('pages')
                ->select('pages.id', 'pages.item_slug', 'cities.city_slug','areas.area_slug')
                ->join('cities', 'cities.id', '=', 'pages.city_id')
                ->join('areas', 'areas.id', '=', 'pages.area_id')
                ->join('page_category', 'page_category.page_id', '=', 'pages.id')
                ->where('page_id', $enquiry->page_id)
                ->distinct()
                ->get();

            // Fetch item category
            $item_categories = DB::table('page_category')
                ->where('page_id', $enquiry->page_id)
                ->get();

            $item_count = count($item_categories);
            $catslug = null;

            if ($item_count > 0) {
                $categoriesss = DB::table('pagecategories')
                    ->where('id', $item_categories[$item_count-1]->category_id)
                    ->first();
                $catslug = $categoriesss->category_slug ?? null;
            }

            // Fetch product category
            $product_categories = DB::table('category_product')
                ->where('product_id', $enquiry->product_id)
                ->get();

            $product_count = count($product_categories);
            $productcatslug = null;

            if ($product_count > 0) {
                $productcategoriesss = DB::table('categories')
                    ->where('id', $product_categories[$product_count-1]->product_category_id)
                    ->first();
                $productcatslug = $productcategoriesss->product_category_slug ?? null;
            }
        @endphp

        <tr>
            <th scope="row">{{ ++$key }}</th>
            <td>
                <a href="{{ route('user.profile.view', $enquiry->userid) }}" class="text-dark fw-bold" target="_blank">
                    {{ $enquiry->name ?? "" }}
                </a>
                <br><small class="text-muted">{{ $enquiry->mobileno }}</small>
            </td>
            <td>{{ $enquiry->city_name }}</td>
            <td>
                @if (!empty($pages[0]) && $catslug && $productcatslug)
                    <a href="{{ route('single.product', [
                        'city_slug' => $pages[0]->city_slug,
                        'area_slug' => $pages[0]->area_slug,
                        'category_slug' => $catslug,
                        'item_slug' => $pages[0]->item_slug,
                        'product_category_slug' => $productcatslug,
                        'product_slug' => $enquiry->product_slug
                    ]) }}" target="_blank">
                        {{ $enquiry->title }}
                    </a>
                @else
                    {{ $enquiry->title }}
                @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($enquiry->createdAt)->format('d M Y, h:i A') }}</td>
            <td>
                @if ($enquiry->lead_stage_id)
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            <strong>{{ $enquiry->stage_name }}</strong>
                            @if($enquiry->comment)
                                <br><small class="text-muted">"{{ $enquiry->comment }}"</small>
                            @endif
                        </div>
                    </div>
                @else
                    <span class="badge bg-secondary">No Status</span>
                @endif
            </td>

            <td>
                <button class="btn btn-sm btn-primary open-lead-stage-modal"
                        data-enquiry-id="{{ $enquiry->id }}"
                        data-user-id="{{ $enquiry->userid }}"
                        data-lead-stage-id="{{ $enquiry->lead_stage_id ?? '' }}"
                        data-comment="{{ $enquiry->comment ?? '' }}">
                    Update Lead Stage
                </button>
            </td>

        </tr>
    @endforeach
</tbody>

                </table>
                <!-- Pagination -->
                <div class="pagination-area mt-3 d-flex justify-content-center">
                    {{ $enquiries->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


    @include('backend.footer')
</div>
<!-- Buyer Lead Stage Modal -->
<div class="modal fade" id="leadStageModal" tabindex="-1" aria-labelledby="leadStageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leadStageModalLabel">Update Buyer Lead Stage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="leadStageForm">
                    @csrf
                    <input type="hidden" name="enquiry_id" id="modal-enquiry-id">
                    <input type="hidden" name="user_id" id="modal-user-id">

                    <div class="mb-3">
                        <label class="form-label">Lead Stage</label>
                        <select name="lead_stage_id" id="modal-lead-stage-id" class="form-select">
                            <option value="">Select Lead Stage</option>
                            @foreach($leadStages as $stage)
                                <option value="{{ $stage->id }}">{{ $stage->stage_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comment</label>
                        <textarea name="comment" id="modal-comment" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript -->
<script>
    $(document).ready(function() {
        $('#city').change(function() {
            let cityId = $(this).val();
            $('#area').html('<option value="">Loading...</option>');

            if (cityId) {
                $.ajax({
                    url: "{{ route('get.areas.by.city') }}",
                    type: "GET",
                    data: { city_id: cityId },
                    success: function(response) {
                        $('#area').html('<option value="">Select Area</option>');
                        $.each(response, function(key, value) {
                            $('#area').append('<option value="' + value.id + '">' + value.area_name + '</option>');
                        });
                    },
                    error: function(xhr) {
                        console.error("Error fetching areas:", xhr.responseText);
                        $('#area').html('<option value="">Error loading areas</option>');
                    }
                });
            } else {
                $('#area').html('<option value="">Select Area</option>');
            }
        });

        // Auto-populate areas when page loads
        let selectedCity = $('#city').val();
        let selectedArea = "{{ request('area') }}";
        if (selectedCity) {
            $.ajax({
                url: "{{ route('get.areas.by.city') }}",
                type: "GET",
                data: { city_id: selectedCity },
                success: function(response) {
                    $('#area').html('<option value="">Select Area</option>');
                    $.each(response, function(key, value) {
                        let selected = (selectedArea == value.id) ? 'selected' : '';
                        $('#area').append('<option value="' + value.id + '" ' + selected + '>' + value.area_name + '</option>');
                    });
                }
            });
        }

         // Open modal and populate data
         $('.open-lead-stage-modal').on('click', function () {
            $('#modal-enquiry-id').val($(this).data('enquiry-id'));
            $('#modal-user-id').val($(this).data('user-id'));
            $('#modal-lead-stage-id').val($(this).data('lead-stage-id'));
            $('#modal-comment').val($(this).data('comment'));
            $('#leadStageModal').modal('show');
        });

        // Handle form submission
        $('#leadStageForm').on('submit', function (e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('buyer.leadStage.store') }}",
                type: "POST",
                data: formData,
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        $('#leadStageModal').modal('hide');
                        location.reload(); // Refresh to show updated data
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>

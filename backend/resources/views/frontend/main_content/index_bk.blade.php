@php $user_info = Auth()->user() @endphp

@include('frontend.story.index')


@if (session('spam_error'))
    <div class="alert alert-danger mt-2">
        {{ session('spam_error') }}
    </div>
@endif

@include('frontend.main_content.create_post')

<!-- Recent Listings Section -->
<div class="recent-listings-section mb-4">
    <div class="container">
        <h3 class="section-title mb-4">Recent Listings</h3>
        
        <!-- Business Listings -->
        @if(isset($recentBusinesses) && $recentBusinesses->count() > 0)
        <div class="listing-category mb-4">
            <h4 class="category-title">
                <i class="fas fa-building text-primary"></i> Recent Businesses
            </h4>
            <div class="row">
                @foreach($recentBusinesses as $business)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ get_page_logo($business->logo, 'logo') }}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ strip_tags($business->title ?? '') }}">
                                <div>
                                    <h6 class="card-title mb-0">{{ strip_tags($business->title ?? '') }}</h6>
                                    <small class="text-muted">{{ $business->category->name ?? $business->categories->first()->name ?? 'Business' }}</small>
                                </div>
                            </div>
                            <p class="card-text small text-muted">{{ Str::limit(strip_tags($business->description ?? ''), 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ $business->location ? strip_tags($business->location) : ($business->city->city_name ?? 'Location not specified') }}</small>
                                <a href="{{ route('page.view.simple', $business->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('pages') }}" class="btn btn-outline-primary">View All Businesses</a>
            </div>
        </div>
        @endif

        <!-- Products -->
        @if(isset($recentProducts) && $recentProducts->count() > 0)
        <div class="listing-category mb-4">
            <h4 class="category-title">
                <i class="fas fa-shopping-bag text-success"></i> Recent Products
            </h4>
            <div class="row">
                @foreach($recentProducts as $product)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $product->image ? (filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/marketplace/' . $product->image)) : asset('assets/frontend/images/default-product.png') }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ strip_tags($product->title ?? '') }}">
                                <div>
                                    <h6 class="card-title mb-0">{{ strip_tags($product->title ?? '') }}</h6>
                                    <small class="text-muted">{{ $product->category->product_category_name ?? 'Product' }}</small>
                                </div>
                            </div>
                            <p class="card-text small text-muted">{{ Str::limit(strip_tags($product->description ?? ''), 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-success fw-bold">₹{{ number_format($product->price ?? 0) }}</small>
                                <a href="{{ route('single.product', ['city_slug' => 'city', 'area_slug' => 'area', 'category_slug' => $product->category->product_category_slug ?? 'category', 'item_slug' => 'item', 'product_category_slug' => $product->category->product_category_slug ?? 'category', 'product_slug' => $product->id]) }}" class="btn btn-sm btn-outline-success">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('allproducts') }}" class="btn btn-outline-success">View All Products</a>
            </div>
        </div>
        @endif

        <!-- Blogs -->
        @if(isset($recentBlogs) && $recentBlogs->count() > 0)
        <div class="listing-category mb-4">
            <h4 class="category-title">
                <i class="fas fa-blog text-info"></i> Recent Blogs
            </h4>
            <div class="row">
                @foreach($recentBlogs as $blog)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $blog->thumbnail ? (filter_var($blog->thumbnail, FILTER_VALIDATE_URL) ? $blog->thumbnail : asset('storage/blog/' . $blog->thumbnail)) : asset('assets/frontend/images/default-blog.png') }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ strip_tags($blog->title ?? '') }}">
                                <div>
                                    <h6 class="card-title mb-0">{{ strip_tags($blog->title ?? '') }}</h6>
                                    <small class="text-muted">{{ $blog->category->name ?? 'Blog' }}</small>
                                </div>
                            </div>
                            <p class="card-text small text-muted">{{ Str::limit(strip_tags($blog->description), 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">By {{ $blog->user->name ?? 'User' }}</small>
                                <a href="{{ route('single.blog', ['category_slug' => $blog->category->category_slug ?? 'category', 'blog_slug' => $blog->id, 'city_slug' => 'city', 'area_slug' => 'area']) }}" class="btn btn-sm btn-outline-info">Read</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('blogs') }}" class="btn btn-outline-info">View All Blogs</a>
            </div>
        </div>
        @endif

        <!-- Events -->
        @if(isset($recentEvents) && $recentEvents->count() > 0)
        <div class="listing-category mb-4">
            <h4 class="category-title">
                <i class="fas fa-calendar-alt text-warning"></i> Recent Events
            </h4>
            <div class="row">
                @foreach($recentEvents as $event)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $event->banner ? (filter_var($event->banner, FILTER_VALIDATE_URL) ? $event->banner : asset('storage/event/' . $event->banner)) : asset('assets/frontend/images/default-event.png') }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ strip_tags($event->title ?? '') }}">
                                <div>
                                    <h6 class="card-title mb-0">{{ strip_tags($event->title ?? '') }}</h6>
                                    <small class="text-muted">{{ $event->category->name ?? 'Event' }}</small>
                                </div>
                            </div>
                            <p class="card-text small text-muted">{{ Str::limit(strip_tags($event->description ?? ''), 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ $event->event_date ? date('M d, Y', strtotime($event->event_date)) : 'TBD' }}</small>
                                <a href="{{ route('single.event', ['city_slug' => 'city', 'area_slug' => 'area', 'category_slug' => $event->category->category_slug ?? 'category', 'event_slug' => $event->id]) }}" class="btn btn-sm btn-outline-warning">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('event') }}" class="btn btn-outline-warning">View All Events</a>
            </div>
        </div>
        @endif

        <!-- Videos -->
        @if(isset($recentVideos) && $recentVideos->count() > 0)
        <div class="listing-category mb-4">
            <h4 class="category-title">
                <i class="fas fa-video text-danger"></i> Recent Videos
            </h4>
            <div class="row">
                @foreach($recentVideos as $video)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="position-relative me-2">
                                    <img src="{{ asset('assets/frontend/images/' . $video->thumbnail) }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ strip_tags($video->title ?? '') }}">
                                    <i class="fas fa-play-circle position-absolute top-50 start-50 translate-middle text-white" style="font-size: 16px;"></i>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0">{{ strip_tags($video->title ?? '') }}</h6>
                                    <small class="text-muted">{{ $video->category ?? 'Video' }}</small>
                                </div>
                            </div>
                            <p class="card-text small text-muted">{{ Str::limit(strip_tags($video->description ?? ''), 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ $video->user->name ?? 'User' }}</small>
                                <a href="{{ route('video.detail.info', $video->id) }}" class="btn btn-sm btn-outline-danger">Watch</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('videos') }}" class="btn btn-outline-danger">View All Videos</a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Timeline Posts -->
<div id="timeline-posts">
    @include('frontend.main_content.posts', ['types' => ['user_post', 'page','events','blogs']])
</div>

 

@include('frontend.main_content.scripts')

<!-- CSS to style the search box and ensure it fits beautifully -->
<style>
    .search-box-container {
        margin-top: 20px;
        margin-bottom: 30px;
        text-align: center;
        background-color: #ffffff;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .search-box-wrapper {
        max-width: 600px;
        margin: 0 auto;
    }

    .search-dropdown {
        font-size: 16px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .search-dropdown:focus {
        outline: none;
        border-color: #5c6bc0;
        box-shadow: 0 0 0 3px rgba(92, 107, 192, 0.3);
    }

    .select2-container .select2-selection--single {
        height: 45px;
        line-height: 35px;
        font-size: 16px;
        padding: 0 10px;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 10px;
        padding-right: 10px;
    }

    /* Recent Listings Styling */
    .recent-listings-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 30px 0;
        border-radius: 15px;
        margin: 20px 0;
    }

    .section-title {
        color: #2c3e50;
        font-weight: 700;
        text-align: center;
        margin-bottom: 30px;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #007bff, #28a745, #17a2b8, #ffc107, #dc3545);
        border-radius: 2px;
    }

    .listing-category {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .listing-category:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .category-title {
        color: #495057;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    .category-title i {
        margin-right: 8px;
        font-size: 1.1em;
    }

    .card {
        border: none;
        border-radius: 10px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .card-body {
        padding: 15px;
    }

    .card-title {
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.3;
    }

    .card-text {
        color: #6c757d;
        line-height: 1.4;
    }

    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        border-color: #007bff;
        transform: scale(1.05);
    }

    .btn-outline-success {
        border-color: #28a745;
        color: #28a745;
        transition: all 0.3s ease;
    }

    .btn-outline-success:hover {
        background-color: #28a745;
        border-color: #28a745;
        transform: scale(1.05);
    }

    .btn-outline-info {
        border-color: #17a2b8;
        color: #17a2b8;
        transition: all 0.3s ease;
    }

    .btn-outline-info:hover {
        background-color: #17a2b8;
        border-color: #17a2b8;
        transform: scale(1.05);
    }

    .btn-outline-warning {
        border-color: #ffc107;
        color: #ffc107;
        transition: all 0.3s ease;
    }

    .btn-outline-warning:hover {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
        transform: scale(1.05);
    }

    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
        transition: all 0.3s ease;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        transform: scale(1.05);
    }

    .text-success.fw-bold {
        font-size: 1.1em;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .recent-listings-section {
            padding: 20px 0;
        }
        
        .listing-category {
            padding: 15px;
        }
        
        .card-body {
            padding: 12px;
        }
    }
</style>

<!-- JavaScript for Select2 Initialization and Search Handling -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


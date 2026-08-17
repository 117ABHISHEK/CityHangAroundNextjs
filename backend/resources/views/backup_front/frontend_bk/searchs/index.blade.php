<div class="container py-5">
    <h1 class="text-center mb-5 text-gradient fw-bold display-5">
        🔍 Discover Blogs, Pages, Events & Products
    </h1>

    {{-- ✅ Pages Section --}}
    @if(count($mypages) > 0)
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-heading">📘 Popular Pages</h2>
            </div>
            <div class="row g-4">
                @include('frontend.searchs.single_page', ['mypages' => $mypages])
            </div>
        </section>
    @endif

    {{-- ✅ Products Section --}}
    @if(count($products) > 0)
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-heading">🛍️ Trending Products</h2>
            </div>
            <div class="row g-4">
                @include('frontend.searchs.single_product', ['products' => $products])
            </div>
        </section>
    @endif

    {{-- ✅ Events Section --}}
    @if(isset($events) && count($events) > 0)
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-heading">🎉 Upcoming Events</h2>
            </div>
            <div class="row g-4">
                @include('frontend.searchs.single_event', ['events' => $events])
            </div>
        </section>
    @endif

    {{-- ✅ Blogs Section --}}
    @if(count($blogs) > 0)
        <section class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-heading">🔥 Top Blogs</h2>
            </div>
            <div class="row g-4 blog-cards">
                @include('frontend.searchs.single_blog', ['blogs' => $blogs])
            </div>
        </section>
    @endif
</div>

{{-- ✨ Add Custom CSS --}}
@push('styles')
<style>
    .text-gradient {
        background: linear-gradient(90deg, #007bff, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .section-heading {
        font-size: 1.75rem;
        font-weight: 600;
        color: #343a40;
        display: flex;
        align-items: center;
    }

    .section-heading::before {
        content: "";
        display: inline-block;
        width: 5px;
        height: 25px;
        background-color: #0d6efd;
        margin-right: 10px;
        border-radius: 5px;
    }

    .btn-outline-primary {
        font-size: 0.875rem;
        border-radius: 20px;
        padding: 4px 12px;
    }
</style>
@endpush

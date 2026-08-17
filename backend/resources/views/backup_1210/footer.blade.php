<footer class="pt-4 pb-4 border-top bg-light mt-5 mb-4">
    <div class="container">
        <h5 class="mb-3">{{ get_phrase('Important Links') }}</h5>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            @php
                use App\Models\CustomPage;
                $customPages = CustomPage::latest()->get();
            @endphp

            @foreach ($customPages as $page)
                <div class="col">
                    <a href="{{ route('custom_pages.show', ['slug' => $page->slug]) }}" class="d-flex align-items-center text-decoration-none text-dark">
                        <i class="fas fa-file-alt me-2" style="color: #FF4939;"></i>
                        <span>{{ $page->title }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</footer>

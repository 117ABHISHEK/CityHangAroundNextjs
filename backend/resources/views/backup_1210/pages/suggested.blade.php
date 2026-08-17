
<style>
  .sugg-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    padding: 12px; /* Reduce padding */
    border-radius: 10px; /* Optional: Make it slightly rounded */
}

.sugg-card a {
    display: block;
    font-size: 14px; /* Reduce font size for links */
    margin-bottom: 5px; /* Reduce space between links */
}

.thumbnail-110-106 {
    width: 100%;
    height: 100px; /* Ensure uniform image height */
    background-size: cover;
    background-position: center;
    border-radius: 8px; /* Optional: Rounded corners */
}

.sugg-card h4 {
    font-size: 16px; /* Adjust heading size */
    line-height: 1.3;
    margin-bottom: 5px;
}

.sugg-card .text-muted {
    font-size: 12px; /* Reduce font size for like count */
    margin-bottom: 5px;
}

.sugg-card .btn-primary {
    font-size: 14px;
    padding: 6px 10px; /* Reduce button size */
    margin-top: auto; /* Push button to bottom */
}

.col-xl-3, .col-lg-4, .col-md-3, .col-sm-4, .col-6 {
    padding: 5px; /* Reduce space between columns */
}


</style>   
 <div class="d-md-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5"><span><i class="fa fa-flag"></i></span> {{ get_phrase('Suggested Pages') }}</h3>
          <div class="pagebtnListing">
                <!-- <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.marketplace.create_product'])}}', '{{get_phrase('Create Product')}}');" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#createProduct" class="btn btn-primary"> <i class="fa fa-plus-circle"></i></a> -->
                    <a href="{{ route('pages.create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle"></i> {{ get_phrase('Create Page') }}
            </a>
            <a href="{{ route('pages') }}" class="btn btn mx-1">{{ get_phrase('Pages') }}</a>
            <a href="{{ route('pages.user') }}" class="btn btn mx-1">{{ get_phrase('My Pages') }}</a>
            <a href="{{ route('pages.joined') }}" class="btn btn mx-1">{{ get_phrase('Joined Pages') }}</a>
            <a href="{{ route('pages.incomplete') }}" class="btn btn mx-1">{{ get_phrase('Incomplete Pages') }}</a>
            
        </div>
    </div>
    
<div class="product-listing">
<div class="row g-3">    
suggested.blade
<div class="suggest-wrap row">
    @foreach ($suggestedpages as $page)
        @php
            $lastCategory = $page->categories->last();
            $likeCount = $page->likes->count();
            $isLiked = $page->likes->contains('user_id', auth()->id());
        @endphp

        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card sugg-card p-2 rounded">

                @if ($page->city && $page->area && $lastCategory && $page->item_slug)
                    <a href="{{ route('single.page', [
                        'city_slug' => $page->city->city_slug,
                        'area_slug' => $page->area->area_slug,
                        'category_slug' => $lastCategory->category_slug,
                        'item_slug' => $page->item_slug
                    ]) }}" class="mb-2 thumbnail-110-auto" style="background-image: url('{{ get_page_logo($page->logo, 'logo') }}')"></a>
                @endif

                <h4>
                    @if ($page->city && $page->area && $lastCategory && $page->item_slug)
                        <a href="{{ route('single.page', [
                            'city_slug' => $page->city->city_slug,
                            'area_slug' => $page->area->area_slug,
                            'category_slug' => $lastCategory->category_slug,
                            'item_slug' => $page->item_slug
                        ]) }}">
                        {{ $page->title}}
                        </a>
                    @else
                    {{ $page->title}}
                    @endif
                </h4>

                @if ($lastCategory)
                    <a href="{{ route('page.category', ['category_slug' => $lastCategory->category_slug]) }}">
                        {{ $lastCategory->category_name }}
                    </a>
                @endif

                @if ($page->city && $page->state)
                    <h4>
                        <a href="{{ route('page.category.city', [
                            'category_slug' => $lastCategory?->category_slug,
                            'city_slug' => $page->city->city_slug
                        ]) }}">
                            {{ $page->area->area_name }}, {{ $page->city->city_name }}  
                        </a>
                    </h4>
                @endif

                <span class="small text-muted">
                    <i class="fa fa-thumbs-up"></i> {{ $likeCount }} {{ get_phrase('People follow this') }}
                </span>

                @if ($isLiked)
                    <a href="javascript:void(0)" onclick="ajaxAction('{{ route('page.dislike', $page->id) }}')" class="btn btn-primary mt-2">
                        <i class="fa fa-thumbs-up"></i> {{ __('Following') }}
                    </a>
                @else
                    <a href="javascript:void(0)" onclick="ajaxAction('{{ route('page.like', $page->id) }}')" class="btn btn-primary mt-2">
                        <i class="fa fa-thumbs-up"></i> {{ get_phrase('Follow') }}
                    </a>
                @endif

            </div>
        </div>
    @endforeach
   </div>
</div>
                        </div>


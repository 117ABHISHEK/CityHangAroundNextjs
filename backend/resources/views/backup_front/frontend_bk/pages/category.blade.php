<h1 class="font-weight-light text-primary">{{ $category->category_name }}</h1>
<div class="sameHieght suggest-wrap row" id="pagedata">

    @foreach ($mypages as $key => $page)

        @php
            $itemCategories = $page->pageCategories;
            $lastCategory = $itemCategories->last();
        @endphp

        @if ($page->city && $page->area && $lastCategory && $page->item_slug)

            <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mb-3" id="page-{{ $page->id }}">
                <div class="card sugg-card p-2 rounded">
                   

                    <a class="thumbImg" 
                       href="{{ route('single.page', [
                            'city_slug' => $page->city->city_slug,
                            'area_slug' => $page->area->area_slug,
                            'category_slug' => $lastCategory->category_slug,
                            'item_slug' => $page->item_slug
                        ]) }}"
                       style="background-image: url({{ get_page_logo($page->logo, 'logo') }})">
                    </a>

                    <div class="smp-info">
                        <a href="{{ route('single.page', [
                                'city_slug' => $page->city->city_slug,
                                'area_slug' => $page->area->area_slug,
                                'category_slug' => $lastCategory->category_slug,
                                'item_slug' => $page->item_slug
                            ]) }}">
                            <h4 class="h6">{{ $page->title }}</h4>
                        </a>

                         <a href="{{ route('page.category', ['category_slug' => $lastCategory->category_slug]) }}">
                        {{ $lastCategory->category_name }}
                    </a>

                        <a href="{{ route('page.category.city', [
                                'category_slug' => $lastCategory->category_slug,
                                'city_slug' => $page->city->city_slug
                            ]) }}">
                            {{ $page->area->area_name }}, {{ $page->city->city_name }}
                        </a>
                        <br>

                        @php
                            $likecount = \App\Models\Page_like::where('page_id', $page->id)->count();
                        @endphp
                        <a href="{{ route('single.page', [
                                'city_slug' => $page->city->city_slug,
                                'area_slug' => $page->area->area_slug,
                                'category_slug' => $lastCategory->category_slug,
                                'item_slug' => $page->item_slug
                            ]) }}">
                            <span><i class="fa fa-thumbs-up"></i>{{ $likecount }} {{ get_phrase('People like this') }}</span>
                        </a>
                    </div>
                </div>
            </div>

        @endif

    @endforeach

    <div class="row">
        <div class="col-12">
            {{ $mypages->links() }}
        </div>
    </div>
</div>

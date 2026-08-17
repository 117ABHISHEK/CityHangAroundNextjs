<div class="page-wrap">
    <div class="d-md-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5 pt-3"><span><i class="fa-solid fa-calendar-days"></i></span> {{ get_phrase('Marketplace') }}</h3>
        <div class="d-flex pagebtnListing">
                <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.marketplace.create_product'])}}', '{{get_phrase('Create Product')}}');" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#createProduct" class="btn btn-primary"> <i class="fa fa-plus-circle"></i></a>
            <a href="{{ route('userproduct') }}" class="btn  mx-1">{{ get_phrase('My Products') }}</a>
            <a href="{{ route('product.saved') }}" class="btn " data-bs-toggle="tooltip" data-bs-placement="bottom" title="Saved Product">{{ get_phrase('Saved') }}</a>
        </div>
    </div>
    
   @foreach ($saved_products as $saved_product)
    @php
        $product = $saved_product->productData;
        $page = $product->page;
        $area = $page->area ?? null;
        $city = $area->city ?? null;
        $itemCategory = $page->categories->last(); // last page category
        $productCategory = $product->productCategories->last(); // last product category
        $currencySymbol = $product->getCurrency->symbol ?? '₹';
        $productSellingPrice = $product->product_selling_price ?? 0;
    @endphp

    <article class="single-entry svideo-entry bg-white p-3">
        <div class="row">
            <div class="col-md-5 col-lg-4 col-sm-12">
                <div class="entry-thumb">
                    <img width="100%" src="{{ get_product_image($product->image, 'thumbnail') }}" alt="">
                </div>
            </div>
            <div class="col-md-7 col-lg-8 col-sm-12">
                <div class="entry-text ms-4">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('single.product', [
                            'city_slug' => $city->city_slug ?? 'city',
                            'area_slug' => $area->area_slug ?? 'area',
                            'category_slug' => $itemCategory->category_slug ?? 'category',
                            'item_slug' => $page->item_slug ?? 'item',
                            'product_category_slug' => $productCategory->product_category_slug ?? 'subcategory',
                            'product_slug' => $product->product_slug ?? 'product'
                        ]) }}">
                            <h3 class="h6 mt-4 mt-md-1">{{ $product->title }}</h3>
                        </a>

                        <div class="post-controls dropdown dotted mt-4 mt-md-1">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false"></a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @php
                                    $isSaved = \App\Models\SavedProduct::where('product_id', $product->id)
                                        ->where('user_id', auth()->id())->exists();
                                @endphp
                                @if ($isSaved)
                                    <li><a href="javascript:void(0)" onclick="ajaxAction('{{ route('unsave.product.later', $product->id) }}')" class="dropdown-item btn btn-primary btn-sm">{{ get_phrase('Unsave') }}</a></li>
                                @else
                                    <li><a href="javascript:void(0)" onclick="ajaxAction('{{ route('save.product.later', $product->id) }}')" class="dropdown-item btn btn-primary btn-sm">{{ get_phrase('Save') }}</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <p class="ellipsis-line-4">{{ strip_tags(ellipsis($product->description, 300)) }}</p>

                    <div class="d-flex my-2">
                        <div class="avatar">
                            <a href="#!">
                                <img class="avatar-img rounded-circle w-40 user_image_proifle_height"
                                     src="{{ get_user_image($product->getUser->photo, 'optimized') }}" alt="">
                            </a>
                        </div>
                        <div class="avatar-info ms-2">
                            <h4 class="ava-nave"><a href="#">{{ $product->getUser->name }}</a></h4>
                            <div class="activity-time">{{ date('M d', strtotime($product->created_at)) }} at {{ date('h:i A', strtotime($product->created_at)) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
@endforeach


</div>


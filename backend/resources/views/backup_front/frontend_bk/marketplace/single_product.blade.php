
<?php


         //print_r($area);exit;


        $pages = $product->page;
        $city_name = optional($pages->city)->city_name;
        $city_slug = optional($pages->city)->city_slug;
        $area_name = optional($pages->area)->area_name;
        $area_slug = optional($pages->area)->area_slug;
        $item_slug = optional($pages)->item_slug;

        $catName = $pages->categories->last()?->category_name;
        $catSlug = $pages->categories->last()?->category_slug;
        $productCatName = $product->productCategories->last()?->product_category_name;
        $productCatSlug = $product->productCategories->last()?->product_category_slug;

        $product_selling_price = $product->product_selling_price ?? 0;

       

         
    ?>
<style>
    .location {
    display: block;
    min-height: 20px; /* Adjust this to match your design */
}

</style>
<div class="col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-white pl-0 pr-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('pages') }}">
                                        <i class="fas fa-bars"></i>
                                       Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('allproducts') }}">All Categories</a></li>

                                <li class="breadcrumb-item"><a href="{{ route('product.city', ['city_slug'=>$city_slug]) }}">{{ $city_name }}</a></li>

                                <li class="breadcrumb-item"><a href="{{ route('product.city.area', ['city_slug'=>$city_slug,'area_slug'=>$area_slug]) }}">{{ $area_name }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('product.category.city.area', ['city_slug'=>$city_slug,'category_slug'=>$productCatSlug,'area_slug'=>$area_slug]) }}">{{ $productCatName }}</a></li>
                                @foreach($parent_categories as $key => $parent_category)
                                     <li class="breadcrumb-item"><a href="{{route('product.category.city.area', ['city_slug'=>$city_slug,'category_slug'=>$parent_category->product_category_slug,'area_slug'=>$area_slug]) }}">{{ $parent_category->product_category_name }}</a></li>
                                @endforeach
                                <li class="breadcrumb-item"><a href="{{ route('single.page',['city_slug'=>$city_slug,'area_slug'=>$area_slug,'category_slug'=>$catSlug,'item_slug'=>$product->page->item_slug]) }}">{{ $product->page->title }}</a></li>
                                <li class="breadcrumb-item">{{ $product->title }}</li>
                                 </ol>
                        </nav>
                    </div>
<div class="product-details-wrap border p-3 rounded bg-white">
     
    <div class="product-header row">
        <div class="col-lg-6">
            <div id="carouselExampleIndicators" class="carousel slide product-slider"
                data-bs-ride="false">
                
                <div class="carousel-indicators">
                    @foreach ($product_image as $image )
                        <button type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->index=='0'? "active":"" }}" aria-current="true"
                        aria-label="Slide {{ $loop->index+1 }}"><img class="w-55 custome-height-50" src="{{ get_product_image($image->file_name,"thumbnail") }}" alt=""></button>
                        {{--  indicator images  need  here  --}}
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach ($product_image as $image )
                        <div class="carousel-item {{ $loop->index=='0'? "active":"" }}"  onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.marketplace.load_image', 'image' => $image->file_name])}}', '');">
                            <img class="rounded w-100" src="{{ get_product_image($image->file_name,"coverphoto") }}" alt=""> 
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button"
                    data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">{{get_phrase('Previous')}}</span>
                </button>
                <button class="carousel-control-next" type="button"
                    data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">{{get_phrase('Next')}}</span>
                </button>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="product-info">
                <h1 class="product-title h4 fw-7">{{ $product->title }}</h1>
                 @php
                        if(is_null($product->product_selling_price) || empty($product->product_selling_price)){
                            $product_selling_price=0;
                        }
                        else{
                            $product_selling_price=$product->product_selling_price;
                        }
                    
                        @endphp
                <span class="pt-price text-primary sub-title">{{ $product->getCurrency->symbol }} {{ $product_selling_price }}</span>
                @if(auth()->user())
                <p>{{get_phrase('Listed')}} {{ $product->created_at->timezone(Auth::user()->timezone)->format("d-m-Y") }}  . <strong>{{ $product->location }}</strong></p>
                @endif
                <div class="pb-author d-flex align-items-center">
                <span style="margin-right: 10px;">
                    {{ is_array(json_decode($products->view, true)) ? count(json_decode($products->view, true)) : 0 }}
                    {{ get_phrase('Views') }}
                </span>
                    </div>
                <div class="pt-publisher @if(isset($_GET['shared'])) hidden-on-shared-view @else d-flex @endif align-items-center justify-content-between">
                    <div class="pb-author d-flex align-items-center">
                        <img class="user_image_proifle_height" src="{{get_user_image($product->getUser->photo, 'optimized')}}" alt="">
                        <div class="pb-info ms-2">
                            <p class="text-primary mb-0">{{ get_phrase('Published By') }}</p>
                            <li class="breadcrumb-item"><a href="{{ route('single.page',['city_slug'=>$city_slug,'area_slug'=>$area_slug,'category_slug'=>$catSlug,'item_slug'=>$product->page->item_slug]) }}">{{ $product->page->title }}</a></li>
                        </div>
                    </div>
                    <div class="pb-share d-flex justify-content-between">
                    @if(auth()->user())
                        @if ($product->user_id!=auth()->user()->id)
                        
                        @endif
                        @endif
                        <span>
                            
                            @php
                            if(auth()->user())
                                $saved = \App\Models\SavedProduct::where('product_id',$product->id)->where('user_id',auth()->user()->id)->count();
                         
                                @endphp
                            @if(auth()->user())    
                            @if ($saved>0)
                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('unsave.product.later',$product->id); ?>')"> <i class="fa-solid fa-link-slash"></i> </a>
                            @else
                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('save.product.later',$product->id); ?>')"> <i class="fa fa-bookmark"></i></a>
                            @endif
                            @endif
                        </span>
                        
                         <span><a href="#" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.main_content.share_post_modal','city_slug'=>$city_slug,'category_slug'=>$catSlug,'item_slug'=>$product->page->item_slug,'product_category_slug'=>$productCatSlug,'product_slug'=>$product->product_slug] )}}', '{{get_phrase('Share Product')}}');" ><i class="fa fa-share"></i></a></span>
                    </div>
                </div>
                @php
                
                $idsString = $product->category; // Comma-separated string of category IDs

// Convert the string into an array
$idsArray = explode(',', $idsString);

// Fetch the category names for the given IDs
$names = DB::table('categories')
    ->whereIn('id', $idsArray) // Use the array here
    ->pluck('product_category_name')
    ->toArray(); // Get the names as an array

// Convert the names into a comma-separated string
$commaSeparatedNames = implode(', ', $names);

echo $commaSeparatedNames;
                @endphp
                <div class="pt-details @if(isset($_GET['shared'])) hidden-on-shared-view @endif">
                    <h3 class="sub-title">{{ get_phrase('Details') }}</h3>
                    <ul>
                        <!-- <li>{{ get_phrase('Condition') }}<span>{{ ucfirst($product->condition) }}</span></li> -->
                        <li>{{ get_phrase('Status') }}<span>{{ $product->status=='1'?"In Stock":"Out Of Stock" }}</span></li>
                        <li>{{ get_phrase('Category') }}<span>{{ $commaSeparatedNames }}</span></li>
                        <li>{{ get_phrase('Brand') }}<span>{{ ucfirst($product->getBrand->name) }}</span></li>
                        <li class="d-flex flex-column mt-3">
                              @if(auth()->user())
                                <a 
                                    class="btn btn-primary mb-2"
                                    target="_blank"
                                    href="{{ route('chat.marketplace', ['marketplace' => $product->id]) }}">
                                    {{ get_phrase('Enquire Now') }}
                                </a>
                            @else
                                <a 
                                    href="javascript:void(0);"
                                    onclick="showLoginPrompt();"
                                    class="btn btn-primary mb-2">
                                    {{ get_phrase('Enquire Now') }}
                                </a>
                            @endif

                            <a class="btn btn-primary mb-2"  onclick="openReportModal({{ $product->id }}, '{{ $product->title }}')">{{ get_phrase('Report') }}</a>
                        </li>
                    </ul>
                </div>
               
            </div>

        </div>
    </div> <!-- row end -->
    <div class="row @if(isset($_GET['shared'])) hidden-on-shared-view @endif">
        <div class="col-lg-12">
            <div class="product-description my-3">
                <h3 class="sub-title">{{ get_phrase('Description') }}</h3>
                @php echo script_checker($product->description, false); @endphp
            </div>
        </div>
    </div> <!-- row end -->

    @if(auth()->check())
<div class="card mt-4">
    <div class="card-body">
        <h5>Leave a Review</h5>
        <form method="POST" action="{{ route('marketplace.reviews.store') }}">
            @csrf
            <input type="hidden" name="marketplace_id" value="{{ $product->id }}">
            <input type="hidden" name="type" value="product">
            
            <div class="mb-3">
                <label>Rating</label>
                <div class="star-rating">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required />
                        <label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">&#9733;</label>
                    @endfor
                </div>
            </div>
            
            <div class="mb-3">
                <label for="review">Review (optional)</label>
                <textarea class="form-control" name="review" rows="3"></textarea>
            </div>
            
            <button class="btn btn-primary" type="submit">Submit Review</button>
        </form>
    </div>
</div>
@endif
<div id="reviews-container">
    @foreach($reviews->take(5) as $review)
        <div class="review-item">
            <div class="user-info">
                @if($review->user && $review->user->photo)
                    <img src="{{ asset($review->user->photo) }}" alt="{{ $review->user->name }}" width="50" height="50" style="border-radius:50%;">
                @else
                    <img src="{{ asset('/storage/userimage/default.png') }}" alt="{{ $review->user ? $review->user->name : 'User' }}" width="50" height="50" style="border-radius:50%;">
                @endif
                <strong>{{ $review->user->name }}</strong>
            </div>
            <div class="review-content">
                <div class="rating">
                    @for ($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            <span class="star filled">★</span>
                        @else
                            <span class="star">☆</span>
                        @endif
                    @endfor
                </div>
                <p>{{ $review->review }}</p>
            </div>
        </div>
    @endforeach
</div>

@if($has_more_reviews)
<button id="load-more-btn"
            class="btn btn-primary mt-3"
            data-url="{{ route('marketplace.reviews.load_more', $product->id) }}"
            data-offset="5">
        Load More Reviews
    </button>
@endif


<style>
.star-rating {
  direction: rtl;
  font-size: 1.5rem;
  unicode-bidi: bidi-override;
  display: inline-flex;
}

.star-rating input[type="radio"] {
  display: none;
}

.star-rating label {
  color: #ccc;
  cursor: pointer;
  padding: 0 5px;
  transition: color 0.2s;
}

.star-rating input[type="radio"]:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
  color: #f5b301;
}

.review-item {
    border-bottom: 1px solid #eee;
    padding: 15px 0;
    display: flex;
    gap: 15px;
}

.user-info {
    flex-shrink: 0;
    text-align: center;
    width: 80px;
}

.user-info img {
    display: block;
    margin: 0 auto 5px;
}

.user-info strong {
    display: block;
    font-weight: 600;
    font-size: 0.9rem;
}

.review-content {
    flex-grow: 1;
}

.rating .star {
    font-size: 18px;
    color: #ccc;
    margin-right: 2px;
}

.rating .star.filled {
    color: #f39c12; /* gold color */
}

.review-content p {
    margin-top: 8px;
    font-size: 0.95rem;
    color: #555;
}

#load-more-btn {
    display: block;
    margin: 10px auto 0;
    padding: 8px 20px;
}

</style>


</div>
@if(isset($related_product))
<div class="related-prodcut bg-white p-3 border rounded my-3">
    <h3 class="sub-title">{{get_phrase('Related Product')}}</h3>
    <div class="rl-products owl-carousel">
 @foreach ($related_product as $product)

    @php
        $page = $product->page;
        $city_slug = optional($page->city)->city_slug;
         $area_slug = optional($page->area)->area_slug;
        $item_slug = optional($page)->item_slug;

        $catSlug = $page->categories->last()?->category_slug;
        $productCatSlug = $product->productCategories->last()?->product_category_slug;

        $product_selling_price = $product->product_selling_price ?? 0;
    @endphp

    <div class="card product p-3">
        <div class="product-figure position-relative">
            <a href="{{ route('single.product', [
                'city_slug' => $city_slug,
                 'area_slug' => $area_slug,
                'category_slug' => $catSlug,
                'item_slug' => $item_slug,
                'product_category_slug' => $productCatSlug,
                'product_slug' => $product->product_slug
            ]) }}">
                <div class="thumbnail-90-90" style="background-image: url('{{ get_product_image($product->image, 'coverphoto') }}');"></div>
            </a>

            @auth
                @if ($product->user_id != auth()->id())
                    <a class="message-trigger" href="{{ route('chat', ['reciver' => $product->user_id, 'product' => $product->id]) }}">
                        <i class="fa fa-message"></i>
                    </a>
                @endif
            @endauth
        </div>

        <h3 class="h6">
            <a href="{{ route('single.product', [
                'city_slug' => $city_slug,
                'area_slug' => $area_slug,
                'category_slug' => $catSlug,
                'item_slug' => $item_slug,
                'product_category_slug' => $productCatSlug,
                'product_slug' => $product->product_slug
            ]) }}">
                {{ ellipsis($product->title, 15) }}
            </a>
        </h3>

        <span class="location">{{ $product->location }}</span>

        <a href="{{ route('single.product', [
            'city_slug' => $city_slug,
            'area_slug' => $area_slug,
            'category_slug' => $catSlug,
            'item_slug' => $item_slug,
            'product_category_slug' => $productCatSlug,
            'product_slug' => $product->product_slug
        ]) }}" class="btn btn-primary d-block mt-3">
            ${{ $product_selling_price }}
        </a>
    </div>

@endforeach

    </div>
</div>
@endif

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm" action="{{ route('report.group') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Hidden Fields -->
                    <input type="hidden" id="type" name="type">
                    <input type="hidden" id="entity_id" name="entity_id">

                    <!-- Group Name (Pre-filled) -->
                    <div class="mb-3">
                        <label for="group_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="group_name" name="group_name"  readonly>
                    </div>

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name (Optional)</label>
                        <input type="text" class="form-control" id="full_name" name="full_name">
                    </div>

                    <!-- Email Address (Required) -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number (Optional)</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>

                    <!-- Reason for Reporting -->
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Reporting *</label>
                        <select class="form-select" id="reason" name="reason" required>
                            <option value="">Select Reason</option>
                            <option value="Spam">Spam</option>
                            <option value="Inappropriate Content">Inappropriate Content</option>
                            <option value="Harassment/Bullying">Harassment/Bullying</option>
                            <option value="Fake Group">Fake Group</option>
                            <option value="Other">Other (Specify Below)</option>
                        </select>
                    </div>

                    <!-- Additional Comments -->
                    <div class="mb-3">
                        <label for="additional_comments" class="form-label">Additional Comments (Optional)</label>
                        <textarea class="form-control border" id="additional_comments" name="additional_comments" rows="3"></textarea>
                    </div>

                    <!-- File Upload (Proof) -->
                    <div class="mb-3">
                        <label for="proof_attachment" class="form-label">Attach Proof (Optional)</label>
                        <input type="file" class="form-control" id="proof_attachment" name="proof_attachment" accept="image/*, .pdf, .docx">
                    </div>

                    <!-- Would You Like a Response? -->
                    <div class="mb-3">
                        <label class="form-label">Would you like a response?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="response_required" id="response_yes" value="Yes" checked>
                            <label class="form-check-label" for="response_yes">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="response_required" id="response_no" value="No">
                            <label class="form-check-label" for="response_no">No</label>
                        </div>
                    </div>

                    <!-- CAPTCHA Verification -->
                    <div class="mb-3">
                        <label class="form-label">CAPTCHA Verification *</label>
                        <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-danger w-100">Submit Report</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openReportModal(groupId, groupName) {
        $('#entity_id').val(groupId);
        $('#group_name').val(groupName);
        $('#type').val('products');
        $('#reportModal').modal('show');
    }

    function toggleOtherReason() {
        if ($('#reason').val() === 'Other') {
            $('#otherReasonDiv').show();
        } else {
            $('#otherReasonDiv').hide();
            $('#other_reason').val('');
        }
    }

//     $('#reportForm').submit(function (e) {
//     e.preventDefault();

//     let formData = new FormData(this); // ✅ FormData supports file uploads

//     $.ajax({
//         url: "{{ route('report.group') }}",
//         method: "POST",
//         data: formData,
//         processData: false,  // ✅ Prevent jQuery from converting FormData into a string
//         contentType: false,  // ✅ Ensure correct content type for file upload
//         success: function (response) {
//             alert(response.message);
//             $('#reportModal').modal('hide');
//             $('#reportForm')[0].reset();
//         },
//         error: function (xhr) {
//             let errors = xhr.responseJSON.errors;
//             let errorMessage = "Error: " + xhr.responseJSON.message + "\n";
            
//             if (errors) {
//                 $.each(errors, function (key, value) {
//                     errorMessage += value + "\n"; // Append validation errors
//                 });
//             }

//             alert(errorMessage);
//         }
//     });
// });

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('load-more-btn');
    const container = document.getElementById('reviews-container');

    function renderStars(rating) {
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            starsHtml += `<span class="star ${i <= rating ? 'filled' : ''}">&#9733;</span>`;
        }
        return starsHtml;
    }

    function renderReviews(reviews) {
        reviews.forEach(review => {
            const div = document.createElement('div');
            div.classList.add('review-item');

            const userPhoto = review.user?.photo || '/storage/userimage/default.png';
            const userName = review.user?.name || 'User';
            const reviewText = review.review || '';

            div.innerHTML = `
                <div class="user-info">
                    <img src="${userPhoto}" alt="${userName}" width="50" height="50" style="border-radius:50%;">
                    <strong>${userName}</strong>
                </div>
                <div class="review-content">
                    <div class="rating">${renderStars(review.rating)}</div>
                    <p>${reviewText}</p>
                </div>
            `;
            container.appendChild(div);
        });
    }

    if (btn) {
        btn.addEventListener('click', () => {
            const url = btn.getAttribute('data-url');
            const offset = parseInt(btn.getAttribute('data-offset'));

            fetch(`${url}?offset=${offset}&limit=5`)
                .then(response => response.json())
                .then(data => {
                    renderReviews(data);
                    btn.setAttribute('data-offset', offset + data.length);
                    if (data.length < 5) {
                        btn.style.display = 'none';
                    }
                })
                .catch(err => console.error(err));
        });
    }
});
</script>

<script>
    function showLoginPrompt() {
        const modalHtml = `
            <div id="modalBackdrop" style="
                position: fixed;
                top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 9998;"></div>

            <div id="loginPromptModal" style="
                position: fixed;
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                background: #fff;
                padding: 25px;
                width: 90%;
                max-width: 400px;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                z-index: 9999;
                font-family: sans-serif;
                text-align: center;
            ">
                <div style="margin-bottom: 20px;">
                    <i class="fa fa-comments" style="font-size: 28px; color: #4CAF50;"></i>
                </div>

                <h4 style="margin-bottom: 10px;">Please log in to enquire</h4>
                <p style="margin-bottom: 20px; color: #555;">Login or register to send a message to the seller.</p>

                <div style="margin-bottom: 15px;">
                    <a href="{{ route('login') }}" style="
                        display: inline-block;
                        background: #4CAF50;
                        color: #fff;
                        padding: 10px 20px;
                        border-radius: 6px;
                        text-decoration: none;
                        margin-bottom: 10px;
                    ">Login</a>
                </div>

                <div style="margin-bottom: 15px;">
                    <a href="{{ route('register') }}" style="
                        display: inline-block;
                        background: #f0f0f0;
                        color: #333;
                        padding: 10px 20px;
                        border-radius: 6px;
                        text-decoration: none;
                        border: 1px solid #ccc;
                    ">Register</a>
                </div>

                <button onclick="closeLoginPrompt()" style="
                    background: none;
                    border: none;
                    color: #888;
                    text-decoration: underline;
                    font-size: 14px;
                    cursor: pointer;
                ">Cancel</button>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }

    function closeLoginPrompt() {
        const modal = document.getElementById('loginPromptModal');
        const backdrop = document.getElementById('modalBackdrop');
        if (modal) modal.remove();
        if (backdrop) backdrop.remove();
    }
</script>
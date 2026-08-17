<script src="https://www.google.com/recaptcha/api.js" async defer></script>
 
<style>
.btn-danger {
    background-color: #dc3545 !important; /* Bootstrap danger red */
    color: #fff !important; /* White text */
    border: none !important;
}
</style>  

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
    <!-- <div class="widget page-widget">
        <div class="inline-btn">
            @php
            if(Auth()->user())
                $likecount = \App\Models\Page_like::where('page_id',$page->id)->where('user_id',auth()->user()->id)->count();
            else 
            $likecount =0;
            @endphp
            
            @if ($likecount>0) -->
                <!-- <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('page.dislike',$page->id); ?>')"  class="btn btn-primary" ><img class="mb-1 me-1" src="{{ asset('assets/frontend/images/like-i.png') }}" alt=""><span class="d-sm-inline-block d-md-none d-xl-inline-block">{{ get_phrase('Liked') }}</a> -->
                <!-- <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('page.dislike',$page->id); ?>')"  class="btn btn-primary" ><img class="mb-1 me-1" src="{{ asset('assets/frontend/images/like-i.png') }}" alt=""><span class="d-sm-inline-block d-md-none d-xl-inline-block">{{ get_phrase('Following') }}</a>
            @else -->
                <!-- <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('page.like',$page->id); ?>')" class="btn btn-primary"><img class="mb-1 me-1" src="{{ asset('assets/frontend/images/like-i.png') }}" alt=""><span class="d-sm-inline-block d-md-none d-xl-inline-block">{{ get_phrase('Like') }}</a> -->
                <!-- <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('page.like',$page->id); ?>')" class="btn btn-primary"><img class="mb-1 me-1" src="{{ asset('assets/frontend/images/like-i.png') }}" alt=""><span class="d-sm-inline-block d-md-none d-xl-inline-block">{{ get_phrase('Follow') }}</a>
            @endif
            <a class="btn btn-primary" href="{{ route('pages') }}"><img src="{{ asset('assets/frontend/images/page.svg') }}" class="w-20 height-20-css" alt=""> <span class="d-sm-inline-block d-md-none d-xl-inline-block">{{ get_phrase('Pages') }}</a>
        </div> -->
    <!-- </div> -->
    <aside class="sidebar plain-sidebar">

        <div class="widget intro-widget">
            <h4>{{get_phrase('Intro')}}</h4>
            <div class="my-about mb-3">
                @php echo ellipsis($page->description, 500); @endphp
            </div>
        </div>
         @php
            $lastCategory = $page->categories->last();
        @endphp
        <div class="widget">
            <h4 class="widget-title mb-2">{{ get_phrase('Info') }}</h4>
            <ul>
                @php
                    $likecount = \App\Models\Page_like::where('page_id',$page->id)->count();
                @endphp
                <li><i class="fa fa-thumbs-up"></i><span>{{ $likecount }} People @if($likecount>1) s @endif  {{ get_phrase('Follow this') }}</span></li>
                @php
                    $postcount = \App\Models\Posts::where('publisher','page')->where('publisher_id',$page->id)->count();
                @endphp
                <li><i class="fa-solid fa-file-lines"></i><span>{{ $postcount }} {{ get_phrase('Posts') }}</span></li>
    
                <li><i class="fa-solid fa-briefcase"></i><span>{{ $page->products_count }} Products</span></li>
                <li><i class="fa-solid fa-location"></i><span> <a href="{{ route('page.city', ['city_slug' => $page->city->city_slug]) }}">
                            {{ $page->city->city_name }}
                        </a></span>
                </li>
                <li><i class="fa-solid fa-tags"></i><span> <a href="{{ route('page.category', ['category_slug' => $lastCategory->category_slug]) }}">
                        {{ $lastCategory->category_name }}
                    </a></span></li>
            </ul>
            @if(Auth()->user())
            @if ($page->user_id==auth()->user()->id)
                <button class="btn btn-primary w-100 mt-3" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.pages.edit-page-info','page_id'=>$page->id])}}', '{{get_phrase('Update Page Info')}}');">{{ get_phrase('Edit Info') }}</button>
            @endif
            @endif
        </div>

    @if(auth()->check())
<div class="card mt-4">
    <div class="card-body">
        <h5>Leave a Review</h5>
        <form method="POST" action="{{ route('pages.reviews.store') }}">
            @csrf
            <input type="hidden" name="marketplace_id" value="{{ $page->id }}">
            <input type="hidden" name="type" value="pages">
            
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
<div class="card mt-4">
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
            data-url="{{ route('marketplace.pages.reviews.load_more', $page->id) }}"
            data-offset="5">
        Load More Reviews
    </button>
@endif
</div>
        <div class="widget">
            <div class="d-flex pagetab-head align-items-center">
                 <span><i class="fa-solid fa-flag"></i></span>
                 <h3 class="widget-title ms-1">{{ get_phrase('Page you may like') }}</h3>
            </div>
            
            @foreach ($suggestedpages as $suggestedpage)
                @php
                
                $catslug = $suggestedpage->page->pagecategories->last()->category_slug ?? null;

                    $likecount = \App\Models\Page_like::where('page_id',$suggestedpage->page_id)->where('user_id',auth()->user()->id)->count();
                    
                @endphp
                @if ($likecount==0)
                <div class="card border-0 mt-3">
                    <img src="{{ get_page_banner_image($suggestedpage->page, 'coverphoto') }}" alt="">
                    <div class="d-flex align-items-center my-2">
                        <a href="{{route('single.page',['city_slug'=>$suggestedpage->page->city->city_slug,'area_slug'=>$suggestedpage->page->area->area_slug,'category_slug'=>$catslug,'item_slug'=>$suggestedpage->page->item_slug])}}"><img class="circle me-2" src="{{ get_page_logo($suggestedpage->page->logo,'logo') }}" width="60" alt=""></a>
                        <div class="ava-info">
                            <h3 class="h6 mb-0"><a href="{{route('single.page',['city_slug'=>$suggestedpage->page->city->city_slug,'area_slug'=>$suggestedpage->page->area->area_slug,'category_slug'=>$catslug,'item_slug'=>$suggestedpage->page->item_slug])}}">{{ $page->title }}</a> </h3>
                            @php
                                $likecount = \App\Models\Page_like::where('page_id',$suggestedpage->id)->count();
                            @endphp
                            <span class="mute small">{{ $likecount }} {{ get_phrase('likes') }}</span>
                        </div>
                    </div>
                    @if ($likecount>0)
                        <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('page.dislike',$page->id); ?>')" class="btn btn-primary"><img src="{{ asset('assets/frontend/images/like-i.png') }}" alt="">{{ get_phrase('Liked') }}</a>
                    @else
                        <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('page.like',$page->id); ?>')" class="btn btn-primary"><img src="{{ asset('assets/frontend/images/like-i.png') }}" alt="">{{ get_phrase('Like') }}</a>
                    @endif
                </div>
                @endif
            @endforeach
        </div>

        <div class="widget">
            <h4 class="widget-title">{{ get_phrase('Photo/Video') }}</h4>
            <div class="row row-cols-3 row-cols-md-5 row-cols-lg-2 row-cols-xl-3 g-1 mt-3">
                @foreach($all_photos as $media_file)
                    @if($media_file->file_type == 'video')
                        <div class="single-item-countable col">
                            <a href="{{ route('single.post',$media_file->post_id) }}">
                                <video muted controlsList="nodownload" class="img-thumbnail w-100 user_info_custom_height">
                                    <source src="{{get_post_video($media_file->file_name)}}" type="">
                                </video>
                            </a>
                        </div>
                    @else
                        <div class="single-item-countable col">
                            <a href="{{ route('single.post',$media_file->post_id) }}">
                                <img class="img-thumbnail w-100 user_info_custom_height" src="{{get_post_image($media_file->file_name, 'optimized')}}">
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
            @php
            $catslug = $page->categories->last()->category_slug ?? null;
            @endphp
            <a href="{{ route('single.page.photos',['city_slug'=>$page->city->city_slug,'area_slug'=>$page->area->area_slug,'category_slug'=>$catslug,'item_slug'=>$page->item_slug]) }}" class="btn btn-primary mt-3 d-block mx-auto">{{ get_phrase('See More') }}</a>
             <!-- Claim Listing Button -->
               <!-- Claim Listing Button -->
               @if(Auth()->user())
<a href="javascript:void(0);" class="btn btn-primary mt-3 d-block mx-auto" data-bs-toggle="modal" data-bs-target="#claimListingModal">
    {{ get_phrase('Claim Listing') }}
</a>
@endif
        </div><!--  Widget End -->
    </aside>
<!-- Claim Listing Modal -->
<div class="modal fade" id="claimListingModal" tabindex="-1" aria-labelledby="claimListingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="claimListingModalLabel">{{ get_phrase('Claim Listing') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="claimListingForm" action="{{ route('claim.listing.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="page_id" value="{{ $page->id }}">

                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Full Name') }}</label>
                        <input type="text" class="form-control" name="full_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Email Address') }}</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Phone Number') }}</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Business Name') }}</label>
                        <input type="text" class="form-control" name="business_name" value="{{ $page->title }}" >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Business Address') }}</label>
                        <input type="text" class="form-control" name="business_address" value="{{ $page->location }}" >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Proof of Ownership') }}</label>
                        <input type="file" class="form-control" name="ownership_proof" accept=".pdf,.jpg,.png,.doc,.docx" required>
                        <small class="text-muted">{{ get_phrase('Upload a business license, utility bill, etc.') }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ get_phrase('Additional Comments (Optional)') }}</label>
                        <textarea class="form-control border" name="additional_comments" rows="3"></textarea>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="termsCheckbox" name="agree_terms" required>
                        <label class="form-check-label" for="termsCheckbox">
                            {{ get_phrase('I agree to the Terms & Conditions') }}
                        </label>
                    </div>
                   
                    <button type="submit" class="btn btn-primary w-100">{{ get_phrase('Submit Claim') }}</button>
                   
                </form>
            </div>
        </div>
    </div>
</div>

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
                        <label for="group_name" class="form-label">Group Name</label>
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
        $('#type').val('page');
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
//             $('#reportForm').reset();
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

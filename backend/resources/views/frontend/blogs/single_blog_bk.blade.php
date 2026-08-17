<?php use Carbon\Carbon;?>
@php
    $comments = DB::table('comments')->join('users', 'comments.user_id', '=', 'users.id')->where('comments.is_type', 'blog')->where('comments.id_of_type', $blog->id)->where('comments.parent_id', 0)->select('comments.*', 'users.name', 'users.photo')->orderBy('comment_id', 'DESC')->take(1)->get();                                                                
    $total_comments = DB::table('comments')->where('comments.is_type', 'blog')->where('comments.id_of_type', $blog->id)->where('comments.parent_id', 0)->get()->count();
@endphp
<?php
         $city= DB::table('cities')->select('cities.*')
         ->where('id', $blog->city_id)
         ->first();
         $area=DB::table('areas')->select('areas.*')
         ->where('id', $blog->area_id)
         ->where('city_id', $blog->city_id)
         ->first();
         $item_categories = DB::table('blog_category')
         ->where('blog_id', $blog->id)
         ->get();
 
         
         $item_count=count($item_categories);
         $categoriesss = DB::table('blogcategories')
             ->where('id', $item_categories[$item_count-1]->category_id)
             ->get();
                                 
         $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 



        $routeParams = [
            'category_slug' => $catslug,
            'blog_slug'    => $blog->blog_slug
        ];

        if (!empty($city->city_slug)) {
            $routeParams['city_slug'] = $city->city_slug;
        }

        if (!empty($area->area_slug)) {
            $routeParams['area_slug'] = $area->area_slug;
        }

        $blogRoute = $catslug && $blog->group_slug ? route('single.blog', $routeParams) : '#';
    ?>
<div class="col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-white pl-0 pr-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('pages') }}">
                                        <i class="fas fa-bars"></i>
                                       Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('blogs') }}">All Categories</a></li>
                                @if(!empty($city)  && !empty($city->city_slug) )
                                <li class="breadcrumb-item"><a href="{{ route('blog.category.city', ['category_slug'=>$catslug, 'city_slug'=>$city->city_slug]) }}">{{ $city->city_name }}</a></li>
                                @endif
                                @if(!empty($city) && !empty($area) && !empty($city->city_slug) && !empty($area->area_slug))
                                <li class="breadcrumb-item"><a href="{{ route('blog.city.area', ['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug, ]) }}">{{ $area->area_name }}</a></li>
                                @endif

                                @foreach($parent_categories as $key => $parent_category)
                                @if(!empty($city) && !empty($area) && !empty($city->city_slug) && !empty($area->area_slug))
                                    <li class="breadcrumb-item"><a href="{{ route('blog.category.city.area', ['city_slug'=>$city->city_slug,'category_slug'=>$category->category_slug,'area_slug' => $area->area_slug]) }}">{{ $parent_category->category_name }}</a></li>
                                @else
                                <li class="breadcrumb-item"><a href="{{ route('category.blog', ['category_slug'=>$category->category_slug]) }}">{{ $parent_category->category_name }}</a></li>
                                @endif
                                @endforeach
                                @if(!empty($city) && !empty($area) && !empty($city->city_slug) && !empty($area->area_slug))
                                <li class="breadcrumb-item"><a href="{{ route('blog.category.city.area', ['city_slug'=>$city->city_slug,'category_slug'=>$category->category_slug,'area_slug' => $area->area_slug]) }}">{{ $category->category_name }}</a></li>
                                @else
                                <li class="breadcrumb-item"><a href="{{ route('category.blog', ['category_slug'=>$category->category_slug]) }}">{{ $category->category_name }}</a></li>
                                @endif
                            </ol>
                        </nav>
                    </div>
<div class="single-wrap">
    <div class="blog-feature" style="background-image: url('{{ get_blog_image($blog->thumbnail,'coverphoto') }}')">
        <div class="blog-head">
            <a href="#" class="btn btn-primary"> {{ $blog->created_at->format("d-M-Y") }} </a>
           <h1 style="margin: 0; font-size: 32px;">
  <span style="
    background: #636363 !important;
    color: #fff !important;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.3s ease;
    display: inline-block;
    margin: 12px 0;    /* top & bottom margin */
  ">
    {{ $blog->title }}
  </span>
</h1>




            <div class="d-flex align-items-center">
                <img src="{{ get_user_image($blog->user_id,'optimized') }}" class="user-round user_image_show_on_modal" alt="">
                <div class="ava-info ms-2">
                    <h6 class="mb-0"><a href="{{ route('user.profile.view',$blog->getUser->id) }}">{{ $blog->getUser->name }}</a></h6>
                    <small>{{ $blog->created_at->diffForHumans()  }}</small>
                </div>
            </div>
           <div class="bhead-meta" style="
    background: rgba(0, 0, 0, 0.6);
    color: #fff;
    padding: 10px 16px;
    border-radius: 8px;
    display: inline-block;
    margin-top: 14px;
    font-size: 14px;
    line-height: 1.6;
">
    <div style="margin-bottom: 4px;">
        <i class="fas fa-comment me-1"></i>
        {{ $total_comments }} {{ get_phrase('Comments') }}
    </div>
    <div>
        <i class="fas fa-eye me-1"></i>
        {{ count(json_decode($blog->view)) }} {{ get_phrase('Views') }}
    </div>
</div>

        </div>
    </div><!--  Blog Cover End -->
    <div class="row g-2 mt-3 ">
        <div class="col-lg-7">
        @if(auth()->check())
    <div class="card mt-4">
    <div class="card-body">
        <h5>Leave a Review</h5>
        <form method="POST" action="{{ route('marketplace.reviews.blog.store') }}">
            @csrf
            <input type="hidden" name="marketplace_id" value="{{ $blog->id }}">
            <input type="hidden" name="type" value="blog">
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
            data-url="{{ route('marketplace.blog.reviews.load_more', $blog->id) }}"
            data-offset="5">
        Load More Reviews
    </button>
@endif
</div>
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
            <div class="card p-3 blog-details">
                @php echo script_checker($blog->description, false); @endphp
                <div class="blog-footer">
                    <div class="post-share justify-content-between align-items-center border-bottom pb-3">
                        <div class="post-meta">
                            @php
                                $tags = json_decode($blog->tag, true);
                            @endphp
                            
                            @if(is_array($tags))
                                @foreach ($tags as $tag )
                                    <a href="#"><span class="badge bg-primary mt-1">#{{ $tag }}</span></a>
                                @endforeach
                            @endif
                        </div>
                        <div class="p-share d-flex align-items-center mt-3">
                            <h3 class="h6">{{ get_phrase('Share') }}: </h3>
                            <div class="social-share ms-2">
                                <ul>
                                    @foreach ($socailshare as $key => $value )
                                        <li><a href="{{ $value }}" target="_blank"><i class="fa-brands fa-{{ $key }}"></i></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comment Start -->
                        <div class="user-comments  bg-white" id="user-comments-{{$blog->id}}">
                            <div class="comment-form d-flex p-3 bg-secondary">
                               
                                @if(Auth()->user())
                                <img src="{{get_user_image(Auth()->user()->photo, 'optimized')}}" alt="" class="rounded-circle img-fluid" width="40px">
                                @endif
                                <form action="javascript:void(0)" class="w-100 ms-2" method="post">
                                    <input class="form-control py-3" onkeypress="postComment(this, 0, {{$blog->id}}, 0,'blog');" rows="1" placeholder="Write Comments">
                                </form>
                            </div>
                            <ul class="comment-wrap pt-3 pb-0 list-unstyled" id="comments{{$blog->id}}">
                                @include('frontend.main_content.comments',['comments'=>$comments,'post_id'=>$blog->id,'type'=>"blog"])
                            </ul>
                            @if($comments->count() < $total_comments) 
                                <a class="btn p-3 pt-0" onclick="loadMoreComments(this, {{$blog->id}}, 0, {{$total_comments}},'blog')">{{get_phrase('View Comment')}}</a>
                            @endif
                        </div>
                    
                </div><!--  Blog Details Footer End -->
            </div>
            
        </div>

    
        <div class="col-lg-5">
            <aside class="sidebar">
                <div class="widget search-widget">
                    <form action="#" class="search-form">
                        <input class="bg-secondary" type="search" id="searchblogfield" placeholder="Search">
                        <span><i class="fa fa-search"></i></span>
                    </form>
                </div>
                <div class="widget recent-posts">
                <a href="javascript:void(0);" 
                onclick="openReportModal({{ $blog->id }}, '{{ $blog->title }}')" 
                class="btn btn-secondary mt-3 d-block mx-auto">
                    {{ get_phrase('Report') }}
                </a>
                    <h3 class="widget-title mb-4">{{ get_phrase('Recent Post') }}</h3>
                    <div class="posts-wrap" id="searchblogviewsection">
                        @foreach ($recent_posts as $post )

                        <?php

         
         $item_categories = DB::table('blog_category')
         ->where('blog_id', $post->id)
         ->get();


        
 
         
         $item_count=count($item_categories);
         $categoriesss = DB::table('blogcategories')
             ->where('id', $item_categories[$item_count-1]->category_id)
             ->first();
             
        if( $categoriesss){
            $catslug = !is_null($categoriesss) ? $categoriesss->category_slug:null; 
            $cat_name = !is_null($categoriesss) ? $categoriesss->category_name:null; 
        }
        else{

            $catslug = ""; 
            $cat_name = ""; 
        }

        $dateString = $blog->created_at; // String
        $created_at = Carbon::parse($dateString);
         
    ?>
                        
                            <div class="post-entry d-flex">
                                <div class="post-thumb"><img class="img-fluid rounded" src="{{ get_blog_image($post->thumbnail,'thumbnail') }}" alt="Recent Post">
                                </div>
                                <div class="post-txt ms-2">
                                    <h3><a class="ellipsis-line-2" href="{{ route('single.blog',['city_slug'=>$post->city_slug,'area_slug'=>$post->area_slug,'category_slug'=>$catslug,'blog_slug'=>$post->blog_slug]) }}">{{ $post->title }}</a></h3>
                                    <div class="post-meta">
                                        <span class="date-meta"><a href="#">{{ $created_at->format("d-M-Y") }}</a></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div> <!-- Recent Post Widget End -->
                <div class="widget tag-widget">
                    <h3 class="widget-title mb-3">{{ get_phrase('Categories') }}</h3>
                    <div class="tags">
                        @foreach ($categories as $category )
                            <a href="{{ route('category.blog',['category_slug'=>$category->category_slug]) }}" class="@if($post->category_id == $category->id) active @endif">{{ $category->category_name }} ({{DB::table('blog_category')->where('category_id', $category->id)->get()->count()}})</a>
                        @endforeach                         
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div><!-- Single Page Wrap End -->
@include('frontend.main_content.scripts')
@include('frontend.initialize')

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
                        <label for="group_name" class="form-label">Blog Name</label>
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
        $('#type').val('blog');
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

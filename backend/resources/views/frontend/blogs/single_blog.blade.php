<?php
         $item_categories = DB::table('blog_category')
         ->where('blog_id', $blog->id)
         ->get();

         $item_count = count($item_categories);
         $categoriesss = DB::table('blogcategories')
             ->where('id', $item_categories[$item_count - 1]->category_id)
             ->first();

         $catslug = !is_null($categoriesss) ? $categoriesss->category_slug : '';

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

        $blogRoute = $catslug ? route('single.blog', $routeParams) : '#';
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
                                    <li class="breadcrumb-item"><a href="{{ route('blog.category.city.area', ['city_slug'=>$city->city_slug,'category_slug'=>$catslug,'area_slug' => $area->area_slug]) }}">{{ $parent_category->category_name }}</a></li>
                                @else
                                <li class="breadcrumb-item"><a href="{{ route('category.blog', ['category_slug'=>$catslug]) }}">{{ $parent_category->category_name }}</a></li>
                                @endif
                                @endforeach
                                @if(!empty($city) && !empty($area) && !empty($city->city_slug) && !empty($area->area_slug))
                                <li class="breadcrumb-item"><a href="{{ route('blog.category.city.area', ['city_slug'=>$city->city_slug,'category_slug'=>$catslug,'area_slug' => $area->area_slug]) }}">{{ $category->category_name }}</a></li>
                                @else
                                <li class="breadcrumb-item"><a href="{{ route('category.blog', ['category_slug'=>$catslug]) }}">{{ $category->category_name }}</a></li>
                                @endif
                            </ol>
                        </nav>
                    </div>

      <div class="container blog-wrapper">
      <div class="row">
        <!-- Main Content -->
        <div class="col-lg-12 order-1 order-lg-1">
          <!-- Banner with Overlay Content at Bottom -->
          <div
            class="position-relative mb-3"
            style="height: 350px; border-radius: 8px; overflow: hidden"
          >
            <!-- Background Image -->
            <div
  class="w-100 h-100 object-fit-cover"
  style="background-image: url('{{ get_blog_banner_image($blog, 'coverphoto') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
  alt="Blog Banner"
></div>

            <!-- Overlay (at bottom of banner) -->
            <div
              class="position-absolute bottom-0 start-0 w-100 text-white p-2"
              style="
                background: linear-gradient(
                  to top,
                  rgba(0, 0, 0, 0.6),
                  transparent
                );
              "
            >
              <h1 class="fw-bold fs-3 mb-2"> {{ $blog->title }}</h1>
              <div class="d-flex align-items-center">
                <img
                  src="https://img.freepik.com/premium-vector/man-profile_1083548-15963.jpg?semt=ais_incoming&w=740&q=80"
                  class="rounded-circle me-2"
                  alt="Author"
                  style="width: 45px; height: 45px; object-fit: cover"
                />
                <div>
                  <strong>{{ $blog->getUser->name }}</strong><br />
                  <small>🕒 Posted on {{ $blog->created_at->diffForHumans()  }}</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Views & Comments Count -->
          <div class="post-stats mb-4" style="margin-left: 1rem">
            @php
              $blogViews = json_decode($blog->view ?? '[]', true);
              $blogViews = is_array($blogViews) ? $blogViews : [];
              $blogTags = json_decode($blog->tag ?? '[]', true);
              $blogTags = is_array($blogTags) ? $blogTags : [];
            @endphp
            <span id="viewsCount">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
              >
                <path
                  d="M12 5c-7 0-11 6-11 7s4 7 11 7 11-6 11-7-4-7-11-7zm0 12c-3.038 0-5.5-2.462-5.5-5.5S8.962 6 12 6s5.5 2.462 5.5 5.5-2.462 5.5-5.5 5.5zm0-9a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7z"
                />
              </svg>
              Views: <strong>{{ count($blogViews) }} {{ get_phrase('Views') }}</strong>
            </span>
            <span id="commentsCount">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
              >
                <path
                  d="M20 2H4a2 2 0 0 0-2 2v15l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"
                />
              </svg>
              Comments: <strong id="commentsNumber">  {{ $total_comments }} {{ get_phrase('Comments') }}</strong>
            </span>
          </div>
      </div>
      </div>
      </div>

          <!-- Blog Content -->
          <div
            class="blog-content-box mb-4 p-4 rounded shadow-sm"
            style="
              background-color: #fff;
              font-family: 'Poppins', sans-serif;
              font-weight: 500;
              line-height: 1.7;
            "
          >
            @php echo script_checker($blog->description, false); @endphp

            @if(!empty($blogTags))
            <div class="mt-3">
              @foreach($blogTags as $tag)
              <span class="badge bg-logo me-2">#{{ $tag }}</span>
              @endforeach
            </div>
            @endif
          </div>

          <!-- Review Box -->
          <!-- <div class="review-box">
            @if(auth()->check())
    <div class="card mt-4">
    <div class="card-body">
        <h5>Leave a Review</h5>
        <form method="POST" action="{{ route('marketplace.reviews.blog.store') }}" enctype="multipart/form-data">
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

             <div class="mb-3">
                <label for="review_image">Upload Image (optional)</label>
                <input type="file" name="review_image" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="review_video">Upload Video (optional)</label>
                <input type="file" name="review_video" class="form-control" accept="video/*">
            </div>

            
            <button class="btn btn-primary" type="submit">Submit Review</button>
        </form>
    </div> -->



<!-- <div id="reviews-container">
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
          </div>

         
        </div> -->

        <!-- Sidebar -->
        <!-- <div class="col-lg-4 order-2 order-lg-2 mt-4 mt-lg-0">
          <aside style="margin-top: 0"> -->
            <!-- 🎯 Sponsors Section -->
          <!--   <div
              style="
                margin-bottom: 24px;
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 16px;
              "
            >
              <h3
                style="
                  font-size: 16px;
                  font-weight: 600;
                  margin-bottom: 16px;
                  border-bottom: 2px solid #eee;
                  padding-bottom: 8px;
                "
              >
                🎯 Sponsored
              </h3>
              <div style="display: flex; flex-direction: column; gap: 12px">
              
                <a
                  href="#"
                  style="
                    display: flex;
                    align-items: center;
                    text-decoration: none;
                    background: #fafafa;
                    border: 1px solid #eee;
                    padding: 10px;
                    border-radius: 6px;
                  "
                >
                  <img
                    src="https://logos-world.net/wp-content/uploads/2020/04/Nike-Logo-1971-present.jpg"
                    alt="Nike"
                    style="margin-right: 10px; border-radius: 4px"
                    width="50"
                    height="50"
                  />
                  <div>
                    <h6
                      style="margin: 0 0 4px; font-weight: 600; font-size: 14px"
                    >
                      Nike
                    </h6>
                    <small style="color: #666">Performance gear & offers</small>
                  </div>
                </a>

               
                <a
                  href="#"
                  style="
                    display: flex;
                    align-items: center;
                    text-decoration: none;
                    background: #fafafa;
                    border: 1px solid #eee;
                    padding: 10px;
                    border-radius: 6px;
                  "
                >
                  <img
                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Spotify_icon.svg/512px-Spotify_icon.svg.png"
                    alt="Spotify"
                    style="margin-right: 10px; border-radius: 4px"
                    width="50"
                    height="50"
                  />
                  <div>
                    <h6
                      style="margin: 0 0 4px; font-weight: 600; font-size: 14px"
                    >
                      Spotify
                    </h6>
                    <small style="color: #666">Stream music free</small>
                  </div>
                </a>
              </div>
            </div> -->

            <!-- 🔥 Trending Products Section -->
            <!-- <div
              style="
                margin-bottom: 24px;
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 16px;
              "
            >
              <h3
                style="
                  font-size: 16px;
                  font-weight: 600;
                  margin-bottom: 16px;
                  border-bottom: 2px solid #eee;
                  padding-bottom: 8px;
                "
              >
      🔥 Trending Blogs
              </h3>
              <div style="display: flex; flex-direction: column; gap: 12px"> -->
                <!-- Product 1 -->
                 <!-- @foreach ($recent_posts as $post ) -->
                   <?php



        
 
         
    //      $item_count=count($item_categories);
    //      
             
    //     if( $categoriesss){
    //         $catslug = !is_null($categoriesss) ? $categoriesss->category_slug:null; 
    //         $cat_name = !is_null($categoriesss) ? $categoriesss->category_name:null; 
    //     }
    //     else{

    //         $catslug = ""; 
    //         $cat_name = ""; 
    //     }

    //     $dateString = $blog->created_at; // String
    //     $created_at = Carbon::parse($dateString);
         
    // ?>
                <!-- <a
                  href="#"
                  style="
                    display: flex;
                    align-items: center;
                    text-decoration: none;
                    background: #fafafa;
                    border: 1px solid #eee;
                    border-radius: 6px;
                    padding: 8px;
                  "
                > -->
                  <!-- <img
                    src="{{ get_blog_banner_image($post, 'thumbnail') }}"
                    alt="Diary"
                    width="50"
                    height="50"
                    style="margin-right: 10px; border-radius: 4px"
                  />
                  <div>
                    <h6
                      style="margin: 0 0 4px; font-size: 14px; font-weight: 600"
                    >
                     {{ $post->title }}
                    </h6>
                  </div>
                </a> -->
  <!-- @endforeach
                Product 2 --> 
               
              <!-- </div>
            </div> -->

            <!-- 📌 Featured Pages Section -->
            <!-- <div
              style="
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 16px;
              "
            >
              <h3
                style="
                  font-size: 16px;
                  font-weight: 600;
                  margin-bottom: 16px;
                  border-bottom: 2px solid #eee;
                  padding-bottom: 8px;
                "
              >
                📌 Recent Post
              </h3>
              <div style="display: flex; flex-direction: column; gap: 12px">
                  @foreach ($recent_posts as $post )
                   <?php

         
        


        
 
         
        //  $item_count=count($item_categories);
        
             
        // if( $categoriesss){
        //     $catslug = !is_null($categoriesss) ? $categoriesss->category_slug:null; 
        //     $cat_name = !is_null($categoriesss) ? $categoriesss->category_name:null; 
        // }
        // else{

        //     $catslug = ""; 
        //     $cat_name = ""; 
        // }

        // $dateString = $blog->created_at; // String
        // $created_at = Carbon::parse($dateString);
         
    ?>
                <a
                  href="#"
                  style="
                    text-decoration: none;
                    color: #000;
                    background: #fafafa;
                    padding: 8px;
                    border-radius: 6px;
                    border: 1px solid #eee;
                  "
                >
                  <h6 style="margin: 0; font-size: 14px">{{ $post->title }}</h6>
                  <small style="color: #777"
                    >Top seller in handmade items</small
                  >
                </a>
               
@endforeach
              </div>
            </div>
          </aside>
        </div>
      </div>
    </div>  

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

      body {
        background-color: #f8f9fa;
        font-family: "Segoe UI", sans-serif;
      }
      .blog-wrapper {
        max-width: 1450px;
        margin: 20px;
      }
      .blog-banner {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
      }
      .blog-title {
        font-size: 1.8rem;
        font-weight: 700;
      }
      .author-box {
        display: flex;
        align-items: center;
        margin-top: 10px;
      }
      .author-box img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
      }
      .sidebar-widget {
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
      }
      .review-box,
      .comments-box {
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        margin-top: 30px;
      }
      .review-stars {
        color: #ffc107;
        font-size: 20px;
      }
      .comment-item {
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
        margin-bottom: 10px;
      }
      .comment-item:last-child {
        border-bottom: none;
      }
      /* Views & Comments info */
      .post-stats {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 15px;
      }
      .post-stats span {
        margin-right: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
      }
      .post-stats svg {
        fill: #666;
        width: 18px;
        height: 18px;
      }
      .bg-logo {
        background-color: #ff4939;
      } 
    </style> -->

    <style>

        .post-stats {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 15px;
      }
      .post-stats span {
        margin-right: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
      }
      .post-stats svg {
        fill: #666;
        width: 18px;
        height: 18px;
      }
      .bg-logo {
        background-color: #ff4939;
      } 
      </style>
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

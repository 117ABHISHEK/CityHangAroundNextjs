@foreach ($blogs as $blog)
    <div class="col-12 col-sm-6 col-md-4 col-lg-6 col-xl-4 my-1 single-item-countable" id="blog-{{ $blog->id }}">
        <article class="single-entry">
            <a
                href="{{ route('single.blog', ['city_slug' => $blog->city_slug, 'area_slug' => $blog->area_slug, 'category_slug' => $blog->cat_slug, 'blog_slug' => $blog->blog_slug]) }}">
                <div class="entry-img thumbnail-210-200"
                    style="background-image: url('{{ get_blog_banner_image($blog, 'thumbnail') }}')">
                    <span class="date-meta">
                        {{ $blog->formatted_date ?? ($blog->publication_date ?? $blog->created_at ?? '') }}
                    </span>
                </div>
            </a>
            <div class="entry-txt min-hight-125px">
                <div class="blog-meta">
                    <span><a
                            href="{{ route('single.blog', ['city_slug' => $blog->city_slug, 'area_slug' => $blog->area_slug, 'category_slug' => $blog->cat_slug, 'blog_slug' => $blog->blog_slug]) }}">{{ $blog->cat_name }}</a></span>
                </div>
                <h3 class="h6 ellipsis-line-2"><a
                        href="{{ route('single.blog', ['city_slug' => $blog->city_slug, 'area_slug' => $blog->area_slug, 'category_slug' => $blog->cat_slug, 'blog_slug' => $blog->blog_slug]) }}">{{ $blog->title }}</a>
                </h3>
                <div class="d-flex blog-ava">
                    <img src="{{ get_user_image($blog->user_photo, 'optimized') }}" class="user-round" alt="">
                    <div class="ava-info">
                        <h6><a href="{{ route('user.profile.view', $blog->userid) }}">{{ $blog->username }}</a></h6>
                        <small>{{ $blog->formatted_date ?? ($blog->publication_date ?? $blog->created_at ?? '') }}</small>
                    </div>
                </div>
            </div>
        </article>
    </div>
@endforeach

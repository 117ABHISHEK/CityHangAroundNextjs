<?php use Carbon\Carbon;?>
@php
    $schema = [
        "@@context" => "https://schema.org",
        "@type" => "ItemList",
        "name" => "Top Businesses in {$city->city_name}",
        "url" => request()->fullUrl(),
    ];
@endphp

<script type="application/ld+json">
{!! json_encode(
    $schema,
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
) !!}
</script>

<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white pl-0 pr-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('blogs') }}">
                        <i class="fas fa-bars"></i>
                        Home
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('blogs') }}">Blog</a></li>
                <li class="breadcrumb-item"><a href="">{{ $city->city_name }}</a></li>
            </ol>
        </nav>
    </div>
</div>
<div class="page-wrap">
    <div class="d-flex align-items-center flex-wrap">
        <h1 class="h2 mb-0 text-dark" style="font-weight: 700; letter-spacing: -0.5px;">Blogs</h1>
        <a href="{{ route('create.blog') }}" class="btn-create-article-inline">
            <i class="fa-solid fa-plus me-1"></i>{{ get_phrase('Create articles') }}
        </a>
    </div>
    <div class="metadata-row">
        <span>{{ $city->city_name }}</span>
    </div>

    @foreach ($categories as $key => $category)
            <div class="card blog-tags p-4">
                <h1 class="font-weight-light text-primary">{{ $category->category_name }} in {{$city->city_name}}</h1>
            </div>
            <?php 

            $blogs = App\Http\Controllers\BlogController::getblogsbycategoryid($category->id, $city->id);

           ?>
            <div class="row g-3 blog-cards mt-3">
                @foreach ($blogs as $blog)
                        <?php


                        $item_categories = DB::table('blog_category')
                            ->where('blog_id', $blog->id)
                            ->get();





                        $item_count = count($item_categories);
                        $categoriesss = DB::table('blogcategories')
                            ->where('id', $item_categories[$item_count - 1]->category_id)
                            ->first();

                        if ($categoriesss) {
                            $catslug = !is_null($categoriesss) ? $categoriesss->category_slug : null;
                            $cat_name = !is_null($categoriesss) ? $categoriesss->category_name : null;
                        } else {

                            $catslug = "";
                            $cat_name = "";
                        }

                        $dateString = $blog->created_at; // String
                        $created_at = Carbon::parse($dateString);

                    ?>
                        <div class="col-lg-4" id="blog-{{ $blog->id }}">
                            <article class="single-entry">
                                <div class="entry-img">
                                    <a
                                        href="{{ route('single.blog', ['city_slug' => $blog->city_slug, 'area_slug' => $blog->area_slug, 'category_slug' => $catslug, 'blog_slug' => $blog->blog_slug]) }}"><img
                                            src="{{ get_blog_banner_image($blog, 'thumbnail') }}" alt="" class="img-fluid"></a>
                                    <span class="date-meta">{{ $created_at->format("d-M-Y") }}</span>
                                </div>
                                <div class="entry-txt">
                                    <div class="blog-meta">
                                        <span><a href="#">{{ $cat_name }}</a></span>
                                    </div>
                                    <h3 class="h6"><a
                                            href="{{ route('single.blog', ['city_slug' => $blog->city_slug, 'area_slug' => $blog->area_slug, 'category_slug' => $catslug, 'blog_slug' => $blog->blog_slug]) }}">{{$blog->title}}</a>
                                    </h3>
                                    <div class="d-flex justify-content-between blog-ava">
                                        <div class="d-flex">
                                            <img src="{{ get_user_image($blog->userid, 'optimized') }}" class="user-round" alt="">
                                            <div class="ava-info">
                                                <h6><a href="#">{{ $blog->username }}</a></h6>
                                                <small>{{ $created_at->diffForHumans()  }} </small>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-secondary" type="button" id="dropdownMenuButton1"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa-solid fa-ellipsis"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a href="{{ route('blog.edit', $blog->id) }}"
                                                            class="dropdown-item btn btn-primary btn-sm"> <i class="fa fa-edit"></i>
                                                            {{ get_phrase('Edit Article') }}</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)"
                                                            onclick="confirmAction('<?php        echo route('blog.delete', ['blog_id' => $blog->id]); ?>', true)"
                                                            class="dropdown-item btn btn-primary btn-sm"><i
                                                                class="fa fa-trash me-1"></i> {{get_phrase('Delete Article')}}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                @endforeach
                <a href="{{ route('category.blog', ['category_slug' => $category->category_slug]) }}"
                    style="text-align:center;">View All</a>
            </div>
    @endforeach
</div>

<?php use Carbon\Carbon;?>
<div class="page-wrap">
    <div class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5 pt-3"><span><img width="12" src="{{ asset('assets/frontend/images/stickies-fill.png') }}" alt=""></span> {{ get_phrase('Blogs') }}</h3>
        <div class="inline-btn w-50">
            <a href="{{ route('create.blog') }}" class="btn btn-primary"><i class="fa fa-plus-circle me-1"></i>{{ get_phrase('Create articles') }}</a>
        </div>
    </div>

    <div class="card blog-tags p-4">
        <div class="tags">
            @foreach ($categories as $category )
                <a href="{{ route('category.blog',['category_slug'=>$category->category_slug]) }}" class="@if($category->id == $category_id) active @endif">{{ $category->category_name }}</a>
            @endforeach 
        </div>
    </div>
    <div class="card blog-tags p-4">
    <h1 class="font-weight-light text-primary">{{ $category->category_name }}</h1>
   </div>

    <div class="row g-3 blog-cards mt-3">
        @foreach ($blogs as $blog )
        <?php

         
         $item_categories = DB::table('blog_category')
         ->where('blog_id', $blog->id)
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
            <div class="col-lg-4" id="blog-{{ $blog->id }}">
                <article class="single-entry">
                    <div class="entry-img">
                        <a href="{{ route('single.blog',['city_slug'=>$blog->city_slug,'area_slug'=>$blog->area_slug,'category_slug'=>$catslug,'blog_slug'=>$blog->blog_slug]) }}"><img src="{{ get_blog_image($blog->thumbnail,'thumbnail') }}" alt="" class="img-fluid"></a>
                        <span class="date-meta">{{ $created_at->format("d-M-Y") }}</span>
                    </div>
                    <div class="entry-txt">
                        <div class="blog-meta">
                            <span><a href="#">{{ $cat_name }}</a></span>
                        </div>
                        <h3 class="h6"><a href="{{ route('single.blog',['city_slug'=>$blog->city_slug,'area_slug'=>$blog->area_slug,'category_slug'=>$catslug,'blog_slug'=>$blog->blog_slug]) }}">{{$blog->title}}</a></h3>
                        <div class="d-flex justify-content-between blog-ava">
                            <div class="d-flex">
                                <img src="{{ get_user_image($blog->userid,'optimized') }}" class="user-round" alt="">
                                <div class="ava-info">
                                    <h6><a href="#">{{ $blog->username }}</a></h6>
                                    <small>{{ $created_at->diffForHumans()  }} </small>
                                </div>
                            </div>
                            <div class="dropdown">
                                <div class="dropdown">
                                    <button class="btn btn-secondary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i> 
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <a href="{{ route('blog.edit',$blog->id) }}" class="dropdown-item btn btn-primary btn-sm"> <i class="fa fa-edit"></i> {{ get_phrase('Edit Article') }}</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" onclick="confirmAction('<?php echo route('blog.delete', ['blog_id' => $blog->id]); ?>', true)" class="dropdown-item btn btn-primary btn-sm"><i class="fa fa-trash me-1"></i> {{get_phrase('Delete Article')}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        @endforeach
    </div>
</div>
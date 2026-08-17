<?php use Carbon\Carbon;?>
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
    <div class="col-12 col-sm-6 col-md-4 col-lg-6 col-xl-4 my-1 single-item-countable" id="blog-{{ $blog->id }}">
        <article class="single-entry">
            <a href="{{ route('single.blog',['city_slug'=>$blog->city_slug,'area_slug'=>$blog->area_slug,'category_slug'=>$catslug,'blog_slug'=>$blog->blog_slug]) }}">
                <div class="entry-img thumbnail-210-200" style="background-image: url('{{ get_blog_banner_image($blog, 'thumbnail') }}')">
                    <span class="date-meta">{{ $created_at->timezone('Asia/Kolkata')->diffForHumans()}}</span>
                </div>
            </a>
            <div class="entry-txt min-hight-125px">
                <div class="blog-meta">
                    <span><a href="{{ route('single.blog',['city_slug'=>$blog->city_slug,'area_slug'=>$blog->area_slug,'category_slug'=>$catslug,'blog_slug'=>$blog->blog_slug]) }}">{{ $cat_name }}</a></span>
                </div>
                <h3 class="h6 ellipsis-line-2"><a href="{{ route('single.blog',['city_slug'=>$blog->city_slug,'area_slug'=>$blog->area_slug,'category_slug'=>$catslug,'blog_slug'=>$blog->blog_slug]) }}">{{$blog->title}}</a></h3>
                <div class="d-flex blog-ava">
                    <img src="{{ get_user_image($blog->user_id,'optimized') }}" class="user-round" alt="">
                    <div class="ava-info">
                        <h6><a href="{{ route('user.profile.view',$blog->user->id) }}">{{ $blog->user->username }} </a></h6>
                        <small>{{ $created_at->timezone('Asia/Kolkata')->diffForHumans() }}</small>

                    </div>
                </div>
            </div>
        </article>
    </div> 
@endforeach

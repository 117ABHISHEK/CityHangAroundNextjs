
<div class="row">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-white pl-0 pr-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('pages') }}">
                                        <i class="fas fa-bars"></i>
                                       Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('pages') }}">All Categories</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('page.category.city', ['category_slug'=>$category->category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $city->city_name }}</a></li>


                                @foreach($parent_categories as $key => $parent_category)
                                    <li class="breadcrumb-item"><a href="{{ route('page.category.city', ['category_slug'=>$parent_category->category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $parent_category->category_name }}</a></li>
                                @endforeach
                                <li class="breadcrumb-item"><a href="{{ route('page.category.city', ['category_slug'=>$category->category_slug, 'city_slug'=>$city->city_slug]) }}">{{ $category->category_name }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
<div class="suggest-wrap row" id="pagedata">
<h1 class="font-weight-light text-primary">
Top {{ $category->category_name }} business listings in {{$city->city_name}}
    </h1>

    @foreach ($mypages as $key => $mypage)
    <?php
         
         $item_categories = DB::table('page_category')
         ->where('page_id', $mypage->id)
         ->get();
 
         
         $item_count=count($item_categories);
         $categoriesss = DB::table('pagecategories')
             ->where('id', $item_categories[$item_count-1]->category_id)
             ->get();
                                 
         $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
    ?>
    
    <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mb-3" id="page-{{ $mypage->id }}">
        
    
    <div class="card sugg-card p-2 rounded">
    <a href="{{ route('page.category',['category_slug'=>$catslug]) }}">
            {{ $categoriesss[0]->category_name }}
             </a>
            <a href="{{ route('single.page',['city_slug'=>$mypage->city_slug,'area_slug'=>$mypage->area_slug,'category_slug'=>$catslug,'item_slug'=>$mypage->item_slug]) }}">
                <img src="{{ get_page_logo($mypage->logo, 'logo') }}" class="rounded-8px" width="90px" alt="">
            </a>
           
            <div class="smp-info">
                <a href="{{ route('single.page',['city_slug'=>$mypage->city_slug,'area_slug'=>$mypage->area_slug,'category_slug'=>$catslug,'item_slug'=>$mypage->item_slug]) }}"> <h4 class="h6">{{ ellipsis($mypage->title,10) }}</h4> </a>
              
                <a href="{{ route('page.category.city',['category_slug'=>$catslug,'city_slug'=>$mypage->city_slug]) }}">
            {{ $mypage->city_name }}, {{ $mypage->state_name }}
             </a>
             <br>
                @php
                    $likecount = \App\Models\Page_like::where('page_id',$mypage->id)->count();
                @endphp
                <a href="{{ route('single.page',['city_slug'=>$mypage->city_slug,'area_slug'=>$mypage->area_slug,'category_slug'=>$catslug,'item_slug'=>$mypage->item_slug]) }}"><span><i class="fa fa-thumbs-up"></i>{{ $likecount }} {{ get_phrase('People like this') }}</span></a>
            </div>
        </div>

        </div>

    @endforeach
    <div class="row">
                        <div class="col-12">
                            {{ $mypages->links() }}
                        </div>
                    </div>
</div>
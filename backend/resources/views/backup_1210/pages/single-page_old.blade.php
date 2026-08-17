<div class="suggest-wrap row" id="pagedata">
   

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
  
              <a href="{{ route('single.page',['city_slug'=>$mypage->city_slug,'area_slug'=>$mypage->area_slug,'category_slug'=>$catslug,'item_slug'=>$mypage->item_slug]) }}" class="mb-2 thumbnail-110-106" style="background-image: url('{{ get_page_logo($mypage->logo, 'logo') }}')"></a>
           
           
           
            <div class="smp-info">
                <a href="{{ route('single.page',['city_slug'=>$mypage->city_slug,'area_slug'=>$mypage->area_slug,'category_slug'=>$catslug,'item_slug'=>$mypage->item_slug]) }}"> <h4 class="h6">{{ ellipsis($mypage->title,10) }}</h4> </a>

                  <a href="{{ route('page.category',['category_slug'=>$catslug]) }}">
    {{ $categoriesss[0]->category_name }}
             </a>
              
                <a href="{{ route('page.category.city',['category_slug'=>$catslug,'city_slug'=>$mypage->city_slug]) }}">
            {{ $mypage->city_name }}, {{ $mypage->state_name }}
             </a>
             <br>
                @php
                    $likecount = \App\Models\Page_like::where('page_id',$mypage->id)->count();
                @endphp
                <a href="{{ route('single.page',['city_slug'=>$mypage->city_slug,'area_slug'=>$mypage->area_slug,'category_slug'=>$catslug,'item_slug'=>$mypage->item_slug]) }}"><span><i class="fa fa-thumbs-up"></i>{{ $likecount }} {{ get_phrase('People follow this') }}</span></a>
            </div>
        </div>

        </div>

    @endforeach
</div>
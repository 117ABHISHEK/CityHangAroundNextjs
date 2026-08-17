<div class="suggest-wrap row">
    @foreach ($likedpage as $likepage )
    <?php
         
         $item_categories = DB::table('page_category')
         ->where('page_id', $likepage->id)
         ->get();
 
         
         $item_count=count($item_categories);
         $categoriesss = DB::table('pagecategories')
             ->where('id', $item_categories[$item_count-1]->category_id)
             ->get();
                                 
         $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
    ?>
        @php
            $pagedata = \App\Models\Page::find($likepage->id);
        @endphp
        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card sugg-card p-2 rounded">
           
                <a href="{{ route('single.page',['city_slug'=>$likepage->city_slug,'area_slug'=>$likepage->area_slug,'category_slug'=>$catslug,'item_slug'=>$likepage->item_slug]) }}" class="mb-2 thumbnail-110-auto" style="background-image: url('{{ get_page_logo($pagedata->logo, 'logo') }}')"></a>
                <h4><a href="{{ route('single.page',['city_slug'=>$likepage->city_slug,'area_slug'=>$likepage->area_slug,'category_slug'=>$catslug,'item_slug'=>$likepage->item_slug]) }}">{{ ellipsis($pagedata->title,10) }}</a></h4>
                 <a href="{{ route('page.category',['category_slug'=>$catslug]) }}">
            {{ $categoriesss[0]->category_name }}
             </a>
                <h4><a href="{{ route('page.category.city',['category_slug'=>$catslug,'city_slug'=>$likepage->city_slug]) }}">
            {{ $likepage->city_name }}, {{ $likepage->state_name }}
             </a></h4>
                @php
                    $likecount = \App\Models\Page_like::where('page_id',$pagedata->id)->count();
                @endphp
                <span class="small text-muted">{{get_phrase('____ likes', [$likecount])}}</span>
                @php
                //checking this user data if this user already liker or not
                    $likecount = \App\Models\Page_like::where('page_id',$likepage->id)->where('user_id',auth()->user()->id)->count();
                @endphp
                @if ($likecount>0)
                    <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('page.dislike',$likepage->id); ?>')" class="btn btn-primary"><i class="fa fa-thumbs-up"></i>{{ ('Liked') }}</a>
                @else
                    <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('page.like',$likepage->id); ?>')" class="btn btn-primary"><i class="fa fa-thumbs-up"></i>{{ ('Like') }}</a>
                @endif
            </div><!--  Card End -->
        </div>
    @endforeach
</div> 
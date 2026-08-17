
<style>
  .sugg-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    padding: 12px; /* Reduce padding */
    border-radius: 10px; /* Optional: Make it slightly rounded */
}

.sugg-card a {
    display: block;
    font-size: 14px; /* Reduce font size for links */
    margin-bottom: 5px; /* Reduce space between links */
}

.thumbnail-110-106 {
    width: 100%;
    height: 100px; /* Ensure uniform image height */
    background-size: cover;
    background-position: center;
    border-radius: 8px; /* Optional: Rounded corners */
}

.sugg-card h4 {
    font-size: 16px; /* Adjust heading size */
    line-height: 1.3;
    margin-bottom: 5px;
}

.sugg-card .text-muted {
    font-size: 12px; /* Reduce font size for like count */
    margin-bottom: 5px;
}

.sugg-card .btn-primary {
    font-size: 14px;
    padding: 6px 10px; /* Reduce button size */
    margin-top: auto; /* Push button to bottom */
}

.col-xl-3, .col-lg-4, .col-md-3, .col-sm-4, .col-6 {
    padding: 5px; /* Reduce space between columns */
}


</style>   
<div class="suggest-wrap row">
    @foreach ($pages as $suggestedpage)
    <?php
         
         $item_categories = DB::table('page_category')
         ->where('page_id', $suggestedpage->id)
         ->get();
 
         
         $item_count=count($item_categories);
         $categoriesss = DB::table('pagecategories')
             ->where('id', $item_categories[$item_count-1]->category_id)
             ->get();
                                 
         $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
    ?>
        <div class="col-xl-4 col-lg-4 col-md-3 col-sm-4 col-6 mb-3">
            <div class="card sugg-card p-2 rounded">
            
                <a href="{{ route('single.page',['city_slug'=>$suggestedpage->city_slug,'area_slug'=>$suggestedpage->area_slug,'category_slug'=>$catslug,'item_slug'=>$suggestedpage->item_slug]) }}" class="mb-2 thumbnail-110-106" style="background-image: url('{{ get_page_logo($suggestedpage->logo, 'logo') }}')"></a>
                <h4><a href="#">{{ ellipsis($suggestedpage->title,10) }}</a></h4>
                <a href="{{ route('page.category',['category_slug'=>$catslug]) }}">
            {{ $categoriesss[0]->category_name }}
             </a>
                <h4><a href="{{ route('page.category.city',['category_slug'=>$catslug,'city_slug'=>$suggestedpage->city_slug]) }}">
            {{ $suggestedpage->city_name }}, {{ $suggestedpage->state_name }}
             </a></h4>
                @php
                $likecount = \App\Models\Page_like::where('page_id',$suggestedpage->id)->count();
                @endphp
                <span class="small text-muted">{{ $likecount }} {{ ('likes') }}</span>
                @php
                    $likecount = \App\Models\Page_like::where('page_id',$suggestedpage->id)->where('user_id',auth()->user()->id)->count();
                @endphp
                @if ($likecount>0)
                    <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('page.dislike',$suggestedpage->id); ?>')" class="btn btn-primary"><i class="fa fa-thumbs-up"></i>{{ ('Liked') }}</a>
                @else
                    <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('page.like',$suggestedpage->id); ?>')" class="btn btn-primary"><i class="fa fa-thumbs-up"></i>{{ get_phrase('Like') }}</a>
                @endif
            </div>
        </div>
    @endforeach
    <div class="row">
                        <div class="col-12">
                            {{ $pages->links() }}
                        </div>
                    </div>
</div>
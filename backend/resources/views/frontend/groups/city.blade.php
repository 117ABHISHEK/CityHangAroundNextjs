<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
<script type="application/ld+json">
            {
                 "@@context":"https://schema.org",
                 "@type":"Review","itemReviewed":{
                 "@type":"LocalBusiness",
                 "name":"Top 5 LocalBusiness in {{$city->city_name}}",
                 "url":"{{$_SERVER['REQUEST_URI']}}",
                 "address":{"@type":"PostalAddress","addressLocality":"{{$city->city_name}}"}},
                 "author":"Users",
                 "ReviewRating":{
                    "@type":"AggregateRating",
                    "ratingValue":"4.1",
                    "ratingCount":"14198",
                    "bestRating":"5"
            }}
</script>
   
<div class="row">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-white pl-0 pr-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('event') }}">
                                        <i class="fas fa-bars"></i>
                                       Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('groups') }}">All Categories</a></li>
                                <li class="breadcrumb-item"><a href="">{{ $city->city_name }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>

@foreach ($categories as $key => $category)               
    <?php
     $groups=App\Http\Controllers\GroupController::getgroupbycategoryid($category->id,$city->id);
    
    ?>
<div class="row gx-3">
    <div class="col-lg-12">
        <div class="group-inner ">
            
            <div class="page-suggest mt-4">
                <h1 class="h1">Top {{ $category->category_name }} groups listings in {{$city->city_name}}</h1>
                <div class="ps-wrap mt-3 justify-content-between">
                <div class="row">
                        @foreach ($groups as $group)
                        <?php

                            //print_r($group);exit;

                            $city= DB::table('cities')
                            ->where('id', $group->city_id)
                            ->first();

                            $area= DB::table('areas')
                            ->where('city_id', $group->city_id)
                            ->where('id', $group->area_id)
                            ->first();

                            // Skip this group if city or area is not found
                            if (!$city || !$area) {
                                continue;
                            }

                            $item_categories = DB::table('group_category')
                            ->where('group_id',$group->id)
                            ->get();


                            $item_count=count($item_categories);
                            $categoriesss = DB::table('groupcategories')
                                ->where('id', $item_categories[$item_count-1]->category_id)
                                ->get();
                                                    
                            $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
                            $catname = !is_null($categoriesss) ? $categoriesss[0]->category_name:null; 
                            ?>
                            <div class="col-md-3">
                                <div class="card p-2 rounded">
                                    <div class="mb-2 thumbnail-103-103" style="background-image: url('{{ get_group_logo($group->logo,'logo') }}');"></div>
                                    <a href="{{ route('single.group',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'group_slug'=>$group->group_slug]) }}"><h4>{{ ellipsis($group->title,10) }}</h4></a>
                                    <a href="{{ route('category.group',['category_slug'=>$catslug]) }}"><h6>{{ $catname }}</h6></a>
                                    @php $joined = \App\Models\Group_member::where('group_id',$group->id)->where('is_accepted','1')->count(); @endphp
                                    <span class="small text-muted">{{ $joined }} {{ get_phrase('Member') }}{{ $joined>1?"s":"" }}</span>
                                    @php $join = \App\Models\Group_member::where('group_id',$group->id)->where('user_id',auth()->user()->id)->count(); @endphp
                                    
                                </div>
                                
                            </div>
                        @endforeach
                        
                    </div>
                </div>
              
            </div>
        </div>
    </div><!--  Group Content Inner Col End -->
    
   
</div>
@endforeach
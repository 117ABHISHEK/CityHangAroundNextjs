<?php
    // Safely fetch city and area using optional chaining approach
    $city = null;
    $area = null;

    if (!empty($group->city_id)) {
        $city = DB::table('cities')->where('id', $group->city_id)->first();
    }

    if (!empty($group->city_id) && !empty($group->area_id)) {
        $area = DB::table('areas')->where('city_id', $group->city_id)->where('id', $group->area_id)->first();
    }

    $item_categories = DB::table('group_category')
        ->where('group_id', $group->id)
        ->get();

    $catslug = null;

    if (!$item_categories->isEmpty()) {
        $last_category = $item_categories->last();
        $categoriesss = DB::table('groupcategories')
            ->where('id', $last_category->category_id)
            ->first();

        $catslug = optional($categoriesss)->category_slug;
    }
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
                                <li class="breadcrumb-item"><a href="{{ route('groups') }}">All Categories</a></li>
                                @if(!empty($city)  && !empty($city->city_slug) )
                                <li class="breadcrumb-item">
                                    <a href="{{ route('group.category.city', ['category_slug' => $catslug, 'city_slug' => $city->city_slug]) }}">
                                        {{ $city->city_name }}
                                    </a>
                                </li>
                                @endif
                                @if(!empty($city) && !empty($area) && !empty($city->city_slug) && !empty($area->area_slug))
                                <li class="breadcrumb-item">
                                    <a href="{{ route('group.city.area', ['city_slug' => $city->city_slug, 'area_slug' => $area->area_slug]) }}">
                                        {{ $area->area_name }}
                                    </a>
                                </li>
                               @endif


                                @foreach($parent_categories as $key => $parent_category)
                                @if(!empty($city) && !empty($area) && !empty($city->city_slug) && !empty($area->area_slug))
                                <li class="breadcrumb-item"><a href="{{ route('group.category.city.area', ['city_slug'=>$city->city_slug,'category_slug'=>$category->category_slug, 'area_slug'=>$area->area_slug]) }}">{{ $parent_category->category_name }}</a></li>
                                @else
                                <li class="breadcrumb-item"><a href="{{ route('category.group', ['category_slug'=>$category->category_slug]) }}">{{ $parent_category->category_name }}</a></li>
                                @endif
                                @endforeach
                                @if(!empty($city) && !empty($area) && !empty($city->city_slug) && !empty($area->area_slug))
                                <li class="breadcrumb-item"><a href="{{ route('group.category.city.area', ['city_slug'=>$city->city_slug,'category_slug'=>$category->category_slug, 'area_slug'=>$area->area_slug]) }}">{{ $category->category_name }}</a></li>
                                @else
                                <li class="breadcrumb-item"><a href="{{ route('category.group', ['category_slug'=>$category->category_slug]) }}">{{ $category->category_name }}</a></li>
                                @endif
                            </ol>
                        </nav>
                    </div>
 <div class="profile-cover group-cover rounded mb-3">
        @include('frontend.groups.cover-photo')
    </div>
    <div class="group-content profile-content">
        <div class="row gx-3">
            <div class="col-lg-7 col-sm-12">
                 @if(auth()->user())
                @php $join = \App\Models\Group_member::where('group_id',$group->id)->where('user_id',auth()->user()->id)->count(); @endphp
                @if ($join>0||$group->user_id==auth()->user()->id)
                    @include('frontend.groups.iner-nav')
                    @include('frontend.main_content.create_post',['group_id'=>$group->id])
                    @php
                        $comments = DB::table('comments')->join('users', 'comments.user_id', '=', 'users.id')->where('comments.is_type', 'post')->where('comments.id_of_type', $group->id)->where('comments.parent_id', 0)->select('comments.*', 'users.name', 'users.photo')->orderBy('comment_id', 'DESC')->take(1)->get();                                                                
                        $total_comments = DB::table('comments')->where('comments.is_type', 'post')->where('comments.id_of_type', $group->id)->where('comments.parent_id', 0)->get()->count();
                    @endphp

                    @include('frontend.main_content.comments',['comments'=>$comments,'post_id'=>$group->id,'type'=>"group"])
                    
                    @if($comments->count() < $total_comments) 
                        <a class="btn p-3 pt-0" onclick="loadMoreComments(this, {{$group->id}}, 0, {{$total_comments}},'group')">{{get_phrase('View Comment')}}</a>
                    @endif
                    
                        @include('frontend.main_content.posts',['type'=>"group"])
                    
                    
                @else
                <div class="card">
                    <div class="card-body">
                        {{ get_phrase('join Group First') }}
                    </div>
                </div>
                @endif
                 @endif
            </div> <!-- COL END -->
            <!--  Group Content Inner Col End -->
            @include('frontend.groups.bio')
        </div>
    </div><!-- Group content End -->
@include('frontend.main_content.scripts')
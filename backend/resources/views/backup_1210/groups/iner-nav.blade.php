
<nav class="profile-nav border bg-white mb-3">  
<?php

//print_r($group);exit;

 $city= DB::table('cities')
 ->where('id', $group->city_id)
 ->first();

 $area= DB::table('areas')
 ->where('city_id', $group->city_id)
 ->where('id', $group->area_id)
 ->first();

 $item_categories = DB::table('group_category')
 ->where('group_id',$group->id)
 ->get();


 $item_count=count($item_categories);
 $categoriesss = DB::table('pagecategories')
     ->where('id', $item_categories[$item_count-1]->category_id)
     ->get();
                         
 $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 

 $routeParams = [
    'category_slug' => $catslug,
    'group_slug'    => $group->group_slug
];

if (!empty($city->city_slug)) {
    $routeParams['city_slug'] = $city->city_slug;
}

if (!empty($area->area_slug)) {
    $routeParams['area_slug'] = $area->area_slug;
}

$discussionRoute = $catslug && $group->group_slug ? route('single.group', $routeParams) : '#';
 ?>
    <ul class="nav align-items-center justify-content-center">
        <li class="nav-item @if(str_contains(url()->current(), 'group/view/details')) active @endif"><a href="{{ $discussionRoute }}"
                class="nav-link">{{ get_phrase('Discussion')}}</a></li>
        <li class="nav-item @if(str_contains(url()->current(), 'group/peopel/info/')) active @endif"><a href="{{ route('group.people.info',$group->id) }}" class="nav-link">{{ get_phrase('People') }}</a>
        </li>
        <li class="nav-item @if(str_contains(url()->current(), 'group/event/view/')) active @endif"><a href="{{ route('group.event.view',$group->id) }}" class="nav-link">{{ get_phrase('Events') }}</a>
        </li>
        <li class="nav-item @if(str_contains(url()->current(), 'group/photo/view')) active @endif"><a href="{{ route('single.group.photos',$group->id) }}" class="nav-link">{{ get_phrase('Media') }}</a>
        </li>
    </ul>
</nav>
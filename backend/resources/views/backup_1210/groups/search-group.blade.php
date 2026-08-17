<div class="row gx-3">
    <div class="col-lg-12">
        <div class="group-inner">
            <div class="gr-search">
                <h3 class="h6"><span><i class="fa-solid fa-users"></i></span>{{ get_phrase('Group') }}</h3>
                <form action="{{ route('search.group') }}" method="GET">
                    <div class="position-relative;">
                    <input type="text" class="form-control bg-secondary rounded ps-5" name="search" value="@if(isset($_GET['search'])) {{ $_GET['search'] }} @endif" placeholder="Search Group">
                    <span class="i fa fa-search position-absolute" style="left:15px; top:50%; transform:translateY(-50%);"></span>
                </form>
            </div>
            <div class="page-suggest mt-4">
                <h3 class="h6">{{ get_phrase('Community')}}</h3>
                <div class="ps-wrap mt-3 justify-content-between">
                    <div class="row gx-2">
                        @foreach ($searchgroup as $group)
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
->where('group_id', $group->id)
->get();

$item_count = count($item_categories);

if ($item_count > 0) { // Ensure there is at least one item before accessing it
$last_category_id = $item_categories[$item_count - 1]->category_id;

$categoriesss = DB::table('groupcategories')
    ->where('id', $last_category_id)
    ->get();

$catslug = count($categoriesss) > 0 ? $categoriesss[0]->category_slug : null;
$catname = count($categoriesss) > 0 ? $categoriesss[0]->category_name : null;
} else {
// Handle case where there are no categories
$catslug = null;
$catname = null;
}

?>
 <?php if (!empty($catslug)) {?>
                            <div class="col-md-4 col-lg-4 col-sm-6">
                                <div class="card p-2 rounded">
                                    <div class="mb-2"> <img class="img-fluid img-radisu" src="{{ get_group_logo($group->logo,'logo') }}" ></div>
                                    <a href="{{ route('single.group',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'group_slug'=>$group->group_slug]) }}"><h4>{{ ellipsis($group->title,10) }}</h4></a>
                                    @php $joined = \App\Models\Group_member::where('group_id',$group->id)->where('is_accepted','1')->count(); @endphp
                                    <span class="small text-muted">{{ $joined }} Member @if($joined>1) s @endif</span>
                                    @php $join = \App\Models\Group_member::where('group_id',$group->id)->where('user_id',auth()->user()->id)->count(); @endphp
                                    @if ($join>0)
                                    <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('group.rjoin',$group->id); ?>')" class="btn btn-primary">{{ get_phrase('Joined') }}</a>
                                    @else
                                        <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('group.join',$group->id); ?>')" class="btn btn-primary">{{ get_phrase('Join') }}</a>
                                    @endif
                                </div>
                            </div>
                            <?php } ?>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div><!--  Group Content Inner Col End -->
       
</div>
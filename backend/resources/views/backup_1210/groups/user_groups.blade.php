<div class="marketplace-wrap">
    <nav class="market-nav border bg-white mb-3 rounded">
        <ul class="nav align-items-center">
            <li class="nav-item"><a href="{{ route('groups') }}" class="nav-link">{{ get_phrase('Groups') }}</a></li>
            <li class="nav-item active"><a class="nav-link">{{ get_phrase('My Groups') }}</a></li>
            
            
        </ul>
    </nav>
    <div
        class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5 pt-3"><span><i class="fa-solid fa-calendar-days"></i></span> {{ get_phrase('Groups') }}</h3>
        <div class="">
            <!-- <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.marketplace.create_product'])}}', '{{get_phrase('Create Product')}}');" data-bs-toggle="modal"
                data-bs-target="#createProduct" class="btn btn-primary py-2"> <i class="fa fa-plus-circle"></i> {{get_phrase('Create Product')}}</a> -->
                <a href="{{route('groups.create')}}" onclick="" class="btn btn-primary"  class="btn btn-primary"> <i class="fa fa-plus-circle"></i>{{get_phrase('Create Product')}}</a>
        </div>
    </div>
    <!-- Product Listing Start -->
    <div class="product-listing"> user_groups.blade
        <div class="row g-3">
            @foreach ($groups as $group)
            <?php
  $city= DB::table('cities')
  ->where('id', $group->city_id)
  ->first();

  $area= DB::table('areas')
  ->where('city_id', $group->city_id)
  ->where('id', $group->area_id)
  ->first();

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

$routeParams = [
    'category_slug' => $catslug,
    'group_slug' => $group->group_slug
];

if (!empty($city->city_slug)) {
    $routeParams['city_slug'] = $city->city_slug;
}

if (!empty($area->area_slug)) {
    $routeParams['area_slug'] = $area->area_slug;
}

 ?>
                <?php if (!empty($catslug)) {?>
                            <div class="col-md-4">
                                <div class="card p-2 rounded">
                                    <div class="mb-2 thumbnail-103-103" style="background-image: url('{{ get_group_logo($group->logo,'logo') }}');"></div>
                                    <a href="{{ route('single.group',$routeParams) }}"><h4>{{ ellipsis($group->title,10) }}</h4></a>
                                    <a href="{{ route('category.group',['category_slug'=>$catslug]) }}"><h6>{{ $catname }}</h6></a>
                                    @php $joined = \App\Models\Group_member::where('group_id',$group->id)->where('is_accepted','1')->count(); @endphp
                                    <span class="small text-muted">{{ $joined }} {{ get_phrase('Member') }}{{ $joined>1?"s":"" }}</span>
                                    @php $join = \App\Models\Group_member::where('group_id',$group->id)->where('user_id',auth()->user()->id)->count(); @endphp
                                    @if ($join>0)
                                        @if ($group->user_id==auth()->user()->id)
                                            <a href="javascript:void(0)" class="btn btn-secondary">{{ get_phrase('Admin') }}</a>
                                        @else
                                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('group.rjoin',$group->id); ?>')" class="btn btn-secondary">{{ get_phrase('Joined') }}</a>
                                        @endif
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
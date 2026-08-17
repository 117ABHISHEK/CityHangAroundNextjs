<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
<div class="row gx-6">
    <div class="col-lg-12">
        <div class="group-inner bg-white border rounded p-3">
            
            <div class="page-suggest mt-6">
                <h3 class="h6">{{ get_phrase('All Groups') }}</h3>
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
                            <div class="col-md-3 col-lg-6 col-xl-6 col-sm-4 col-6">
                                <div class="card p-2 rounded">
                                    <div class="mb-2 thumbnail-103-103" style="background-image: url('{{ get_group_logo($group->logo,'logo') }}');"></div>
                                    <a href="{{ $discussionRoute }}"><h4>{{ ellipsis($group->title,10) }}</h4></a>
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
                        @endforeach
                    </div>
                </div>
                @if (count($groups)>15)
                    <a href="{{ route('all.group.view') }}" class="btn btn-secondary btn-lg d-block mt-3">{{ get_phrase('See More') }}</a>
                @endif
            </div>
        </div>
    </div><!--  Group Content Inner Col End -->
    
  
</div>
<div class="col-lg-5">
        <aside class="sidebar plain-sidebar">
            <div class="widget">
                    <!-- <button class="btn btn-primary d-block w-100" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.groups.create'])}}', '{{get_phrase(' Create New Group')}}');" data-bs-toggle="modal"
                        data-bs-target="#newGroup"><i class="fa fa-plus-circle"></i>{{get_phrase(' Create New Group')}}</button> -->
                        <a href="{{ route('groups.create') }}" class="btn btn-primary d-block w-100"><i class="fa fa-plus-circle"></i>{{ get_phrase("Create New Group") }}</a>
            </div>
            <div class="widget">
                <div class="gr-search">
                    <h3 class="h6">{{ get_phrase('Groups')}}</h3>
                    <form action="{{ route('search.group') }}">
                        <input type="text" class="bg-secondary rounded" name="search" value="@if(isset($_GET['search'])) {{ $_GET['search'] }} @endif" placeholder="Search Group">
                        <span class="i fa fa-search"></span>
                    </form>
                </div>
            </div><!--  Widget End -->

            <div class="widget group-widget">
                <h3 class="widget-title">{{ get_phrase('Group you Manage') }}</h3>
                    @foreach ($managegroups as $managegroup )
                    <?php

                       

                        $city= DB::table('cities')
                        ->where('id', $managegroup->city_id)
                        ->first();

                        $area= DB::table('areas')
                        ->where('city_id', $managegroup->city_id)
                        ->where('id', $managegroup->area_id)
                        ->first();

                        // Skip this group if city or area is not found
                        if (!$city || !$area) {
                            continue;
                        }

                        $item_categories = DB::table('group_category')
                        ->where('group_id',$managegroup->id)
                        ->get();


                        $item_count=count($item_categories);
                        $categoriesss = DB::table('groupcategories')
                            ->where('id', $item_categories[$item_count-1]->category_id)
                            ->get();
                                                
                        $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
                        ?>
                        <div class="d-flex align-items-center mt-3">
                            <div class="widget-img">
                                <img src="{{ get_group_logo($managegroup->logo,'logo') }}" alt="" class="img-fluid img-radisu">
                            </div>
                            <div class="widget-info ms-2">
                                <h6><a href="{{ route('single.group',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'group_slug'=>$managegroup->group_slug]) }}">{{ $managegroup->title }}</a></h6>
                            </div>
                        </div>
                    @endforeach
                    @if (count($managegroups)>8)
                        <a href="{{ route('group.user.created') }}" class="btn btn-primary mt-3 d-block w-100">{{ get_phrase('See All') }}</a>
                    @endif
            </div> <!-- Widget End -->
            <div class="widget group-widget">
                <h3 class="widget-title">{{ get_phrase('Group you Joined') }}</h3>
                    @foreach ($joinedgroups as $joinedgroup )
                    <?php

                       //print_r($joinedgroup->city_id);exit;

                        $city= DB::table('cities')
                        ->where('id', $joinedgroup->city_id)
                        ->first();

                        $area= DB::table('areas')
                        ->where('city_id', $joinedgroup->city_id)
                        ->where('id', $joinedgroup->area_id)
                        ->first();

                        // Skip this group if city or area is not found
                        if (!$city || !$area) {
                            continue;
                        }

                        $item_categories = DB::table('group_category')
                        ->where('group_id',$joinedgroup->id)
                        ->get();


                        $item_count=count($item_categories);
                        $categoriesss = DB::table('groupcategories')
                            ->where('id', $item_categories[$item_count-1]->category_id)
                            ->get();
                                                
                        $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
                        ?>
                        <div class="d-flex align-items-center mt-3">
                            <div class="widget-img">
                                <img src="{{ get_group_logo($joinedgroup->logo,'logo') }}" alt="" class="img-fluid img-radisu">
                            </div>
                            <div class="widget-info ms-2">
                                <h6><a href="{{ route('single.group',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'group_slug'=>$joinedgroup->group_slug]) }}"> {{ $joinedgroup->title }} </a></h6>
                            </div>
                        </div>
                    @endforeach
                    @if (count($joinedgroups)>8)
                        <a href="{{ route('group.user.joined') }}" class="btn btn-primary mt-3 d-block w-100">{{ get_phrase('See All') }}</a>
                    @endif
            </div> <!-- Widget End -->
        </aside>
    </div> <!-- Group Sidebar End -->
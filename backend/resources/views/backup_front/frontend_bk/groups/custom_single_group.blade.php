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
                            ->where('group_id',$group->id)
                            ->get();


                            $item_count=count($item_categories);
                            $categoriesss = DB::table('groupcategories')
                                ->where('id', $item_categories[$item_count-1]->category_id)
                                ->get();
                                                    
                            $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
                            $catname = !is_null($categoriesss) ? $categoriesss[0]->category_name:null; 

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
                            <div class="col-md-3">
                                <div class="card p-2 rounded">
                                    <div class="mb-2 thumbnail-103-103" style="background-image: url('{{ get_group_logo($group->logo,'logo') }}');"></div>
                                    <a href="{{ $discussionRoute }}"><h4>{{ ellipsis($group->title,10) }}</h4></a>
                                    <a href="{{ route('category.group',['category_slug'=>$catslug]) }}"><h6>{{ $catname }}</h6></a>
                                    @php $joined = \App\Models\Group_member::where('group_id',$group->id)->where('is_accepted','1')->count(); @endphp
                                    <span class="small text-muted">{{ $joined }} {{ get_phrase('Member') }}{{ $joined>1?"s":"" }}</span>
                                    @if(auth()->user())
                                    @php $join = \App\Models\Group_member::where('group_id',$group->id)->where('user_id',auth()->user()->id)->count(); @endphp
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="row">
                        <div class="col-12">
                            {{ $groups->links() }}
                        </div>
                    </div>
                    </div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
@include('frontend.top_group_filter')
<div class="row gx-3">
    <div class="col-lg-12">
        <div class="group-inner">
            <div class="gr-search">
                <h3 class="h6"><span><i class="fa-solid fa-users"></i></span>{{ get_phrase('Group') }} </h3>
                
                <div class="d-flex justify-content-end">
                      @if(auth()->user())
    <a href="{{ route('groups.create') }}" class="btn red-btn btn-primary me-2">
        <i class="fa fa-plus-circle"></i> {{ get_phrase('Create Group') }}
    </a>
    <a href="{{ route('usergroup') }}" class="btn btn-primary">
        {{ get_phrase('My Groups') }}
    </a>
    @endif
</div>


                <form action="{{ route('search.group') }}" method="GET">
                    <!--old searchbar-->
                    <!--<input type="text" class="bg-primary rounded" name="search" value="@if(isset($_GET['search'])) {{ $_GET['search'] }} @endif" placeholder="Search Group">-->
                     <!--<span class="i fa fa-search"></span>-->
                    
                    <!--without search icon searchbar-->
                    <!--<input type="text" name="search" value="@if(isset($_GET['search'])) {{ $_GET['search'] }} @endif" placeholder="Search Group" style=" border: 1px solid #FF4939; border-radius: 0.375rem; padding: 0.5rem;" fdprocessedid="6k5rej">-->
                   
                   <!--nwe searchbar-->
                   <div class="position-relative;">
                    <input type="text" class=" rounded ps-5" name="search" value="@if(isset($_GET['search'])) {{ $_GET['search'] }} @endif" placeholder="Search Group" fdprocessedid="ev9ado" style=" border: 1px solid #FF4939; border-radius: 0.375rem; padding: 0.5rem;" fdprocessedid="6k5rej">
                    <span class="i fa fa-search position-absolute" style="left:15px; top:50%; transform:translateY(-50%);"></span>
                
            </div>
                </form>
            </div>
            <div class="page-suggest mt-3">
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
                         
                            <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4">
                            <div class="card p-3 rounded shadow-sm border-0 h-100">
                                <!-- Image Thumbnail -->
                                <div class="mb-3 rounded" 
                                     style="background-image: url('{{ get_group_logo($group->logo, 'logo') }}'); background-size: cover; background-position: center; height: 180px;">
                                </div>
                        
                                <!-- Group Title -->
                                <a href="{{ $discussionRoute }}" class="text-decoration-none">
                                    <h5 class="fw-bold text-dark mb-1">{{ ellipsis($group->title, 25) }}</h5>
                                </a>
                        
                                <!-- Category -->
                                <a href="{{ route('category.group', ['category_slug' => $catslug]) }}" class="text-decoration-none">
                                    <h6 class="text-muted mb-2">{{ $catname }}</h6>
                                </a>
                        
                                <!-- Member Count -->
                                @php 
                                    $joined = \App\Models\Group_member::where('group_id', $group->id)->where('is_accepted', '1')->count(); 
                                @endphp
                                <p class="text-muted small mb-3">{{ $joined }} {{ get_phrase('Member') }}{{ $joined > 1 ? 's' : '' }}</p>
                        
                                <!-- Join/Admin Button -->
                                @if(auth()->user())
                                    @php 
                                        $join = \App\Models\Group_member::where('group_id', $group->id)
                                                ->where('user_id', auth()->user()->id)->count(); 
                                    @endphp
                        
                                    @if($join > 0)
                                        @if($group->user_id == auth()->user()->id)
                                            <a href="javascript:void(0)" class="btn btn-primary w-100">{{ get_phrase('Admin') }}</a>
                                        @else
                                            <a href="javascript:void(0)" onclick="ajaxAction('{{ route('group.rjoin', $group->id) }}')" class="btn btn-primary w-100">{{ get_phrase('Joined') }}</a>
                                        @endif
                                    @else
                                        <a href="javascript:void(0)" onclick="ajaxAction('{{ route('group.join', $group->id) }}')" class="btn btn-primary w-100">{{ get_phrase('Join') }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @if (count($groups)>15)
                    <a href="{{ route('all.group.view') }}" class="btn btn-primary btn-lg d-block mt-3">{{ get_phrase('See More') }}</a>
                @endif
            </div>
        </div>
    </div><!--  Group Content Inner Col End -->
    
  
</div>
     @include('frontend.footer')
     
<div class="bg-white py-12" hidden>
    <div class="container mx-auto px-6 lg:px-12">
        
        <!-- Main Heading -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800">
                Explore Groups & Connect with Like-Minded People
            </h1>
            <p class="text-lg text-gray-600 mt-2">
                Join local communities, network with professionals, and find groups that match your interests.
            </p>
        </div>

        <!-- Subheadings and Content -->
        <div class="space-y-10">
            
            <!-- Find the Best Groups -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">
                    Find the Best Groups in Your City – Connect & Share Interests
                </h2>
                <p class="text-gray-700 mt-2">
                    Browse through various groups in your city and connect with people who share your interests. From business networking to hobby-based communities, there's something for everyone.
                </p>
            </div>

            <!-- Business & Networking Groups -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">
                    Business & Networking Groups – Grow Your Professional Circle
                </h2>
                <p class="text-gray-700 mt-2">
                    Expand your network and meet industry professionals in local business groups. Get insights, collaborations, and exclusive events to grow your career.
                </p>
            </div>

            <!-- Hobby & Interest-Based Groups -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">
                    Hobby & Interest-Based Groups – Meet People Who Share Your Passion
                </h2>
                <p class="text-gray-700 mt-2">
                    Love photography, music, or fitness? Find a community that shares your passion and engage in meaningful activities.
                </p>
            </div>

            <!-- Community & Social Groups -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">
                    Community & Social Groups – Stay Connected with Locals
                </h2>
                <p class="text-gray-700 mt-2">
                    Get involved in your local community through social groups, volunteer activities, and neighborhood meetups.
                </p>
            </div>

            <!-- Event-Based Groups -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">
                    Event-Based Groups – Join & Stay Updated on Local Events
                </h2>
                <p class="text-gray-700 mt-2">
                    Never miss out on local events! Join groups focused on concerts, meetups, and city-wide happenings.
                </p>
            </div>

            <!-- How to Create & Manage a Group -->
            <div class="bg-gray-100 p-6 rounded-lg">
                <h2 class="text-2xl font-semibold text-gray-800">
                    How to Create & Manage a Group on CityHangaround
                </h2>
                <p class="text-gray-700 mt-2">
                    Want to start your own group? Register for free, set up your group, and invite members to join your community.
                </p>
                <a href="{{ route('groups.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700">
                    Create Your Group Now
                </a>
            </div>

        </div>
    </div>
</div>

<?php

namespace App\Http\Controllers\Event;

use Image, Session;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Posts;
use App\Models\Friendships;
use App\Models\Invite;
use App\Models\Notification;
use App\Models\Share;
use App\Models\User;
use App\Models\ManageApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\Eventcategory;
use Illuminate\Support\Facades\URL;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use App\Helpers\CityHelper;
use App\Services\UserActivityService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
class EventController extends Controller
{
    //

    // event view
    // public function allevents(Request $request)
    // {

    //     SEOMeta::setTitle('Find & Explore Local Events – Concerts, Festivals,Workshops & More');
    //     //SEOMeta::setDescription($area->area_name . ', ' . 'Top 10 ' . $parentcategory->category_name . ', ' .  implode(',',$subcategories) . ' Free Listing Cityhangaround');
    //     SEOMeta::setDescription('Discover the best local events happening near you! Explore concerts, festivals, business expos, workshops, and cultural events. Stay updated and never miss out!');

    //     SEOMeta::setCanonical(URL::current());
    //     // Get search query
    //     $search_param = $request->title;

    //     $page_data['all_cities'] = CityHelper::getActiveCities();
    //     if(auth()->user() && auth()->user()->user_role=="admin"){
    //         if(!empty($search_param) && $search_param!=""){
    //             $page_data['events'] = Event::
    //             where('events.event_date', '>=', Carbon::now())
    //             ->where('title', 'LIKE', "%{$search_param}%")
    //             ->get();
    //         }
    //         else{
    //             $page_data['events'] = Event::
    //             where('events.event_date', '>=', Carbon::now())
    //             ->get();
    //         }

    //     }
    //     else{
    //         if(!empty($search_param) && $search_param!=""){
    //             $page_data['events'] = Event::where('privacy', 'public')->where('event_status',2)
    //             ->where('events.event_date', '>=', Carbon::now())
    //             ->where('title', 'LIKE', "%{$search_param}%")
    //             ->whereNull('group_id')->orderBy('id', 'DESC')->limit(20)->get();
    //         }
    //         else
    //         {
    //             $page_data['events'] = Event::where('privacy', 'public')->where('event_status',2)
    //             ->where('events.event_date', '>=', Carbon::now())
    //             ->whereNull('group_id')->orderBy('id', 'DESC')->limit(20)->get();
    //         }

    //     }

    //    //print_r( $page_data['events'] );exit;
    //     $page_data['view_path'] = 'frontend.events.events';
    //     return view('frontend.index', $page_data);
    // }
    public function allevents(Request $request)
    {
        SEOMeta::setTitle('Find & Explore Local Events – Concerts, Festivals,Workshops & More');
        SEOMeta::setDescription('Discover the best local events happening near you! Explore concerts, festivals, business expos, workshops, and cultural events. Stay updated and never miss out!');
        SEOMeta::setCanonical(URL::current());

        $search_param = $request->title;
        $page_data['all_cities'] = CityHelper::getActiveCities();

        // if(auth()->user() && auth()->user()->user_role == "admin"){
        //     if(!empty($search_param)){
        //         $page_data['events'] = Event::where('event_date', '>=', Carbon::now())
        //             ->where('title', 'LIKE', "%{$search_param}%")
        //             ->where(function ($query) {
        //                 $query
        //                     ->whereHas('user.userSubscriptions', function ($q) {
        //                         $q->where('status', 'active')
        //                           ->where('expires_at', '>=', now())
        //                           ->whereHas('subscription', function ($subQ) {
        //                               $subQ->where(function ($sq) {
        //                                   $sq->where('offered_services', 'like', '%events%')
        //                                      ->orWhereNull('offered_services');
        //                               });
        //                           });
        //                     })
        //                     ->orWhereDoesntHave('user.userSubscriptions');
        //             })
        //             ->get();
        //     } else {
        //         $page_data['events'] = Event::where('event_date', '>=', Carbon::now())
        //             ->where(function ($query) {
        //                 $query
        //                     ->whereHas('user.userSubscriptions', function ($q) {
        //                         $q->where('status', 'active')
        //                           ->where('expires_at', '>=', now())
        //                           ->whereHas('subscription', function ($subQ) {
        //                               $subQ->where(function ($sq) {
        //                                   $sq->where('offered_services', 'like', '%events%')
        //                                      ->orWhereNull('offered_services');
        //                               });
        //                           });
        //                     })
        //                     ->orWhereDoesntHave('user.userSubscriptions');
        //             })
        //             ->get();
        //     }
        // } else {
        //     if(!empty($search_param)){
        //         $page_data['events'] = Event::where('privacy', 'public')->where('event_status', 2)
        //             ->where('event_date', '>=', Carbon::now())
        //             ->where('title', 'LIKE', "%{$search_param}%")
        //             ->whereNull('group_id')
        //             ->where(function ($query) {
        //                 $query
        //                     ->whereHas('user.userSubscriptions', function ($q) {
        //                         $q->where('status', 'active')
        //                           ->where('expires_at', '>=', now())
        //                           ->whereHas('subscription', function ($subQ) {
        //                               $subQ->where(function ($sq) {
        //                                   $sq->where('offered_services', 'like', '%events%')
        //                                      ->orWhereNull('offered_services');
        //                               });
        //                           });
        //                     })
        //                     ->orWhereDoesntHave('user.userSubscriptions');
        //             })
        //             ->orderBy('id', 'DESC')
        //             ->limit(20)
        //             ->get();
        //     } else {
        //         $page_data['events'] = Event::where('privacy', 'public')->where('event_status', 2)
        //             ->where('event_date', '>=', Carbon::now())
        //             ->whereNull('group_id')
        //             ->where(function ($query) {
        //                 $query
        //                     ->whereHas('user.userSubscriptions', function ($q) {
        //                         $q->where('status', 'active')
        //                           ->where('expires_at', '>=', now())
        //                           ->whereHas('subscription', function ($subQ) {
        //                               $subQ->where(function ($sq) {
        //                                   $sq->where('offered_services', 'like', '%events%')
        //                                      ->orWhereNull('offered_services');
        //                               });
        //                           });
        //                     })
        //                     ->orWhereDoesntHave('user.userSubscriptions');
        //             })
        //             ->orderBy('id', 'DESC')
        //             ->limit(20)
        //             ->get();
        //     }
        // }
        $page_data['system_name'] = Cache::remember('system_name', 3600, function () {
            return DB::table('settings')->where('type', 'system_name')->value('description');
        });
        $page_data['system_favicon'] = Cache::remember('system_fav_icon', 3600, function () {
            return DB::table('settings')->where('type', 'system_fav_icon')->value('description');
        });

        $page_data['all_cities'] = Cache::remember('active_cities_events_with_areas', 3600, function () {
            return CityHelper::getActiveCities();
        });

        // If user selected a city in header, it is stored in session('selected_city_id')
        $filter_city = $request->city ?: session('selected_city_id');
        $filter_area = $request->area;

        /**
         * Category dropdown must not show empty categories for the selected city.
         * Source of truth: content_master (category_count rows).
         */
        $page_data['all_categories'] = Cache::remember('event_parent_categories_city_' . ($filter_city ?: 'all') . '_v1', 1800, function () use ($filter_city) {
            return DB::table('eventcategories as ec')
                ->select('ec.id', 'ec.category_name', 'ec.category_slug', 'ec.category_parent_id')
                ->where(function ($q) {
                    $q->whereNull('ec.category_parent_id')
                      ->orWhere('ec.category_parent_id', 0);
                })
                ->whereExists(function ($q) use ($filter_city) {
                    $q->select(DB::raw(1))
                        ->from('content_master as cm')
                        ->where('cm.source_type', 'category_count')
                        ->where('cm.status', 'event')
                        ->where('cm.total_count', '>', 0)
                        ->when($filter_city, fn($qq) => $qq->where('cm.city_id', $filter_city))
                        ->whereRaw('(cm.category_id = ec.id OR cm.parent_category_id = ec.id)');
                })
                ->orderBy('ec.category_name', 'asc')
                ->get();
        });

        $page_data['filter_city'] = $filter_city;
        $page_data['filter_area'] = $filter_area;
        $page_data['filter_sort_by'] = $request->get('filter_sort_by', 'newest');

        $cityId = auth()->check() ? auth()->user()->city_id : null;

        $eventsQuery = Event::with([
            'city',
            'state',
            'area'
        ])
            ->where('event_status', 2)
            ->where('event_date', '>=', now());

        // City/Area filters (keeps UI + list in sync)
        if (!empty($filter_city) && $filter_city != 0) {
            $eventsQuery->where('city_id', $filter_city);
        }
        if (!empty($filter_area) && $filter_area != 0) {
            $eventsQuery->where('area_id', $filter_area);
        }

        //  Optional search
        if (!empty($search_param)) {
            $eventsQuery->where('title', 'LIKE', "%{$search_param}%");
        }

        // Only public if not admin
        if (!auth()->check() || auth()->user()->user_role !== 'admin') {
            $eventsQuery->where('privacy', 'public')
                ->whereNull('group_id');
        }

        //  Prioritize featured events
        $eventsQuery->orderByDesc('item_featured')
            ->orderBy('event_date', 'asc');

        // 📦 Paginate results
        $events = $eventsQuery->paginate(20)->appends(request()->query());

        // 👉 Pass to view
        $page_data['events'] = $events;



        $page_data['view_path'] = 'frontend.events.events';
        return view('frontend.index', $page_data);
    }

    // public function allevents()
    // {
    //     $page_data['all_cities']= DB::table('cities')->select('cities.*')
    //     ->join('pages','pages.city_id','cities.id')
    //     ->join('page_category','page_category.page_id','pages.id')
    //     ->join('pagecategories','page_category.category_id','=','pagecategories.id')
    //     ->distinct('cities.id')
    //     ->where('pages.item_status',2)
    //     ->orderBy('cities.city_name','asc')->get();
    //     if(auth()->user()->user_role=="admin"){
    //         $page_data['events'] = Event::
    //         where('events.event_date', '>=', Carbon::now())
    //         ->get();
    //     }
    //     else{
    //         $page_data['events'] = Event::where('privacy', 'public')->where('event_status',2)
    //         ->where('events.event_date', '>=', Carbon::now())
    //         ->whereNull('group_id')->orderBy('id', 'DESC')->limit(20)->get();
    //     }

    //    //print_r( $page_data['events'] );exit;
    //     $page_data['view_path'] = 'frontend.events.events';
    //     return view('frontend.index', $page_data);
    // }

    // user event

    public function userevent()
    {
        $page_data['all_cities'] = CityHelper::getActiveCities();
        if (auth()->user()->user_role == "admin") {
            $page_data['events'] = Event::where('events.event_date', '>=', Carbon::now())
                ->get();
        } else {

            $page_data['events'] = Event::where('user_id', Auth::user()->id)->where('event_status', 2)
                ->where('events.event_date', '>=', Carbon::now())
                ->whereNull('group_id')->orderBy('id', 'DESC')->get();
        }
        $page_data['view_path'] = 'frontend.events.user_event';
        return view('frontend.index', $page_data);
    }

    public function create()
    {
        $page_data['all_cities'] = CityHelper::getActiveCities();
        $page_data['printable_categories'] = DB::table('eventcategories')
            ->whereNull('category_parent_id')->orWhere('category_parent_id', 0)->get();
        $page_data['all_states'] = DB::table('states')->select('states.*')
            ->where('country_id', 101)->get();
        $page_data['parent'] = DB::table('eventcategories')
            ->whereNull('category_parent_id')->orWhere('category_parent_id', 0)->get();
        $page_data['all_countries'] = DB::table('countries')->orderBy('country_name', 'asc')->get();
        $page_data['view_path'] = 'frontend.events.create_event';
        return view('frontend.index', $page_data);
    }


    public function edit($id)
    {
        $page_data['event_id'] = $id;
        $page_data['event']    = \App\Models\Event::findOrFail($id);
        $page_data['printable_categories'] = DB::table('eventcategories')
            ->whereNull('category_parent_id')->orWhere('category_parent_id', 0)->get();
        $page_data['parent'] = DB::table('eventcategories')
            ->whereNull('category_parent_id')->orWhere('category_parent_id', 0)->get();
        $page_data['all_states'] = DB::table('states')->select('states.*')
            ->where('country_id', 101)->get();
        $page_data['all_cities'] = DB::table('cities')->select('cities.*')
            ->where('state_id', $page_data['event']->state_id)->get();
        $page_data['all_areas'] = DB::table('areas')->select('areas.*')
            ->where('city_id', $page_data['event']->city_id)->get();
        $page_data['all_countries'] = DB::table('countries')->orderBy('country_name', 'asc')->get();
        $page_data['view_path'] = 'frontend.events.edit_event';
        return view('frontend.index', $page_data);
    }

    public function dataAjax(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data = DB::table("eventcategories")
                ->select("id", "category_name")
                ->where('category_name', 'LIKE', "$search%")
                ->where('category_parent_id', '!=', 0)
                ->get();
        }
        return response()->json($data);
    }

    public function storecategories(Request $request)
    {


        $duplicatecount = DB::table('eventcategories')->where('category_name', $request->category_name)
            ->count();

        if ($duplicatecount == 0) {



            $category = new Eventcategory();




            $category->category_name = $request->category_name;
            $category->category_slug = strtolower(str_replace(' ', '-', $request->category_name));
            $category->category_icon = "";
            $category->category_parent_id = $request->category_parent_id;
            $category->category_description = "";
            $category->category_createdby = auth()->user()->id;

            $category->save();

            if (auth()->user()) {
                app(UserActivityService::class)->log(auth()->user()->id, 'category_suggest', 'event_category', $category->id, $category->id);
            }

            \Session::flash('flash_message', __('Created'));
            \Session::flash('flash_type', 'success');
            return response()->json(1);
        } else {
            return response()->json("duplicate");
        }
        //return redirect()->route('user.items.create');
    }

    public function jsonGetParentCategories()
    {

        $parents = DB::table('eventcategories')
            ->where('eventcategories.category_parent_id', null)
            // ->orWhereNull('pagecategories.category_parent_id')
            ->get()->toJson();

        return response()->json($parents);
    }


    public function jsonGetCategories()
    {

        $parents = DB::table('eventcategories')->select('eventcategories.id', 'eventcategories.category_name', 'cat.category_name as parent')
            ->leftjoin('eventcategories as cat', 'cat.id', '=', 'eventcategories.category_parent_id')->orderby('id', 'asc')
            ->get()->toJson();

        return response()->json($parents);
    }


    // event store
    public function store(Request $request)
    {
        // return $request->all();

        $rules = array(
            'coverphoto' => 'mimes:jpeg,jpg,png,gif|nullable',
            'eventname' => 'required|max:255',
            'eventdate' => 'required',
            'eventtime' => 'required',
            'eventlocation' => 'required',
            'category' => 'required',
            'state' => 'required|not_in:0',
            'city' => 'required|not_in:0',
            'area' => 'required|not_in:0',
        );
        $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails()) {
        //     return redirect()->back();
        //     //return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        // }
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput(); // Optional: to retain the input data
        }
        if ($request->coverphoto && !empty($request->coverphoto)) {

            //Upload image
            $file_name = rand(1, 35000) . '.' . $request->coverphoto->getClientOriginalExtension();

            //thumbnail
            $img = Image::make($request->coverphoto);
            $img->resize(325, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save(uploadTo('event/thumbnail') . $file_name);

            // cover photo 
            $img = Image::make($request->coverphoto);
            $img->resize(1120, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save(uploadTo('event/coverphoto') . $file_name);
        }


        $title = 'event';
        $approval = ManageApproval::where('title', $title)->first();

        if ($approval && $approval->status == 1) {
            // Approval status is ON
            $event_status = 2;

        } elseif (auth()->check() && auth()->user()->user_role == 'admin') {
            // Status is OFF but user is admin
            $event_status = 2;

        } else {
            //Status is OFF and user is not admin
            $event_status = 1;
        }
        // if(auth()->user()->user_role=="admin"){

        //     $event_status=2;

        // }
        // else{

        //     $event_status=1;
        // }


        $event_slug = preg_replace("/[^A-Za-z0-9 ]/", '', $request->eventname);


        $multiSelectArray = $request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }

        $categories_id = implode(',', $multiSelectArray);

        //print_r($categories_id);exit;

        $event = new Event();

        $event->user_id            = Auth::user()->id;
        $event->title              = $request->eventname;
        $event->event_status       = $event_status;
        $event->event_slug         = str_slug($event_slug);
        $event->state_id           = $request->state;
        $event->city_id            = $request->city;
        $event->area_id            = $request->area;
        $event->category_id        = $categories_id;
        $event->description        = $request->description;
        $event->event_date         = $request->eventdate;
        $event->event_time         = $request->eventtime;
        $event->location           = $request->eventlocation;
        $event->privacy            = $request->privacy;
        $event->going_users_id     = '[]';
        $event->interested_users_id = '[]';

        // ── New schema fields ─────────────────────────────────────────
        $event->business_id            = $request->business_id ?: null;
        $event->organizer_id           = $request->organizer_id ?: null;
        $event->event_type             = $request->event_type ?? 'offline';
        $event->start_datetime         = $request->start_datetime ?: null;
        $event->end_datetime           = $request->end_datetime ?: null;
        $event->venue_name             = $request->venue_name ?: null;
        $event->address                = $request->address ?: null;
        $event->latitude               = $request->latitude ?: null;
        $event->longitude              = $request->longitude ?: null;
        $event->country_id             = $request->country_id ?: null;
        $event->map_url                = $request->map_url ?: null;
        $event->website                = $request->website ?: null;
        $event->registration_required  = $request->has('registration_required') ? 1 : 0;
        $event->booking_url            = $request->booking_url ?: null;
        $event->max_capacity           = $request->max_capacity ?: null;
        $event->registration_deadline  = $request->registration_deadline ?: null;
        $event->featured               = $request->has('featured') ? 1 : 0;

        if (isset($request->group_id)) {
            $event->group_id = $request->group_id;
        }
        !empty($request->coverphoto) ? $event->banner = $file_name : '';
        // Also save logo if uploaded separately
        if ($request->hasFile('logo_file') && $request->file('logo_file')->isValid()) {
            $logo_name = rand(1, 35000) . '.' . $request->logo_file->getClientOriginalExtension();
            $request->logo_file->move(uploadTo('event/logo'), $logo_name);
            $event->logo = $logo_name;
        }

        $user = auth()->user();
        $activeSubscription = $user->activeSubscription()->with('subscription')->first();

        if ($activeSubscription && $activeSubscription->subscription && Str::contains($activeSubscription->subscription->offered_services, 'event')) {
            $durations = json_decode($activeSubscription->subscription->area_durations, true);

            $cityDays = $durations['event']['city'] ?? 0;
            $areaDays = $durations['event']['area'] ?? 0;

            $subscriptionStart = Carbon::parse($activeSubscription->created_at ?? now());


            $priorityEnd = $subscriptionStart->copy()->addDays(max($cityDays, $areaDays));

            if ($cityDays > 0)
                $event->priority_until_city = $subscriptionStart->copy()->addDays($cityDays);
            if ($areaDays > 0)
                $event->priority_until_area = $subscriptionStart->copy()->addDays($areaDays);
            if ($priorityEnd->isFuture())
                $event->item_featured = 1;
        }
        $done = $event->save();
        if ($done) {

            $data['user_id'] = auth()->user()->id;
            $data['privacy'] = $request->privacy;
            $data['publisher'] = 'event';
            $data['publisher_id'] = $event->id;
            $data['post_type'] = "event";
            $data['status'] = 'active';
            $data['description'] = $request->description;
            $data['user_reacts'] = json_encode(array());
            $data['user_reacts'] = json_encode(array());
            $data['tagged_user_ids'] = json_encode(array());
            $data['created_at'] = time();
            $data['updated_at'] = $data['created_at'];
            Posts::create($data);

            foreach ($request->category as $key => $category_id) {
                $data = array(
                    'category_id' => $category_id,
                    "event_id" => $event->id
                );
                $row = DB::table('event_category')->insertGetId($data);


            }

            $slug_count = DB::table('events')->select('events.id')
                ->where('events.event_slug', str_slug($request->name))->count();
            ;

            if ($slug_count > 1) {

                DB::table('events')->where('id', $event->id)
                    ->update(array('event_slug' => DB::raw('concat("' . str_slug($request->eventname) . '",' . '-' . $event->id . ')')));
            }

            if (auth()->user()) {
                app(UserActivityService::class)->log(auth()->user()->id, 'event_listing', 'event', $event->id, $event->id);
            }


            Session::flash('success_message', get_phrase('Event Created Successfully'));
            return redirect()->route('event');
        }

    }

    //  update event 
    public function update(Request $request, $id)
    {
        $rules = array(
            'coverphoto' => 'mimes:jpeg,jpg,png,gif|nullable',
            'eventname' => 'required|max:255',
            'eventdate' => 'required',
            'eventtime' => 'required',
            'eventlocation' => 'required',
            'category' => 'required',
            'state' => 'required|not_in:0',
            'city' => 'required|not_in:0',
            'area' => 'required|not_in:0',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if ($request->coverphoto && !empty($request->coverphoto)) {

            //Upload image
            $file_name = rand(1, 35000) . '.' . $request->coverphoto->getClientOriginalExtension();

            //thumbnail
            $img = Image::make($request->coverphoto);
            $img->resize(325, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save(uploadTo('event/thumbnail') . $file_name);

            // cover photo 
            $img = Image::make($request->coverphoto);
            $img->resize(1120, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save(uploadTo('event/coverphoto') . $file_name);
        }




        $event_slug = preg_replace("/[^A-Za-z0-9 ]/", '', $request->eventname);


        $multiSelectArray = $request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }

        $categories_id = implode(',', $multiSelectArray);


        $event = Event::find($id);

        $title = 'event';
        $approval = ManageApproval::where('title', $title)->first();

        if ($approval && $approval->status == 1) {
            // Approval status is ON
            $event_status = 2;

        } elseif (auth()->check() && auth()->user()->user_role == 'admin') {
            // Status is OFF but user is admin
            $event_status = 2;

        } else {
            //Status is OFF and user is not admin
            $event_status = $event->event_status;
        }

        //$event->user_id = Auth::user()->id;
        // store image name for delete file operation 
        $imagename = $event->banner;

        $event->title        = $request->eventname;
        $event->event_slug   = str_slug($event_slug);
        $event->event_status = $event_status;
        $event->state_id     = $request->state;
        $event->city_id      = $request->city;
        $event->area_id      = $request->area;
        $event->category_id  = $categories_id;
        $event->description  = $request->description;
        $event->event_date   = $request->eventdate;
        $event->event_time   = $request->eventtime;
        $event->location     = $request->eventlocation;
        $event->privacy      = $request->privacy;
        !empty($request->coverphoto) ? $event->banner = $file_name : $event->banner;

        // ── New schema fields ─────────────────────────────────────────
        $event->business_id           = $request->business_id ?: $event->business_id;
        $event->organizer_id          = $request->organizer_id ?: $event->organizer_id;
        $event->event_type            = $request->event_type ?? $event->event_type ?? 'offline';
        $event->start_datetime        = $request->start_datetime ?: $event->start_datetime;
        $event->end_datetime          = $request->end_datetime ?: $event->end_datetime;
        $event->venue_name            = $request->venue_name ?: $event->venue_name;
        $event->address               = $request->address ?: $event->address;
        $event->latitude              = $request->latitude ?: $event->latitude;
        $event->longitude             = $request->longitude ?: $event->longitude;
        $event->country_id            = $request->country_id ?: $event->country_id;
        $event->map_url               = $request->map_url ?: $event->map_url;
        $event->website               = $request->website ?: $event->website;
        $event->registration_required = $request->has('registration_required') ? 1 : 0;
        $event->booking_url           = $request->booking_url ?: $event->booking_url;
        $event->max_capacity          = $request->max_capacity ?: $event->max_capacity;
        $event->registration_deadline = $request->registration_deadline ?: $event->registration_deadline;
        $event->featured              = $request->has('featured') ? 1 : 0;

        // Logo upload
        if ($request->hasFile('logo_file') && $request->file('logo_file')->isValid()) {
            $logo_name = rand(1, 35000) . '.' . $request->logo_file->getClientOriginalExtension();
            $request->logo_file->move(uploadTo('event/logo'), $logo_name);
            $event->logo = $logo_name;
        }

        $done = $event->save();

        if ($done) {
            // just put the file name and folder name nothing more :) 
            removeFile('event', $imagename);

            foreach ($request->category as $key => $category_id) {
                $data = array(
                    'category_id' => $category_id,
                    "event_id" => $id
                );
                $row = DB::table('event_category')->insertGetId($data);


            }

            $slug_count = DB::table('events')->select('events.id')
                ->where('events.event_slug', str_slug($request->name))->count();
            ;

            if ($slug_count > 1) {

                DB::table('events')->where('id', $id)
                    ->update(array('event_slug' => DB::raw('concat("' . str_slug($request->eventname) . '",' . '-' . $id . ')')));
            }

            Session::flash('success_message', get_phrase('Event Updated Successfully'));
            return redirect()->route('event');
        }
    }

    // delete event 

    public function event_delete()
    {
        $response = array();
        $event = Event::find($_GET['event_id']);
        // store image name for delete file operation 
        $imagename = $event->banner;

        $done = $event->delete();
        if ($done) {
            DB::table('event_category')->where('event_id', $_GET['event_id'])->delete();
            $response = array('alertMessage' => get_phrase('Event Deleted Successfully'), 'fadeOutElem' => "#event-" . $_GET['event_id']);
            // just put the file name and folder name nothing more :) 
            removeFile('event', $imagename);
        }
        return json_encode($response);
    }



    // single event view 

    public function single_event($city_slug, $area_slug, $category_slug, $event_slug)
    {
        $page_data['all_cities'] = CityHelper::getActiveCities();

        $event = Event::with('getUser:id,name,photo')
            ->where('event_slug', $event_slug)
            ->firstOrFail();
        $id = $event->id;

        if (auth()->user()) {
            app(UserActivityService::class)->log(auth()->user()->id, 'view', 'event', $event->id, $event->id);
        }

        //print_r($pages);exit;

        if ($event) {
            $eventViewData = $event->view ? json_decode($event->view, true) : [];

            if (auth()->user() && !in_array(auth()->user()->id, $eventViewData)) {
                $eventViewData[] = auth()->user()->id;
                $updatedViewJson = json_encode($eventViewData);
                Event::whereKey($event->id)->update(['view' => $updatedViewJson]);
                $event->view = $updatedViewJson;
            }
        }
        $page_data['events'] = $event;

        $category = Eventcategory::where('category_slug', $category_slug)->first();

        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();

        $area = DB::table('areas')->select('areas.*')->where('area_slug', $area_slug)
            ->where('city_id', $city->id)
            ->first();

        $page_data['category'] = $category;

        $page_data['city'] = $city;
        $page_data['area'] = $area;

        $parentcategories = DB::table('eventcategories')->select('eventcategories.*')
            ->join('event_category', 'event_category.category_id', '=', 'eventcategories.id')
            ->join('events', 'events.id', '=', 'event_category.event_id')
            ->where('events.event_status', 2)
            ->where('eventcategories.id', $category->category_parent_id)
            ->where('events.city_id', $city->id)
            ->distinct('category_name')
            ->orderBy('category_name')->get();

        $page_data['category'] = $category;
        $page_data['parent_categories'] = $parentcategories;
        // calculation of popular event 
        if (auth()->user()) {
            $popularEventsQuery = Event::with([
                    'getUser:id,name,photo',
                    'city:id,city_slug',
                    'area:id,area_slug',
                    'categories:id,category_name,category_slug',
                ])
                ->where('privacy', 'public')
                ->where('user_id', '!=', auth()->user()->id)
                ->where('id', '!=', $id)
                ->where('city_id', $city->id)
                ->orderByDesc('id')
                ->limit(500);

            $popularEvents = isset($area->id)
                ? (clone $popularEventsQuery)->where('area_id', $area->id)->get()
                : collect();

            if ($popularEvents->isEmpty()) {
                $popularEvents = $popularEventsQuery->get();
            }
        } else {
            $popularEvents = collect();
        }
        $popularrate = [];
        foreach ($popularEvents as $popularEvent) {
            $popularCategory = $popularEvent->categories->last();
            $goingusercount = count(json_decode($popularEvent->going_users_id));
            $interestedusercount = count(json_decode($popularEvent->interested_users_id));
            $total = $goingusercount + $interestedusercount;
            array_push($popularrate, [
                'id' => $popularEvent->id,
                'popular' => $total,
                'banner' => $popularEvent->banner,
                'event_date' => $popularEvent->event_date,
                'title' => $popularEvent->title,
                'post_user' => $popularEvent->getUser->name,
                'user_id' => $popularEvent->getUser->id,
                'photo' => $popularEvent->getUser->photo,
                'interested_users_id' => $popularEvent->interested_users_id,
                'city_id' => $popularEvent->city_id,
                'area_id' => $popularEvent->area_id,
                'city_slug' => $popularEvent->city?->city_slug,
                'area_slug' => $popularEvent->area?->area_slug,
                'category_slug' => $popularCategory?->category_slug,
                'category_name' => $popularCategory?->category_name,
                'event_slug' => $popularEvent->event_slug
            ]);
        }
        //print_r($popularrate);exit;
        // custom function for desending order 
        aasort($popularrate, "popular");

        // friend find 
        if (auth()->user()) {
            $friends = Friendships::where('requester', auth()->user()->id)->orWhere('accepter', auth()->user()->id)->where('is_accepted', '1')->orderBy('id', 'DESC')->get();
            $invited_friend_going = Invite::where('event_id', $id)->where('is_accepted', "1")->count();
        } else {

            $friends = [];
            $invited_friend_going = [];

        }



        // for sending  user invite 
        $users = User::select('id', 'name', 'photo')->orderByDesc('id')->limit(10)->get();

        $posts = Posts::where(function ($query) {
            if (auth()->check()) {
                // If logged in, allow public OR own private posts
                $query->where('posts.privacy', '!=', 'private')
                    ->orWhere('posts.user_id', auth()->id());
            } else {
                // Not logged in, only public posts
                $query->where('posts.privacy', '!=', 'private');
            }
        })
            ->where('publisher_id', $id)->where('publisher', 'event')
            ->where('posts.status', 'active')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')
            ->orderBy('posts.post_id', 'DESC')->get();

        $page_data['users'] = $users;
        $page_data['posts'] = $posts;
        $page_data['invited_friend_going'] = $invited_friend_going;
        $page_data['friends'] = $friends;
        $page_data['popularevents'] = $popularrate;
        $page_data['event'] = $event;
        $page_data['event_going'] = $event;
        $page_data['view_path'] = 'frontend.events.single_event';
        return view('frontend.index', $page_data);
    }


    public function eventcategoryByCityArea(Request $request, $city_slug, $category_slug, $area_slug)
    {

        $category = Eventcategory::where('category_slug', $category_slug)->first();
        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        if (!$category || !$city) {
            abort(404);
        }
        $page_data['area'] = DB::table('areas')->select('areas.*')->where('area_slug', $area_slug)
            ->where('city_id', $city->id)
            ->first();
        $area = $page_data['area'];
        if (!$area) {
            abort(404);
        }


        $page_data['categories'] = Cache::remember('event_sidebar_cats_' . $city->id . '_' . $area->id . '_' . $category->id, 3600, function () use ($city, $area, $category) {
            return DB::table('eventcategories')->select('eventcategories.*')
                ->join('event_category', 'event_category.category_id', '=', 'eventcategories.id')
                ->join('events', 'event_category.event_id', 'events.id')
                ->distinct('eventcategories.id')
                ->where('events.event_status', 2)
                ->orderBy('eventcategories.id', 'DESC')
                ->where(function ($query) use ($category) {
                    $query->where('event_category.category_id', $category->id)
                        ->orWhere('eventcategories.category_parent_id', $category->id);
                })
                ->where("events.city_id", $city->id)
                ->where("events.area_id", $area->id)
                ->get();
        });


        if (!is_null($category) && !is_null($city) && !is_null($area)) {
            $parentcategories = DB::table('eventcategories')->select('eventcategories.*')
                ->join('event_category', 'event_category.category_id', '=', 'eventcategories.id')
                ->join('events', 'events.id', '=', 'event_category.event_id')
                ->where('events.event_status', 2)
                ->where('eventcategories.id', $category->category_parent_id)
                ->where('events.city_id', $city->id)
                ->where('events.city_id', $area->id)
                ->distinct('category_name')
                ->orderBy('category_name')->get();

            $parentcategory = Eventcategory::where('id', $category->category_parent_id)->first();
            //echo  $parentcategory;exit;


            $subcategories = [];

            foreach ($parentcategories as $allcategoriesresult) {
                $subcategories[] = $allcategoriesresult->category_name;
            }
            $page_data['parent_categories'] = $parentcategories;



            SEOMeta::setTitle($category->category_name . ' Events in ' . $area->area_name . ', ' . $city->city_name . '– Find the Best Upcoming Events Near You');
            //SEOMeta::setDescription($area->area_name . ', ' . 'Top 10 ' . $parentcategory->category_name . ', ' .  implode(',',$subcategories) . ' Free Listing Cityhangaround');
            SEOMeta::setDescription('Discover top ' . $category->category_name . ' events in ' . $area->area_name . ',' . $city->city_name . '! Explore concerts, festivals, business meetups, workshops, and more. Stay updated with the latest happenings in your city.');
            SEOMeta::setKeywords([
                $category->category_name . ' events in ' . $area->area_name . ', ' . $city->city_name,
                $category->category_name . ' in ' . $area->area_name . ', ' . $city->city_name,
                'upcoming ' . $category->category_name . ' events in ' . $city->city_name,
                'best ' . $category->category_name . ' events in ' . $city->city_name,
                'local ' . $category->category_name . ' events'
            ]);
            SEOMeta::setCanonical(URL::current());




            //echo  $request->city;exit;

            //  $paid_items_query=  DB::table('events')->select('events.id','events.event_slug','events.title',
            //  'events.city_id','events.area_id','events.banner','events.event_date','events.event_time',
            //  'cities.city_slug','areas.area_slug','users.id as userid','users.photo as userphoto','users.name as username'
            //  ,'events.location'
            // ,'cities.city_name','areas.area_name','states.state_name','events.created_at')
            // ->join('cities','cities.id','events.city_id')
            // ->join('areas','areas.id','events.area_id')
            // ->join('states','states.id','events.state_id')
            // ->join('event_category','event_category.event_id','events.id')
            // ->join('eventcategories','event_category.category_id','=','eventcategories.id')
            // ->join('users','users.id','events.user_id')
            // ->where('events.event_date', '>=', Carbon::now())
            // ->where('events.event_status',2)
            // ->where('events.city_id',$city->id)
            // ->where('events.area_id',$area->id)
            // ->where(function ($query) use ($category) {
            //     $query->where('event_category.category_id', $category->id)
            //     ->orWhere('eventcategories.category_parent_id',$category->id);
            // })
            // ->distinct('events.id');

            $filter_sort_by = $request->get('filter_sort_by', 'newest');
            $page_data['filter_sort_by'] = $filter_sort_by;

            $paid_items_query = DB::table('events')->select(
                'events.id',
                'events.user_id',
                'events.event_slug',
                'events.title',
                'events.city_id',
                'events.area_id',
                'events.banner',
                'events.event_date',
                'events.event_time',
                'cities.city_slug',
                'areas.area_slug',
                'users.photo as userphoto',
                'users.name as username',
                'users.id as userid',
                'events.location',
                'cities.city_name',
                'areas.area_name',
                'states.state_name',
                'events.created_at',
                'events.item_featured', //  Add this to help with sorting
                'eventcategories.category_slug as category_slug'
            )
                ->join('cities', 'cities.id', '=', 'events.city_id')
                ->join('areas', 'areas.id', '=', 'events.area_id')
                ->join('states', 'states.id', '=', 'events.state_id')
                ->join('event_category', 'event_category.event_id', '=', 'events.id')
                ->join('eventcategories', 'event_category.category_id', '=', 'eventcategories.id')
                ->join('users', 'users.id', '=', 'events.user_id')
                ->where('events.event_date', '>=', now())
                ->where('events.event_status', 2)
                ->where('events.city_id', $city->id)
                ->where('events.area_id', $area->id)
                ->where(function ($query) use ($category) {
                    $query->where('event_category.category_id', $category->id)
                        ->orWhere('eventcategories.category_parent_id', $category->id);
                })
                ->distinct('events.id')
                ->orderBy('events.id', 'desc')
                ->orderByDesc('events.item_featured') //  Put featured items on top
                ->orderBy('events.created_at', $filter_sort_by === 'oldest' ? 'asc' : 'desc');

            // Step 2: Paginate directly — no post-processing needed
            $paid_items = $paid_items_query->paginate(50);
            // Final assign
            $page_data['events'] = $paid_items;
            $page_data['view_path'] = 'frontend.events.categorycityarea';

            return view('frontend.event_category_city_area_index', $page_data);


        } else {
            abort(404);
        }
    }


    public static function geteventbycategoryid($categoryid, $cityid)
    {



        //echo $categoryid;
        $paid_items_query = DB::table('events')->select(
            'events.id',
            'events.event_slug',
            'events.title',
            'events.city_id',
            'events.area_id',
            'events.banner',
            'events.event_date',
            'events.event_time',
            'cities.city_slug',
            'areas.area_slug',
            'users.id as userid',
            'users.photo as userphoto',
            'users.name as username'
            ,
            'events.location'
            ,
            'cities.city_name',
            'areas.area_name',
            'states.state_name',
            'events.created_at'
        )
            ->join('cities', 'cities.id', 'events.city_id')
            ->join('areas', 'areas.id', 'events.area_id')
            ->join('states', 'states.id', 'events.state_id')
            ->join('event_category', 'event_category.event_id', 'events.id')
            ->join('eventcategories', 'event_category.category_id', '=', 'eventcategories.id')
            ->join('users', 'users.id', 'events.user_id')
            ->where('events.event_date', '>=', Carbon::now())
            ->where('events.event_status', 2)
            ->where('events.city_id', $cityid)
            ->where(function ($query) use ($categoryid) {
                $query->where('event_category.category_id', $categoryid)
                    ->orWhere('eventcategories.category_parent_id', $categoryid);
            })
            ->distinct('events.id')->orderBy('events.id', 'DESC')->limit(4)->get();

        return $paid_items_query;
    }


    public function eventcity(Request $request, $city_slug)
    {
        $city = DB::table('cities')->where('city_slug', $city_slug)->first();
        $page_data['city'] = $city;

        $page_data['system_name'] = Cache::remember('system_name', 3600, function () {
            return DB::table('settings')->where('type', 'system_name')->value('description');
        });
        $page_data['system_favicon'] = Cache::remember('system_fav_icon', 3600, function () {
            return DB::table('settings')->where('type', 'system_fav_icon')->value('description');
        });

        $page_data['all_cities'] = Cache::remember('active_cities_events', 3600, function () {
            return CityHelper::getActiveCities();
        });




        if (!is_null($city)) {


            SEOMeta::setTitle('Upcoming Events in ' . $city->city_name . ' – Concerts, Festivals & Local Activities');
            SEOMeta::setDescription('Discover the best events happening in' . $city->city_name . '! Explore concerts, festivals, business meetups, workshops, and more. Stay updated with the latest happenings near you!');

            SEOMeta::setCanonical(URL::current());

            $categories = DB::table('eventcategories')->select('eventcategories.*')
                ->join('event_category', 'event_category.category_id', '=', 'eventcategories.id')
                ->join('events', 'event_category.event_id', 'events.id')
                ->join('cities', 'events.city_id', 'cities.id')
                ->distinct('eventcategories.id')
                ->orderBy('eventcategories.id', 'DESC')
                ->where('events.event_status', 2)
                ->where('events.city_id', $city->id)
                ->where('events.event_date', '>=', Carbon::now())
                ->where('eventcategories.category_parent_id', null)->get();

            //print_r($categories);exit;

            $page_data['categories'] = $categories;

            $page_data['view_path'] = 'frontend.events.city';
            return view('frontend.event_city_index', $page_data);
        } else {

            abort(404);
        }

    }


    public function eventcategory(Request $request, string $category_slug)
    {

        //print_r($request->city);exit;
        $category = Eventcategory::where('category_slug', $category_slug)->first();
        $page_data['category'] = $category;

        $page_data['system_name'] = Cache::remember('system_name', 3600, function () {
            return DB::table('settings')->where('type', 'system_name')->value('description');
        });
        $page_data['system_favicon'] = Cache::remember('system_fav_icon', 3600, function () {
            return DB::table('settings')->where('type', 'system_fav_icon')->value('description');
        });

        $page_data['all_cities'] = Cache::remember('active_cities_events_with_areas', 3600, function () {
            return CityHelper::getActiveCities();
        });

        $page_data['all_categories'] = Cache::remember('event_parent_categories', 3600, function () {
            return DB::table('eventcategories')->select('eventcategories.*')
                ->join('event_category', 'event_category.category_id', '=', 'eventcategories.id')
                ->join('events', 'event_category.event_id', 'events.id')
                ->distinct('eventcategories.id')
                ->where('events.event_status', 2)
                ->where('events.event_date', '>=', now())
                ->where(function ($query) {
                    $query->where('eventcategories.category_parent_id', 0)
                          ->orWhereNull('eventcategories.category_parent_id');
                })
                ->orderBy('eventcategories.id', 'DESC')
                ->get();
        });

        $page_data['all_event_cities'] = Cache::remember('event_cities_for_cat_' . ($category->id ?? 0), 3600, function () use ($category) {
            if (!$category) return [];
            return DB::table('cities')->select('cities.*')
                ->join('events', 'events.city_id', 'cities.id')
                ->join('event_category', 'event_category.event_id', 'events.id')
                ->join('eventcategories', 'event_category.category_id', '=', 'eventcategories.id')
                ->distinct('cities.id')
                ->where('events.event_status', 2)
                ->where('events.event_date', '>=', now())
                ->where('event_category.category_id', $category->id)
                ->orderBy('cities.city_name', 'asc')
                ->get();
        });



        if ($category) {
            SEOMeta::setTitle('Near by top ' . $category->category_name . ', listing, deals, offers');
            SEOMeta::setDescription('Near by top ' . $category->category_name . ' deals, offers');
            SEOMeta::setCanonical(URL::current());
            //SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);



            $filter_city = empty($request->city) ? null : $request->city;
            $filter_area = empty($request->area) ? null : $request->area;
            if ($filter_area == "" || is_null($filter_area)) {
                $filter_area = "0";
            }
            $page_data['filter_city'] = $filter_city;
            $page_data['filter_area'] = $filter_area;

            //echo $filter_area;exit;

            //  $paid_items_query=  DB::table('events')->select('events.id','events.event_slug','events.title',
            //  'events.city_id','events.area_id','events.banner','events.event_date','events.event_time',
            //  'cities.city_slug','areas.area_slug','users.id as userid','users.photo as userphoto','users.name as username'
            //  ,'events.location'
            // ,'cities.city_name','areas.area_name','states.state_name','events.created_at')
            // ->join('cities','cities.id','events.city_id')
            // ->join('areas','areas.id','events.area_id')
            // ->join('states','states.id','events.state_id')
            // ->join('event_category','event_category.event_id','events.id')
            // ->join('users','users.id','events.user_id')
            // ->where('events.event_date', '>=', Carbon::now())
            // ->where('events.event_status',2)
            // ->where(function ($query) use ($category) {
            //     $query->where('event_category.category_id', $category->id);
            // })
            // ->distinct('event_category.id');
            $filter_sort_by = $request->get('filter_sort_by', 'newest');

            $page_data['filter_sort_by'] = $filter_sort_by;

            $paid_items_query = DB::table('events')->select(
                'events.*',
                'cities.city_slug',
                'cities.city_name',
                'areas.area_slug',
                'areas.area_name',
                'states.state_name',
                'users.id as userid',
                'users.photo as userphoto',
                'users.name as username'
            )
                ->join('cities', 'cities.id', '=', 'events.city_id')
                ->join('areas', 'areas.id', '=', 'events.area_id')
                ->join('states', 'states.id', '=', 'events.state_id')
                ->join('event_category', 'event_category.event_id', '=', 'events.id')
                ->join('users', 'users.id', '=', 'events.user_id')
                ->where('events.event_date', '>=', now())
                ->where('events.event_status', 2)
                ->where(function ($query) use ($category) {
                    $query->where('event_category.category_id', $category->id);
                });

            // Filters
            if (!empty($filter_city)) {
                $paid_items_query->where('events.city_id', $filter_city);
            }
            if (!empty($filter_area)) {
                $paid_items_query->where('events.area_id', $filter_area);
            }

            // Just sort by featured first, then by created_at
            $paid_items_query
                ->orderByDesc('events.item_featured')
                ->orderBy('events.created_at', $filter_sort_by === 'oldest' ? 'asc' : 'desc');

            $paid_items = $paid_items_query->distinct('events.id')->orderBy('events.id', 'DESC')->paginate(50);

            $page_data['events'] = $paid_items;
            $page_data['view_path'] = 'frontend.events.category';

            return view('frontend.event_index', $page_data);

        } else {
            abort(404);
        }

    }

    public function eventcategoryByCity(Request $request, $category_slug, $city_slug)
    {

        $category = Eventcategory::where('category_slug', $category_slug)->first();
        $city = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        if (!$category || !$city) {
            abort(404);
        }
        $page_data['city'] = $city;
        $page_data['category'] = $category;
        $page_data['system_name'] = Cache::remember('system_name', 3600, function () {
            return DB::table('settings')->where('type', 'system_name')->value('description');
        });
        $page_data['system_favicon'] = Cache::remember('system_fav_icon', 3600, function () {
            return DB::table('settings')->where('type', 'system_fav_icon')->value('description');
        });

        $page_data['all_cities'] = Cache::remember('active_cities_events', 3600, function () {
            return CityHelper::getActiveCities();
        });

        $page_data['categories'] = Cache::remember('event_cat_city_cats_' . $city->id . '_' . $category->id, 3600, function () use ($city, $category) {
            return DB::table('eventcategories')->select('eventcategories.*')
                ->join('event_category', 'event_category.category_id', '=', 'eventcategories.id')
                ->join('events', 'event_category.event_id', 'events.id')
                ->distinct('eventcategories.id')
                ->where('events.event_status', 2)
                ->orderBy('eventcategories.id', 'asc')
                ->where('events.city_id', $city->id)
                ->where(function ($query) use ($category) {
                    $query->where('event_category.category_id', $category->id)
                        ->orWhere('eventcategories.category_parent_id', $category->id);
                })
                ->get();
        });


        $pageLiked = [];
        // $likepages = Page_like::where('user_id',auth()->user()->id)->get();
        // foreach($likepages as $likepage){
        //     $likepageid = $likepage->page_id;
        //     array_push($pageLiked,$likepageid);
        // }
        if (!is_null($category) && !is_null($city)) {
            $parentcategories = DB::table('eventcategories')->select('eventcategories.*')
                ->join('event_category', 'event_category.category_id', '=', 'eventcategories.id')
                ->join('events', 'events.id', '=', 'event_category.event_id')
                ->where('events.event_status', 2)
                ->where('eventcategories.id', $category->category_parent_id)
                ->where('events.city_id', $city->id)
                ->distinct('category_name')
                ->orderBy('category_name')->get();

            $parentcategory = Eventcategory::where('id', $category->category_parent_id)->first();
            //echo  $parentcategory;exit;


            $subcategories = [];

            foreach ($parentcategories as $allcategoriesresult) {
                $subcategories[] = $allcategoriesresult->category_name;
            }
            $page_data['parent_categories'] = $parentcategories;

            if ($parentcategory) {
                SEOMeta::setTitle($city->city_name . ' Near by top ' . $category->category_name . ' ' . $parentcategory->category_name . ', listing, deals, offers');
                SEOMeta::setDescription($city->city_name . ' Near by top ' . $category->category_name . ' ' . $parentcategory->category_name . ' listing, deals, offers');
            } else {
                SEOMeta::setTitle($city->city_name . ' Near by top ' . $category->category_name . ', listing, deals, offers');
                SEOMeta::setDescription($city->city_name . ' Near by top ' . $category->category_name . ' listing, deals, offers');
            }


            SEOMeta::setCanonical(URL::current());




            //echo  $request->city;exit;



            //  $paid_items_query=  DB::table('events')->select('events.id','events.event_slug','events.title',
            //  'events.city_id','events.area_id','events.banner','events.event_date','events.event_time',
            //  'cities.city_slug','areas.area_slug','users.id as userid','users.photo as userphoto','users.name as username'
            //  ,'events.location'
            // ,'cities.city_name','areas.area_name','states.state_name','events.created_at')
            // ->join('cities','cities.id','events.city_id')
            // ->join('areas','areas.id','events.area_id')
            // ->join('states','states.id','events.state_id')
            // ->join('event_category','event_category.event_id','events.id')
            // ->join('users','users.id','events.user_id')
            // ->join('eventcategories','event_category.category_id','=','eventcategories.id')
            // ->where('events.event_status',2)
            // ->where('events.event_date', '>=', Carbon::now())
            // ->where('events.city_id',$city->id)
            // ->where(function ($query) use ($category) {
            //     $query->where('event_category.category_id', $category->id)
            //     ->orWhere('eventcategories.category_parent_id',$category->id);
            // })
            // ->distinct('events.id');

            $filter_sort_by = $request->filter_sort_by ?? "newest";
            $page_data['filter_sort_by'] = $filter_sort_by;

            $paid_items_query = DB::table('events')->select(
                'events.id',
                'events.user_id',
                'events.event_slug',
                'events.title',
                'events.city_id',
                'events.area_id',
                'events.banner',
                'events.event_date',
                'events.event_time',
                'events.location',
                'events.created_at',
                'events.item_featured', // ✅ Make sure to select this
                'cities.city_slug',
                'cities.city_name',
                'areas.area_slug',
                'areas.area_name',
                'states.state_name',
                'users.id as userid',
                'users.photo as userphoto',
                'users.name as username'
            )
                ->join('cities', 'cities.id', '=', 'events.city_id')
                ->join('areas', 'areas.id', '=', 'events.area_id')
                ->join('states', 'states.id', '=', 'events.state_id')
                ->join('event_category', 'event_category.event_id', '=', 'events.id')
                ->join('eventcategories', 'event_category.category_id', '=', 'eventcategories.id')
                ->join('users', 'users.id', '=', 'events.user_id')
                ->where('events.event_status', 2)
                ->where('events.event_date', '>=', Carbon::now())
                ->where('events.city_id', $city->id)
                ->where(function ($query) use ($category) {
                    $query->where('event_category.category_id', $category->id)
                        ->orWhere('eventcategories.category_parent_id', $category->id);
                });

            // Sorting - orderBy('events.id', 'DESC') must come first for DISTINCT ON compliance
            $paid_items_query->orderBy('events.id', 'DESC');
            if ($filter_sort_by == "oldest") {
                $paid_items_query->orderByDesc('events.item_featured') // ✅ Top featured first
                    ->orderBy('events.created_at', 'asc');
            } else {
                $paid_items_query->orderByDesc('events.item_featured')
                    ->orderBy('events.created_at', 'desc');
            }

            // Final paginate
            $paid_items = $paid_items_query->distinct('events.id')->paginate(50);
            $paid_items->appends(['filter_sort_by' => $filter_sort_by]);

            $page_data['events'] = $paid_items;
            $page_data['view_path'] = 'frontend.events.categorycity';

            return view('frontend.event_category_city_index', $page_data);


        } else {
            abort(404);
        }

    }

    public function eventarea(Request $request, string $city_slug, string $area_slug)
    {

        $page_data['city'] = DB::table('cities')->select('cities.*')->where('city_slug', $city_slug)->first();
        $city = $page_data['city'];


        $page_data['all_cities'] = Cache::remember('active_cities_events', 3600, function () {
            return CityHelper::getActiveCities();
        });

        if ($city) {
            $page_data['area'] = DB::table('areas')->where('area_slug', $area_slug)->where('city_id', $city->id)->first();
            $area = $page_data['area'];

            if ($area) {
                SEOMeta::setTitle('Upcoming Events in ' . $area->area_name . ',' . $city->city_name . ' – Concerts, Festivals & Local Activities');
                SEOMeta::setDescription('Discover top events happening in ' . $area->area_name . ',' . $city->city_name . '! Explore concerts, festivals, business meetups, workshops, and cultural events. Stay updated with local happenings!');
                SEOMeta::setKeywords(['events in ' . $area->area_name . ',' . $city->city_name . ', concerts in ' . $area->area_name . ', festivals in ' . $area->area_name . ', workshops in ' . $area->area_name . ', business events in ' . $area->area_name]);
                SEOMeta::setCanonical(URL::current());

                $page_data['categories'] = Cache::remember('event_area_cats_' . $city->id . '_' . $area->id, 3600, function () use ($city, $area) {
                    return DB::table('eventcategories')->select('eventcategories.*')
                        ->join('event_category', 'event_category.category_id', '=', 'eventcategories.id')
                        ->join('events', 'events.id', '=', 'event_category.event_id')
                        ->where("events.event_status", 2)
                        ->where("events.city_id", $city->id)
                        ->where("events.area_id", $area->id)
                        ->distinct('eventcategories.id')
                        ->get();
                });

                //print_r($area);exit;



                //  $paid_items_query=   DB::table('events')->select('events.id','events.event_slug','events.title',
                //  'events.city_id','events.area_id','events.banner','events.event_date','events.event_time',
                //  'cities.city_slug','areas.area_slug','users.id as userid','users.photo as userphoto','users.name as username'
                //  ,'events.location'
                // ,'cities.city_name','areas.area_name','states.state_name','events.created_at')
                // ->join('cities','cities.id','events.city_id')
                // ->join('areas','areas.id','events.area_id')
                // ->join('states','states.id','events.state_id')
                // ->join('event_category','event_category.event_id','events.id')
                // ->join('users','users.id','events.user_id')
                // ->join('eventcategories','event_category.category_id','=','eventcategories.id')
                // ->where('events.event_status',2)
                // ->where('events.event_date', '>=', Carbon::now())
                // ->where("events.city_id", $city->id)
                // ->where("events.area_id", $area->id)
                //  ->distinct('events.id');


                $filter_sort_by = $request->filter_sort_by ?? "newest";
                $page_data['filter_sort_by'] = $filter_sort_by;

                $paid_items_query = DB::table('events')->select(
                    'events.id',
                    'events.user_id',
                    'events.event_slug',
                    'events.title',
                    'events.city_id',
                    'events.area_id',
                    'events.banner',
                    'events.event_date',
                    'events.event_time',
                    'events.location',
                    'events.created_at',
                    'events.item_featured', // ✅ important
                    'cities.city_slug',
                    'cities.city_name',
                    'areas.area_slug',
                    'areas.area_name',
                    'states.state_name',
                    'users.id as userid',
                    'users.photo as userphoto',
                    'users.name as username'
                )
                    ->join('cities', 'cities.id', '=', 'events.city_id')
                    ->join('areas', 'areas.id', '=', 'events.area_id')
                    ->join('states', 'states.id', '=', 'events.state_id')
                    ->join('event_category', 'event_category.event_id', '=', 'events.id')
                    ->join('eventcategories', 'event_category.category_id', '=', 'eventcategories.id')
                    ->join('users', 'users.id', '=', 'events.user_id')
                    ->where('events.event_status', 2)
                    ->where('events.event_date', '>=', Carbon::now())
                    ->where('events.city_id', $city->id)
                    ->where('events.area_id', $area->id);

                // ✅ Sorting: events.id first (required for DISTINCT ON), then featured, then created_at
                $paid_items_query->orderBy('events.id', 'DESC');
                $paid_items_query->distinct('events.id');

                $paid_items_query->orderByDesc('events.item_featured');

                if ($filter_sort_by == "oldest") {
                    $paid_items_query->orderBy('events.created_at', 'ASC');
                } else {
                    $paid_items_query->orderBy('events.created_at', 'DESC');
                }

                // ✅ Final paginated result
                $paid_items = $paid_items_query->paginate(50);
                $paid_items->appends(['filter_sort_by' => $filter_sort_by]);

                $page_data['events'] = $paid_items;
                $page_data['view_path'] = 'frontend.events.area';

                return view('frontend.event_city_area_index', $page_data);




            }
        }

    }

    public function jsonGetAreasByCityforitem(int $city_id)
    {


        $areas = DB::table("areas")
            ->select("areas.*")
            ->join('cities', 'cities.id', 'areas.city_id')
            ->join('events', 'events.area_id', 'areas.id')
            ->join('event_category', 'event_category.event_id', 'events.id')
            ->join('eventcategories', 'event_category.category_id', '=', 'eventcategories.id')
            ->distinct('events.id')
            ->orderBy('events.id', 'DESC')
            ->where('events.event_status', 2)
            ->where('areas.city_id', $city_id)
            ->where('events.city_id', $city_id)
            ->get()->toJson();

        return response()->json($areas);
    }



    // event going 

    public function event_going($id)
    {
        $response = array();


        $going_user_id = auth()->user()->id;
        $event_id = $id;
        $event = Event::find($event_id);
        $event_going_user = json_decode($event->going_users_id);
        array_push($event_going_user, $going_user_id);
        $event_going_user = json_encode($event_going_user);

        $event->going_users_id = $event_going_user;
        $event->save();
        $response = array('alertMessage' => get_phrase('Going to Event'), 'showElem' => "#notGoingId$event_id", 'hideElem' => "#goingId$event_id");
        return json_encode($response);
    }

    // event notgoing 

    public function event_notgoing($id)
    {
        $response = array();

        $going_user_id = auth()->user()->id;
        $event_id = $id;
        $event = Event::find($event_id);
        $event_going_user = json_decode($event->going_users_id, true);
        $this_user_key = array_search(auth()->user()->id, $event_going_user);
        array_splice($event_going_user, $this_user_key);
        $event_going_user = json_encode($event_going_user);

        $event->going_users_id = $event_going_user;
        $event->save();
        $response = array('alertMessage' => get_phrase('Cancle to Event Going'), 'showElem' => "#goingId$event_id", 'hideElem' => "#notGoingId$event_id");
        return json_encode($response);
    }








    // event interested

    public function event_interested($id)
    {
        $response = array();


        $going_user_id = auth()->user()->id;
        $event_id = $id;
        $event = Event::find($event_id);
        $event_going_user = json_decode($event->interested_users_id);
        array_push($event_going_user, $going_user_id);
        $event_going_user = json_encode($event_going_user);

        $event->interested_users_id = $event_going_user;
        $event->save();
        $response = array('alertMessage' => get_phrase('Interested to Event'), 'showElem' => "#notInterestedId$event_id", 'hideElem' => "#interestedId$event_id");
        return json_encode($response);
    }


    // event notinterested

    public function event_notinterested($id)
    {
        $response = array();

        $going_user_id = auth()->user()->id;
        $event_id = $id;
        $event = Event::find($event_id);
        $event_going_user = json_decode($event->interested_users_id, true);
        $this_user_key = array_search(auth()->user()->id, $event_going_user);
        array_splice($event_going_user, $this_user_key);
        $event_going_user = json_encode($event_going_user);

        $event->interested_users_id = $event_going_user;
        $event->save();
        $response = array('alertMessage' => get_phrase('Not Interested to Event'), 'showElem' => "#interestedId$event_id", 'hideElem' => "#notInterestedId$event_id");
        return json_encode($response);
    }


    // invite to friend 
    public function event_invite($invited_friend_id, $requester_id, $event_id)
    {

        $invite = new Invite();
        $invite->invite_reciver_id = $invited_friend_id;
        $invite->invite_sender_id = $requester_id;
        $invite->event_id = $event_id;
        $done = $invite->save();
        if ($done) {
            $notify = new Notification();
            $notify->sender_user_id = auth()->user()->id;
            $notify->reciver_user_id = $invited_friend_id;
            $notify->type = 'event';
            $notify->event_id = $event_id;
            $notify->save();

            Session::flash('success_message', get_phrase('Invite Done'));
            return json_encode(array('reload' => 1));
        }
    }



    // load event on scroll 

    public function load_event_by_scrolling(Request $request)
    {

        $events = Event::where('privacy', 'public')
            ->where('events.event_date', '>=', Carbon::now())
            ->whereNull('group_id')->skip($request->offset)->take(20)->orderBy('id', 'DESC')->get();

        $page_data['events'] = $events;
        return view('frontend.events.event-single', $page_data);
    }

    //    share event 
    public function shareevent()
    {
        $id = $_GET['event_id'];
        $url = url('/') . '/event/' . $id;

        $response = array();
        $sahre = new Share();
        $sahre->share_user_id = auth()->user()->id;
        $sahre->event_id = $id;
        $sahre->url = $url;
        $done = $sahre->save();
        if ($done) {
            $response = array('alertMessage' => get_phrase('Event Shared Successfully'));
        }
        return json_encode($response);
    }


    function search_user_for_event_inviting(Request $request)
    {
        $event_id = $request->id;

        $users = User::where('name', 'like', '%' . $request->search_value . '%')
            ->take(30)->get();


        $data['users'] = $users;
        return view('frontend.events.invite', $data);
    }


    public function sent_invition(Request $request)
    {

        $invited_event_users_id = $request->invited_event_users_id;
        $count = count($invited_event_users_id);

        for ($i = 0; $i < $count; $i++) {
            $invite = new Invite();
            $invite->invite_sender_id = auth()->user()->id;
            $invite->invite_reciver_id = $invited_event_users_id[$i];
            $invite->is_accepted = '0';
            $invite->event_id = $request->event_id;
            $invite->save();

            $notify = new Notification();
            $notify->sender_user_id = auth()->user()->id;
            $notify->reciver_user_id = $invited_event_users_id[$i];
            $notify->type = 'event';
            $notify->event_id = $request->event_id;
            $notify->save();
        }
        Session::flash('success_message', get_phrase('Event Invited Done Successfully'));
        return json_encode(array('reload' => 1));
    }
}

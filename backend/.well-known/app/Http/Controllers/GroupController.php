<?php

namespace App\Http\Controllers;

use App\Models\Album_image;
use App\Models\Group;
use App\Models\Group_member;
use App\Models\Groupcategory;
use App\Models\Friendships;
use App\Models\Media_files;
use App\Models\Posts;
use App\Models\Albums;
use App\Models\Event;
use App\Models\Invite;
use App\Models\Notification;
use App\Models\ReportAll;
use App\Models\User;
use App\Models\FileUploader;
use Illuminate\Http\Request;
use App\Models\ManageApproval;
use Image, Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\URL;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Helpers\CityHelper;
use App\Services\UserActivityService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;




class GroupController extends Controller
{



    public function boot()
    {
        view()->share('all_categories', Groupcategory::where('category_parent_id', 0)
            ->orderBy('id', 'DESC')
            ->get());
    }

    public function submitReport(Request $request)
    {
        // Validate form data
        $validator = Validator::make($request->all(), [
            "type" => "required|string", // Group, Page, Event, etc.
            "entity_id" => "required|integer",
            "email" => "required|email",
            "reason" => "required|string",
            "proof_attachment" =>
                "nullable|mimes:jpg,jpeg,png,pdf,docx|max:2048",
            //'g-recaptcha-response' => 'required'
        ]);

        if ($validator->fails()) {
            //return back()->with('success',  $validator->errors());
            //return response()->json(['error' => $validator->errors()], 422);
        }

        //Verify Google reCAPTCHA
        // $captchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        //     'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
        //     'response' => $request->input('g-recaptcha-response')
        // ]);

        // if (!$captchaResponse->json()['success']) {
        //     return response()->json(['error' => 'CAPTCHA verification failed.'], 422);
        // }

        // Handle file upload
        $proofPath = null;
        if ($request->hasFile("proof_attachment")) {
            $proofPath = $request
                ->file("proof_attachment")
                ->store("proofs", "public");
        }

        // Save report to database
        ReportAll::create([
            "type" => $request->type,
            "entity_id" => $request->entity_id,
            "user_id" => auth()->user()->id ?? null, // Capture logged-in user ID or allow anonymous
            "full_name" => $request->full_name ?? (auth()->user()->name ?? 'Guest User'),
            "email" => $request->email ?? (auth()->user()->email ?? 'guest@example.com'),
            "phone" => $request->phone ?? null,
            "reason" => $request->reason,
            "additional_comments" => $request->additional_comments,
            "proof_attachment" => $proofPath,
            "response_required" => $request->response_required ?? "No",
        ]);

        if (auth()->user()) {
            app(UserActivityService::class)->log(
                auth()->user()->id,
                "report",
                $request->type,
                $request->entity_id,
                $request->entity_id
            );
        }

        return back()->with(
            "success",
            "Your report has been submitted successfully."
        );
        //return response()->json(['success' => 'Your report has been submitted successfully.']);
    }
    public function groups(Request $request)
    {
        SEOMeta::setTitle(
            "Join & Explore Local Groups – Connect with Like-Minded People | CityHangaround"
        );
        //SEOMeta::setDescription($area->area_name . ', ' . 'Top 10 ' . $parentcategory->category_name . ', ' .  implode(',',$subcategories) . ' Free Listing Cityhangaround');
        SEOMeta::setDescription(
            "Find and join local groups based on your interests in business, networking, hobbies, events, and more. Connect with people in your city and grow your community!"
        );
        SEOMeta::addKeyword([
            "local groups",
            "community groups",
            "networking groups",
            "hobby groups",
            "business groups",
            "social groups",
            "CityHangaround groups",
            "join groups online",
        ]);

        SEOMeta::setCanonical(URL::current());

        $page_data["all_cities"] = CityHelper::getActiveCities();
        // Cache cities that have public active groups for 1 hour
        $page_data['all_group_cities'] = Cache::remember('active_community_cities', 3600, function () {
            return DB::table('cities')->select('cities.*')
                ->join('groups', 'groups.city_id', '=', 'cities.id')
                ->where('groups.privacy', 'public')
                ->where('groups.status', '1')
                ->where('groups.group_status', '2')
                ->distinct()
                ->get();
        });

        // Use cached categories list
        $page_data['categories'] = Cache::remember('community_parent_categories', 3600, function () {
            return Groupcategory::whereNull('category_parent_id')
                ->orWhere('category_parent_id', 0)
                ->orderBy('category_name', 'ASC')
                ->get();
        });

        // Pre-fetch areas if needed (though groups index usually doesn't have a default city)
        $page_data['all_areas'] = [];
        if (!empty($request->city)) {
            $page_data['all_areas'] = DB::table('areas')->where('city_id', $request->city)->get();
        }

        $page_data["groups"] = Group::select("groups.*")
            ->join(
                "group_category",
                "groups.id",
                "=",
                "group_category.group_id"
            )
            ->join(
                "groupcategories",
                "group_category.category_id",
                "=",
                "groupcategories.id"
            )
            ->where("groups.privacy", "public")
            ->where("groups.status", "1")
            ->where("groups.group_status", "2")
            ->distinct()
            ->orderByDesc("groups.item_featured") // ✅ Show featured first
            ->orderByDesc("groups.id") // ✅ Then latest
            ->limit(18)
            ->get();

        if (auth()->user()) {
            $page_data["managegroups"] = Group::select(
                "groups.*",
                "groupcategories.category_name"
            )
                ->join(
                    "group_category",
                    "groups.id",
                    "=",
                    "group_category.group_id"
                )
                ->join(
                    "groupcategories",
                    "group_category.category_id",
                    "=",
                    "groupcategories.id"
                )
                ->where("groups.user_id", auth()->user()->id)
                ->distinct()
                ->orderBy("groups.id", "DESC")
                ->limit(6)
                ->get();
        } else {
            $page_data["managegroups"] = [];
        }

        if (auth()->user()) {
            $page_data["joinedgroups"] = DB::table("groups")
                ->select("groups.*", "groupcategories.category_name")
                ->join(
                    "group_members",
                    "group_members.group_id",
                    "=",
                    "groups.id"
                )
                ->join(
                    "group_category",
                    "group_category.group_id",
                    "=",
                    "groups.id"
                )
                ->join(
                    "groupcategories",
                    "group_category.category_id",
                    "=",
                    "groupcategories.id"
                )
                ->where("group_members.user_id", auth()->user()->id)
                ->distinct()
                ->orderBy("groups.id", "DESC")
                ->limit(6)
                ->get();
        } else {
            $page_data["joinedgroups"] = [];
        }

        $page_data["all_printable_categories"] = Cache::remember('community_printable_categories', 3600, function () {
            return DB::table("groupcategories")
                ->select("groupcategories.*")
                ->join(
                    "group_category",
                    "group_category.category_id",
                    "=",
                    "groupcategories.id"
                )
                ->join("groups", "groups.id", "group_.category.group_id")
                ->distinct("groupcategories.id")
                ->orderBy("groupcategories.id", "DESC")
                ->where("groups.group_status", 2)
                ->where(function ($q) {
                    $q->where("groupcategories.category_parent_id", 0)
                        ->orWhereNull("groupcategories.category_parent_id");
                })
                ->get();
        });

        $page_data['view_path'] = 'frontend.groups.groups';

        return view('frontend.group_index', $page_data);
    }

    public function userpgroup()
    {
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $groups = Group::where("user_id", auth()->user()->id)
            ->where("groups.group_status", 2)
            ->orderBy("id", "DESC")
            ->get();
        $page_data["groups"] = $groups;
        $page_data["view_path"] = "frontend.groups.user_groups";
        return view("frontend.index", $page_data);
    }

    public function storecategories(Request $request)
    {
        $duplicatecount = DB::table("groupcategories")
            ->where("category_name", $request->category_name)
            ->count();

        if ($duplicatecount == 0) {
            $category = new Groupcategory();

            $category->category_name = $request->category_name;
            $category->category_slug = strtolower(
                str_replace(" ", "-", $request->category_name)
            );
            $category->category_icon = "";
            $category->category_parent_id = $request->category_parent_id;
            $category->category_description = "";
            $category->category_createdby = auth()->user()->id;

            $category->save();

            if (auth()->user()) {
                app(UserActivityService::class)->log(
                    auth()->user()->id,
                    "category_suggest",
                    "group_category",
                    $category->id,
                    $category->id
                );
            }

            \Session::flash("flash_message", __("Created"));
            \Session::flash("flash_type", "success");
            return response()->json(1);
        } else {
            return response()->json("duplicate");
        }
        //return redirect()->route('user.items.create');
    }

    public function dataAjax(Request $request)
    {
        $data = [];
        if ($request->has("q")) {
            $search = $request->q;
            $data = DB::table("groupcategories")
                ->select("id", "category_name")
                ->where("category_name", "LIKE", "$search%")
                ->where("category_parent_id", "!=", null)
                ->get();
        }
        return response()->json($data);
    }

    public function jsonGetCategories()
    {
        $parents = DB::table("groupcategories")
            ->select(
                "groupcategories.id",
                "groupcategories.category_name",
                "cat.category_name as parent"
            )
            ->leftjoin(
                "groupcategories as cat",
                "cat.id",
                "=",
                "groupcategories.category_parent_id"
            )
            ->orderby("id", "asc")
            ->get()
            ->toJson();

        return response()->json($parents);
    }

    public function category_group(Request $request, string $category_slug)
    {
        //print_r($request->city);exit;
        $category = Groupcategory::where(
            "category_slug",
            $category_slug
        )->first();
        $page_data["category"] = $category;
        $page_data["all_cities"] = CityHelper::getActiveCities();

        $page_data["all_categories"] = DB::table("groupcategories")
            ->select("groupcategories.*")
            ->join(
                "group_category",
                "group_category.category_id",
                "=",
                "groupcategories.id"
            )
            ->join("groups", "group_category.group_id", "groups.id")
            ->distinct("groupcategories.id")
            ->where("groups.group_status", 2)
            ->orderBy("groupcategories.id", "DESC")
            ->where("groupcategories.category_parent_id", 0)
            ->get();

        $page_data["all_group_cities"] = Cache::remember('active_community_cities_cat_' . $category->id, 3600, function () use ($category) {
            return DB::table("cities")
                ->select("cities.*")
                ->join("groups", "groups.city_id", "cities.id")
                ->join("group_category", "group_category.group_id", "groups.id")
                ->distinct("cities.id")
                ->where("groups.group_status", 2)
                ->where("group_category.category_id", $category->id)
                ->orderBy("cities.city_name", "asc")
                ->get();
        });

        $page_data["all_categories"] = Cache::remember('active_community_parent_categories', 3600, function () {
            return DB::table("groupcategories")
                ->select("groupcategories.*")
                ->join("group_category", "group_category.category_id", "=", "groupcategories.id")
                ->join("groups", "group_category.group_id", "groups.id")
                ->distinct("groupcategories.id")
                ->where("groups.group_status", 2)
                ->orderBy("groupcategories.id", "DESC")
                ->where("groupcategories.category_parent_id", 0)
                ->get();
        });

        $page_data['categories'] = Cache::remember('community_parent_categories', 3600, function () {
            return Groupcategory::whereNull('category_parent_id')
                ->orWhere('category_parent_id', 0)
                ->orderBy('category_name', 'ASC')
                ->get();
        });

        if ($category) {
            SEOMeta::setTitle("Explore " . $category->category_name . " Groups – Connect & Network on Cityhangaround");
            SEOMeta::setDescription("Find the best " . $category->category_name . " groups in your city. Join like-minded communities, discuss topics, share updates, and engage in exciting conversations on CityHangaround!");
            SEOMeta::setKeywords([
                "{$category->category_name} groups",
                "join {$category->category_name} communities",
                "{$category->category_name} networking",
                "best {$category->category_name} groups",
                "{$category->category_name} discussions",
                "online {$category->category_name} forums",
            ]);
            SEOMeta::setCanonical(URL::current());

            $filter_city = empty($request->city) ? null : $request->city;
            $filter_area = empty($request->area) ? null : $request->area;

            // Pre-fetch areas for the sidebar
            $page_data['all_areas'] = [];
            if (!empty($filter_city)) {
                $page_data['all_areas'] = DB::table('areas')->where('city_id', $filter_city)->get();
            }

            $page_data["filter_city"] = $filter_city;
            $page_data["filter_area"] = $filter_area === "" || is_null($filter_area) ? "0" : $filter_area;

            $paid_items_query = DB::table("groups")
                ->select(
                    "groups.*",
                    "cities.city_name",
                    "areas.area_name",
                    "states.state_name"
                )
                ->leftJoin("cities", "cities.id", "=", "groups.city_id")
                ->leftJoin("areas", "areas.id", "=", "groups.area_id")
                ->leftJoin("states", "states.id", "=", "groups.state_id")
                ->join(
                    "group_category",
                    "group_category.group_id",
                    "=",
                    "groups.id"
                )
                ->where("groups.group_status", 2)
                ->where(function ($query) use ($category) {
                    $query->where("group_category.category_id", $category->id);
                })
                ->when(
                    !empty($filter_city),
                    fn($q) => $q->where("groups.city_id", $filter_city)
                )
                ->when(
                    !empty($filter_area),
                    fn($q) => $q->where("groups.area_id", $filter_area)
                )
                ->orderByDesc("groups.item_featured") // ✅ Featured first
                ->orderByDesc("groups.id") // ✅ Then latest
                ->distinct("groups.id");

            $filter_sort_by = empty($request->filter_sort_by)
                ? "newest"
                : $request->filter_sort_by;
            $page_data["filter_sort_by"] = $filter_sort_by;

            if ($filter_sort_by === "newest") {
                $paid_items_query->orderBy("groups.created_at", "DESC");
            } elseif ($filter_sort_by === "oldest") {
                $paid_items_query->orderBy("groups.created_at", "ASC");
            }

            $paid_items = $paid_items_query->paginate(50);
            $page_data['total_results'] = $paid_items->total();


            $querystringArray = [
                "filter_sort_by" => $filter_sort_by,
                "filter_city" => $filter_city,
                "filter_area" => $filter_area,
            ];

            $paid_items->appends($querystringArray);
            $page_data["groups"] = $paid_items;

            $page_data["view_path"] = "frontend.groups.category";

            return view("frontend.group_index", $page_data);

        } else {
            abort(404);
        }
    }

    public function community_design()
    {
        $page_data["view_path"] = "frontend.groups.community_design";
        $category = Groupcategory::where(
            "category_slug",
            "social-group"
        )->first();
        $page_data["category"] = $category;
        return view("frontend.index", $page_data);
    }

    public function blog_design()
    {
        $page_data["view_path"] = "frontend.blogs.blog_design";
        $category = Groupcategory::where(
            "category_slug",
            "social-group"
        )->first();
        $page_data["category"] = $category;
        return view("frontend.index", $page_data);
    }

    public function jsonGetAreasByCityforitem(int $city_id)
    {
        $areas = DB::table("areas")
            ->select("areas.*")
            ->join("cities", "cities.id", "areas.city_id")
            ->join("groups", "groups.area_id", "areas.id")
            ->join("group_category", "group_category.group_id", "groups.id")
            ->join(
                "groupcategories",
                "group_category.category_id",
                "=",
                "groupcategories.id"
            )
            ->distinct("groups.id")
            ->where("groups.group_status", 2)
            ->where("areas.city_id", $city_id)
            ->where("groups.city_id", $city_id)
            ->get()
            ->toJson();

        $page_data['groupCategories'] = Groupcategory::where('category_parent_id', 0)
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->get();


        return response()->json($areas);
    }

    public function groupcategorycity(
        Request $request,
        $category_slug,
        $city_slug
    ) {
        //echo "123";exit;

        $category = Groupcategory::where(
            "category_slug",
            $category_slug
        )->first();
        $page_data["city"] = DB::table("cities")
            ->select("cities.*")
            ->where("city_slug", $city_slug)
            ->first();
        $city = $page_data["city"];
        $page_data["category"] = $category;

        $page_data["all_group_cities"] = Cache::remember('active_community_cities_cat_' . $category->id, 3600, function () use ($category) {
            return DB::table("cities")
                ->select("cities.*")
                ->join("groups", "groups.city_id", "cities.id")
                ->join("group_category", "group_category.group_id", "groups.id")
                ->distinct("cities.id")
                ->where("groups.group_status", 2)
                ->where("group_category.category_id", $category->id)
                ->orderBy("cities.city_name", "asc")
                ->get();
        });

        $page_data["all_categories"] = Cache::remember('active_community_parent_categories', 3600, function () {
            return DB::table("groupcategories")
                ->select("groupcategories.*")
                ->join(
                    "group_category",
                    "group_category.category_id",
                    "=",
                    "groupcategories.id"
                )
                ->join("groups", "group_category.group_id", "groups.id")
                ->distinct("groupcategories.id")
                ->where("groups.group_status", 2)
                ->orderBy("groupcategories.id", "asc")
                ->where("groups.city_id", $city->id)
                ->where(function ($query) use ($category) {
                    $query->where("group_category.category_id", $category->id)
                        ->orWhere("groupcategories.category_parent_id", $category->id);
                })
                ->get();
        });

        if (!is_null($category) && !is_null($city)) {
            $filter_city = $city->id;

            // Pre-fetch areas for the sidebar
            $page_data['all_areas'] = DB::table('areas')->where('city_id', $city->id)->get();

            $parentcategories = DB::table("groupcategories")
                ->select("groupcategories.*")
                ->join("group_category", "group_category.category_id", "=", "groupcategories.id")
                ->join("groups", "group_category.group_id", "groups.id")
                ->where("groups.group_status", 2)
                ->where("groupcategories.id", $category->category_parent_id)
                ->where("groups.city_id", $city->id)
                ->distinct("category_name")
                ->orderBy("category_name")
                ->get();

            $parentcategory = Groupcategory::where("id", $category->category_parent_id)->first();

            $subcategories = [];
            foreach ($parentcategories as $allcategoriesresult) {
                $subcategories[] = $allcategoriesresult->category_name;
            }
            $page_data["parent_categories"] = $parentcategories;

            if ($parentcategory) {
                SEOMeta::setTitle($city->city_name . " Near by top " . $category->category_name . " " . $parentcategory->category_name . ", listing, deals, offers");
                SEOMeta::setDescription($city->city_name . " Near by top " . $category->category_name . " " . $parentcategory->category_name . " listing, deals, offers");
            } else {
                SEOMeta::setTitle(
                    $city->city_name .
                    " Near by top " .
                    $category->category_name .
                    ", listing, deals, offers"
                );
                SEOMeta::setDescription(
                    $city->city_name .
                    " Near by top " .
                    $category->category_name .
                    " listing, deals, offers"
                );
            }

            SEOMeta::setCanonical(URL::current());

            //echo  $request->city;exit;

            //  $paid_items_query=  DB::table('groups')->select('groups.*')
            // ->leftjoin('cities','cities.id','groups.city_id')
            // ->leftjoin('areas','areas.id','groups.area_id')
            // ->leftjoin('states','states.id','groups.state_id')
            // ->join('group_category','group_category.group_id','groups.id')
            // ->join('groupcategories','group_category.category_id','=','groupcategories.id')
            // ->where('groups.group_status',2)
            // ->where('groups.city_id',$city->id)
            // ->where(function ($query) use ($category) {
            //     $query->where('group_category.category_id', $category->id)
            //     ->orWhere('groupcategories.category_parent_id',$category->id);
            // })
            // ->distinct('groups.id');

            $paid_items_query = DB::table("groups")
                ->select(
                    "groups.*",
                    "cities.city_name",
                    "areas.area_name",
                    "states.state_name"
                )
                ->leftJoin("cities", "cities.id", "=", "groups.city_id")
                ->leftJoin("areas", "areas.id", "=", "groups.area_id")
                ->leftJoin("states", "states.id", "=", "groups.state_id")
                ->join(
                    "group_category",
                    "group_category.group_id",
                    "=",
                    "groups.id"
                )
                ->join(
                    "groupcategories",
                    "group_category.category_id",
                    "=",
                    "groupcategories.id"
                )
                ->join("users", "users.id", "=", "groups.user_id")
                ->where("groups.group_status", 2)
                ->where("groups.city_id", $city->id)
                ->where(function ($query) use ($category) {
                    $query
                        ->where("group_category.category_id", $category->id)
                        ->orWhere(
                            "groupcategories.category_parent_id",
                            $category->id
                        );
                })
                ->distinct("groups.id")
                ->orderByDesc("groups.item_featured") // ✅ Top priority: featured groups
                ->orderByDesc("groups.id"); // ✅ Then newest

            // Sorting
            $filter_sort_by = empty($request->filter_sort_by)
                ? "newest"
                : $request->filter_sort_by;
            $page_data["filter_sort_by"] = $filter_sort_by;

            if ($filter_sort_by === "newest") {
                $paid_items_query->orderBy("groups.created_at", "DESC");
            } elseif ($filter_sort_by === "oldest") {
                $paid_items_query->orderBy("groups.created_at", "ASC");
            } elseif ($filter_sort_by === "highest-rated") {
                // Add your custom logic for rating-based sort here (if exists)
            }

            // Final result
            $paid_items = $paid_items_query->paginate(50);

            // Append query string to pagination
            $paid_items->appends([
                "filter_sort_by" => $filter_sort_by,
            ]);

            // Send to view
            $page_data["groups"] = $paid_items;

            $page_data["view_path"] = "frontend.groups.categorycity";

            return view(
                "frontend.group_category_city_filter_index",
                $page_data
            );
        } else {
            abort(404);
        }
    }

    public function group_area(
        Request $request,
        string $city_slug,
        string $area_slug
    ) {
        $page_data["city"] = DB::table("cities")
            ->select("cities.*")
            ->where("city_slug", $city_slug)
            ->first();
        $city = $page_data["city"];

        $page_data["all_cities"] = CityHelper::getActiveCities();

        if ($city) {
            $page_data["area"] = DB::table("areas")
                ->select("areas.*")
                ->where("area_slug", $area_slug)
                ->where("city_id", $city->id)
                ->first();
            $area = $page_data["area"];

            if ($area) {
                SEOMeta::setTitle(
                    $area->area_name .
                    "," .
                    $city->city_name .
                    " nearby top business listing, deals, offers"
                );
                SEOMeta::setDescription(
                    $area->area_name .
                    "," .
                    $city->city_name .
                    " nearby top business listings, deals, offers, local business"
                );

                SEOMeta::setCanonical(URL::current());

                $page_data["categories"] = DB::table("groupcategories")
                    ->select("groupcategories.*")
                    ->join(
                        "group_category",
                        "group_category.category_id",
                        "=",
                        "groupcategories.id"
                    )
                    ->join("groups", "group_category.group_id", "groups.id")
                    ->distinct("groupcategories.id")
                    ->where("groups.group_status", 2)
                    ->orderBy("groupcategories.id", "asc")
                    ->where("groups.city_id", $city->id)
                    ->where("groups.area_id", $area->id)
                    ->distinct("areas.area_name")
                    ->get();

                //print_r($area);exit;

                //  $paid_items_query=   DB::table('groups')->select('groups.*')
                //  ->join('cities','cities.id','groups.city_id')
                //  ->join('areas','areas.id','groups.area_id')
                //  ->join('states','states.id','groups.state_id')
                //  ->join('group_category','group_category.group_id','groups.id')
                //  ->join('groupcategories','group_category.category_id','=','groupcategories.id')
                //  ->where('groups.group_status',2)
                //  ->where("groups.city_id", $city->id)
                // ->where("groups.area_id", $area->id)
                //  ->distinct('groups.id');

                $paid_items_query = DB::table("groups")
                    ->select(
                        "groups.*",
                        "cities.city_name",
                        "areas.area_name",
                        "states.state_name"
                    )
                    ->join("cities", "cities.id", "=", "groups.city_id")
                    ->join("areas", "areas.id", "=", "groups.area_id")
                    ->join("states", "states.id", "=", "groups.state_id")
                    ->join(
                        "group_category",
                        "group_category.group_id",
                        "=",
                        "groups.id"
                    )
                    ->join(
                        "groupcategories",
                        "group_category.category_id",
                        "=",
                        "groupcategories.id"
                    )
                    ->join("users", "users.id", "=", "groups.user_id")
                    ->where("groups.group_status", 2)
                    ->where("groups.city_id", $city->id)
                    ->where("groups.area_id", $area->id)
                    ->distinct("groups.id")
                    ->orderByDesc("groups.item_featured") // ✅ item_featured pe priority
                    ->orderByDesc("groups.id"); // ✅ phir latest

                // 🔁 Sorting filter
                $filter_sort_by = empty($request->filter_sort_by)
                    ? "newest"
                    : $request->filter_sort_by;
                $page_data["filter_sort_by"] = $filter_sort_by;

                if ($filter_sort_by === "newest") {
                    $paid_items_query->orderBy("groups.created_at", "DESC");
                } elseif ($filter_sort_by === "oldest") {
                    $paid_items_query->orderBy("groups.created_at", "ASC");
                }

                // 🔄 Pagination
                $paid_items = $paid_items_query->paginate(50);
                $paid_items->appends([
                    "filter_sort_by" => $filter_sort_by,
                ]);

                // ➕ Final assignment to view
                $page_data["groups"] = $paid_items;

                $page_data["view_path"] = "frontend.groups.area";

                return view("frontend.group_city_area_index", $page_data);
            }
        }
    }

    public function categorycityarea(
        Request $request,
        $city_slug,
        $category_slug,
        $area_slug
    ) {
        $category = Groupcategory::where(
            "category_slug",
            $category_slug
        )->first();
        $city = DB::table("cities")
            ->select("cities.*")
            ->where("city_slug", $city_slug)
            ->first();
        $page_data["area"] = DB::table("areas")
            ->select("areas.*")
            ->where("area_slug", $area_slug)
            ->where("city_id", $city->id)
            ->first();
        $area = $page_data["area"];
        $page_data["city"] = $city;
        $page_data["category"] = $category;
        $page_data["all_cities"] = CityHelper::getActiveCities();

        $page_data["categories"] = DB::table("groupcategories")
            ->select("groupcategories.*")
            ->join(
                "group_category",
                "group_category.category_id",
                "=",
                "groupcategories.id"
            )
            ->join("groups", "group_category.group_id", "groups.id")
            ->distinct("groupcategories.id")
            ->where("groups.group_status", 2)
            ->orderBy("groupcategories.id", "asc")
            ->where(function ($query) use ($category) {
                $query
                    ->where("group_category.category_id", $category->id)
                    ->orWhere(
                        "groupcategories.category_parent_id",
                        $category->id
                    );
            })
            ->where("groups.city_id", $city->id)
            ->where("groups.area_id", $area->id)
            ->distinct("areas.area_name")
            ->get();

        if (!is_null($category) && !is_null($city) && !is_null($area)) {
            $parentcategories = DB::table("groupcategories")
                ->select("groupcategories.*")
                ->join(
                    "group_category",
                    "group_category.category_id",
                    "=",
                    "groupcategories.id"
                )
                ->join("groups", "group_category.group_id", "groups.id")
                ->where("groups.group_status", 2)
                ->where("groupcategories.id", $category->category_parent_id)
                ->where("groups.city_id", $city->id)
                ->where("groups.area_id", $area->id)
                ->distinct("category_name")
                ->orderBy("category_name")
                ->get();

            $parentcategory = Groupcategory::where(
                "id",
                $category->category_parent_id
            )->first();
            //echo  $parentcategory;exit;

            $subcategories = [];

            foreach ($parentcategories as $allcategoriesresult) {
                $subcategories[] = $allcategoriesresult->category_name;
            }
            $page_data["parent_categories"] = $parentcategories;

            SEOMeta::setTitle(
                $area->area_name .
                " Near by top " .
                $category->category_name .
                ", listing, deals, offers"
            );
            //SEOMeta::setDescription($area->area_name . ', ' . 'Top 10 ' . $parentcategory->category_name . ', ' .  implode(',',$subcategories) . ' Free Listing Cityhangaround');
            SEOMeta::setDescription(
                $area->area_name .
                " Near by top " .
                $category->category_name .
                ", deals, offers"
            );

            SEOMeta::setCanonical(URL::current());

            //     DB::table('groups')->select('groups.*')
            //     ->join('cities','cities.id','groups.city_id')
            //     ->join('areas','areas.id','groups.area_id')
            //     ->join('states','states.id','groups.state_id')
            //     ->join('group_category','group_category.group_id','groups.id')
            //     ->join('groupcategories','group_category.category_id','=','groupcategories.id')
            //     ->where('groups.group_status',2)
            //     ->where("groups.city_id", $city->id)
            //    ->where("groups.area_id", $area->id)
            //    ->where(function ($query) use ($category) {
            //     $query->where('group_category.category_id', $category->id)
            //     ->orWhere('groupcategories.category_parent_id',$category->id);
            //    })
            //     ->distinct('groups.id');

            $paid_items_query = DB::table("groups")
                ->select(
                    "groups.*",
                    "cities.city_name",
                    "areas.area_name",
                    "states.state_name"
                )
                ->join("cities", "cities.id", "=", "groups.city_id")
                ->join("areas", "areas.id", "=", "groups.area_id")
                ->join("states", "states.id", "=", "groups.state_id")
                ->join(
                    "group_category",
                    "group_category.group_id",
                    "=",
                    "groups.id"
                )
                ->join(
                    "groupcategories",
                    "group_category.category_id",
                    "=",
                    "groupcategories.id"
                )
                ->join("users", "users.id", "=", "groups.user_id")
                ->where("groups.group_status", 2)
                ->where("groups.city_id", $city->id)
                ->where("groups.area_id", $area->id)
                ->where(function ($query) use ($category) {
                    $query
                        ->where("group_category.category_id", $category->id)
                        ->orWhere(
                            "groupcategories.category_parent_id",
                            $category->id
                        );
                })
                ->distinct("groups.id")
                ->orderByDesc("groups.item_featured") // ✅ Top priority for featured groups
                ->orderByDesc("groups.id"); // ✅ Then newest by ID
            $filter_sort_by = empty($request->filter_sort_by)
                ? "newest"
                : $request->filter_sort_by;
            $page_data["filter_sort_by"] = $filter_sort_by;

            if ($filter_sort_by === "newest") {
                $paid_items_query->orderBy("groups.created_at", "DESC");
            } elseif ($filter_sort_by === "oldest") {
                $paid_items_query->orderBy("groups.created_at", "ASC");
            }
            $paid_items = $paid_items_query->paginate(50);

            $querystringArray = [
                "filter_sort_by" => $filter_sort_by,
            ];
            $paid_items->appends($querystringArray);

            $page_data["groups"] = $paid_items;

            $page_data["view_path"] = "frontend.groups.categorycityarea";

            return view("frontend.group_city_category_area_index", $page_data);
        } else {
            abort(404);
        }
    }

    public function group_city(Request $request, $city_slug)
    {
        dd("hello");
        $city = DB::table("cities")
            ->select("cities.*")
            ->where("city_slug", $city_slug)
            ->first();
        $page_data["city"] = $city;

        //print_r( $city );exit;

        $page_data["all_cities"] = CityHelper::getActiveCities();

        if (!is_null($city)) {
            SEOMeta::setTitle(
                $city->city_name .
                "," .
                "near by top business listing, deals, offers"
            );
            SEOMeta::setDescription(
                "Get top business listing, deals, offers, local business list near -" .
                $city->city_name
            );

            SEOMeta::setCanonical(URL::current());

            $categories = DB::table("groupcategories")
                ->select("groupcategories.*")
                ->join(
                    "group_category",
                    "group_category.category_id",
                    "=",
                    "groupcategories.id"
                )
                ->join("groups", "group_category.group_id", "groups.id")
                ->distinct("groupcategories.id")
                ->where("groups.group_status", 2)
                ->orderBy("groupcategories.id", "asc")
                ->where("groups.city_id", $city->id)
                ->where("groupcategories.category_parent_id", 0)
                ->get();

            //print_r($categories);exit;

            //print_r($city);exit;

            $page_data["categories"] = $categories;

            $page_data["view_path"] = "frontend.groups.city";
            return view("frontend.group_city_index", $page_data);
        } else {
            abort(404);
        }
    }

    public static function getgroupbycategoryid($categoryid, $cityid)
    {
        //echo $categoryid;
        $paid_items_query = DB::table("groups")
            ->select("groups.*")
            ->join("cities", "cities.id", "=", "groups.city_id")
            ->join("areas", "areas.id", "=", "groups.area_id")
            ->join("states", "states.id", "=", "groups.state_id")
            ->join(
                "group_category",
                "group_category.group_id",
                "=",
                "groups.id"
            )
            ->join(
                "groupcategories",
                "group_category.category_id",
                "=",
                "groupcategories.id"
            )
            ->join("users", "users.id", "=", "groups.user_id")
            ->where("groups.group_status", 2)
            ->where("groups.city_id", $cityid)
            ->where(function ($query) use ($categoryid) {
                $query
                    ->where("group_category.category_id", $categoryid)
                    ->orWhere(
                        "groupcategories.category_parent_id",
                        $categoryid
                    );
            })
            ->orderByDesc("groups.item_featured") // ✅ Featured groups first
            ->orderByDesc("groups.id") // ✅ Then latest
            ->distinct("groups.id")
            ->limit(4)
            ->get();

        return $paid_items_query;
    }

    public function create()
    {
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $page_data["printable_categories"] = DB::table("groupcategories")
            ->where("category_parent_id", null)
            ->get();
        $page_data["countries"] = DB::table("countries")
            ->select("countries.*")
            ->where("id", 101)
            ->get();
        $page_data["all_states"] = DB::table("states")
            ->select("states.*")
            ->where("country_id", 101)
            ->get();
        $page_data["parent"] = DB::table("groupcategories")
            ->where("groupcategories.category_parent_id", null)
            // ->orWhereNull('pagecategories.category_parent_id')
            ->get();
        //print_r($page_data['parent']);exit;
        $page_data["view_path"] = "frontend.groups.create";
        return view("frontend.index", $page_data);
    }

    public function edit($id)
    {
        $page_data["group_id"] = $id;
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $page_data["printable_categories"] = DB::table("groupcategories")
            ->where("category_parent_id", null)
            ->get();

        $page_data["group"] = \App\Models\Group::find($id);
        $page_data["all_states"] = DB::table("states")
            ->select("states.*")
            ->where("country_id", 101)
            ->get();
        $page_data["parent"] = DB::table("groupcategories")
            ->where("groupcategories.category_parent_id", null)
            // ->orWhereNull('pagecategories.category_parent_id')
            ->get();

        $page_data["countries"] = DB::table("countries")
            ->select("countries.*")
            ->where("id", 101)
            ->get();

        $page_data["all_states"] = DB::table("states")
            ->select("states.*")
            ->where("country_id", 101)
            ->get();
        $page_data["all_cities"] = DB::table("cities")
            ->select("cities.*")
            ->where("state_id", $page_data["group"]->state_id)
            ->get();
        $page_data["all_areas"] = DB::table("areas")
            ->select("areas.*")
            ->where("city_id", $page_data["group"]->city_id)
            ->get();

        //print_r($page_data['parent']);exit;
        $page_data["view_path"] = "frontend.groups.edit-modal";
        return view("frontend.index", $page_data);
    }

    public function single_group(
        $category_slug,
        $item_slug,
        $city_slug = null,
        $area_slug = null
    ) {
        // Fetch all cities (for dropdowns or filtering)
        $page_data["all_cities"] = DB::table("cities")
            ->select("cities.*")
            ->join("pages", "pages.city_id", "=", "cities.id")
            ->join("page_category", "page_category.page_id", "=", "pages.id")
            ->join(
                "pagecategories",
                "page_category.category_id",
                "=",
                "pagecategories.id"
            )
            ->distinct()
            ->where("pages.item_status", 2)
            ->orderBy("cities.city_name", "asc")
            ->get();

        // Get the group by slug
        $group = DB::table("groups")
            ->where("group_slug", $item_slug)
            ->first();
        if (!$group) {
            abort(404, "Group not found");
        }
        if (auth()->user()) {
            app(UserActivityService::class)->log(
                auth()->user()->id,
                "view",
                "group",
                $group->id,
                $group->id
            );
        }
        // Get the group ID
        $id = $group->id;

        // Get category by slug
        $category = Groupcategory::where(
            "category_slug",
            $category_slug
        )->first();
        if (!$category) {
            abort(404, "Category not found");
        }

        // Get city by slug (if provided)
        $city = null;
        if (!empty($city_slug)) {
            $city = DB::table("cities")
                ->where("city_slug", $city_slug)
                ->first();

            // Optional validation: check if group's city matches the slug
            if (!$city || $group->city_id != $city->id) {
                abort(404, "City mismatch or not found");
            }
        }

        // Get parent categories based on city & category parent
        $parentcategories = collect();
        if ($city) {
            $parentcategories = DB::table("groupcategories")
                ->select("groupcategories.*")
                ->join(
                    "group_category",
                    "group_category.category_id",
                    "=",
                    "groupcategories.id"
                )
                ->join("groups", "groups.id", "=", "group_category.group_id")
                ->where("groups.group_status", 2)
                ->where("groupcategories.id", $category->category_parent_id)
                ->where("groups.city_id", $city->id)
                ->distinct()
                ->orderBy("category_name")
                ->get();
        }

        // Get parent category object
        $parentcategory = Groupcategory::find($category->category_parent_id);

        // Subcategory names array
        $subcategories = $parentcategories->pluck("category_name")->toArray();

        // Posts related to this group
        $posts = Posts::where("posts.privacy", "!=", "private")
            ->where("posts.publisher", "group")
            ->where("posts.publisher_id", $id)
            ->where("posts.status", "active")
            ->join("users", "posts.user_id", "=", "users.id")
            ->select(
                "posts.*",
                "users.name",
                "users.photo",
                "users.friends",
                "posts.created_at as created_at"
            )
            ->orderBy("posts.post_id", "DESC")
            ->get();

        // Count accepted members
        $totalmember = Group_member::where("group_id", $id)
            ->where("is_accepted", 1)
            ->count();

        // Page data to pass to view
        $page_data["category"] = $category;
        $page_data["parent_categories"] = $parentcategories;
        $page_data["group"] = $group;
        $page_data["membercount"] = $totalmember;
        $page_data["posts"] = $posts;
        $page_data["view_path"] = "frontend.groups.discuss";
        $page_data['all_categories'] = Groupcategory::all();
        $page_data['all_group_cities'] = DB::table('cities')->orderBy('city_name')->get();

        $page_data['filter_city'] = null;
        $page_data['filter_area'] = null;
        $page_data['filter_sort_by'] = 'newest';
        $page_data['total_results'] = 0;


        return view("frontend.group_index", $page_data);
    }



    public function createCategoryFromSelect2(Request $request)
    {
        $duplicateCount = DB::table("groupcategories")
            ->where("category_name", $request->category_name)
            ->count();

        if ($duplicateCount === 0) {
            $category = new Groupcategory();

            $category->category_name = $request->category_name;
            $category->category_slug = strtolower(
                str_replace(" ", "-", $request->category_name)
            );
            $category->category_icon = "";
            $category->category_parent_id = 0; // Or set dynamically if needed
            $category->category_description = "";
            $category->category_createdby = auth()->user()->id;

            $category->save();

            if (auth()->user()) {
                app(UserActivityService::class)->log(
                    auth()->user()->id,
                    "category_suggest",
                    "group_category",
                    $category->id,
                    $category->id
                );
            }

            return response()->json([
                "id" => $category->id,
                "category_name" => $category->category_name,
            ]);
        } else {
            // Return existing category if duplicate found (optional fallback)
            $existing = DB::table("groupcategories")
                ->where("category_name", $request->category_name)
                ->first();

            return response()->json([
                "id" => $existing->id,
                "category_name" => $existing->category_name,
                "duplicate" => true,
            ]);
        }
    }


    public function store(Request $request)
    {
        $rules = [
            "image" => "mimes:jpeg,jpg,png,gif|nullable",
            "name" => "required|max:255",
            "parent" => "required|not_in:0",
            "category" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput(); // Optional: to retain the input data
            //return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        }

        if ($request->image && !empty($request->image)) {
            $file_name = FileUploader::upload(
                $request->image,
                "public/storage/groups/logo",
                300
            );
        }

        $title = "group";
        $approval = ManageApproval::where("title", $title)->first();

        if ($approval && $approval->status == 1) {
            // Approval status is ON
            $group_status = 2;
        } elseif (auth()->check() && auth()->user()->user_role == "admin") {
            // Status is OFF but user is admin
            $group_status = 2;
        } else {
            //Status is OFF and user is not admin
            $group_status = 1;
        }

        // if(auth()->user()->user_role=="admin"){

        //     $group_status=2;

        // }
        // else{

        //     $group_status=1;
        // }

        if ($request->state) {
            $state_id = $request->state;
        } else {
            $state_id = null;
        }

        if ($request->city) {
            $city_id = $request->city;
        } else {
            $city_id = null;
        }

        if ($request->area) {
            $area_id = $request->area;
        } else {
            $area_id = null;
        }

        if ($request->country) {
            $country_id = $request->country;
        } else {
            $country_id = null;
        }

        $group_slug = preg_replace("/[^A-Za-z0-9 ]/", "", $request->name);

        $multiSelectArray = $request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }

        $categories_id = implode(",", $multiSelectArray);

        $group = new Group();
        $group->user_id = auth()->user()->id;
        $group->title = $request->name;
        $group->subtitle = $request->subtitle;

        $group->group_status = $group_status;
        $group->group_slug = str_slug($group_slug);
        $group->country_id = $country_id;
        $group->state_id = $state_id;
        $group->city_id = $city_id;
        $group->area_id = $area_id;
        $group->category_id = $categories_id;

        $group->about = $request->about;
        $group->privacy = $request->privacy;
        $group->status = $request->status;
        if ($request->image && !empty($request->image)) {
            $group->logo = $file_name;
        }

        $user = auth()->user();
        $activeSubscription = $user
            ->activeSubscription()
            ->with("subscription")
            ->first();

        if (
            $activeSubscription &&
            $activeSubscription->subscription &&
            Str::contains(
                $activeSubscription->subscription->offered_services,
                "group"
            )
        ) {
            $durations = json_decode(
                $activeSubscription->subscription->area_durations,
                true
            );

            $cityDays = $durations["group"]["city"] ?? 0;
            $areaDays = $durations["group"]["area"] ?? 0;

            $subscriptionStart = Carbon::parse(
                $activeSubscription->created_at ?? now()
            );

            $priorityEnd = $subscriptionStart
                ->copy()
                ->addDays(max($cityDays, $areaDays));

            if ($cityDays > 0) {
                $group->priority_until_city = $subscriptionStart
                    ->copy()
                    ->addDays($cityDays);
            }
            if ($areaDays > 0) {
                $group->priority_until_area = $subscriptionStart
                    ->copy()
                    ->addDays($areaDays);
            }
            if ($priorityEnd->isFuture()) {
                $group->item_featured = 1;
            }
        }
        $done = $group->save();
        if ($done) {
            $group_member = new Group_member();
            $group_member->group_id = $group->id;
            $group_member->user_id = auth()->user()->id;
            $group_member->role = "admin";
            $group_member->is_accepted = "1";
            $done = $group_member->save();

            foreach ($multiSelectArray as $category_id) {
                $data = [
                    "category_id" => $category_id,
                    "group_id" => $group->id,
                ];
                $row = DB::table("group_category")->insertGetId($data);
            }

            $slug_count = DB::table("groups")
                ->select("groups.id")
                ->where("groups.group_slug", str_slug($request->name))
                ->count();

            if ($slug_count > 1) {
                DB::table("groups")
                    ->where("id", $group->id)
                    ->update([
                        "group_slug" => DB::raw(
                            'concat("' .
                            str_slug($request->name) .
                            '",' .
                            "-" .
                            $group->id .
                            ")"
                        ),
                    ]);
            }
            if ($done) {
                if (auth()->user()) {
                    app(UserActivityService::class)->log(
                        auth()->user()->id,
                        "group_listing",
                        "group",
                        $group->id,
                        $group->id
                    );
                }
                Session::flash(
                    "success_message",
                    get_phrase("Group Created Successfully")
                );
                return redirect()->route("groups");
                //return json_encode(array('reload' => 1));
            }
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "image" => "mimes:jpeg,jpg,png,gif|nullable",
            "name" => "required|max:255",
            "parent" => "required|not_in:0",
            "category" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput(); // Optional: to retain the input data
        }

        $group_slug = preg_replace("/[^A-Za-z0-9 ]/", "", $request->name);

        if ($request->state) {
            $state_id = $request->state;
        } else {
            $state_id = null;
        }

        if ($request->city) {
            $city_id = $request->city;
        } else {
            $city_id = null;
        }

        if ($request->area) {
            $area_id = $request->area;
        } else {
            $area_id = null;
        }

        if ($request->country) {
            $country_id = $request->country;
        } else {
            $country_id = null;
        }

        $multiSelectArray = $request->category;
        // Single select ID to be added
        $parent_id = $request->parent;

        // Check if the ID is already in the array
        if (!in_array($parent_id, $multiSelectArray)) {
            $multiSelectArray[] = $parent_id; // Add the single ID to the multi-select array
        }

        $categories_id = implode(",", $multiSelectArray);

        $group = Group::find($id);

        $title = "group";
        $approval = ManageApproval::where("title", $title)->first();

        if ($approval && $approval->status == 1) {
            // Approval status is ON
            $group_status = 2;
        } elseif (auth()->check() && auth()->user()->user_role == "admin") {
            // Status is OFF but user is admin
            $group_status = 2;
        } else {
            //Status is OFF and user is not admin
            $group_status = $group->group_status;
        }
        //previous image name
        $imagename = $group->logo;
        if ($request->image && !empty($request->image)) {
            $file_name = FileUploader::upload(
                $request->image,
                "public/storage/groups/logo",
                300
            );
        }

        // $group->user_id = auth()->user()->id;
        $group->title = $request->name;
        $group->subtitle = $request->subtitle;

        $group->group_slug = str_slug($group_slug);
        $group->country_id = $country_id;
        $group->state_id = $state_id;
        $group->city_id = $city_id;
        $group->area_id = $area_id;
        $group->category_id = $categories_id;
        $group->group_status = $group_status;
        $group->about = $request->about;
        $group->privacy = $request->privacy;
        $group->status = $request->status;
        $group->location = $request->location;
        $group->group_type = $request->group_type;
        if ($request->image && !empty($request->image)) {
            $group->logo = $file_name;
        }
        $done = $group->save();
        if ($done) {
            // just put the file name and folder name nothing more :)
            if (!empty($request->image)) {
                if (
                    File::exists(
                        public_path("storage/groups/logo/" . $imagename)
                    )
                ) {
                    File::delete(
                        public_path("storage/groups/logo/" . $imagename)
                    );
                }
            }

            foreach ($multiSelectArray as $category_id) {
                $category_count = DB::table("group_category")
                    ->select("group_category.id")
                    ->where("category_id", $category_id)
                    ->where("group_id", $id)
                    ->count();
                if ($category_count == 0) {
                    $data = [
                        "category_id" => $category_id,
                        "group_id" => $id,
                    ];
                    $row = DB::table("group_category")->insertGetId($data);
                }
            }
            $slug_count = DB::table("groups")
                ->select("groups.id")
                ->where("groups.group_slug", str_slug($request->name))
                ->count();

            if ($slug_count > 1) {
                DB::table("groups")
                    ->where("id", $id)
                    ->update([
                        "group_slug" => DB::raw(
                            'concat("' .
                            str_slug($request->name) .
                            '",' .
                            "-" .
                            $id .
                            ")"
                        ),
                    ]);
            }
        }
        Session::flash(
            "success_message",
            get_phrase("Group Updated Successfully")
        );
        //return json_encode(array('reload' => 1));
        return redirect()->route("groups");
    }

    public function updatecoverphoto(Request $request, $id)
    {
        $group = Group::find($id);
        $imagename = $group->coverphoto;

        if ($request->cover_photo && !empty($request->cover_photo)) {
            //Upload image
            $file_name =
                rand(1, 35000) .
                "." .
                $request->cover_photo->getClientOriginalExtension();
            //logo
            $img = Image::make($request->cover_photo);
            $img->resize(1120, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save(uploadTo("groups/coverphoto") . $file_name);
            $group->banner = $file_name;
        }
        $done = $group->save();
        if ($done) {
            // just put the file name and folder name nothing more :)
            if (!empty($request->cover_photo)) {
                if (
                    File::exists(
                        public_path("storage/groups/coverphoto/" . $imagename)
                    )
                ) {
                    File::delete(
                        public_path("storage/groups/coverphoto/" . $imagename)
                    );
                }
            }
        }
        Session::flash(
            "success_message",
            get_phrase("Cover Photo Updated Successfully")
        );
        return json_encode(["reload" => 1]);
    }

    public function join($id)
    {
        $response = [];
        $group_member = new Group_member();
        $group_member->group_id = $id;
        $group_member->user_id = auth()->user()->id;
        $group_member->role = "general";
        $group_member->is_accepted = "1";
        $group_member->save();
        if (auth()->user()) {
            app(UserActivityService::class)->log(
                auth()->user()->id,
                "follow",
                "group",
                $id,
                $id
            );
        }
        Session::flash(
            "success_message",
            get_phrase("Group Joind  Successfully")
        );
        $response = ["reload" => 1];
        return json_encode($response);
    }

    public function rjoin($id)
    {
        $response = [];
        $group_member = Group_member::where("group_id", $id)->delete();
        if (auth()->user()) {
            app(UserActivityService::class)->deleteBygrouplikeActivityId($id);
        }
        Session::flash("success_message", get_phrase("Group Joining Canceled"));
        $response = ["reload" => 1];
        return json_encode($response);
    }

    public function peopelinfo($id)
    {
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $page_data["friends"] = Friendships::where(
            "requester",
            auth()->user()->id
        )
            ->orWhere("accepter", auth()->user()->id)
            ->where("is_accepted", "1")
            ->orderBy("id", "DESC")
            ->limit("20")
            ->get();
        $page_data["friends_count"] = Friendships::where(
            "requester",
            auth()->user()->id
        )
            ->orWhere("accepter", auth()->user()->id)
            ->where("is_accepted", "1")
            ->orderBy("id", "DESC")
            ->count();
        $page_data["users"] = User::whereJsonDoesntContain(
            "friends",
            auth()->user()->id
        )->get();
        $page_data["group"] = Group::find($id);
        $page_data["total_member"] = Group_member::where("is_accepted", "1")
            ->where("group_id", $id)
            ->count();
        $page_data["recent_team_member"] = Group_member::where(
            "is_accepted",
            "1"
        )
            ->where("group_id", $id)
            ->orderBY("id", "DESC")
            ->limit("5")
            ->get();
        $page_data["view_path"] = "frontend.groups.people";
        return view("frontend.index", $page_data);
    }

    public function group_photos($id)
    {
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $page_data["group"] = Group::find($id);
        $page_data["all_photos"] = Media_files::where("group_id", $id)
            ->where("file_type", "image")
            ->orderBy("id", "DESC")
            ->get();
        $page_data["all_videos"] = Media_files::where("group_id", $id)
            ->where("file_type", "video")
            ->orderBy("id", "DESC")
            ->get();
        $page_data["all_albums"] = Albums::where("group_id", $id)
            ->orderBy("id", "DESC")
            ->get();
        $page_data["view_path"] = "frontend.groups.photos";
        return view("frontend.index", $page_data);
    }

    public function all_people_group($id)
    {
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $page_data["group"] = Group::find($id);
        $page_data["all_members"] = Group_member::where("is_accepted", "1")
            ->where("group_id", $id)
            ->orderBY("id", "DESC")
            ->get();
        $page_data["total_member"] = Group_member::where("is_accepted", "1")
            ->where("group_id", $id)
            ->count();
        $page_data["view_path"] = "frontend.groups.all_people";
        return view("frontend.index", $page_data);
    }

    public function group_event($id)
    {
        $page_data["all_cities"] = CityHelper::getActiveCities();

        $page_data["group"] = Group::find($id);
        $group_events = Event::where("group_id", $id)
            ->where(function ($query) {
                $query
                    ->where("privacy", "!=", "private")
                    ->orWhere("user_id", auth()->user()->id);
            })
            ->get();
        $page_data["group_events"] = $group_events;
        $page_data["view_path"] = "frontend.groups.event";
        return view("frontend.index", $page_data);
    }

    public function add_album_image(Request $request)
    {
        $response = [];
        if (is_array($request->images) && $request->images[0] != null) {
            //Data validation
            $rules = ["multiple_files" => "mimes:jpeg,jpg,png,gif"];
            $validator = Validator::make($request->images, $rules);
            if ($validator->fails()) {
                return json_encode([
                    "validationError" => $validator->getMessageBag()->toArray(),
                ]);
            }
            foreach ($request->images as $key => $media_file) {
                $file_name = FileUploader::upload(
                    $media_file,
                    "public/storage/album/images",
                    1000,
                    null,
                    300
                );
                $file_type = "image";

                $albumimage = new Album_image();
                $albumimage->user_id = auth()->user()->id;
                $albumimage->album_id = $request->album;
                $albumimage->image = $file_name;
                if (isset($request->page_id) && !empty($request->page_id)) {
                    $albumimage->page_id = $request->page_id;
                } elseif (
                    isset($request->group_id) &&
                    !empty($request->group_id)
                ) {
                    $albumimage->group_id = $request->group_id;
                } else {
                }
                $done = $albumimage->save();

                if (
                    isset($request->profile_id) &&
                    !empty($request->profile_id)
                ) {
                    $data["publisher_id"] = auth()->user()->id;
                    $data["user_id"] = auth()->user()->id;
                    $data["publisher"] = "post";
                    $data["post_type"] = "general";
                    $data["privacy"] = "public";
                    $data["privacy"] = "public";
                    $data["status"] = "active";
                    $data["tagged_user_ids"] = json_encode([]);
                    $data["user_reacts"] = json_encode([]);
                    $data["shared_user"] = json_encode([]);
                    $data["created_at"] = time();
                    $data["updated_at"] = $data["created_at"];

                    $post_id = Posts::insertGetId($data);
                    foreach ($request->images as $key => $media_file) {
                        $file_extention = strtolower(
                            $media_file->getClientOriginalExtension()
                        );
                        if (
                            $file_extention == "avi" ||
                            $file_extention == "mp4" ||
                            $file_extention == "webm" ||
                            $file_extention == "mov" ||
                            $file_extention == "wmv" ||
                            $file_extention == "mkv"
                        ) {
                            $file_name = FileUploader::upload(
                                $media_file,
                                "public/storage/post/videos"
                            );
                            $file_type = "video";
                        } else {
                            $file_name = FileUploader::upload(
                                $media_file,
                                "public/storage/post/images",
                                1000,
                                null,
                                300
                            );
                            $file_type = "image";
                        }

                        $media_file_data = [
                            "user_id" => auth()->user()->id,
                            "post_id" => $post_id,
                            "album_id" => $request->album,
                            "file_name" => $file_name,
                            "file_type" => $file_type,
                            "privacy" => $request->privacy,
                        ];
                        $media_file_data["created_at"] = time();
                        $media_file_data["updated_at"] =
                            $media_file_data["created_at"];
                        $done = Media_files::create($media_file_data);
                    }
                }

                if ($done) {
                    Session::flash(
                        "success_message",
                        get_phrase("Your images is added to album")
                    );
                    return json_encode(["reload" => 1]);
                }
            }
        }
    }

    public function search_group()
    {
        $search = $_GET["search"];
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $page_data["searchgroup"] = Group::where(
            "title",
            "like",
            "%" . $search . "%"
        )->get();
        $page_data["managegroups"] = Group::orderBy("id", "DESC")
            ->where("user_id", auth()->user()->id)
            ->limit("6")
            ->get();
        $page_data["joinedgroups"] = Group_member::where(
            "user_id",
            auth()->user()->id
        )
            ->where("is_accepted", "1")
            ->limit("6")
            ->get();
        $page_data["view_path"] = "frontend.groups.search-group";
        return view("frontend.index", $page_data);
    }

    public function group_all_view()
    {
        $page_data["all_cities"] = CityHelper::getActiveCities();
        $page_data["managegroups"] = Group::orderBy("id", "DESC")
            ->where("user_id", auth()->user()->id)
            ->limit("6")
            ->get();

        //$page_data['joinedgroups'] = Group_member::where('user_id',auth()->user()->id)->where('is_accepted','1')->limit('6')->get();
        $page_data["joinedgroups"] = DB::table("groups")
            ->select("groups.*")
            ->join("group_members", "group_members.group_id", "groups.id")
            ->orderBy("id", "DESC")
            ->where("group_members.user_id", auth()->user()->id)
            ->limit("6")
            ->get();
        $page_data["groups"] = Group::orderBy("id", "DESC")
            ->limit("6")
            ->get();
        $page_data["view_path"] = "frontend.groups.allgroup";
        return view("frontend.index", $page_data);
    }

    public function load_groups_by_scrolling(Request $request)
    {
        $groups = Group::skip($request->offset)
            ->take(6)
            ->orderBy("id", "DESC")
            ->get();

        $page_data["groups"] = $groups;
        return view("frontend.groups.group-single", $page_data);
    }

    public function group_user_create()
    {
        $page_data["managegroups"] = Group::orderBy("id", "DESC")
            ->where("user_id", auth()->user()->id)
            ->limit("6")
            ->get();
        $page_data["joinedgroups"] = Group_member::where(
            "user_id",
            auth()->user()->id
        )
            ->where("is_accepted", "1")
            ->limit("6")
            ->get();
        $page_data["groups"] = Group::where(
            "user_id",
            auth()->user()->id
        )->get();
        $page_data["view_path"] = "frontend.groups.user-group";
        return view("frontend.index", $page_data);
    }

    public function group_user_joined()
    {
        $page_data["managegroups"] = Group::orderBy("id", "DESC")
            ->where("user_id", auth()->user()->id)
            ->limit("6")
            ->get();
        $page_data["joinedgroups"] = Group_member::where(
            "user_id",
            auth()->user()->id
        )
            ->where("is_accepted", "1")
            ->limit("6")
            ->get();
        $page_data["groups"] = Group_member::where(
            "user_id",
            auth()->user()->id
        )
            ->where("is_accepted", "1")
            ->get();
        $page_data["view_path"] = "frontend.groups.user-joined";
        return view("frontend.index", $page_data);
    }

    function search_friends_for_inviting(Request $request)
    {
        $friends = User::where(
            "name",
            "like",
            "%" . $request->search_value . "%"
        )
            ->take(30)
            ->get();

        $data["users"] = $friends;
        $data["group_id"] = $request->group_id;
        return view("frontend.groups.invite-user", $data);
    }

    public function sent_invition(Request $request)
    {
        // return $request->all();
        $invited_group_users_id = $request->invited_group_users_id;
        $count = count($invited_group_users_id);

        for ($i = 0; $i < $count; $i++) {
            $invite = new Invite();
            $invite->invite_sender_id = auth()->user()->id;
            $invite->invite_reciver_id = $invited_group_users_id[$i];
            $invite->is_accepted = "0";
            $invite->group_id = $request->group_id;
            $invite->save();

            $notify = new Notification();
            $notify->sender_user_id = auth()->user()->id;
            $notify->reciver_user_id = $invited_group_users_id[$i];
            $notify->type = "group";
            $notify->group_id = $request->group_id;
            $notify->save();
        }
        Session::flash(
            "success_message",
            get_phrase("Group Invited Done Successfully")
        );
        return json_encode(["reload" => 1]);
    }
}

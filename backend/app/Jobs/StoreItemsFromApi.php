<?php
namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\Page;
use App\Models\Page_like;
class StoreItemsFromApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function handle()
    {
        foreach ($this->items as $item) {
            $country = DB::table('countries')->where('country_name', $item['country_name'])->first();

            if ($country) {
                $country_id = $country->id;
            } else {
                $country_id = DB::table('countries')->insertGetId([
                    'country_name' => $country_name,
                    'country_slug'=>str_slug($item['country_name']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $state = DB::table('states')->where('state_name', $item['state_name'])->where('country_id',$country_id)->first();

            if ($state) {
                $state_id = $state->id;
            } else {
                $state_id = DB::table('states')->insertGetId([
                    'country_id' => $country_id,
                    'state_name' =>$item['state_name'],
                    'state_slug'=>str_slug($item['state_name']),
                    'state_abbr'=>"ST",
                    'state_country_abbr'=>"IN",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }


            $city = DB::table('cities')->where('city_name', $item['city_name'])->where('state_id',$state_id)->first();

            if ($city) {
                $city_id = $city->id;
            } else {
                $city_id = DB::table('cities')->insertGetId([
                    'state_id' => $country_id,
                    'city_name' =>$item['state_name'],
                    'city_slug'=>str_slug($item['state_name']),
                    'city_state'=>$state->state_abbr,
                ]);
            }


            $area = DB::table('areas')->where('area_name', $item['area_name'])->where('city_id',$city_id)->first();

            if ($area) {
                $area_id = $area->id;
            } else {
                $area_id = DB::table('areas')->insertGetId([
                    'city_id' => $city_id,
                    'area_name' =>$item['area_name'],
                    'area_slug'=>str_slug($item['area_name']),
                ]);
            }
       
      
            $allCategories = explode(',', $item['all_category']);
            $categoryIds = [];
            
            foreach ($allCategories as $categoryName) {
                // 1. Trim spaces
                $categoryName = trim($categoryName);
            
                // 2. Remove special characters and newlines
                $categoryName = preg_replace('/[^A-Za-z0-9\s]/', '', $categoryName); // keep letters, numbers, space
                $categoryName = preg_replace('/\s+/', ' ', $categoryName); // replace multiple spaces/newlines with single space
            
                // Skip if empty after cleaning
                if (empty($categoryName)) {
                    continue;
                }
            
                // 3. Check or insert into DB
                // $category = DB::table('pagecategories')->where('category_name', $categoryName)->first();
                $category = DB::table('pagecategories')
                            ->where('category_name', $categoryName)
                            ->orWhere('category_slug', str_slug($categoryName))
                            ->first();

            
                if ($category) {
                    $categoryIds[] = $category->id;
                } else {
                    $newId = DB::table('pagecategories')->insertGetId([
                        'category_name' => $categoryName,
                        'category_slug'=>clean_slug($categoryName),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $categoryIds[] = $newId;
                }
            }
            
            $categoryIdsString = implode(',', $categoryIds);


            


       
            // Original title
        $itemTitle = $item['item_title'] ?? '';

        // 1. Trim extra spaces
        $itemTitle = trim($itemTitle);

        // 2. Remove all special characters except letters, numbers, and spaces
        $itemTitle = preg_replace('/[^A-Za-z0-9\s]/', '', $itemTitle);

        // 3. Replace multiple whitespace (including newlines, tabs) with a single space
        $itemTitle = preg_replace('/\s+/', ' ', $itemTitle);


        $itemAddress = $item['item_address'] ?? '';

        // 1. Trim leading/trailing whitespace
        $itemAddress = trim($itemAddress);

        // 2. Remove unwanted special characters (keep letters, numbers, commas, dots, slashes, hyphens, and spaces)
        $itemAddress = preg_replace('/[^A-Za-z0-9\s,.\-\/]/', '', $itemAddress);

        // 3. Replace multiple whitespace or newlines with a single space
        $itemAddress = preg_replace('/\s+/', ' ', $itemAddress);

        // Assign back
        $item['item_address'] = $itemAddress;


        $title = trim($itemTitle);
        $address = trim($item['item_address']);
        $phone = trim($item['item_phone']);

        // Check for existing record
        $existingPage = DB::table('pages')
            ->where('title', $title)
            ->where('address', $address)
            ->where('item_phone', $phone)
            ->first();

        
        if (!$existingPage && !empty($categoryIdsString) && !empty($country_id) && !empty($state_id) && !empty($city_id) && !empty($area_id)) {
        $page = new Page();
        $page->user_id = 1;
        $page->title = $itemTitle;
        $page->item_slug = str_slug($itemTitle);
        $page->address = $item['item_address'];
        $page->state_id =$state_id;
        $page->city_id =$city_id;
        $page->area_id =$area_id;
        $page->pincode ="";
        $page->category_id = $categoryIdsString;

        $page->item_type = $item['item_type'];
        $page->item_status = 2;
        $page->item_featured = $item['item_featured'];
        $page->item_featured_by_admin = 0;
        $page->item_website = $item['item_website'];
        $page->item_email = $item['item_email'];
        $page->item_whatsapp = $item['item_whatsapp'];
        $page->item_phone = $item['item_phone'];
        $page->item_lat = $item['item_lat'];
        $page->item_lng =$item['item_lng'];
        $page->item_social_facebook =$item['item_social_facebook'];
        $page->item_social_twitter = $item['item_social_twitter'];
        $page->item_social_linkedin = $item['item_social_linkedin'];
        $page->item_youtube_id = $item['item_youtube_id'];
        $page->description = $item['item_description'];


        $page->product_categories_ids = "";
        
        $page->why_visit_us = "";
        $page->our_story = "";
        $page->year_of_establishment = "";
        $page->service_offeres_areas_ids = "";



        $page->country_id = $country_id;
        $page->db_primary_id= $item['id'];
       
        // $page->service_offered_state = $service_state_ids;
        // $page->service_offered_city = $service_city_ids;

        // $page->policy = $request->policy;

        // if($request->Proof_of_Ownership && !empty($request->Proof_of_Ownership)){
        //     $page->ownership_document = $proof_of_ownership_file_name;
        // }
        // if ($request->logo && !empty($request->logo)) {
        //     $page->logo = $logo_file_name;
        // }
        // if ($request->coverphoto && !empty($request->coverphoto)) {
        //     $page->coverphoto = $coverphoto_file_name;
        // }
        $done = $page->save();
        if($done){
            
            if($done){

            // Convert string back to array
            $categoryIds = explode(',', $categoryIdsString);

            foreach ($categoryIds as $category_id) {
                // Now you can use $category_id in your logic
                //echo "Processing category ID: " . $category_id . "<br>";

                // Your logic here, like:
                $category_count = DB::table('page_category')
                    ->where('category_id', $category_id)
                    ->where('page_id', $page->id)
                    ->count();

                if ($category_count == 0) {
                    $data = [
                        'category_id' => $category_id,
                        'page_id' => $page->id
                    ];

                    $row = DB::table('page_category')->insertGetId($data);
                }
            }

           
           $slug_count=DB::table('pages')->select('pages.id')
            ->where('pages.item_slug',str_slug($itemTitle))->count();;
    
            if($slug_count>1){
    
                DB::table('pages')->where('id', $page->id)
                ->update(array('item_slug' =>DB::raw('concat("'.str_slug($itemTitle).'",'.'-'.$page->id.')')));
            }

            }
        }
        }
    }
    }
}

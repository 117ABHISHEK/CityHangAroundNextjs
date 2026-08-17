<?php
// import facade 
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

// Global Settings
if (!function_exists('get_image')) {
    function get_image($url = null)
    {
        if(is_file(public_path($url)) && file_exists(public_path($url))){
            return asset($url);
        }elseif($url != null){
            $url_arr = explode('/', $url);
            $file_name = $url_arr[count($url_arr)-1];
            if(empty($file_name)){
                $url = $url.'default/default.png';
            }else{
                $url = str_replace($file_name,'default/default.png',$url);
            }
            return asset($url);
        }else{
            return asset($url);
        }
    }
}
// Global Settings
if (!function_exists('remove_file')) {
    function remove_file($url = null)
    {
        $url = $url = str_replace('optimized/','',$url);
        $url_arr = explode('/', $url);
        $file_name = $url_arr[count($url_arr)-1];

        if(is_file($url) && file_exists($url) && !empty($file_name)){
            unlink($url);

            $url = str_replace($file_name,'optimized/'.$file_name,$url);
            if(is_file($url) && file_exists($url)){
                unlink($url);
            }
        }
    }
}

//All common helper functions
if (! function_exists('get_user_image')) {
    function get_user_image($file_name_or_user_id = "", $optimized = "") {

        if($file_name_or_user_id == ''){
            $file_name_or_user_id = 'default.png' ;
        }
        if(is_numeric($file_name_or_user_id)){
            $user_id = $file_name_or_user_id;
            $file_name = Cache::remember("user_photo_{$user_id}", 86400, function () use ($user_id) {
                return DB::table('users')->where('id', $user_id)->value('photo');
            });
        }else{
            $file_name = $file_name_or_user_id;
        }

        if(empty($file_name)){
            $file_name = 'default.png';
        }

        //this file comes from another online link as like amazon s3 server
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }

        if($optimized != ""){
            return asset('storage/userimage/'.$optimized.'/'.$file_name);
        }

        return asset('storage/userimage/'.$file_name);
    }
}

if (! function_exists('get_cover_photo')) {
    function get_cover_photo($file_name_or_user_id = '', $optimized = "") {

        if($file_name_or_user_id == ''){
            $file_name_or_user_id = Auth()->check() ? Auth()->user()->photo : 'default.jpg';
        }
        if(is_numeric($file_name_or_user_id)){
            $user_id = $file_name_or_user_id;
            $file_name = Cache::remember("user_cover_{$user_id}", 86400, function () use ($user_id) {
                return DB::table('users')->where('id', $user_id)->value('cover_photo');
            });
        }else{
            $file_name = $file_name_or_user_id;
        }

        if(empty($file_name)){
            $file_name = 'default.jpg';
        }

        //this file comes from another online link as like amazon s3 server
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }

        if($optimized != ""){
            return asset('storage/cover_photo/'.$optimized.'/'.$file_name);
        }

        return asset('storage/cover_photo/'.$file_name);
    }
}



if (! function_exists('get_album_thumbnail')) {
    function get_album_thumbnail($id = '', $optimized = "") {
        $optimized = $optimized ? $optimized.'/' : '';
        $file_name = DB::table('albums')->where('id', $id)->value('thumbnail');

        //this file comes from another online link as like amazon s3 server
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }

        if(!empty($file_name)){
            return asset('storage/thumbnails/album/'.$optimized.$file_name);
        }

        $file_name = DB::table('media_files')->where('album_id', $id)->orderBy('id', 'DESC')->value('file_name');
        if(!empty($file_name)){
            return asset('storage/post/images/'.$optimized.$file_name);
        }else{
            return asset('storage/thumbnails/album/default.png');
        }
    }
}

if (! function_exists('get_post_image')) {
    function get_post_image($file_name = '', $optimized = "") {
        //this file comes from another online link as like amazon s3 server
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }

        if($optimized != ""){
            return asset('storage/post/images/'.$optimized.'/'.$file_name);
        }

        return asset('storage/post/images/'.$file_name);
    }
}

if (! function_exists('get_post_video')) {
    function get_post_video($file_name = '', $optimized = "") {
        //this file comes from another online link as like amazon s3 server
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }

        if($optimized != ""){
            return asset('storage/post/videos/'.$optimized.'/'.$file_name);
        }

        return asset('storage/post/videos/'.$file_name);
    }
}

if (! function_exists('get_video_url')) {
    function get_video_url($file_name = '') {
        //this file comes from another online link as like amazon s3 server
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }

        return asset('storage/videos/'.$file_name);
    }
}



if ( ! function_exists('get_all_language'))
{
    function get_all_language(){
        return DB::table('languages')->select('name')->distinct()->get();
    }
}



if ( ! function_exists('get_phrase'))
{
    function get_phrase($phrase = '', $value_replace = array()) {
        static $translations = [];
        
        if(Session('active_language')){
            $active_language = Session('active_language');
        }else{
            static $system_lang = null;
            if ($system_lang === null) {
                $system_lang = get_settings('system_language');
            }
            $active_language = $system_lang;
            Session(['active_language' => $active_language]);
        }

        if (!isset($translations[$active_language])) {
            $translations[$active_language] = Cache::remember("translations_{$active_language}", 3600, function() use ($active_language) {
                return DB::table('languages')->where('name', $active_language)->pluck('translated', 'phrase')->toArray();
            });
        }

        if (array_key_exists($phrase, $translations[$active_language])) {
            $tValue = $translations[$active_language][$phrase];
        } else {
            // Phrase not found — return as-is to avoid slow DB insert loops on every request.
            // Missing phrases will be auto-inserted by a background job or admin trigger.
            $tValue = $phrase;
            $translations[$active_language][$phrase] = $tValue;
        }

        if(count($value_replace) > 0){
            $translated_value_arr = explode('____', $tValue);
            $tValue = '';
            foreach($translated_value_arr as $key => $value){

                if(array_key_exists($key,$value_replace)){
                    $tValue .= $value.$value_replace[$key];
                }else{
                    $tValue .= $value;
                }
            }
        }

        return $tValue;
    }
}



if (! function_exists('script_checker')) {
    function script_checker($string = '', $convert_string = true) {

        if($convert_string){
            return nl2br(htmlspecialchars(strip_tags($string)));
        }else{
            return $string;
        }

    }
}

if (! function_exists('is_image')) {
    function is_image($file_name = '') {
        if(empty($file_name))
            return false;

        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        if($file_extension == 'jpg' || $file_extension == 'png' || $file_extension == 'jpeg' || $file_extension == 'gif'){
            return true;
        }else{
            return false;
        }
    }
}


if (!function_exists('date_formatter')) {
    function date_formatter($strtotime = "", $format = "")
    {
        if($strtotime && !is_numeric($strtotime)){
            $strtotime = strtotime($strtotime);
        }elseif(!$strtotime){
            $strtotime = time();
        }

        if ($format == "") {
            return date('d', $strtotime) . ' ' . date('M', $strtotime) . ' ' . date('Y', $strtotime);
        }

        if ($format == 1) {
            return date('D', $strtotime) . ', ' . date('d', $strtotime) . ' ' . date('M', $strtotime) . ' ' . date('Y', $strtotime);
        }

        if($format == 2){
            $time_difference = time() - $strtotime;
            if( $time_difference <= 10 ) { return get_phrase('Just now'); }
            //864000 = 10 days
            if($time_difference > 864000){ return date_formatter($strtotime, 3); }

            $condition = array(
                12 * 30 * 24 * 60 * 60  => get_phrase('year'),
                30 * 24 * 60 * 60       =>  get_phrase('month'),
                24 * 60 * 60            =>  get_phrase('day'),
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
            );

            foreach( $condition as $secs => $str ){
                $d = $time_difference / $secs;
                if( $d >= 1 ){
                    $t = round( $d );
                    return $t .' '. $str . ( $t > 1 ? 's' : '' ) .' '. get_phrase('ago');
                }
            }
        }

        if ($format == 3) {
            $date = date('d', $strtotime);
            $date .= ' '. date('M', $strtotime);

            if(date('Y', $strtotime) != date('Y', time())){
                $date .= date(' Y', $strtotime);
            }

            $date .= ' '.get_phrase('at').' ';
            $date .= date('h:i a', $strtotime);
            return $date;
        }

        if ($format == 4) {
            return date('d', $strtotime) . ' ' . date('M', $strtotime) . ' ' . date('Y', $strtotime). ', ' . date('h:i:s A', $strtotime);
        }
    }
}

if (!function_exists('currency')) {
    function currency($price = "")
    {
        return $price.'$';
    }
}

if (!function_exists('slugify')) {
    function slugify($string)
    {
        $string = preg_replace('~[^\\pL\d]+~u', '-', $string);
        $string = trim($string, '-');
        return strtolower($string);
    }
}

if (!function_exists('get_video_extension')) {
    function get_video_extension($url)
    {
        if (strpos($url, '.mp4') > 0) {
            return 'mp4';
        } elseif (strpos($url, '.webm') > 0) {
            return 'webm';
        } else {
            return 'unknown';
        }
    }
}

if (!function_exists('ellipsis')) {
    function ellipsis($long_string, $max_character = 30)
    {
        $long_string = strip_tags($long_string);
        $short_string = strlen($long_string) > $max_character ? mb_substr($long_string, 0, $max_character) . "..." : $long_string;
        return $short_string;
    }
}



// RANDOM NUMBER GENERATOR FOR ELSEWHERE
if (!function_exists('random')) {
    function random($length_of_string, $lowercase = false)
    {
        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shufle the $str_result and returns substring
        // of specified length
        $randVal = substr(str_shuffle($str_result), 0, $length_of_string);
        if($lowercase){
            $randVal = strtolower($randVal);
        }
        return $randVal;
    }
}

if (! function_exists('get_settings')) {
    function get_settings($type = "", $return_type = "") {
        static $settingsCache = [];
        
        if (!isset($settingsCache[$type])) {
            // Cache all settings for 1 hour, load entire table once
            static $allSettings = null;
            if ($allSettings === null) {
                $allSettings = Cache::remember('all_settings_v1', 3600, function() {
                    return DB::table('settings')->pluck('description', 'type')->toArray();
                });
            }
            $settingsCache[$type] = $allSettings[$type] ?? null;
        }
        
        $value = $settingsCache[$type];
        if ($return_type === true || $return_type === 'decode') {
            return json_decode($value, true);
        } elseif ($return_type == "object") {
            return json_decode($value);
        } else {
            return $value;
        }
    }
}

// folder check and create
if (! function_exists('uploadTo')) {
    function uploadTo($folderpath = "") {
        $path = public_path('storage/'.$folderpath);
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            return $path.'/';
    }
}



// file remove on delete and update 

if (! function_exists('removeFile')) {
    function removeFile($foldername = "",$imagename = "") {
        if (File::exists(public_path('public/storage/'.$foldername.'/coverphoto/'.$imagename))) {
            File::delete(public_path('public/storage/'.$foldername.'/coverphoto/'.$imagename));
        }
        if(File::exists(public_path('public/storage/'.$foldername.'/thumbnail/'.$imagename))){
            File::delete(public_path('public/storage/'.$foldername.'/thumbnail/'.$imagename));
        }
    }
}


// image view in blade 

// file remove on delete and update 

// just pass {folder name},{file name}and {image type} then feel the function 

if (! function_exists('viewImage')) {
    function viewImage($foldername = "",$imagename = "",$imagetype = "") {
        if (!empty($foldername)&&!empty($imagename)&&!empty($imagetype)){
            return asset('storage/'.$foldername.'/'.$imagetype.'/'.$imagename);
        }else{
           return asset('storage/'.$foldername.'/'.$imagetype.'/default/default.jpg');
        }
    }
}


// associative array sorting desending 

function aasort (&$array, $key) {
    $sorter = array();
    $ret = array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii] = $va[$key];
    }
    arsort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii] = $array[$ii];
    }
    $array = $ret;
}


// get marketplace product image
if (! function_exists('get_product_image')) {
    function get_product_image($file_name = "", $foldername = "") {

        // if image is from external URL (S3/CDN)
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }

        // Build paths dynamically
        $imagePath   = public_path("storage/marketplace/$foldername/$file_name");

        // Check if image exists – return image else return default
        if(!empty($file_name) && file_exists($imagePath)){
            return asset("storage/marketplace/$foldername/$file_name");
        } else {
            $defaultPng = "storage/marketplace/$foldername/default/default.png";
            $defaultJpg = "storage/marketplace/$foldername/default/default.jpg";
            if (file_exists(public_path($defaultPng))) {
                return asset($defaultPng);
            } elseif (file_exists(public_path($defaultJpg))) {
                return asset($defaultJpg);
            } else {
                // Global fallback for marketplace images
                return asset("storage/marketplace/thumbnail/default/default.jpg");
            }
        }
    }
}

//get sponsor post  image
if (! function_exists('get_sponsor_image')) {
    function get_sponsor_image($file_name = "", $foldername = "") {
        //this file comes from another online link as like amazon s3 server
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }

        $foldername = $foldername.'/';

        if(!empty($file_name)){
            if(File::exists('public/storage/sponsor/'.$foldername.$file_name)){
                return asset('storage/sponsor/'.$foldername.$file_name);
            }else{
                return asset('storage/sponsor/'.$foldername.'default/default.jpg');
            }
        }else{
            return asset('storage/sponsor/'.$foldername.'default/default.jpg');
        }
    }
}

//get blog product image
if (! function_exists('get_blog_image')) {
    function get_blog_image($file_name = "", $foldername = "") {
        //this file comes from another online link as like amazon s3 server
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }

        $foldername = $foldername.'/';

        if(!empty($file_name)){
            if(File::exists('public/storage/blog/'.$foldername.$file_name)){
                return asset('storage/blog/'.$foldername.$file_name);
            }else{
                return asset('storage/blog/'.$foldername.'default/default.jpg');
            }
        }else{
            return asset('storage/blog/'.$foldername.'default/default.jpg');
        }
    }
}


//get page logo
if (! function_exists('get_page_logo')) {
    function get_page_logo($file_name = "", $foldername = "", $category_sources = null) {
        // 1. Already a full URL (S3, CDN, etc.)
        if (!empty($file_name) && preg_match('/^https?:\/\//i', $file_name)) {
            return $file_name;
        }

        $foldername = $foldername . '/';

        // 2. Uploaded business logo exists
        if (!empty($file_name) && File::exists(public_path('storage/pages/' . $foldername . $file_name))) {
            return asset('storage/pages/' . $foldername . $file_name);
        }

        // 3. Category icon fallback — try EVERY category in order, return first with an icon
        if (!empty($category_sources)) {
            $categories = [];

            if ($category_sources instanceof \Illuminate\Support\Collection) {
                $categories = $category_sources->all();
            } elseif (is_iterable($category_sources) && !is_object($category_sources)) {
                $categories = is_array($category_sources) ? $category_sources : iterator_to_array($category_sources);
            } elseif (is_object($category_sources)) {
                $categories = [$category_sources];
            }

            foreach ($categories as $category) {
                if (!$category || empty($category->category_icon)) {
                    continue;
                }

                $iconUrl = normalize_media_asset_url($category->category_icon, [
                    'storage/categories/icons',
                    'storage/pagecategories/icons',
                ]);
                if ($iconUrl) {
                    return $iconUrl;
                }

                if (preg_match('/^https?:\/\//i', $category->category_icon)) {
                    return $category->category_icon;
                }
            }
        }

        // 4. Default placeholder
        return asset('storage/pages/' . $foldername . 'default.png');
    }
}




// FIX APPLIED HERE:
// Replaced the broken get_page_cover_photo with a version that correctly
// handles the 'coverphoto' path structure for your working URL.
if (! function_exists('get_page_cover_photo')) {
    function get_page_cover_photo($file_name = "", $foldername = "coverphoto") {

        // 1. Check if it is already a full link (S3, etc.)
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }

        // 2. Ensure foldername defaults to 'coverphoto' if empty
        if(empty($foldername)) {
            $foldername = 'coverphoto';
        }

        // 3. Construct URL: storage/pages/coverphoto/filename.jpg
        if(!empty($file_name) && File::exists(public_path('storage/pages/' . $foldername . '/' . $file_name))){
            return asset('storage/pages/' . $foldername . '/' . $file_name);
        }

        // 4. Fallback — use the foldername-specific default
        return asset('storage/pages/' . $foldername . '/default.png');
    }

}

if (! function_exists('normalize_media_asset_url')) {
    function normalize_media_asset_url($value = "", array $candidateDirectories = []) {
        if (empty($value) || !is_string($value)) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $value)) {
            return $value;
        }

        $normalized = ltrim(str_replace('\\', '/', $value), '/');
        if (str_starts_with($normalized, 'public/')) {
            $normalized = substr($normalized, 7);
        }

        if (!empty($normalized) && File::exists(public_path($normalized))) {
            return asset($normalized);
        }

        foreach ($candidateDirectories as $directory) {
            $path = trim(str_replace('\\', '/', $directory), '/') . '/' . ltrim($value, '/');
            if (File::exists(public_path($path))) {
                return asset($path);
            }
        }

        return null;
    }
}

if (! function_exists('find_matching_page_category_media')) {
    function find_matching_page_category_media($slug = null, $name = null) {
        static $cache = [];

        $cacheKey = strtolower(trim(($slug ?? '') . '|' . ($name ?? '')));
        if (array_key_exists($cacheKey, $cache)) {
            return $cache[$cacheKey];
        }

        $query = DB::table('pagecategories')
            ->select('category_slug', 'category_name', 'category_banner', 'category_icon');

        if (!empty($slug)) {
            $query->where('category_slug', $slug);
        } elseif (!empty($name)) {
            $query->where('category_name', $name);
        } else {
            return $cache[$cacheKey] = null;
        }

        return $cache[$cacheKey] = $query->first();
    }
}

if (! function_exists('resolve_category_media_url')) {
    function resolve_category_media_url($category = null) {
        if (!$category) {
            return null;
        }

        $bannerUrl = normalize_media_asset_url($category->category_banner ?? '', [
            'storage/pagecategories/banners',
            'storage/categories/banners',
        ]);
        if ($bannerUrl) {
            return $bannerUrl;
        }

        $iconValue = $category->category_icon ?? $category->icon ?? '';
        if (is_string($iconValue) && preg_match('/^https?:\/\//i', $iconValue)) {
            return $iconValue;
        }

        return null;
    }
}

if (! function_exists('resolve_page_category_fallback_banner')) {
    function resolve_page_category_fallback_banner($slug = null, $name = null) {
        $matchedCategory = find_matching_page_category_media($slug, $name);

        return resolve_category_media_url($matchedCategory);
    }
}

if (! function_exists('resolve_banner_from_category_sources')) {
    function resolve_banner_from_category_sources(array $sources = []) {
        foreach ($sources as $source) {
            if (!$source) {
                continue;
            }

            // Normalize to a flat list of category objects
            $categories = [];

            if ($source instanceof \Illuminate\Support\Collection) {
                $categories = $source->all();
            } elseif (is_iterable($source) && !is_object($source)) {
                $categories = is_array($source) ? $source : iterator_to_array($source);
            } elseif (is_object($source)) {
                $categories = [$source];
            }

            foreach ($categories as $category) {
                if (!$category) {
                    continue;
                }

                $directUrl = resolve_category_media_url($category);
                if ($directUrl) {
                    return $directUrl;
                }

                $matchedUrl = resolve_page_category_fallback_banner(
                    $category->category_slug ?? null,
                    $category->category_name ?? null
                );
                if ($matchedUrl) {
                    return $matchedUrl;
                }
            }
        }

        return null;
    }
}

if (! function_exists('get_page_banner_image')) {
    function get_page_banner_image($page, $foldername = 'coverphoto') {
        $fileName = is_object($page) ? ($page->coverphoto ?? '') : (string) $page;
        $ownBanner = normalize_media_asset_url($fileName, ["storage/pages/$foldername"]);
        if ($ownBanner) {
            return $ownBanner;
        }

        if (is_object($page)) {
            $fallback = resolve_banner_from_category_sources([
                $page->categories ?? null,
                $page->pageCategories ?? null,
                $page->pagecategories ?? null,
                $page->category ?? null,
                isset($page->category_slug) || isset($page->category_name) ? (object) [
                    'category_slug' => $page->category_slug ?? null,
                    'category_name' => $page->category_name ?? null,
                ] : null,
            ]);

            if ($fallback) {
                return $fallback;
            }
        }

        return get_page_cover_photo('', $foldername);
    }
}

if (! function_exists('get_blog_banner_image')) {
    function get_blog_banner_image($blog, $foldername = 'thumbnail') {
        $fileName = is_object($blog) ? ($blog->thumbnail ?? $blog->logo ?? '') : (string) $blog;
        $ownBanner = normalize_media_asset_url($fileName, ["storage/blog/$foldername"]);
        if ($ownBanner) {
            return $ownBanner;
        }

        if (is_object($blog)) {
            $fallback = resolve_banner_from_category_sources([
                $blog->categories ?? null,
                $blog->category ?? null,
                $blog->cagtegory ?? null,
                isset($blog->cat_slug) || isset($blog->cat_name) ? (object) [
                    'category_slug' => $blog->cat_slug ?? null,
                    'category_name' => $blog->cat_name ?? null,
                ] : null,
                isset($blog->category_slug) || isset($blog->category_name) ? (object) [
                    'category_slug' => $blog->category_slug ?? null,
                    'category_name' => $blog->category_name ?? null,
                ] : null,
            ]);

            if ($fallback) {
                return $fallback;
            }
        }

        return get_blog_image('', $foldername);
    }
}

if (! function_exists('get_event_banner_image')) {
    function get_event_banner_image($event, $imagetype = 'thumbnail') {
        $fileName = is_object($event) ? ($event->banner ?? '') : (string) $event;
        $ownBanner = normalize_media_asset_url($fileName, ["storage/event/$imagetype"]);
        if ($ownBanner) {
            return $ownBanner;
        }

        if (is_object($event)) {
            $fallback = resolve_banner_from_category_sources([
                $event->categories ?? null,
                $event->category ?? null,
                isset($event->category_slug) || isset($event->category_name) ? (object) [
                    'category_slug' => $event->category_slug ?? null,
                    'category_name' => $event->category_name ?? null,
                ] : null,
            ]);

            if ($fallback) {
                return $fallback;
            }
        }

        return viewImage('event', '', $imagetype);
    }
}

if (! function_exists('get_marketplace_banner_image')) {
    function get_marketplace_banner_image($product, $foldername = 'thumbnail') {
        $fileName = is_object($product) ? ($product->image ?? '') : (string) $product;
        $ownBanner = normalize_media_asset_url($fileName, ["storage/marketplace/$foldername"]);
        if ($ownBanner) {
            return $ownBanner;
        }

        if (is_object($product)) {
            $page = $product->page ?? null;

            $fallback = resolve_banner_from_category_sources([
                $page->categories ?? null,
                $page->pageCategories ?? null,
                $product->productCategories ?? null,
                $product->category ?? null,
                isset($product->page_category_slug) || isset($product->page_category_name) ? (object) [
                    'category_slug' => $product->page_category_slug ?? null,
                    'category_name' => $product->page_category_name ?? null,
                ] : null,
                isset($product->product_category_slug) || isset($product->product_category_name) ? (object) [
                    'category_slug' => $product->product_category_slug ?? null,
                    'category_name' => $product->product_category_name ?? null,
                ] : null,
            ]);

            if ($fallback) {
                return $fallback;
            }
        }

        return get_product_image('', $foldername);
    }
}


//get page logo
if (! function_exists('get_group_logo')) {
    function get_group_logo($file_name = "", $foldername = "") {
        //this file comes from another online link as like amazon s3 server
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }

        $foldername = $foldername.'/';

        if(!empty($file_name)){
            if(File::exists('public/storage/groups/'.$foldername.$file_name)){
                return asset('storage/groups/'.$foldername.$file_name);
            }else{
                return asset('storage/groups/'.$foldername.'default/default.jpg');
            }
        }else{
            return asset('storage/groups/'.$foldername.'default/default.jpg');
        }
    }

}



//get group cover photo
if (! function_exists('get_group_cover_photo')) {
    function get_group_cover_photo($file_name = "", $foldername = "") {
        //this file comes from another online link as like amazon s3 server
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }

        $foldername = $foldername.'/';

        if(!empty($file_name)){
            if(File::exists('public/storage/groups/'.$foldername.$file_name)){
                return asset('storage/groups/'.$foldername.$file_name);
            }else{
                return asset('storage/groups/'.$foldername.'default/default.jpg');
            }
        }else{
            return asset('storage/groups/'.$foldername.'default/default.jpg');
        }
    }

}



//get system dark logo
if (! function_exists('get_system_logo_favicon')) {
    function get_system_logo_favicon($file_name = "", $foldername = "") {
        //this file comes from another online link as like amazon s3 server
        if(strpos($file_name, 'https://') !== false){
            return $file_name;
        }
        
        $foldername = $foldername.'/';

        if(!empty($file_name)){
            if(File::exists('public/storage/logo/'.$foldername.$file_name)){
                return asset('storage/logo/'.$foldername.$file_name);
            }else{
                return asset('storage/logo/'.$foldername.'default/default.jpg');
            }
        }else{
            return asset('storage/logo/'.$foldername.'default/default.jpg');
        }
    }
}





// Global Settings
if (!function_exists('set_config')) {
    function set_config($key = '', $value='')
    {
        $config = json_decode(file_get_contents(base_path('config/config.json')), true);

        $config[$key] = $value;

        file_put_contents(base_path('config/config.json'), json_encode($config));
    }
}

// Remove country code from phone number
if (!function_exists('remove_country_code')) {
    function remove_country_code($phone = '', $country_code = '91')
    {
        if (empty($phone)) {
            return $phone;
        }
        
        // Remove country code if it exists at the beginning
        if (strpos($phone, $country_code) === 0 && strlen($phone) > 10) {
            return substr($phone, strlen($country_code));
        }
        
        return $phone;
    }
}

// Custom slug function that properly handles special characters
if (!function_exists('clean_slug')) {
    function clean_slug($string, $separator = '-', $maxLength = 50)
    {
        if (empty($string)) {
            return '';
        }
        
        // Decode HTML entities first (like &amp; to &)
        $string = html_entity_decode($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Replace common special characters with their word equivalents
        $replacements = [
            '&' => 'and',
            '+' => 'plus',
            '@' => 'at',
            '#' => 'hash',
            '$' => 'dollar',
            '%' => 'percent',
            '^' => 'power',
            '*' => 'star',
            '(' => '',
            ')' => '',
            '[' => '',
            ']' => '',
            '{' => '',
            '}' => '',
            '<' => '',
            '>' => '',
            '|' => '',
            '\\' => '',
            '/' => '',
            '?' => '',
            '!' => '',
            '.' => '',
            ',' => '',
            ';' => '',
            ':' => '',
            '"' => '',
            "'" => '',
            '`' => '',
            '~' => '',
            '=' => '',
        ];
        
        // Apply replacements
        $string = str_replace(array_keys($replacements), array_values($replacements), $string);
        
        // Remove any remaining special characters except letters, numbers, spaces, and hyphens
        $string = preg_replace('/[^A-Za-z0-9\s\-]/', '', $string);
        
        // Replace multiple spaces with single space
        $string = preg_replace('/\s+/', ' ', $string);
        
        // Trim spaces
        $string = trim($string);
        
        // Replace spaces with separator
        $string = str_replace(' ', $separator, $string);
        
        // Remove multiple consecutive separators
        $string = preg_replace('/' . preg_quote($separator, '/') . '+/', $separator, $string);
        
        // Trim separators from start and end
        $string = trim($string, $separator);
        
        // Convert to lowercase
        $string = strtolower($string);
        
        // Limit length to prevent database issues
        if (strlen($string) > $maxLength) {
            $string = substr($string, 0, $maxLength);
            // Remove trailing separator if it exists
            $string = rtrim($string, $separator);
        }
        
        return $string;
    }
} 
// END OF FILE

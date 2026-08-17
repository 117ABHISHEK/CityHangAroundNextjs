<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image, DB, Session;

class FileUploader extends Model
{
    use HasFactory;

    public static function upload($uploaded_file, $upload_to, $width = null, $height = null, $optimized_width = 250, $optimized_height = null){
        // EX: $upload_file = this is the uploaded temp file => $request->video_feild_name
        //     $upload_to = "public/storage/video" OR "public/storage/video/Sj8Ro5Gksde3T.mp4" OR "sdsdncts7sn.png" OR empty if amazon s3 is active
        if(!$uploaded_file) return;

        $s3_keys = get_settings('amazon_s3', 'object');
        if($s3_keys->active != 1){
            if(is_dir($upload_to)){
                $file_name = time().'-'.random(30).'.'.$uploaded_file->extension();
            }else{
                $uploaded_path_arr = explode('/', $upload_to);
                $file_name = end($uploaded_path_arr);
                $upload_to = str_replace('/'.$file_name,"",$upload_to);
                if(!is_dir($upload_to)){
                    return "This path doesn't exist!";
                }
            }

            if($width == null){
                $uploaded_file->move($upload_to, $file_name);
            }else{
                $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());

                //Image optimization
                $img = $manager->read($uploaded_file->path());
                $img->scaleDown(width: $width, height: $height);
                $img->save($upload_to.'/'.$file_name);

                //Ultra Image optimization
                $optimized_path = $upload_to.'/optimized';
                if(is_dir($optimized_path)){
                    $imgOpt = $manager->read($uploaded_file->path());
                    $imgOpt->scaleDown(width: $optimized_width, height: $optimized_height);
                    $imgOpt->save($optimized_path.'/'.$file_name);
                }
            }

            return $file_name;
        } else {
            //upload to amazon s3
            ini_set('max_execution_time', '600');

            // If the uploaded file is an image, compress and resize it locally first to minimize network upload size
            if (str_starts_with($uploaded_file->getMimeType(), 'image/')) {
                $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                $img = $manager->read($uploaded_file->path());
                
                $targetWidth = $width ?? 1200; // default limit to 1200px width
                if ($targetWidth !== null || $height !== null) {
                    $img->scaleDown(width: $targetWidth, height: $height);
                }

                // Save to a temporary file with 75% quality compression
                $tempPath = tempnam(sys_get_temp_dir(), 's3img');
                $img->save($tempPath, quality: 75);

                // Upload the compressed temp file
                $s3_file_path = Storage::disk('blaze_s3')->putFile('uploads', new \Illuminate\Http\File($tempPath), 'public');

                // Delete the temp file from local system
                @unlink($tempPath);
            } else {
                // For videos and other file types, upload directly
                $s3_file_path = Storage::disk('blaze_s3')->put('uploads', $uploaded_file, 'public');
            }

            $s3_file_path = Storage::disk('blaze_s3')->url($s3_file_path);
            return $s3_file_path;
        }
    }
}

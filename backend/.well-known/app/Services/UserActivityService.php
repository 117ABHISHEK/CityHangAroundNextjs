<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\EventMaster;

class UserActivityService
{
    public function log($userId, $eventName, $contentType, $contentId, $activity_id)
{
    $event = EventMaster::where('event_name', $eventName)->first();
    if (!$event) return;

    // Allow duplicate entries for these:
    $allowDuplicateEvents = ['like', 'comment','post'];

    if (in_array($eventName, $allowDuplicateEvents)) {
        // Always insert
        DB::table('user_activity_logs')->insert([
            'user_id' => $userId,
            'event_name' => $eventName,
            'content_type' => $contentType,
            'content_id' => $contentId,
            'activity_id' => $activity_id,
            'score' => $event->score,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        // Check before insert (for view, share, etc.)
        $exists = DB::table('user_activity_logs')->where([
            'user_id' => $userId,
            'event_name' => $eventName,
            'content_type' => $contentType,
            'content_id' => $contentId,
        ])->exists();

        if (!$exists) {
            DB::table('user_activity_logs')->insert([
                'user_id' => $userId,
                'event_name' => $eventName,
                'content_type' => $contentType,
                'content_id' => $contentId,
                'activity_id' => $activity_id,
                'score' => $event->score,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

 public function deleteByvideoActivityId($activityId)
    {

        DB::table('user_activity_logs')
            ->where('activity_id', $activityId)
             ->where('event_name','video_post')
            ->delete();
    }

 public function deleteBylikeActivityId($activityId)
    {

        DB::table('user_activity_logs')
            ->where('activity_id', $activityId)
             ->where('event_name','like')
            ->delete();
    }


    public function deleteBypagelikeActivityId($activityId)
    {

        DB::table('user_activity_logs')
            ->where('activity_id', $activityId)
             ->where('event_name','follow')
              ->where('content_type','page')
            ->delete();
    }

      public function deleteBygrouplikeActivityId($activityId)
    {

        DB::table('user_activity_logs')
            ->where('activity_id', $activityId)
             ->where('event_name','follow')
              ->where('content_type','group')
            ->delete();
    }

    public function deleteByActivityId($activityId)
    {

        DB::table('user_activity_logs')
            ->where('activity_id', $activityId)
            ->delete();
    }

    public function deleteLogsByContent($contentId, $contentType = null)
    {
        $query_post = DB::table('user_activity_logs')->where('content_id', $contentId)
        ->where('content_type', 'post')->delete();

         $query_post = DB::table('user_activity_logs')->where('content_id', $contentId)
        ->where('event_name', 'comment')->delete();

        $query = DB::table('user_activity_logs')->where('activity_id', $contentId)
        ->where('content_type', $contentType)->delete();
    }


}

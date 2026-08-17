<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class TrackingController extends Controller
{
    public function track()
    {
        $campaignId = request('campaign_id');
        $subscriberId = request('subscriber_id');

        DB::table('email_opens')->insert([
            'campaign_id' => $campaignId,
            'subscriber_id' => $subscriberId,
            'opened_at' => now(),
        ]);

        $pixel = base64_decode('R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==');
        return response($pixel, 200)->header('Content-Type', 'image/gif');
    }
}

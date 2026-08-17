<?php
namespace App\Jobs;

use App\Models\Campaign;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\CampaignMail;

class SendCampaignEmails implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function handle()
    {
        $subscribers = $this->campaign->mailingList->subscribers;

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->queue(new CampaignMail($this->campaign, $subscriber));
        }
    }
}

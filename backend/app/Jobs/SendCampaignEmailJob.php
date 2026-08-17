<?php
namespace App\Jobs;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendCampaignEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaign;
    protected $email;

    public function __construct(Campaign $campaign, string $email)
    {
        $this->campaign = $campaign;
        $this->email = $email;
    }

    public function handle()
    {
        $html = $this->campaign->template->html_content;

        $trackingUrl = route('track.email', [
            'campaign_id' => $this->campaign->id,
            'email' => $this->email,
        ]);

        $html .= '<img src="' . $trackingUrl . '" width="1" height="1" style="display:none;" />';

        Mail::send([], [], function ($message) use ($html) {
            $message->from('info@cityhangaround.com', 'City Hang Around')
                    ->to($this->email)
                    ->subject($this->campaign->name)
                    ->html($html);
        });
    }
}

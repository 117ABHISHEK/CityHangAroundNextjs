<?php
namespace App\Jobs;

use App\Mail\CampaignEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendCampaignEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $html;

    public function __construct($email, $html)
    {
        $this->email = $email;
        $this->html = $html;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new CampaignEmail($this->html));
    }
}

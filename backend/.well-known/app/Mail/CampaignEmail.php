<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $htmlContent;

    public function __construct($htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }

    public function build()
    {
        return $this->subject('Your Campaign')
                    ->html($this->htmlContent);
    }
}

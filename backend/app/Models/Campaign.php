<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'campaign_template_id',
        'mailing_list_id',
        'status',
        'scheduled_at',
    ];

    // Campaign belongs to a template
    public function template()
    {
        return $this->belongsTo(CampaignTemplate::class, 'campaign_template_id');
    }

    // Campaign belongs to a mailing list
    public function mailingList()
    {
        return $this->belongsTo(MailingList::class, 'mailing_list_id');
    }
}

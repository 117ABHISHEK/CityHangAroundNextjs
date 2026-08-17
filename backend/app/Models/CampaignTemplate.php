<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'html_content',
        'created_by',
    ];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}

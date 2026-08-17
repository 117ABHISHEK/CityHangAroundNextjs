<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerLeadStage extends Model {
    use HasFactory;

    protected $fillable = ['enquiry_id', 'user_id', 'lead_stage_id', 'comment'];

    public function enquiry() {
        return $this->belongsTo(Enquiry::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function leadStage() {
        return $this->belongsTo(EnquiryLeadStage::class);
    }
}

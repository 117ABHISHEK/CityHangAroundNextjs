<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryLeadStage extends Model
{
    use HasFactory;

    protected $fillable = ['stage_name', 'description', 'for_role'];
}

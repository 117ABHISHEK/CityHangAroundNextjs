<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAll extends Model
{
    use HasFactory;

    protected $table = 'reports_all'; // Explicitly set the table name

    protected $fillable = [
        'type',
        'entity_id',
        'user_id', // Nullable for anonymous reports
        'full_name',
        'email',
        'phone',
        'reason',
        'additional_comments',
        'proof_attachment',
        'response_required'
    ];
}

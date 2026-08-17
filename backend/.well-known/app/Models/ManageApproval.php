<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageApproval extends Model
{
    protected $table = 'manage_approvale';

    protected $fillable = [
        'title',
        'status',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true; // Laravel automatically manages created_at and updated_at
}

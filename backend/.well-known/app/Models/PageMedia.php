<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageMedia extends Model
{
    use HasFactory;

    protected $table = 'page_media'; // Define table name
    protected $fillable = ['page_id', 'file', 'file_type', 'createdAt'];
}

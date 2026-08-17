<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    // Make sure to add the `attribute_id` and `value` to the fillable property
    protected $fillable = ['attribute_id', 'value'];

    // Define the inverse relationship with the Attribute model
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}

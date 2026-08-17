<?php
// app/Models/MailingListPage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MailingListPage extends Pivot
{
    // Optionally, specify table name (not required if the table name follows Laravel convention)
    protected $table = 'mailing_list_page';

    // The primary key for the pivot table
    protected $primaryKey = 'id';

    // Fillable attributes for the pivot table (optional)
    protected $fillable = [
        'mailing_list_id',
        'page_id',
    ];

    // Optionally, you can define any additional methods or logic
}

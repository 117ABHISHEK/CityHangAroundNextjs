namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ContentMaster extends Model
{
    use HasFactory;
    use Searchable;

    protected $table = 'content_master';

    protected $fillable = [
        'source_type',
        'source_id',
        'title',
        'slug',
        'category_id',
        'category_name',
        'parent_category_id',
        'parent_category_name',
        'location',
        'city_id',
        'area_id',
        'state_id',
        'price',
        'product_status',
        'product_featured',
        'total_messages',
        'total_conversations',
        'publication_status',
        'event_date',
        'event_time',
        'description',
        'user_id',
        'event_status',
        'total_count',
        'rank_order',
        'status',
    ];
}
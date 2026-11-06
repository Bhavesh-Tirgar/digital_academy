<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'cover_image',
        'file_path',
        'pages',
        'category_id',
        'downloads_count',
        'is_free',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'name'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'subject_id');
    }
}

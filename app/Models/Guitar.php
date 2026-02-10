<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guitar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'image_path', 'short_desc', 'detail_desc',
        'base_price', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'base_price' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($guitar) {
            $guitar->slug = $guitar->createUniqueSlug($guitar->name);
        });

        static::updating(function ($guitar) {
            if ($guitar->isDirty('name') && !$guitar->isDirty('slug')) {
                $guitar->slug = $guitar->createUniqueSlug($guitar->name);
            }
        });
    }

    protected function createUniqueSlug(string $name): string
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $original = $slug;
        $count = 2;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

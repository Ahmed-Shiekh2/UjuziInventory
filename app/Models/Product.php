<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'category',
        'image_path',
        'price',
        'quantity',
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getImageUrlAttribute()
    {
        if (! $this->image_path) {
            return null;
        }

        return Str::startsWith($this->image_path, 'images/')
            ? asset($this->image_path)
            : Storage::url($this->image_path);
    }
}

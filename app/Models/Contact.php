<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Contact extends Model
{
    use CrudTrait; // enables Backpack CRUD features
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'image',
    ];

    // Accessor to get full URL of image (for non-Backpack contexts)
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        return Storage::disk('public')->url($this->image);
    }

    // Optional cleanup on delete
    protected static function booted(): void
    {
        static::deleting(function (self $model) {
            /* Uncomment to remove stored file when deleting contact
            if ($model->image && Storage::disk('public')->exists($model->image)) {
                Storage::disk('public')->delete($model->image);
            }
            */
        });
    }

    /**
     * Mutator to ensure image is stored on the public disk under contacts/ and we only persist relative path.
     * Handles case where a temporary absolute path like C:\xampp\tmp\phpXXXX.tmp was saved previously.
     */
    public function setImageAttribute($value): void
    {
        if (!$value) {
            $this->attributes['image'] = null;
            return;
        }

        // If value already looks like a relative path inside contacts/, keep it.
        if (preg_match('#^contacts/.+#', $value)) {
            $this->attributes['image'] = $value;
            return;
        }

        // If it's an absolute temp path that still exists, move it manually
        if (is_string($value) && preg_match('#[A-Za-z]:\\\\?#', $value) && is_file($value)) {
            $contents = file_get_contents($value);
            $filename = 'contacts/'.uniqid('img_').'.'.pathinfo($value, PATHINFO_EXTENSION) ?: 'jpg';
            Storage::disk('public')->put($filename, $contents);
            $this->attributes['image'] = $filename;
            return;
        }

        // Fallback: store raw string (could be URL) – Backpack image column will try to display it.
        $this->attributes['image'] = $value;
    }
}

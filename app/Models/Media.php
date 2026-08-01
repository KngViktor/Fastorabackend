<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'disk',
        'path',
        'filename',
        'mime_type',
        'size',
        'alt',
        'width',
        'height',
    ];

    /** Public URL for this file on its disk. */
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    protected static function booted(): void
    {
        // The Filament form only asks for a file upload + alt text; everything
        // else (filename, mime type, size, image dimensions) is derived here
        // from the file Filament has already stored on disk by save time —
        // simpler and more reliable than trying to capture it via a form-level
        // upload callback.
        static::saving(function (Media $media) {
            if (! $media->path || ! $media->isDirty('path')) {
                return;
            }

            $disk = Storage::disk($media->disk ?: 'public');
            if (! $disk->exists($media->path)) {
                return;
            }

            $media->filename = basename($media->path);
            $media->mime_type = $disk->mimeType($media->path) ?: null;
            $media->size = $disk->size($media->path);

            // Local-disk only (e.g. "public"); cloud disks like S3 don't expose
            // a local file path, so dimensions are simply left blank there.
            if (str_starts_with((string) $media->mime_type, 'image/')) {
                try {
                    $dimensions = @getimagesize($disk->path($media->path));
                    if ($dimensions) {
                        $media->width = $dimensions[0];
                        $media->height = $dimensions[1];
                    }
                } catch (\Throwable) {
                    // Not a local disk — skip dimension extraction.
                }
            }
        });
    }
}

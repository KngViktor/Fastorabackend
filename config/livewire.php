<?php

return [

    /*
    |---------------------------------------------------------------------------
    | Temporary File Uploads
    |---------------------------------------------------------------------------
    |
    | Left unset ('disk' => null), Livewire's immediate drag-and-drop upload
    | writes to whatever filesystems.default resolves to: the 'local' disk,
    | rooted at storage/app/private, which nothing else in this app touches.
    |
    | Every other upload here targets the 'public' disk (see HasMediaSelect,
    | MediaForm), which is exercised constantly and therefore known good.
    | Pinning the temporary upload to the same disk means uploads no longer
    | depend on a directory that is never otherwise written to.
    |
    | Housekeeping, not a bug fix. This was originally written to explain a
    | failing upload, on the guess that the private directory was not
    | writable. That guess was wrong: the failure was the host's ModSecurity
    | rejecting filenames containing a quote, before PHP ever ran. See
    | resources/views/filament/sanitize-upload-filenames.blade.php.
    |
    */

    'temporary_file_upload' => [
        'disk' => 'public',
        'rules' => null,
        'directory' => 'livewire-tmp',
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5,
        'cleanup' => true,
    ],

];

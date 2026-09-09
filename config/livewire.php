<?php

return [

    /*
    |---------------------------------------------------------------------------
    | Temporary File Uploads
    |---------------------------------------------------------------------------
    |
    | Left unset ('disk' => null), Livewire's immediate drag-and-drop upload
    | writes to whatever filesystems.default resolves to — the 'local' disk,
    | rooted at storage/app/private. On this host that directory isn't
    | reliably writable by the web server process, so every upload failed at
    | that first step with a generic "Error during upload" before the form
    | was ever submitted.
    |
    | Every other upload in this app already targets the 'public' disk (see
    | HasMediaSelect, MediaForm), which is confirmed writable — pointing the
    | temporary upload at the same disk removes the dependency on the
    | default disk resolving to something usable.
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

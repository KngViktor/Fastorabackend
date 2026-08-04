<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API response cache
    |--------------------------------------------------------------------------
    |
    | Caches the public read endpoints the frontend consumes. Set
    | API_CACHE_ENABLED=false to bypass the cache entirely, which is useful
    | when diagnosing whether a stale response is a caching problem or a data
    | problem.
    |
    | Which store is used comes from CACHE_STORE in config/cache.php, not from
    | here. The cache layer only uses operations that every store supports, so
    | `database` works today on shared hosting and `redis` needs nothing more
    | than the env change once a Redis instance is available.
    |
    */

    'enabled' => env('API_CACHE_ENABLED', true),

];

<?php

use App\Http\Controllers\Api\CaseStudyController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\NavController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SiteSettingsController;
use App\Http\Controllers\Api\TestimonialController;
use Illuminate\Support\Facades\Route;

// Public, read-only content API for the Next.js frontend — mirrors the shape
// of Payload's REST API closely so the frontend's data-fetching layer needed
// only its transport changed, not its rendering code (see docs/frontend
// rewiring). All endpoints only return published content.

Route::get('/site-settings', [SiteSettingsController::class, 'show']);
Route::get('/header', [NavController::class, 'header']);
Route::get('/footer', [NavController::class, 'footer']);

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{slug}', [ServiceController::class, 'show']);

Route::get('/case-studies', [CaseStudyController::class, 'index']);
Route::get('/case-studies/{slug}', [CaseStudyController::class, 'show']);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);

Route::get('/testimonials', [TestimonialController::class, 'index']);

Route::get('/pages', [PageController::class, 'index']);
Route::get('/pages/slugs', [PageController::class, 'slugs']);
Route::get('/pages/{slug}', [PageController::class, 'show']);

Route::post('/contact', [ContactController::class, 'store']);

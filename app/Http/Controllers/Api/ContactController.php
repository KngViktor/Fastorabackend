<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Honeypot — real users never fill this hidden field.
        if (filled($request->input('website'))) {
            return response()->json(['success' => true]);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'company' => ['nullable', 'string', 'max:255'],
            'serviceNeeded' => ['nullable', 'integer', 'exists:services,id'],
            'budgetRange' => ['nullable', 'in:under-1k,1k-5k,5k-15k,15k-plus,not-sure'],
            'timeline' => ['nullable', 'in:asap,1-month,1-3-months,exploring'],
            'brief' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = $validator->validated();

        Inquiry::create([
            'status' => 'new',
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'] ?? null,
            'service_needed_id' => $data['serviceNeeded'] ?? null,
            'budget_range' => $data['budgetRange'] ?? null,
            'timeline' => $data['timeline'] ?? null,
            'brief' => $data['brief'],
        ]);

        return response()->json(['success' => true]);
    }
}

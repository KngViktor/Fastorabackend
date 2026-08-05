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
            // Free text: a fixed set of bands forced people into the wrong one, and
            // 'not sure' told us nothing. Whatever they type is more useful.
            'budgetRange' => ['nullable', 'string', 'max:255'],
            'timeline' => ['nullable', 'in:asap,1-month,1-3-months,exploring'],
            'brief' => ['required', 'string'],
            // Consultation requests post to this same endpoint, so they land in
            // one inbox with one notification path and the same status workflow.
            'kind' => ['nullable', 'in:general,consultation'],
            'preferredTimes' => ['nullable', 'string', 'max:2000'],
            'timezone' => ['nullable', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = $validator->validated();

        Inquiry::create([
            'status' => 'new',
            'kind' => $data['kind'] ?? 'general',
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'] ?? null,
            'service_needed_id' => $data['serviceNeeded'] ?? null,
            'budget_range' => $data['budgetRange'] ?? null,
            'timeline' => $data['timeline'] ?? null,
            'brief' => $data['brief'],
            'preferred_times' => $data['preferredTimes'] ?? null,
            'timezone' => $data['timezone'] ?? null,
        ]);

        return response()->json(['success' => true]);
    }
}

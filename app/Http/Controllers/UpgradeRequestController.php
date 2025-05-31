<?php

namespace App\Http\Controllers;

use App\Models\UpgradeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpgradeRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // يتطلب تسجيل الدخول
    }

    /**
     * Store a new upgrade request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // فقط الخريجون يمكنهم تقديم طلب
        if ($user->type !== 'خريج') {
            return back()->with('error', 'Only graduates can request an account upgrade.');
        }

        // تحقق من وجود طلب معلق بالفعل
        $existingRequest = UpgradeRequest::where('UserID', $user->UserID)
                                          ->where('status', 'pending')
                                          ->first();
        if ($existingRequest) {
            return back()->with('info', 'You already have a pending upgrade request to ' . $existingRequest->requested_role . '.');
        }

        $validated = $request->validate([
            'requested_role' => ['required', 'string', Rule::in(['خبير استشاري', 'مدير شركة'])],
            'reason' => 'nullable|string|max:1000', // حقل اختياري للسبب
        ]);

        UpgradeRequest::create([
            'UserID' => $user->UserID,
            'requested_role' => $validated['requested_role'],
            'status' => 'pending',
            'reason' => $validated['reason'] ?? null,
        ]);

        // TODO: إرسال إشعار للأدمن بوجود طلب ترقية جديد

        return back()->with('success', 'Your request to upgrade to ' . $validated['requested_role'] . ' has been submitted for review.');
    }
}
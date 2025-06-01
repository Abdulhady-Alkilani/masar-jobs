<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UpgradeRequest;
use App\Models\User; // لاستخدامه عند الترقية
use Illuminate\Http\Request;

class UpgradeRequestController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth', 'isAdmin']); // تفعيل middleware الأدمن
    }

    /**
     * عرض قائمة بطلبات الترقية.
     */
    public function index(Request $request)
    {
        $query = UpgradeRequest::with('user')->latest();

        if ($request->filled('status') && in_array($request->input('status'), ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('role')) {
            $query->where('requested_role', $request->input('role'));
        }

        $upgradeRequests = $query->paginate(20)->withQueryString();
        return view('admin.upgrade_requests.index', compact('upgradeRequests'));
    }

    /**
     * عرض تفاصيل طلب ترقية محدد.
     */
    public function show(UpgradeRequest $upgradeRequest) // استخدام اسم البارامتر المطابق
    {
        $upgradeRequest->load('user');
        return view('admin.upgrade_requests.show', compact('upgradeRequest'));
    }


    /**
     * تحديث حالة طلب الترقية (موافقة/رفض).
     */
    public function update(Request $request, UpgradeRequest $upgradeRequest)
    {
        $validated = $request->validate([
            'action' => 'required|string|in:approve,reject',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($upgradeRequest->status !== 'pending') {
            return redirect()->route('admin.upgrade-requests.index')->with('error', 'This request has already been processed.');
        }

        if ($validated['action'] === 'approve') {
            // 1. تحديث حالة الطلب
            $upgradeRequest->status = 'approved';
            $upgradeRequest->admin_notes = $validated['admin_notes'] ?? $upgradeRequest->admin_notes;
            $upgradeRequest->save();

            // 2. ترقية نوع حساب المستخدم
            $user = $upgradeRequest->user;
            if ($user) {
                $user->type = $upgradeRequest->requested_role;
                $user->save();

                // TODO: إرسال إشعار للمستخدم بنجاح الترقية
                // إذا كانت الترقية إلى "مدير شركة" ولم يكن لديه شركة بعد، قد توجهه لإنشاء ملف شركة
                // أو الأدمن يقوم بإنشاء الشركة له.
                 if($user->type === 'مدير شركة' && !$user->company()->exists()) {
                     // يمكنك توجيه المستخدم لإنشاء شركة أو الأدمن يقوم بذلك
                 }

            } else {
                 return redirect()->route('admin.upgrade-requests.index')->with('error', 'User not found for this request.');
            }

            return redirect()->route('admin.upgrade-requests.show', $upgradeRequest)->with('success', 'Upgrade request approved and user account type updated.');

        } elseif ($validated['action'] === 'reject') {
            $upgradeRequest->status = 'rejected';
            $upgradeRequest->admin_notes = $validated['admin_notes'] ?? 'Request rejected.';
            $upgradeRequest->save();

            // TODO: إرسال إشعار للمستخدم برفض الطلب مع السبب (admin_notes)

            return redirect()->route('admin.upgrade-requests.show', $upgradeRequest)->with('success', 'Upgrade request rejected.');
        }

        return redirect()->route('admin.upgrade-requests.index');
    }

    /**
     * حذف طلب ترقية (اختياري).
     */
    public function destroy(UpgradeRequest $upgradeRequest)
    {
        $upgradeRequest->delete();
        return redirect()->route('admin.upgrade-requests.index')->with('success', 'Upgrade request deleted.');
    }
}
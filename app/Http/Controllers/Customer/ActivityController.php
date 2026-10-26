<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ActivityController extends Controller
{
    /**
     * Display customer browsing activity history grouped by timeline (Tokopedia style).
     */
    public function index(Request $request): View
    {
        $activities = CustomerActivity::with(['product.category'])
            ->where('user_id', Auth::id())
            ->whereHas('product')
            ->latest('last_viewed_at')
            ->get();

        $groupedActivities = $activities->groupBy(function ($activity) {
            $viewed = $activity->last_viewed_at;
            if ($viewed->isToday()) {
                return 'Hari Ini';
            }
            if ($viewed->isYesterday()) {
                return 'Kemarin';
            }
            if ($viewed->greaterThanOrEqualTo(now()->subDays(7))) {
                return 'Minggu Ini';
            }

            return 'Lebih Lama';
        });

        $totalCount = $activities->count();

        return view('customer.activity', compact('groupedActivities', 'totalCount'));
    }

    /**
     * Remove single activity record from browsing history.
     */
    public function destroy(CustomerActivity $activity): RedirectResponse
    {
        if ($activity->user_id !== Auth::id()) {
            abort(403);
        }

        $activity->delete();

        return redirect()->route('customer.activity')
            ->with('success', 'Produk berhasil dihapus dari riwayat.');
    }

    /**
     * Clear all browsing activity for current customer.
     */
    public function clear(): RedirectResponse
    {
        CustomerActivity::where('user_id', Auth::id())->delete();

        return redirect()->route('customer.activity')
            ->with('success', 'Seluruh riwayat aktivitas telah dibersihkan.');
    }
}

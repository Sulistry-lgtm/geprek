<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use App\Models\Menu;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $stats = [
                'stores' => Store::count(),
                'menus' => Menu::count(),
                'reviews' => Review::count(),
                'users' => User::count(),
            ];

            $recentReviews = Review::with(['user', 'store'])
                ->latest()
                ->take(5)
                ->get();
        } else {
            $stats = [
                'stores' => Store::where('user_id', $user->id)->count(),
                'menus' => Menu::whereHas('store', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count(),
                'reviews' => Review::whereHas('store', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count(),
                'users' => 0,
            ];

            $recentReviews = Review::whereHas('store', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->with(['user', 'store'])
                ->latest()
                ->take(5)
                ->get();
        }

        if ($user->isSuperAdmin()) {
            return view('admin.dashboard-superadmin', compact('stats', 'recentReviews'));
        }

        return view('admin.dashboard-admin', compact('stats', 'recentReviews'));
    }
}

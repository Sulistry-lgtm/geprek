<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Secure Eloquent query, protected from SQL injection via bound parameters
        $query = Store::with(['reviews']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_toko', 'like', '%' . $search . '%')
                  ->orWhere('alamat', 'like', '%' . $search . '%');
            });
        }

        $stores = $query->latest()->get();

        return view('home', compact('stores', 'search'));
    }

    public function show($id)
    {
        // Find or fail - prevents access to invalid store IDs
        $store = Store::with(['menus', 'reviews.user'])->findOrFail($id);

        // Calculate rating details
        $averageRating = round($store->reviews->avg('rating') ?? 0, 1);
        $totalReviews = $store->reviews->count();

        // Calculate stars breakdown
        $starsBreakdown = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];
        
        foreach ($store->reviews as $review) {
            if (isset($starsBreakdown[$review->rating])) {
                $starsBreakdown[$review->rating]++;
            }
        }

        return view('store_detail', compact('store', 'averageRating', 'totalReviews', 'starsBreakdown'));
    }
}

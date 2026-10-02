<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        // Check if store exists
        $store = Store::findOrFail($id);

        // Input validation to ensure data quality and system security
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'komentar' => ['required', 'string', 'max:1000'],
        ]);

        // Secure Eloquent query - associates with authenticated user
        Review::create([
            'user_id' => Auth::id(),
            'store_id' => $store->id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('store.detail', $store->id)
            ->with('success', 'Ulasan Anda berhasil ditambahkan. Terima kasih!');
    }
}

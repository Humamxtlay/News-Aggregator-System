<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;

class UserPreferenceController extends Controller
{
    public function setPreferences(Request $request)
    {
        $request->validate([
            'preferred_sources' => 'nullable|string',
            'preferred_categories' => 'nullable|string',
            'preferred_authors' => 'nullable|string',
        ]);

        $user = Auth::user();
        $preference = $user->preference()->firstOrCreate([]);

        $preference->update([
            'preferred_sources' => $request->preferred_sources,
            'preferred_categories' => $request->preferred_categories,
            'preferred_authors' => $request->preferred_authors,
        ]);

        return response()->json(['message' => 'Preferences updated successfully!']);
    }

    public function getPreferences()
    {
        $user = Auth::user();
        $preference = $user->preference;

        if (! $preference) {
            return response()->json(['message' => 'No preferences set'], 404);
        }

        return response()->json($preference);
    }

    public function getPersonalizedFeed()
    {
        $user = Auth::user();
        $preference = $user->preference;

        if (! $preference) {
            return response()->json(['message' => 'No preferences set'], 404);
        }

        $query = Article::query();

        if ($preference->preferred_sources) {
            $query->where('source', $preference->preferred_sources);
        }

        if ($preference->preferred_categories) {
            $query->orWhere('category', $preference->preferred_categories);
        }

        if ($preference->preferred_authors) {
            $query->orWhere('author', $preference->preferred_authors);
        }

        $articles = $query->paginate(10);

        return response()->json($articles);
    }
}

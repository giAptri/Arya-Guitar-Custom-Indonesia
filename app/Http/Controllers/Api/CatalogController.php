<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GuitarResource;
use App\Models\Guitar;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * GET /api/catalog
     * List active guitars for public view
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 12);
        $search = $request->query('search');

        $query = Guitar::query()
            ->where('is_active', true)
            ->latest('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        return GuitarResource::collection($query->paginate($perPage));
    }

    /**
     * GET /api/catalog/{slug}
     * Show detailed guitar info for public view
     */
    public function show($slug)
    {
        $guitar = Guitar::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return new GuitarResource($guitar);
    }
}

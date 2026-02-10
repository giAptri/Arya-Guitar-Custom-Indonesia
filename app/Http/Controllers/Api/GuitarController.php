<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GuitarResource;
use App\Models\Guitar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class GuitarController extends Controller
{
    /**
     * GET /api/guitars
     * list katalog (untuk admin). Bisa filter active & search.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $active = $request->query('active'); // 1/0
        $perPage = (int) $request->query('per_page', 10);

        $q = Guitar::query()->latest('created_at');

        if ($active !== null) {
            $q->where('is_active', (bool) $active);
        }

        if ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        return GuitarResource::collection($q->paginate($perPage));
    }

    /**
     * POST /api/guitars
     * create katalog (admin).
     * Support upload image: multipart/form-data key 'image'
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'short_desc' => ['nullable', 'string', 'max:255'],
            'detail_desc' => ['nullable', 'string'],
            'base_price' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],

            // image upload (optional)
            'image' => ['nullable', 'image', 'max:2048'], // max 2MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('guitars', 'public');
        }

        $guitar = Guitar::create([
            'name' => $data['name'],
            // 'slug' handled by Model boot
            'image_path' => $imagePath,
            'short_desc' => $data['short_desc'] ?? null,
            'detail_desc' => $data['detail_desc'] ?? null,
            'base_price' => $data['base_price'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return new GuitarResource($guitar);
    }

    /**
     * GET /api/guitars/{guitar}
     */
    public function show(Guitar $guitar)
    {
        return new GuitarResource($guitar);
    }

    /**
     * PUT/PATCH /api/guitars/{guitar}
     */
    public function update(Request $request, Guitar $guitar)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:150'],
            'short_desc' => ['nullable', 'string', 'max:255'],
            'detail_desc' => ['nullable', 'string'],
            'base_price' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],

            // image upload (optional)
            'image' => ['nullable', 'image', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        // remove image
        if (!empty($data['remove_image']) && $guitar->image_path) {
            Storage::disk('public')->delete($guitar->image_path);
            $guitar->image_path = null;
        }

        // upload new image
        if ($request->hasFile('image')) {
            if ($guitar->image_path) {
                Storage::disk('public')->delete($guitar->image_path);
            }
            $guitar->image_path = $request->file('image')->store('guitars', 'public');
        }

        $guitar->fill([
            'name' => $data['name'] ?? $guitar->name,
            'short_desc' => array_key_exists('short_desc', $data) ? $data['short_desc'] : $guitar->short_desc,
            'detail_desc' => array_key_exists('detail_desc', $data) ? $data['detail_desc'] : $guitar->detail_desc,
            'base_price' => array_key_exists('base_price', $data) ? $data['base_price'] : $guitar->base_price,
            'is_active' => array_key_exists('is_active', $data) ? (bool)$data['is_active'] : $guitar->is_active,
        ]);

        $guitar->save();

        return new GuitarResource($guitar);
    }

    /**
     * DELETE /api/guitars/{guitar}
     */
    public function destroy(Guitar $guitar)
    {
        if ($guitar->image_path) {
            Storage::disk('public')->delete($guitar->image_path);
        }

        $guitar->delete();

        return response()->json([
            'message' => 'Gitar berhasil dihapus.',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    // GET /api/galleries
    public function index()
    {
        $galleries = Gallery::with(['images', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $galleries
        ]);
    }

    // POST /api/galleries
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user() ?? auth('api')->user();

        $gallery = Gallery::create([
            'event_name' => $request->input('event_name'),
            'description' => $request->input('description'),
            'event_date' => $request->input('event_date') ?? now()->toDateString(),
            'created_by' => $user ? $user->id : null,
        ]);

        $uploadedImages = [];

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                $path = $file->store('gallery', 'public');
                $imageUrl = '/storage/' . $path;

                $galleryImage = GalleryImage::create([
                    'gallery_id' => $gallery->id,
                    'image_url' => $imageUrl,
                ]);

                $uploadedImages[] = $galleryImage;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Gallery album created successfully with ' . count($uploadedImages) . ' photos',
            'data' => $gallery->load(['images', 'creator'])
        ], 201);
    }

    // POST /api/galleries/{id}/add-images
    public function addImages(Request $request, $id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json(['success' => false, 'message' => 'Gallery not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $uploadedImages = [];

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                $path = $file->store('gallery', 'public');
                $imageUrl = '/storage/' . $path;

                $galleryImage = GalleryImage::create([
                    'gallery_id' => $gallery->id,
                    'image_url' => $imageUrl,
                ]);

                $uploadedImages[] = $galleryImage;
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedImages) . ' new photos added to album',
            'data' => $gallery->load(['images', 'creator'])
        ]);
    }

    // GET /api/galleries/{id}
    public function show($id)
    {
        $gallery = Gallery::with(['images', 'creator'])->find($id);

        if (!$gallery) {
            return response()->json(['success' => false, 'message' => 'Gallery album not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $gallery
        ]);
    }

    // DELETE /api/galleries/{id}
    public function destroy($id)
    {
        $gallery = Gallery::with('images')->find($id);

        if (!$gallery) {
            return response()->json(['success' => false, 'message' => 'Gallery album not found'], 404);
        }

        // Delete physical files
        foreach ($gallery->images as $img) {
            $relativePath = str_replace('/storage/', '', $img->image_url);
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        }

        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gallery album deleted successfully'
        ]);
    }

    // DELETE /api/gallery-images/{id}
    public function deleteImage($id)
    {
        $image = GalleryImage::find($id);

        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Image not found'], 404);
        }

        $relativePath = str_replace('/storage/', '', $image->image_url);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }

        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image removed from gallery'
        ]);
    }
}

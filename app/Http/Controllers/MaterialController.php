<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'nullable|exists:subjects,id',
            'type' => 'nullable|in:video,pdf,image,assignment',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Material::with(['subject', 'teacher.user']);

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $materials = $query->latest()->get()->toArray();

        // Also include subjects with pdf uploaded
        $subjectQuery = \App\Models\Subject::with(['teacher.user'])->whereNotNull('pdf')->where('pdf', '!=', '');
        if ($request->has('subject_id')) {
            $subjectQuery->where('id', $request->subject_id);
        }
        if (!$request->has('type') || $request->type === 'pdf') {
            $subjectsWithPdf = $subjectQuery->latest()->get();
            foreach ($subjectsWithPdf as $s) {
                $materials[] = [
                    'id'          => 'subject_' . $s->id,
                    'title'       => $s->topic ? $s->topic : ($s->subject_name . ' - Class Material'),
                    'topic'       => $s->topic,
                    'description' => 'Class Study Material',
                    'type'        => 'pdf',
                    'file_url'    => $s->pdf,
                    'created_at'  => $s->created_at ? $s->created_at->toISOString() : null,
                    'updated_at'  => $s->updated_at ? $s->updated_at->toISOString() : null,
                    'teacher'     => $s->teacher ? $s->teacher->toArray() : null,
                    'subject'     => [
                        'id'    => $s->id,
                        'name'  => $s->subject_name,
                        'grade' => $s->grade,
                    ]
                ];
            }
        }

        return response()->json($materials);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'type' => 'required|in:video,pdf,image,assignment',
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Upload file
        $path = $request->file('file')->store('materials', 'public');
        $size = $request->file('file')->getSize();

        $material = Material::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'type' => $request->type,
            'file_url' => Storage::url($path),
            'file_size' => $size,
        ]);

        return response()->json([
            'message' => 'Material uploaded successfully',
            'material' => $material->load(['subject', 'teacher.user'])
        ], 201);
    }

    public function show($id)
    {
        $material = Material::with(['subject', 'teacher.user'])->find($id);

        if (!$material) {
            return response()->json(['error' => 'Material not found'], 404);
        }

        return response()->json($material);
    }

    public function update(Request $request, $id)
    {
        $material = Material::find($id);

        if (!$material) {
            return response()->json(['error' => 'Material not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'sometimes|exists:subjects,id',
            'type' => 'sometimes|in:video,pdf,image,assignment',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $material->update($request->only(['title', 'description', 'subject_id', 'type']));

        return response()->json([
            'message' => 'Material updated successfully',
            'material' => $material->load(['subject', 'teacher.user'])
        ]);
    }

    public function destroy($id)
    {
        $material = Material::find($id);

        if (!$material) {
            return response()->json(['error' => 'Material not found'], 404);
        }

        // Delete file from storage
        $path = str_replace('/storage/', '', parse_url($material->file_url, PHP_URL_PATH));
        Storage::disk('public')->delete($path);

        $material->delete();

        return response()->json(['message' => 'Material deleted successfully']);
    }
}
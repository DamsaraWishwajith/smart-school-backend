<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MealPlanController extends Controller
{
    // GET /api/meal-plans
    public function index()
    {
        $mealPlans = MealPlan::with('updatedBy')->get();

        return response()->json([
            'success' => true,
            'data' => $mealPlans,
        ]);
    }

    // GET /api/meal-plans/{day}
    public function show($day)
    {
        $mealPlan = MealPlan::with('updatedBy')->where('day', $day)->first();

        if (!$mealPlan) {
            return response()->json(['success' => false, 'message' => 'No meal plan found for ' . $day], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $mealPlan,
        ]);
    }

    // POST /api/meal-plans (create or update the row for the given day)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'meal' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        $mealPlan = MealPlan::updateOrCreate(
            ['day' => $request->input('day')],
            [
                'meal' => $request->input('meal'),
                'notes' => $request->input('notes'),
                'updated_by' => auth()->id(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Meal plan saved successfully',
            'data' => $mealPlan->load('updatedBy'),
        ], 201);
    }
}

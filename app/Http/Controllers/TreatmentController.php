<?php
// app/Http/Controllers/TreatmentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TreatmentDataService;

class TreatmentController extends Controller
{
    /**
     * Display treatment list page
     */
    public function index()
    {
        try {
            $treatments = TreatmentDataService::getAllTreatments();

            return view("landing.pages.perawatan", compact("treatments"));
        } catch (\Exception $e) {
            \Log::error("Error loading treatments: " . $e->getMessage());

            return view("landing.pages.perawatan", [
                "treatments" => [],
                "error" => "Gagal memuat data perawatan",
            ]);
        }
    }

    /**
     * Display single treatment detail
     */
    public function show($slug)
    {
        try {
            $treatment = TreatmentDataService::getTreatmentBySlug($slug);

            if (!$treatment) {
                abort(404, "Treatment tidak ditemukan");
            }

            // Get related treatments (same category, exclude current)
            $relatedTreatments = collect(
                TreatmentDataService::getTreatmentsByCategory(
                    $treatment["category"],
                ),
            )
                ->reject(function ($item) use ($slug) {
                    return $item["slug"] === $slug;
                })
                ->take(3)
                ->values()
                ->toArray();

            return view(
                "landing.pages.treatment-detail",
                compact("treatment", "relatedTreatments"),
            );
        } catch (\Exception $e) {
            \Log::error("Error loading treatment detail: " . $e->getMessage());

            // Redirect back with error message
            return redirect()
                ->route("perawatan.index")
                ->with("error", "Gagal memuat detail perawatan");
        }
    }

    /**
     * Get treatments by category (for AJAX)
     */
    public function byCategory($category)
    {
        try {
            $treatments = TreatmentDataService::getTreatmentsByCategory(
                $category,
            );

            if (empty($treatments)) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Kategori tidak ditemukan",
                    ],
                    404,
                );
            }

            return response()->json([
                "success" => true,
                "data" => $treatments,
            ]);
        } catch (\Exception $e) {
            \Log::error(
                "Error loading treatments by category: " . $e->getMessage(),
            );

            return response()->json(
                [
                    "success" => false,
                    "message" => "Gagal memuat data",
                ],
                500,
            );
        }
    }
}

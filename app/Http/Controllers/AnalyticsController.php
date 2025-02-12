<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Encounter;
use App\Models\Fhir\Patient;
use App\Models\Fhir\Resource;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function getTodayEncounters()
    {
        $count = Encounter::where(function ($query) {
            $today = now()->toDateString();

            // Check if period_start is today or before
            $query->whereDate('period_start', '<=', $today);

            // Check if period_end is today or later, or null
            $query->where(function ($subQuery) use ($today) {
                $subQuery->whereDate('period_end', '>=', $today)
                    ->orWhereNull('period_end');
            });
        })
            ->count();

        return response()->json(['count' => $count]);
    }


    public function getThisMonthNewPatients()
    {
        $firstDayOfMonth = now()->startOfMonth();
        $lastDayOfMonth = now()->endOfMonth();

        // Retrieve the count of resources
        $resourceCount = Resource::where('res_type', 'Patient')
            ->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
            ->count();

        return response()->json(['count' => $resourceCount]);
    }


    public function countPatients()
    {
        $count = Patient::count();

        return response()->json(['count' => $count]);
    }


    public function getEncountersPerMonth()
    {
        // Calculate the date 12 months ago from now
        $twelveMonthsAgo = now()->subMonths(12);

        // Fetch data from the Encounter model grouped by month and class
        $analyticsData = Encounter::select(
            DB::raw('DATE_FORMAT(period_start, "%Y-%m") as month'),
            'class',
            DB::raw('COUNT(*) as encounter_count')
        )
            ->where('period_start', '>=', $twelveMonthsAgo) // Filter data for the last 12 months
            ->groupBy('month', 'class')
            ->get();

        return response()->json(['data' => $analyticsData]);
    }


    public function getPatientAgeGroups()
    {
        $patientCounts = Patient::select(
            DB::raw('CASE
                WHEN DATEDIFF(CURDATE(), birth_date) / 365.25 BETWEEN 0 AND 5 THEN "balita"
                WHEN DATEDIFF(CURDATE(), birth_date) / 365.25 BETWEEN 5 AND 11 THEN "kanak"
                WHEN DATEDIFF(CURDATE(), birth_date) / 365.25 BETWEEN 11 AND 25 THEN "remaja"
                WHEN DATEDIFF(CURDATE(), birth_date) / 365.25 BETWEEN 25 AND 45 THEN "dewasa"
                WHEN DATEDIFF(CURDATE(), birth_date) / 365.25 BETWEEN 45 AND 65 THEN "lansia"
                ELSE "manula"
            END as age_group'),
            DB::raw('count(*) as count')
        )
            ->groupBy('age_group')
            ->get();

        return response()->json(['data' => $patientCounts]);
    }
}

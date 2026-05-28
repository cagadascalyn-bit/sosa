<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers   = User::count();
        $totalRecipes = Recipe::count();
        $myRecipes    = Recipe::where('user_id', auth()->id())->count();

        // Recipes per category for chart
        $categoryData = Recipe::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // Recipes per month (last 6 months)
        $monthlyData = Recipe::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $months = [];
        $counts = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('M', mktime(0, 0, 0, $i, 1));
            $counts[] = $monthlyData[$i] ?? 0;
        }

        $recentRecipes = Recipe::with('user')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalUsers', 'totalRecipes', 'myRecipes',
            'categoryData', 'months', 'counts', 'recentRecipes'
        ));
    }
}

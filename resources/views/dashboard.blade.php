@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner mb-4">
    <div class="wb-text">
        <div class="wb-greeting">Good {{ date('H') < 12 ? 'Morning' : (date('H') < 18 ? 'Afternoon' : 'Evening') }}, {{ explode(' ', auth()->user()->name)[0] }}! 👋</div>
        <div class="wb-sub">Here's what's cooking in your RecipeBook today.</div>
    </div>
    <div class="wb-emoji">🍳</div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card" style="background: linear-gradient(135deg,#ff6b35,#ff9a3c)">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-sub"><i class="bi bi-arrow-up-short"></i> Registered accounts</div>
            <i class="bi bi-people-fill stat-icon"></i>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card" style="background: linear-gradient(135deg,#6c3483,#9b59b6)">
            <div class="stat-label">Total Recipes</div>
            <div class="stat-value">{{ $totalRecipes }}</div>
            <div class="stat-sub"><i class="bi bi-collection"></i> All recipes in system</div>
            <i class="bi bi-journal-richtext stat-icon"></i>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card" style="background: linear-gradient(135deg,#1a6b4a,#27ae60)">
            <div class="stat-label">My Recipes</div>
            <div class="stat-value">{{ $myRecipes }}</div>
            <div class="stat-sub"><i class="bi bi-bookmark-heart"></i> Your personal collection</div>
            <i class="bi bi-bookmark-heart-fill stat-icon"></i>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div style="font-weight:800;font-size:.95rem">Monthly Recipes</div>
                    <div style="font-size:.78rem;color:#888">{{ date('Y') }} overview</div>
                </div>
                <span class="badge-category"><i class="bi bi-bar-chart-fill me-1"></i>Bar Chart</span>
            </div>
            <canvas id="monthlyChart" height="130"></canvas>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div style="font-weight:800;font-size:.95rem">By Category</div>
                    <div style="font-size:.78rem;color:#888">Recipe distribution</div>
                </div>
                <span class="badge-category"><i class="bi bi-pie-chart-fill me-1"></i>Doughnut</span>
            </div>
            <canvas id="categoryChart" height="180"></canvas>
        </div>
    </div>
</div>

{{-- Recent Recipes --}}
<div class="card">
    <div class="p-4">
        <div class="card-header-bar">
            <h6><i class="bi bi-clock-history me-2" style="color:var(--primary)"></i>Recent Recipes</h6>
            <a href="{{ route('recipes.index') }}" class="btn btn-sm btn-primary">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Recipe</th>
                        <th>Category</th>
                        <th>Added By</th>
                        <th>Prep Time</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRecipes as $recipe)
                    <tr>
                        <td>
                            <div style="font-weight:700">{{ $recipe->title }}</div>
                        </td>
                        <td><span class="badge-category">{{ $recipe->category }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#ff6b35,#ff9a3c);color:#fff;font-size:.7rem;font-weight:800;display:flex;align-items:center;justify-content:center">
                                    {{ strtoupper(substr($recipe->user->name,0,1)) }}
                                </div>
                                {{ $recipe->user->name }}
                            </div>
                        </td>
                        <td><i class="bi bi-clock me-1" style="color:var(--primary)"></i>{{ $recipe->prep_time }} min</td>
                        <td style="color:#888;font-size:.82rem">{{ $recipe->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5" style="color:#bbb">
                            <div style="font-size:2.5rem">🍽️</div>
                            <div style="font-weight:700;margin-top:8px">No recipes yet</div>
                            <a href="{{ route('recipes.index') }}" class="btn btn-sm btn-primary mt-2">Add your first recipe</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #1c1c2e, #3d2200);
        border-radius: 16px; padding: 24px 28px;
        display: flex; align-items: center; justify-content: space-between;
        overflow: hidden; position: relative;
    }
    .welcome-banner::after {
        content: ''; position: absolute; right: -30px; top: -30px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,107,53,.1);
    }
    .wb-greeting { color: #fff; font-weight: 800; font-size: 1.2rem; }
    .wb-sub { color: rgba(255,255,255,.5); font-size: .85rem; margin-top: 4px; }
    .wb-emoji { font-size: 3rem; position: relative; z-index: 1; }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const months    = @json($months);
const counts    = @json($counts);
const catLabels = @json($categoryData->keys());
const catData   = @json($categoryData->values());

Chart.defaults.font.family = 'Nunito';

new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Recipes',
            data: counts,
            backgroundColor: counts.map((_, i) =>
                i === new Date().getMonth() ? '#ff6b35' : 'rgba(255,107,53,.2)'
            ),
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, color: '#aaa' }, grid: { color: '#f0ebe5' } },
            x: { ticks: { color: '#aaa' }, grid: { display: false } }
        }
    }
});

new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: catLabels.length ? catLabels : ['No Data'],
        datasets: [{
            data: catData.length ? catData : [1],
            backgroundColor: ['#ff6b35','#6c3483','#1a6b4a','#2980b9','#e67e22','#c0392b'],
            borderWidth: 3,
            borderColor: '#fff',
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { padding: 16, font: { size: 12, weight: '700' } } }
        }
    }
});
</script>
@endsection

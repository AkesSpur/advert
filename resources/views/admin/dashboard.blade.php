@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Панель управления</h1>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Всего пользователей</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($totalUsers) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Всего профилей</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($totalProfiles) }} <span class="text-small text-muted">({{ number_format($activeProfiles) }} активных)</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Активные VIP</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($activeVips) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>VIP в очереди</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($vipsInQueue) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Заявки на верификацию</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($pendingVerifications) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-secondary">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Всего комментариев</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($totalComments) }}
                        @if($pendingComments > 0)
                            <span class="text-small text-warning">({{ number_format($pendingComments) }} ожидают проверки)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-dark">
                    <i class="fas fa-star"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Всего отзывов</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($totalReviews) }}
                        @if($pendingReviews > 0)
                            <span class="text-small text-warning">({{ number_format($pendingReviews) }} ожидают проверки)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-statistic-2">
                <div class="card-stats">
                    <div class="card-stats-title">Доход от рекламы</div>
                    <div class="card-stats-items">
                        <div class="card-stats-item">
                            <div class="card-stats-item-count">{{ number_format($revenueToday, 2) }}</div>
                            <div class="card-stats-item-label">Сегодня</div>
                        </div>
                        <div class="card-stats-item">
                            <div class="card-stats-item-count">{{ number_format($revenueThisMonth, 2) }}</div>
                            <div class="card-stats-item-label">Месяц</div>
                        </div>
                        <div class="card-stats-item">
                            <div class="card-stats-item-count">{{ number_format($revenueThisYear, 2) }}</div>
                            <div class="card-stats-item-label">Год</div>
                        </div>
                    </div>
                </div>
                <div class="card-icon shadow-primary bg-primary">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Общий доход от рекламы</h4>
                    </div>
                    <div class="card-body">
                        {{ number_format($revenueThisYear, 2) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Доход по месяцам ({{ date('Y') }})</h4>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="158"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        const chartData = @json($chartData);
        const monthNames = @json($monthNames);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Доход (₽)',
                    data: chartData,
                    borderWidth: 2,
                    backgroundColor: 'rgba(63, 82, 227, 0.2)',
                    borderColor: 'rgba(63, 82, 227, 1)',
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgba(63, 82, 227, 1)',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₽' + value.toLocaleString();
                            }
                        }
                    }
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return '₽' + tooltipItem.yLabel.toLocaleString();
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
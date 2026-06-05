<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Dashboard | Martech TMS
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3">
        <div>
            <h2 class="mb-0 fw-bold">Dashboard</h2>
            <p class="text-secondary small mb-0">System status and activity overview for the last 30 days</p>
        </div>
        <div>
            <span class="badge bg-secondary text-secondary" style="font-size: 0.85rem; font-weight: 600; padding: 0.5em 1em; border-radius: 20px;">
                <i class="bi bi-clock-history me-1"></i> Last updated: <?= date('d M Y, h:i A') ?>
            </span>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="row g-3 mb-4">
        <!-- Active Tags -->
        <div class="col-md-3">
            <div class="apple-metric-card border-start border-4 border-success" style="cursor: pointer;" onclick="window.location.href='<?= site_url('tags?status=1') ?>'">
                <div class="apple-metric-label">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i> 
                    <span>Active Tags</span>
                </div>
                <div class="apple-metric-value text-success"><?= $active_tags ?></div>
                <div class="text-secondary small mt-2">Currently active in AL</div>
            </div>
        </div>
        
        <!-- Expiring Soon -->
        <div class="col-md-3">
            <div class="apple-metric-card border-start border-4 border-danger" style="cursor: pointer;" onclick="window.location.href='<?= site_url('tags?status=expiring') ?>'">
                <div class="apple-metric-label">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i> 
                    <span>Expiring Soon</span>
                </div>
                <div class="apple-metric-value text-danger"><?= $expiring_tags ?></div>
                <div class="text-secondary small mt-2">Expiring within 15 days</div>
            </div>
        </div>

        <!-- Pending Verification -->
        <div class="col-md-3">
            <div class="apple-metric-card border-start border-4 border-warning" style="cursor: pointer;" onclick="window.location.href='<?= site_url('tags?status=4') ?>'">
                <div class="apple-metric-label">
                    <i class="bi bi-hourglass-split text-warning fs-5"></i> 
                    <span>Pending Verification</span>
                </div>
                <div class="apple-metric-value text-warning"><?= $pending_verification ?></div>
                <div class="text-secondary small mt-2">Renewed tags awaiting QA</div>
            </div>
        </div>

        <!-- Pending Tag Requests -->
        <div class="col-md-3">
            <div class="apple-metric-card border-start border-4 border-primary" style="cursor: pointer;" onclick="window.location.href='<?= site_url('tag-requests?status=pending') ?>'">
                <div class="apple-metric-label">
                    <i class="bi bi-file-earmark-plus-fill text-primary fs-5"></i> 
                    <span>Pending Requests</span>
                </div>
                <div class="apple-metric-value text-primary"><?= $pending_requests ?></div>
                <div class="text-secondary small mt-2">Requests in review process</div>
            </div>
        </div>
    </div>

    <!-- Charts Section: Row 1 -->
    <div class="row g-4 mb-4">
        <!-- Line Chart: System Activity & Ingestion Trend -->
        <div class="col-lg-8">
            <div class="apple-card h-100">
                <h5 class="card-title mb-3">System Activity & Ingestion (Last 30 Days)</h5>
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="activityTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart: Tag Status Distribution -->
        <div class="col-lg-4">
            <div class="apple-card h-100">
                <h5 class="card-title mb-3">Tag Status Distribution</h5>
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="statusDistributionChart"></canvas>
                </div>
                <div class="text-center mt-3 text-secondary small">
                    Total Inventory: <strong><?= $total_tags ?></strong> tags
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section: Row 2 -->
    <div class="row g-4 mb-4">
        <!-- Bar Chart: Top Subsidiaries -->
        <div class="col-md-6">
            <div class="apple-card h-100">
                <h5 class="card-title mb-3">Tags by Subsidiary</h5>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="subsidiaryDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Chart: Top Vendors -->
        <div class="col-md-6">
            <div class="apple-card h-100">
                <h5 class="card-title mb-3">Top 10 Vendors by Tag Count</h5>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="vendorDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section: Row 3 -->
    <div class="row g-4 mb-4">
        <!-- Line Chart: Tag Expiration Forecast (Next 45 Days) -->
        <div class="col-lg-8">
            <div class="apple-card h-100">
                <h5 class="card-title mb-1">Tag Expiration Forecast (Next 45 Days)</h5>
                <p class="text-secondary small mb-3">Expected tag deactivation/renewal timeline</p>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="expirationForecastChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart: Tag Requests Status Distribution -->
        <div class="col-lg-4">
            <div class="apple-card h-100">
                <h5 class="card-title mb-3">Tag Requests Pipeline</h5>
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="requestStatusChart"></canvas>
                </div>
                <div class="text-center mt-3 text-secondary small">
                    Total Requests: <strong><?= $total_requests ?></strong> requests
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section: Row 4 (Activity Logs Analytics) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="apple-card">
                <h5 class="card-title mb-1">Activity Logs Analytics</h5>
                <p class="text-secondary small mb-4">Breakdown of support tickets, tasks, and system activities</p>
                
                <div class="row g-4">
                    <!-- Doughnut Chart: Activity Logs by Status -->
                    <div class="col-md-4">
                        <h6 class="text-center mb-3">Activities by Status</h6>
                        <div style="position: relative; height: 240px; width: 100%;">
                            <canvas id="activityStatusChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Bar Chart: Activity Logs by Priority -->
                    <div class="col-md-4">
                        <h6 class="text-center mb-3">Activities by Priority</h6>
                        <div style="position: relative; height: 240px; width: 100%;">
                            <canvas id="activityPriorityChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Bar Chart: Activity Logs by Project -->
                    <div class="col-md-4">
                        <h6 class="text-center mb-3">Activities by Project</h6>
                        <div style="position: relative; height: 240px; width: 100%;">
                            <canvas id="activityProjectChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Shared chart fonts & styles configurations
    Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
    Chart.defaults.color = '#86868B';
    
    // --- 1. Line Chart: Ingestion & Activity Trend ---
    const activityCtx = document.getElementById('activityTrendChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [
                {
                    label: 'New Requests',
                    data: <?= json_encode($chart_tags) ?>,
                    borderColor: '#FD7E14',
                    backgroundColor: 'rgba(253, 126, 20, 0.05)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#FD7E14',
                    pointHoverRadius: 6
                },
                {
                    label: 'New Implementations',
                    data: <?= json_encode($chart_new_rules) ?>,
                    borderColor: '#DC3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.05)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#DC3545',
                    pointHoverRadius: 6
                },
                {
                    label: 'Pending Requests',
                    data: <?= json_encode($chart_pending) ?>,
                    borderColor: '#00C2FF',
                    backgroundColor: 'rgba(0, 194, 255, 0.05)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#00C2FF',
                    pointHoverRadius: 6
                },
                {
                    label: 'Date Extensions',
                    data: <?= json_encode($chart_requests) ?>,
                    borderColor: '#0071E3',
                    backgroundColor: 'rgba(0, 113, 227, 0.05)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#0071E3',
                    pointHoverRadius: 6
                },
                {
                    label: 'Activities Logged',
                    data: <?= json_encode($chart_activities) ?>,
                    borderColor: '#A855F7',
                    backgroundColor: 'rgba(168, 85, 247, 0.05)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#A855F7',
                    pointHoverRadius: 6
                }
                
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: { size: 12, weight: '500' }
                    }
                },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8,
                    backgroundColor: 'rgba(29, 29, 31, 0.95)',
                    titleFont: { size: 13, weight: '600' },
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0, 0, 0, 0.04)' }
                }
            }
        }
    });

    // --- 2. Doughnut Chart: Tag Status Distribution ---
    const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
    const statusData = <?= json_encode($status_distribution) ?>;
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: [
                    '#2E7D32', // Enabled
                    '#86868B', // Disabled
                    '#DE3535', // Expired
                    '#F57F17', // Pending Verification
                    '#0071E3', // Updated in AL
                    '#6F42C1', // New Tag Request Submitted
                    '#FD7E14'  // New Rule Request Submitted
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 10,
                        padding: 10,
                        font: { size: 10, weight: '500' }
                    }
                },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8,
                    backgroundColor: 'rgba(29, 29, 31, 0.95)'
                }
            },
            cutout: '65%'
        }
    });

    // --- 3. Bar Chart: Subsidiary Distribution ---
    const subCtx = document.getElementById('subsidiaryDistributionChart').getContext('2d');
    const subData = <?= json_encode($subsidiary_distribution) ?>;
    
    new Chart(subCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(subData),
            datasets: [{
                label: 'Tags',
                data: Object.values(subData),
                backgroundColor: 'rgba(0, 113, 227, 0.8)',
                hoverBackgroundColor: '#0071E3',
                borderRadius: 6,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8,
                    backgroundColor: 'rgba(29, 29, 31, 0.95)'
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 5 },
                    grid: { color: 'rgba(0, 0, 0, 0.04)' }
                }
            }
        }
    });

    // --- 4. Bar Chart: Vendor Distribution ---
    const vendorCtx = document.getElementById('vendorDistributionChart').getContext('2d');
    const vendorData = <?= json_encode($vendor_distribution) ?>;
    
    new Chart(vendorCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(vendorData),
            datasets: [{
                label: 'Tags',
                data: Object.values(vendorData),
                backgroundColor: 'rgba(168, 85, 247, 0.8)',
                hoverBackgroundColor: '#A855F7',
                borderRadius: 6,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y', // Horizontal bars
            plugins: {
                legend: { display: false },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8,
                    backgroundColor: 'rgba(29, 29, 31, 0.95)'
                }
            },
            scales: {
                x: { 
                    beginAtZero: true,
                    ticks: { stepSize: 2 },
                    grid: { color: 'rgba(0, 0, 0, 0.04)' }
                },
                y: { grid: { display: false } }
            }
        }
    });

    // --- 5. Line Chart: Tag Expiration Forecast ---
    const forecastCtx = document.getElementById('expirationForecastChart').getContext('2d');
    new Chart(forecastCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($forecast_labels) ?>,
            datasets: [{
                label: 'Tags Expiring',
                data: <?= json_encode($forecast_data) ?>,
                borderColor: '#FF453A',
                backgroundColor: 'rgba(255, 69, 58, 0.05)',
                borderWidth: 2,
                tension: 0.35,
                fill: true,
                pointBackgroundColor: '#FF453A',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8,
                    backgroundColor: 'rgba(29, 29, 31, 0.95)',
                    titleFont: { size: 13, weight: '600' },
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0, 0, 0, 0.04)' }
                }
            }
        }
    });

    // --- 6. Doughnut Chart: Tag Requests Pipeline ---
    const requestStatusCtx = document.getElementById('requestStatusChart').getContext('2d');
    const requestStatusData = <?= json_encode($request_status_distribution) ?>;
    new Chart(requestStatusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(requestStatusData),
            datasets: [{
                data: Object.values(requestStatusData),
                backgroundColor: [
                    '#0071E3', // HQ Review
                    '#FF9F0A', // On-Hold Req
                    '#BF5AF2', // New Rule Request Submitted
                    '#30D158', // Approved
                    '#FF453A', // Rejected
                    '#64D2FF'  // Published
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 10,
                        padding: 10,
                        font: { size: 10, weight: '500' }
                    }
                },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8,
                    backgroundColor: 'rgba(29, 29, 31, 0.95)'
                }
            },
            cutout: '65%'
        }
    });

    // --- 7. Doughnut Chart: Activity Logs by Status ---
    const activityStatusCtx = document.getElementById('activityStatusChart').getContext('2d');
    const activityStatusData = <?= json_encode($activity_status_distribution) ?>;
    new Chart(activityStatusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(activityStatusData),
            datasets: [{
                data: Object.values(activityStatusData),
                backgroundColor: [
                    '#8E8E93', // Not yet started
                    '#FF9F0A', // In Progress - Investigation
                    '#0071E3', // In Progress - Implementation
                    '#BF5AF2', // Testing - QA
                    '#64D2FF', // Pending Review
                    '#30D158', // Completed
                    '#FF453A'  // Blocked/On Hold
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 8,
                        padding: 8,
                        font: { size: 9, weight: '500' }
                    }
                },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8,
                    backgroundColor: 'rgba(29, 29, 31, 0.95)'
                }
            },
            cutout: '65%'
        }
    });

    // --- 8. Bar Chart: Activity Logs by Priority ---
    const activityPriorityCtx = document.getElementById('activityPriorityChart').getContext('2d');
    const activityPriorityData = <?= json_encode($activity_priority_distribution) ?>;
    new Chart(activityPriorityCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(activityPriorityData),
            datasets: [{
                label: 'Tickets',
                data: Object.values(activityPriorityData),
                backgroundColor: [
                    '#30D158', // Low
                    '#64D2FF', // Medium
                    '#FF9F0A', // High
                    '#FF453A', // Critical
                    '#BF5AF2'  // Blocker
                ],
                borderRadius: 6,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8,
                    backgroundColor: 'rgba(29, 29, 31, 0.95)'
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 2 },
                    grid: { color: 'rgba(0, 0, 0, 0.04)' }
                }
            }
        }
    });

    // --- 9. Bar Chart: Activity Logs by Project ---
    const activityProjectCtx = document.getElementById('activityProjectChart').getContext('2d');
    const activityProjectData = <?= json_encode($activity_project_distribution) ?>;
    new Chart(activityProjectCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(activityProjectData),
            datasets: [{
                label: 'Tickets',
                data: Object.values(activityProjectData),
                backgroundColor: 'rgba(0, 113, 227, 0.8)',
                hoverBackgroundColor: '#0071E3',
                borderRadius: 6,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8,
                    backgroundColor: 'rgba(29, 29, 31, 0.95)'
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 2 },
                    grid: { color: 'rgba(0, 0, 0, 0.04)' }
                }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>

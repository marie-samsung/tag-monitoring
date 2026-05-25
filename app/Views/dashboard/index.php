<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Dashboard | Tag Monitoring System
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid py-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Dashboard</h2>
            <p class="text-secondary small mb-0">System status and activity overview for the last 30 days</p>
        </div>
        <div>
            <span class="badge bg-secondary p-2">
                <i class="bi bi-clock-history"></i> Last updated: <?= date('d M Y, h:i A') ?>
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
    <div class="row g-4">
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
                    label: 'New Tags Created',
                    data: <?= json_encode($chart_tags) ?>,
                    borderColor: '#2E7D32',
                    backgroundColor: 'rgba(46, 125, 50, 0.05)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#2E7D32',
                    pointHoverRadius: 6
                },
                {
                    label: 'Tag Requests Submitted',
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
});
</script>

<?= $this->endSection() ?>

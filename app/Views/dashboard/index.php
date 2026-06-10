<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Dashboard | Martech TMS
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
/* Modern Modal Styles */
#activityTagsModal .modal-content {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: saturate(180%) blur(20px);
    -webkit-backdrop-filter: saturate(180%) blur(20px);
    border: 1px solid rgba(0, 0, 0, 0.08) !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
    border-radius: 20px !important;
    overflow: hidden;
    transition: border-top-color 0.3s ease;
}

#activityTagsModal .modal-header {
    padding: 24px 24px 12px;
}

#activityTagsModal .modal-body {
    padding: 12px 24px 24px;
}

#activityTagsModal .modal-footer {
    padding: 20px 24px 24px !important;
}

/* Custom Table for Modal */
.modal-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.modal-table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #86868B;
    border-bottom: 2px solid rgba(0, 0, 0, 0.04);
    padding: 12px 16px;
}

.modal-table td {
    padding: 14px 16px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.04);
    font-size: 0.875rem;
}

.modal-table tbody tr {
    transition: background-color var(--transition-fast, 0.2s ease);
}

.modal-table tbody tr:hover {
    background-color: rgba(0, 113, 227, 0.04) !important;
}

/* Modern Activity Card List */
.activity-modal-card {
    background: rgba(255, 255, 255, 0.6);
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
    transition: all var(--transition-medium, 0.35s cubic-bezier(0.25, 0.8, 0.25, 1));
    position: relative;
    overflow: hidden;
}

.activity-modal-card:hover {
    background: rgba(255, 255, 255, 0.95);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.04);
}

.activity-modal-card::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 4px;
    background: var(--accent-color, #8E8E93);
}

/* Custom Scrollbar for Modal Body */
#activityTagsModal .modal-body {
    max-height: 480px;
    overflow-y: auto;
}

#activityTagsModal .modal-body::-webkit-scrollbar {
    width: 8px;
}

#activityTagsModal .modal-body::-webkit-scrollbar-track {
    background: transparent;
}

#activityTagsModal .modal-body::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

#activityTagsModal .modal-body::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.2);
}

.modal-backdrop {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    background-color: rgba(0, 0, 0, 0.3) !important;
}

#activityTagsModal .btn-close {
    background-size: 10px;
    opacity: 0.6;
    transition: opacity var(--transition-fast, 0.2s ease);
}
#activityTagsModal .btn-close:hover {
    opacity: 1;
}
#activityTagsModal .btn-close:focus {
    box-shadow: none;
}
</style>

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

<!-- Activity Tags Modal -->
<div class="modal fade" id="activityTagsModal" tabindex="-1" aria-labelledby="activityTagsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="activityTagsModalLabel">Tags Overview</h5>
                    <p class="text-secondary small mb-0" id="activityTagsModalSub"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <!-- Loading Spinner -->
                <div id="modalLoading" class="text-center py-5">
                    <div class="spinner-grow text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-secondary mt-3 small">Loading details...</p>
                </div>
                <!-- Content Area -->
                <div id="modalContent" style="display: none;">
                    <div id="tagsListContainer" style="display: none;">
                        <div class="table-responsive">
                            <table class="table modal-table align-middle">
                                <thead>
                                    <tr class="text-secondary" style="font-size: 0.85rem;">
                                        <th>Subsidiary</th>
                                        <th>Tag Name</th>
                                        <th>Property</th>
                                        <th>Vendor</th>
                                        <th>Status</th>
                                        <th>End Date</th>
                                        <th>Ticket</th>
                                    </tr>
                                </thead>
                                <tbody id="tagsTableBody">
                                    <!-- Dynamic content -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="activitiesListContainer" style="display: none;">
                        <!-- Dynamic activities -->
                    </div>
                    <div id="noDataMessage" class="text-center py-5 text-secondary" style="display: none;">
                        <i class="bi bi-info-circle fs-3 mb-2 d-block"></i>
                        <span>No data found for this date.</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="apple-btn-secondary" data-bs-dismiss="modal">Close</button>
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

    const chartLabelsFull = <?= json_encode($chart_labels_full) ?>;
    const forecastLabelsFull = <?= json_encode($forecast_labels_full) ?>;
    
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
            onClick: (event, elements, chart) => {
                if (elements.length > 0) {
                    const firstElement = elements[0];
                    const datasetIndex = firstElement.datasetIndex;
                    const index = firstElement.index;
                    const dateStr = chartLabelsFull[index];
                    const datasetLabel = chart.data.datasets[datasetIndex].label;

                    let category = '';
                    if (datasetLabel === 'New Requests') category = 'new_requests';
                    else if (datasetLabel === 'New Implementations') category = 'new_implementations';
                    else if (datasetLabel === 'Pending Requests') category = 'pending_requests';
                    else if (datasetLabel === 'Date Extensions') category = 'date_extensions';
                    else if (datasetLabel === 'Activities Logged') category = 'activities';

                    if (category) {
                        showActivityTagsModal(dateStr, category, datasetLabel);
                    }
                }
            },
            onHover: (event, elements) => {
                event.native.target.style.cursor = elements.length ? 'pointer' : 'default';
            },
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
            datasets: [
                {
                    label: 'Tags Expiring',
                    data: <?= json_encode($forecast_data_expiring) ?>,
                    borderColor: '#FF453A',
                    backgroundColor: 'rgba(255, 69, 58, 0.05)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#FF453A',
                    pointHoverRadius: 6
                },
                {
                    label: 'Pending Verification',
                    data: <?= json_encode($forecast_data_pending) ?>,
                    borderColor: '#FF9F0A',
                    backgroundColor: 'rgba(255, 159, 10, 0.05)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#FF9F0A',
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            onClick: (event, elements, chart) => {
                if (elements.length > 0) {
                    const firstElement = elements[0];
                    const datasetIndex = firstElement.datasetIndex;
                    const index = firstElement.index;
                    const dateStr = forecastLabelsFull[index];
                    const datasetLabel = chart.data.datasets[datasetIndex].label;

                    let category = '';
                    if (datasetLabel === 'Tags Expiring') category = 'expiring_tags';
                    else if (datasetLabel === 'Pending Verification') category = 'pending_verification_tags';

                    if (category) {
                        showActivityTagsModal(dateStr, category, datasetLabel);
                    }
                }
            },
            onHover: (event, elements) => {
                event.native.target.style.cursor = elements.length ? 'pointer' : 'default';
            },
            plugins: {
                legend: {
                    display: true,
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

    const activityTagsModal = new bootstrap.Modal(document.getElementById('activityTagsModal'));

    function showActivityTagsModal(date, category, labelName) {
        document.getElementById('activityTagsModalLabel').innerText = labelName;
        document.getElementById('activityTagsModalSub').innerText = 'Details for ' + formatDateString(date);

        // Dynamically style the top border matching the category
        let categoryColor = '#0071E3';
        if (category === 'new_requests') categoryColor = '#FD7E14';
        else if (category === 'new_implementations') categoryColor = '#DC3545';
        else if (category === 'pending_requests') categoryColor = '#00C2FF';
        else if (category === 'date_extensions') categoryColor = '#0071E3';
        else if (category === 'activities') categoryColor = '#A855F7';
        else if (category === 'expiring_tags') categoryColor = '#FF453A';
        else if (category === 'pending_verification_tags') categoryColor = '#FF9F0A';

        document.querySelector('#activityTagsModal .modal-content').style.borderTop = `5px solid ${categoryColor}`;

        // Show spinner, hide content
        document.getElementById('modalLoading').style.display = 'block';
        document.getElementById('modalContent').style.display = 'none';
        document.getElementById('tagsListContainer').style.display = 'none';
        document.getElementById('activitiesListContainer').style.display = 'none';
        document.getElementById('noDataMessage').style.display = 'none';

        activityTagsModal.show();

        fetch(`<?= site_url('dashboard/activity-tags') ?>?date=${date}&category=${category}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalLoading').style.display = 'none';
                document.getElementById('modalContent').style.display = 'block';

                if (!data.success) {
                    document.getElementById('noDataMessage').style.display = 'block';
                    return;
                }

                if (category === 'activities') {
                    const container = document.getElementById('activitiesListContainer');
                    container.innerHTML = '';
                    container.style.display = 'block';

                    if (!data.activities || data.activities.length === 0) {
                        document.getElementById('noDataMessage').style.display = 'block';
                        return;
                    }

                    data.activities.forEach(act => {
                        let tagsHtml = '';
                        if (act.tags && act.tags.length > 0) {
                            tagsHtml = `
                                <div class="mt-2.5">
                                    <strong class="text-secondary small d-block mb-1.5"><i class="bi bi-tags me-1"></i>Linked Tags:</strong>
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle mb-0" style="font-size: 0.8rem;">
                                            <thead>
                                                <tr class="text-secondary">
                                                    <th>Subsidiary</th>
                                                    <th>Tag Name</th>
                                                    <th>Property</th>
                                                    <th>Vendor</th>
                                                    <th>Status</th>
                                                    <th>End Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${act.tags.map(t => `
                                                    <tr>
                                                        <td><span class="badge" style="${getSubsidiaryStyle(t.subsidiary)}">${escapeHtml(t.subsidiary || 'N/A')}</span></td>
                                                        <td><a href="<?= site_url('tags/view') ?>/${t.id}" class="apple-link fw-bold">${escapeHtml(t.name)}</a></td>
                                                        <td>${escapeHtml(t.property_name || 'N/A')}</td>
                                                        <td>${escapeHtml(t.vendor_name || 'N/A')}</td>
                                                        <td><span class="badge ${t.status_class}">${escapeHtml(t.status_label)}</span></td>
                                                        <td>${t.al_end_date ? t.al_end_date : 'N/A'}</td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            `;
                        } else {
                            tagsHtml = `<div class="text-secondary small mt-2"><i class="bi bi-info-circle me-1"></i>No tags directly linked to this activity ticket.</div>`;
                        }

                        let accentColor = '#8E8E93';
                        if (act.priority === 'Medium') accentColor = '#FF9F0A';
                        else if (['High', 'Critical', 'Blocker'].includes(act.priority)) accentColor = '#FF453A';

                        let ticketHtml = act.ticket_id ? `<a href="${act.jira_link}" target="_blank" class="apple-link small fw-bold"><i class="bi bi-link-45deg me-0.5"></i>${escapeHtml(act.ticket_id)}</a>` : '<span class="text-secondary small">No JIRA ticket</span>';

                        const card = document.createElement('div');
                        card.className = 'activity-modal-card';
                        card.style.setProperty('--accent-color', accentColor);
                        card.innerHTML = `
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1" style="font-size: 0.95rem;">${escapeHtml(act.title)}</h6>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        ${ticketHtml}
                                        <span class="badge ${act.status_class}">${escapeHtml(act.status)}</span>
                                        <span class="badge ${act.priority_class}">${escapeHtml(act.priority)} Priority</span>
                                    </div>
                                </div>
                            </div>
                            ${tagsHtml}
                        `;
                        container.appendChild(card);
                    });

                } else {
                    const tbody = document.getElementById('tagsTableBody');
                    tbody.innerHTML = '';
                    document.getElementById('tagsListContainer').style.display = 'block';

                    if (!data.tags || data.tags.length === 0) {
                        document.getElementById('noDataMessage').style.display = 'block';
                        document.getElementById('tagsListContainer').style.display = 'none';
                        return;
                    }

                    data.tags.forEach(t => {
                        let ticketHtml = t.ticket_id ? `<a href="${t.jira_link}" target="_blank" class="apple-link fw-bold"><i class="bi bi-link-45deg"></i>${escapeHtml(t.ticket_id)}</a>` : 'N/A';
                        
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td><span class="badge" style="${getSubsidiaryStyle(t.subsidiary)}">${escapeHtml(t.subsidiary || 'N/A')}</span></td>
                            <td><a href="<?= site_url('tags/view') ?>/${t.id}" class="apple-link fw-bold">${escapeHtml(t.name)}</a></td>
                            <td>${escapeHtml(t.property_name || 'N/A')}</td>
                            <td>${escapeHtml(t.vendor_name || 'N/A')}</td>
                            <td><span class="badge ${t.status_class}">${escapeHtml(t.status_label)}</span></td>
                            <td>${t.al_end_date ? t.al_end_date : 'N/A'}</td>
                            <td>${ticketHtml}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('modalLoading').style.display = 'none';
                document.getElementById('modalContent').style.display = 'block';
                document.getElementById('noDataMessage').style.display = 'block';
            });
    }

    function formatDateString(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function getSubsidiaryStyle(value) {
        if (!value) return '';
        let hash = 0;
        for (let i = 0; i < value.length; i++) {
            hash = value.charCodeAt(i) + ((hash << 5) - hash);
        }
        let hex = Math.abs(hash).toString(16);
        while (hex.length < 6) {
            hex = '0' + hex;
        }
        let color = '#' + hex.substring(0, 6);
        
        let r = parseInt(color.substring(1, 3), 16);
        let g = parseInt(color.substring(3, 5), 16);
        let b = parseInt(color.substring(5, 7), 16);
        let brightness = ((r * 299) + (g * 587) + (b * 114)) / 1000;
        let textColor = brightness > 128 ? '#1d1d1f' : '#ffffff';
        return `background-color: ${color}; color: ${textColor}; font-weight: 600; font-size: 0.75rem;`;
    }
});
</script>

<?= $this->endSection() ?>

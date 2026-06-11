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

/* Stacked Operation Metric Cards */
.operation-metric-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.06);
    border-radius: 12px;
    margin-bottom: 12px;
    text-decoration: none !important;
    transition: all var(--transition-medium, 0.35s cubic-bezier(0.25, 0.8, 0.25, 1));
}

.operation-metric-item:last-child {
    margin-bottom: 0;
}

.operation-metric-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    background: var(--color-table-hover-bg, #F4F9FF);
    border-color: rgba(0, 113, 227, 0.15);
}

.operation-metric-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.operation-metric-icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    font-size: 1.1rem;
}

.operation-metric-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--color-text-base, #1D1D1F);
}

.operation-metric-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--color-text-base, #1D1D1F);
    background: rgba(0, 0, 0, 0.03);
    padding: 2px 10px;
    border-radius: 8px;
    min-width: 36px;
    text-align: center;
    transition: all var(--transition-fast, 0.2s);
}

.operation-metric-item:hover .operation-metric-value {
    background: var(--color-primary, #0071E3);
    color: #ffffff;
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

    <!-- Dashboard Hero metrics & System Health -->
    <div class="row g-4 mb-4">
        <!-- Left Column: System Health Gauge Card -->
        <div class="col-lg-4">
            <?php
            // Calculate health variables for styling based on overall score ranges:
            // >= 90%: Excellent (Green)
            // 85% - 89%: Good (Light Green)
            // 80% - 84%: Fair (Blue)
            // 60% - 79%: Attention (Orange/Amber)
            // < 60%: Critical (Red)
            if ($health_score >= 90) {
                $health_color = '#2E7D32'; 
                $health_text_class = 'text-success';
                $health_label = 'Excellent';
                $health_icon = 'bi-shield-fill-check';
            } elseif ($health_score >= 85) {
                $health_color = '#4CAF50'; 
                $health_text_class = 'text-success';
                $health_label = 'Good';
                $health_icon = 'bi-shield-fill-check';
            } elseif ($health_score >= 80) {
                $health_color = '#0071E3'; 
                $health_text_class = 'text-primary';
                $health_label = 'Fair';
                $health_icon = 'bi-shield-fill';
            } elseif ($health_score >= 60) {
                $health_color = '#F57F17'; 
                $health_text_class = 'text-warning';
                $health_label = 'Attention';
                $health_icon = 'bi-exclamation-triangle-fill';
            } else {
                $health_color = '#C62828'; 
                $health_text_class = 'text-danger';
                $health_label = 'Critical';
                $health_icon = 'bi-shield-fill-x';
            }

            // Build dynamic description and alert styling based on actual threats (decoupled from overall score)
            $critical_total = $critical_active + $critical_pending;
            $attention_total = $attention_active + $attention_pending;

            $health_desc = '';
            $alert_icon = 'bi-info-circle-fill';
            $alert_text_class = 'text-secondary';

            if ($critical_total > 0) {
                if ($critical_active > 0) {
                    $health_desc = 'Urgent action required: ' . $critical_total . ' critical tag' . ($critical_total > 1 ? 's' : '') . ' expiring in < 7 days';
                    if ($critical_pending > 0) {
                        $health_desc .= ' (' . $critical_pending . ' pending verification)';
                    }
                    $health_desc .= '.';
                    $alert_icon = 'bi-shield-fill-x';
                    $alert_text_class = 'text-danger';
                } else {
                    $health_desc = 'Attention needed: ' . $critical_total . ' tag' . ($critical_total > 1 ? 's' : '') . ' expiring in < 7 days (' . $critical_pending . ' pending verification)';
                    $alert_icon = 'bi-exclamation-triangle-fill';
                    $alert_text_class = 'text-warning';
                }
            } elseif ($attention_total > 0) {
                $health_desc = 'Action recommended: ' . $attention_total . ' tag' . ($attention_total > 1 ? 's' : '') . ' ' . ($attention_total == 1 ? 'needs' : 'need') . ' attention';
                if ($attention_pending > 0) {
                    $health_desc .= ' (' . $attention_pending . ' pending verification)';
                }
                $health_desc .= '.';
                $alert_icon = 'bi-info-circle-fill';
                $alert_text_class = 'text-primary';
            } else {
                $health_desc = 'All active tags are currently operational and up to date.';
                $alert_icon = 'bi-shield-fill-check';
                $alert_text_class = 'text-success';
            }
            ?>
            <div class="apple-card h-100 d-flex flex-column justify-content-between p-4 position-relative overflow-hidden" style="min-height: 236px;">
                <!-- Subtle background glow matching status color -->
                <div style="position: absolute; width: 150px; height: 150px; border-radius: 50%; background: <?= $health_color ?>; opacity: 0.03; filter: blur(50px); top: -20px; right: -20px; pointer-events: none;"></div>
                
                <h6 class="text-secondary fw-semibold mb-2 w-100 text-start" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    <i class="bi bi-activity text-primary me-2"></i>System Health Index
                </h6>
                
                <div class="d-flex align-items-center justify-content-between flex-grow-1 w-100 my-2 gap-3" style="min-height: 120px;">
                    <!-- Car Fuel Meter SVG Gauge -->
                    <div class="d-flex align-items-center justify-content-center position-relative flex-shrink-0" style="width: 140px; height: 120px;">
                        <svg width="140" height="115" viewBox="0 0 100 82">
                            <defs>
                                <linearGradient id="fuelGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#FF453A" /> <!-- Red -->
                                    <stop offset="40%" stop-color="#FF9F0A" /> <!-- Orange -->
                                    <stop offset="75%" stop-color="#FFD60A" /> <!-- Yellow -->
                                    <stop offset="100%" stop-color="#30D158" /> <!-- Green -->
                                </linearGradient>
                            </defs>
                            <!-- Background Track -->
                            <path d="M 10 62 A 40 40 0 0 1 90 62" fill="none" stroke="rgba(0, 0, 0, 0.05)" stroke-width="7" stroke-linecap="round" />
                            
                            <!-- Foreground Fuel Fill Arc -->
                            <?php
                            $radius = 40;
                            $circumference = M_PI * $radius; // Semi-circle length = ~125.66
                            $dashoffset = $circumference - ($health_score / 100) * $circumference;
                            ?>
                            <path d="M 10 62 A 40 40 0 0 1 90 62" fill="none" stroke="url(#fuelGradient)" stroke-width="7" stroke-linecap="round"
                                  stroke-dasharray="<?= $circumference ?>" stroke-dashoffset="<?= $dashoffset ?>"
                                  style="transition: stroke-dashoffset 1.2s ease-in-out;" />
                            
                            <!-- E / F Text Labels -->
                            <text x="10" y="74" font-size="8" font-weight="bold" fill="#FF453A" text-anchor="middle">E</text>
                            <text x="90" y="74" font-size="8" font-weight="bold" fill="#30D158" text-anchor="middle">F</text>
                            
                            <!-- Value Label and Status label inside gauge -->
                            <text x="50" y="47" font-size="6.5" font-weight="bold" fill="<?= $health_color ?>" text-anchor="middle" style="letter-spacing: 0.5px; text-transform: uppercase;"><?= $health_label ?></text>
                            <text x="50" y="78" font-size="12" font-weight="800" fill="#1d1d1f" text-anchor="middle"><?= $health_score ?>%</text>
                            
                            <!-- Needle (Points to the current health score) -->
                            <polygon points="49.3,22 50.7,22 52,62 48,62" fill="#FF453A"
                                     style="transform: rotate(<?= ($health_score / 100) * 180 - 90 ?>deg); transform-origin: 50px 62px; transition: transform 1.2s cubic-bezier(0.25, 0.8, 0.25, 1);" />
                            
                            <!-- Center pivot cap -->
                            <circle cx="50" cy="62" r="4.5" fill="#1d1d1f" />
                            <circle cx="50" cy="62" r="1.8" fill="#FF453A" />
                        </svg>
                    </div>

                    <!-- Breakdown details -->
                    <div class="text-start flex-grow-1 ps-2" style="font-size: 0.78rem; border-left: 1px solid rgba(0,0,0,0.06);">
                        <div class="mb-1.5 d-flex justify-content-between align-items-center">
                            <span class="text-secondary"><i class="bi bi-x-circle-fill text-danger me-1"></i>Critical (< 7d):</span>
                            <span class="fw-bold text-dark">
                                <?php if ($critical_pending > 0): ?>
                                    <span class="text-secondary fw-normal" style="font-size: 0.7rem;" title="Pending verification">(<?= $critical_pending ?> pv)</span>
                                <?php endif; ?>
                                <?= $critical_active + $critical_pending ?>
                            </span>
                        </div>
                        <div class="mb-1.5 d-flex justify-content-between align-items-center">
                            <span class="text-secondary"><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>Attention (7-9d):</span>
                            <span class="fw-bold text-dark">
                                <?php if ($attention_pending > 0): ?>
                                    <span class="text-secondary fw-normal" style="font-size: 0.7rem;" title="Pending verification">(<?= $attention_pending ?> pv)</span>
                                <?php endif; ?>
                                <?= $attention_active + $attention_pending ?>
                            </span>
                        </div>
                        <div class="mb-1.5 d-flex justify-content-between align-items-center">
                            <span class="text-secondary"><i class="bi bi-arrow-repeat text-primary me-1"></i>Renewal (10-15d):</span>
                            <span class="fw-bold text-dark"><?= $normal_renewal ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary"><i class="bi bi-check-circle-fill text-success me-1"></i>Safe (> 15d):</span>
                            <span class="fw-bold text-dark"><?= $safe_tags ?></span>
                        </div>
                    </div>
                </div>

                <div class="mt-2 w-100">
                    <p class="text-secondary mb-0 small fw-medium text-start" style="line-height: 1.3; min-height: 36px; display: flex; align-items: center;">
                        <i class="bi <?= $alert_icon ?> me-2 <?= $alert_text_class ?> fs-5"></i>
                        <span><?= $health_desc ?></span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Column: 2x2 Grid of Metric Count Cards -->
        <div class="col-lg-8">
            <div class="row g-3 h-100">
                <!-- Pending Tag Requests -->
                <div class="col-md-6 d-flex flex-column">
                    <div class="apple-metric-card border-start border-4 border-primary flex-grow-1" style="cursor: pointer; min-height: 106px;" onclick="window.location.href='<?= site_url('tag-requests?status=pending') ?>'">
                        <div class="apple-metric-label">
                            <i class="bi bi-file-earmark-plus-fill text-primary fs-5"></i> 
                            <span>Pending Requests</span>
                        </div>
                        <div class="apple-metric-value text-primary"><?= $pending_requests ?></div>
                        <div class="text-secondary small mt-2">Requests in review process</div>
                    </div>
                </div>

                <!-- Expiring Soon -->
                <div class="col-md-6 d-flex flex-column">
                    <div class="apple-metric-card border-start border-4 border-danger flex-grow-1" style="cursor: pointer; min-height: 106px;" onclick="window.location.href='<?= site_url('tags?status=expiring') ?>'">
                        <div class="apple-metric-label">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i> 
                            <span>Expiring Soon</span>
                        </div>
                        <div class="apple-metric-value text-danger"><?= $expiring_tags ?></div>
                        <div class="text-secondary small mt-2">Expiring within 15 days</div>
                    </div>
                </div>

                <!-- Pending Verification -->
                <div class="col-md-6 d-flex flex-column">
                    <div class="apple-metric-card border-start border-4 border-warning flex-grow-1" style="cursor: pointer; min-height: 106px;" onclick="window.location.href='<?= site_url('tags?status=4') ?>'">
                        <div class="apple-metric-label">
                            <i class="bi bi-hourglass-split text-warning fs-5"></i> 
                            <span>Pending Verification</span>
                        </div>
                        <div class="apple-metric-value text-warning"><?= $pending_verification ?></div>
                        <div class="text-secondary small mt-2">Renewed tags awaiting QA</div>
                    </div>
                </div>

                <!-- Active Tags -->
                <div class="col-md-6 d-flex flex-column">
                    <div class="apple-metric-card border-start border-4 border-success flex-grow-1" style="cursor: pointer; min-height: 106px;" onclick="window.location.href='<?= site_url('tags?status=1') ?>'">
                        <div class="apple-metric-label">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i> 
                            <span>Active Tags</span>
                        </div>
                        <div class="apple-metric-value text-success"><?= $active_tags ?></div>
                        <div class="text-secondary small mt-2">Currently active in AL</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Proactive Forecast & Status -->
    <div class="pt-4 pb-2 mt-2 border-top d-flex align-items-start" style="border-top: 1px solid rgba(0, 0, 0, 0.06) !important;">
        <span class="d-inline-flex align-items-center justify-content-center text-primary rounded-circle me-2" style="width: 32px; height: 32px; background: rgba(0, 113, 227, 0.08); flex-shrink: 0;">
            <i class="bi bi-shield-check fs-6"></i>
        </span>
        <div>
            <h5 class="fw-bold mb-0 text-dark">Proactive Forecast & Status</h5>
            <p class="text-secondary small mb-0">Upcoming tag expirations and status distributions</p>
        </div>
    </div>

    <!-- Charts Section: Row 1 (Forecast & Status Distribution) -->
    <div class="row g-4 mb-4">
        <!-- Line Chart: Tag Expiration Forecast (Next 45 Days) -->
        <div class="col-lg-8">
            <div class="apple-card h-100">
                <h6 class="text-secondary fw-semibold mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Expiration Timeline (Next 45 Days)
                </h6>
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="expirationForecastChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tag Operations Queue -->
        <div class="col-lg-4">
            <div class="apple-card h-100 d-flex flex-column">
                <h6 class="text-secondary fw-semibold mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    <i class="bi bi-activity me-2 text-primary"></i>Operations Queue
                </h6>
                
                <div class="flex-grow-1 d-flex flex-column justify-content-center">
                    <!-- Blocked renewal requests -->
                    <a href="<?= site_url('activity-logs?status_group=blocked&type=' . \App\Models\Constants::ACTIVITY_TYPE_RENEWAL) ?>" class="operation-metric-item">
                        <div class="operation-metric-left">
                            <div class="operation-metric-icon-wrapper" style="background-color: var(--color-badge-danger-bg); color: var(--color-badge-danger-text); border: 1px solid var(--color-badge-danger-border);">
                                <i class="bi bi-slash-circle-fill"></i>
                            </div>
                            <span class="operation-metric-label">Blocked Renewals</span>
                        </div>
                        <div class="operation-metric-value"><?= $blocked_renewal_requests ?></div>
                    </a>

                    <!-- Pending Review renewal requests -->
                    <a href="<?= site_url('tags?status=' . \App\Models\Constants::STATUS_PENDING_VERIFICATION) ?>" class="operation-metric-item">
                        <div class="operation-metric-left">
                            <div class="operation-metric-icon-wrapper" style="background-color: var(--color-badge-warning-bg); color: var(--color-badge-warning-text); border: 1px solid var(--color-badge-warning-border);">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <span class="operation-metric-label">Pending Review Renewals</span>
                        </div>
                        <div class="operation-metric-value"><?= $pending_review_renewals ?></div>
                    </a>

                    <!-- Pending new tag requests -->
                    <a href="<?= site_url('tag-requests?status=pending&type=' . \App\Models\Constants::JIRA_TYPE_NEW_TAG) ?>" class="operation-metric-item">
                        <div class="operation-metric-left">
                            <div class="operation-metric-icon-wrapper" style="background-color: var(--color-badge-primary-bg); color: var(--color-badge-primary-text); border: 1px solid var(--color-badge-primary-border);">
                                <i class="bi bi-tag-fill"></i>
                            </div>
                            <span class="operation-metric-label">Pending New Tags</span>
                        </div>
                        <div class="operation-metric-value"><?= $pending_new_tag_requests ?></div>
                    </a>

                    <!-- Pending New Rule requests -->
                    <a href="<?= site_url('tag-requests?status=pending&type=' . \App\Models\Constants::JIRA_TYPE_NEW_RULE) ?>" class="operation-metric-item">
                        <div class="operation-metric-left">
                            <div class="operation-metric-icon-wrapper" style="background-color: var(--color-badge-purple-bg); color: var(--color-badge-purple-text); border: 1px solid var(--color-badge-purple-border);">
                                <i class="bi bi-gear-fill"></i>
                            </div>
                            <span class="operation-metric-label">Pending New Rules</span>
                        </div>
                        <div class="operation-metric-value"><?= $pending_new_rule_requests ?></div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: System Performance & Operations -->
    <div class="pt-4 pb-2 mt-5 border-top d-flex align-items-start" style="border-top: 1px solid rgba(0, 0, 0, 0.06) !important;">
        <span class="d-inline-flex align-items-center justify-content-center text-primary rounded-circle me-2" style="width: 32px; height: 32px; background: rgba(0, 113, 227, 0.08); flex-shrink: 0;">
            <i class="bi bi-activity fs-6"></i>
        </span>
        <div>
            <h5 class="fw-bold mb-0 text-dark">System Ingestion & Requests</h5>
            <p class="text-secondary small mb-0">Ingestion trends and active requests pipeline</p>
        </div>
    </div>

    <!-- Charts Section: Row 2 (System Ingestion & Pipeline) -->
    <div class="row g-4 mb-4">
        <!-- Line Chart: System Activity & Ingestion Trend -->
        <div class="col-lg-8">
            <div class="apple-card h-100">
                <h6 class="text-secondary fw-semibold mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    <i class="bi bi-graph-up me-2 text-primary"></i>Ingestion Trend (Last 30 Days)
                </h6>
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="activityTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart: Tag Requests Status Distribution -->
        <div class="col-lg-4">
            <div class="apple-card h-100">
                <h6 class="text-secondary fw-semibold mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    <i class="bi bi-funnel me-2 text-primary"></i>Requests Pipeline
                </h6>
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="requestStatusChart"></canvas>
                </div>
                <div class="text-center mt-3 text-secondary small">
                    Total Requests: <strong><?= $total_requests ?></strong> requests
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Inventory Analysis -->
    <div class="pt-4 pb-2 mt-5 border-top d-flex align-items-start" style="border-top: 1px solid rgba(0, 0, 0, 0.06) !important;">
        <span class="d-inline-flex align-items-center justify-content-center text-primary rounded-circle me-2" style="width: 32px; height: 32px; background: rgba(0, 113, 227, 0.08); flex-shrink: 0;">
            <i class="bi bi-pie-chart-fill fs-6"></i>
        </span>
        <div>
            <h5 class="fw-bold mb-0 text-dark">Inventory Analytics</h5>
            <p class="text-secondary small mb-0">Distribution of tags by subsidiary and vendor</p>
        </div>
    </div>

    <!-- Charts Section: Row 3 (Subsidiaries & Vendors) -->
    <div class="row g-4 mb-4">
        <!-- Bar Chart: Top Subsidiaries -->
        <div class="col-md-6">
            <div class="apple-card h-100">
                <h6 class="text-secondary fw-semibold mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    <i class="bi bi-building me-2 text-primary"></i>Tags by Subsidiary
                </h6>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="subsidiaryDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Chart: Top Vendors -->
        <div class="col-md-6">
            <div class="apple-card h-100">
                <h6 class="text-secondary fw-semibold mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    <i class="bi bi-shop me-2 text-primary"></i>Top 10 Vendors
                </h6>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="vendorDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Activity Logs & Support Analytics -->
    <div class="pt-4 pb-2 mt-5 border-top d-flex align-items-start" style="border-top: 1px solid rgba(0, 0, 0, 0.06) !important;">
        <span class="d-inline-flex align-items-center justify-content-center text-primary rounded-circle me-2" style="width: 32px; height: 32px; background: rgba(0, 113, 227, 0.08); flex-shrink: 0;">
            <i class="bi bi-ticket-detailed fs-6"></i>
        </span>
        <div>
            <h5 class="fw-bold mb-0 text-dark">Activity Logs Analytics</h5>
            <p class="text-secondary small mb-0">Breakdown of support tickets, tasks, and system activities</p>
        </div>
    </div>

    <!-- Charts Section: Row 4 (Activity Logs Analytics) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="apple-card">
                <div class="row g-4">
                    <!-- Doughnut Chart: Activity Logs by Status -->
                    <div class="col-md-4">
                        <h6 class="text-secondary text-center fw-semibold mb-3" style="font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            Activities by Status
                        </h6>
                        <div style="position: relative; height: 240px; width: 100%;">
                            <canvas id="activityStatusChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Bar Chart: Activity Logs by Priority -->
                    <div class="col-md-4">
                        <h6 class="text-secondary text-center fw-semibold mb-3" style="font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            Activities by Priority
                        </h6>
                        <div style="position: relative; height: 240px; width: 100%;">
                            <canvas id="activityPriorityChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Bar Chart: Activity Logs by Team -->
                    <div class="col-md-4">
                        <h6 class="text-secondary text-center fw-semibold mb-3" style="font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            Activities by Team
                        </h6>
                        <div style="position: relative; height: 240px; width: 100%;">
                            <canvas id="activityTeamChart"></canvas>
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

    // --- 9. Bar Chart: Activity Logs by Team ---
    const activityTeamCtx = document.getElementById('activityTeamChart').getContext('2d');
    const activityTeamData = <?= json_encode($activity_team_distribution) ?>;
    new Chart(activityTeamCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(activityTeamData),
            datasets: [{
                label: 'Tickets',
                data: Object.values(activityTeamData),
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

                        let ticketHtml = act.ticket_id ? `<a href="${act.jira_link}" target="_blank" class="apple-link small fw-bold"><i class="bi bi-link-45deg me-1"></i>${escapeHtml(act.ticket_id)}</a>` : '<span class="text-secondary small">No JIRA ticket</span>';

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

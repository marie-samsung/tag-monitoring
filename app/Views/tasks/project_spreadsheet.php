<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Project: <?= esc($project_name) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
use App\Models\Constants;
helper('custom_functions');
?>

<?= csrf_field() ?>

<!-- Custom Spreadsheet & Status Select Badge Styling -->
<style>
    .spreadsheet-container {
        background-color: #ffffff;
        border: 1px solid #d1d1d6;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .spreadsheet-header-bar {
        background-color: #f5f5f7;
        border-bottom: 1px solid #d1d1d6;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .spreadsheet-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
        color: #1d1d1f;
    }

    /* Excel-like Grid Styling */
    .spreadsheet-table th,
    .spreadsheet-table td {
        border: 1px solid #e5e5ea;
        padding: 8px 12px;
        vertical-align: middle;
    }

    /* Column letters header row */
    .col-letters-row {
        background-color: #f5f5f7;
        text-align: center;
        font-weight: 600;
        color: #86868b;
        font-size: 0.7rem;
        user-select: none;
        height: 20px;
        padding: 2px !important;
    }

    /* Table columns header row */
    .col-headers-row {
        background-color: #f5f5f7;
        font-weight: 600;
        color: #1d1d1f;
        text-align: left;
        border-bottom: 2px solid #d1d1d6;
    }

    .col-headers-row th {
        position: relative;
        background-color: #f5f5f7;
    }

    /* Spreadsheet row number index column */
    .row-index-col {
        background-color: #f5f5f7;
        text-align: center;
        font-weight: 600;
        color: #86868b;
        width: 45px;
        user-select: none;
        border-right: 2px solid #d1d1d6 !important;
    }

    .spreadsheet-table tbody tr:hover {
        background-color: #f5f5f7 !important;
    }

    .spreadsheet-table tbody tr:nth-child(even) {
        background-color: #fafafc;
    }

    .comment-cell {
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        position: relative;
        cursor: pointer;
    }

    .comment-cell:hover {
        color: var(--color-primary);
    }

    .search-input-container {
        position: relative;
        max-width: 320px;
        width: 100%;
    }

    .search-input-container i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #86868b;
    }

    .search-input-container input {
        padding-left: 32px !important;
        border-radius: 20px;
        height: 36px;
        border: 1px solid #d1d1d6;
        width: 100%;
    }

    .search-input-container input:focus {
        outline: none;
        border-color: #0071E3;
        box-shadow: 0 0 0 3px rgba(0, 113, 227, 0.15);
    }

    .sheet-tab {
        background-color: #f5f5f7;
        border: 1px solid #d1d1d6;
        border-bottom: none;
        border-radius: 8px 8px 0 0;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.8rem;
        color: #86868b;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
        transition: background-color 0.2s, color 0.2s;
        margin-bottom: -1px;
        position: relative;
        z-index: 1;
    }

    .sheet-tab:hover {
        background-color: #e5e5ea;
        color: #1d1d1f;
    }

    .sheet-tab.active {
        background-color: #ffffff;
        color: #0071E3;
        border-bottom: 1px solid #ffffff !important;
        z-index: 2;
    }

    .sheet-tab-bar {
        border-bottom: 1px solid #d1d1d6;
        margin-top: 1.5rem;
        padding-left: 20px;
        display: flex;
        align-items: flex-end;
    }

    /* Badge styled select dropdowns (for AJAX inline updates) */
    .status-select-badge {
        display: inline-block;
        padding: 0.3em 1.6em 0.3em 0.6em !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        line-height: 1.25 !important;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        border-radius: 6px !important;
        cursor: pointer;
        width: auto !important;
        height: auto !important;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-repeat: no-repeat;
        background-position: right 0.45rem center;
        background-size: 8px 10px;
        transition: background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease, box-shadow 0.25s ease;
    }

    .status-select-badge:focus {
        outline: none;
        box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.25) !important;
    }

    /* Dropdown Arrow Icons */
    .status-select-badge.bg-success {
        background-color: var(--color-badge-success-bg) !important;
        color: var(--color-badge-success-text) !important;
        border: 1px solid var(--color-badge-success-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%232E7D32' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-danger {
        background-color: var(--color-badge-danger-bg) !important;
        color: var(--color-badge-danger-text) !important;
        border: 1px solid var(--color-badge-danger-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23C62828' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-warning {
        background-color: var(--color-badge-warning-bg) !important;
        color: var(--color-badge-warning-text) !important;
        border: 1px solid var(--color-badge-warning-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23F57F17' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-secondary {
        background-color: var(--color-badge-secondary-bg) !important;
        color: var(--color-badge-secondary-text) !important;
        border: 1px solid var(--color-badge-secondary-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2349494D' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-info {
        background-color: var(--color-badge-primary-bg) !important;
        color: var(--color-badge-primary-text) !important;
        border: 1px solid var(--color-badge-primary-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%231565C0' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    /* Specific theme styles for Assignee users */
    .status-select-badge.bg-purple {
        background-color: var(--color-badge-purple-bg) !important;
        color: var(--color-badge-purple-text) !important;
        border: 1px solid var(--color-badge-purple-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%237B1FA2' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-pink {
        background-color: var(--color-badge-pink-bg) !important;
        color: var(--color-badge-pink-text) !important;
        border: 1px solid var(--color-badge-pink-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23C2185B' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-green {
        background-color: var(--color-badge-green-bg) !important;
        color: var(--color-badge-green-text) !important;
        border: 1px solid var(--color-badge-green-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%231B5E20' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-blue {
        background-color: var(--color-badge-blue-bg) !important;
        color: var(--color-badge-blue-text) !important;
        border: 1px solid var(--color-badge-blue-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%230071E3' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    .status-select-badge.bg-orange {
        background-color: var(--color-badge-orange-bg) !important;
        color: var(--color-badge-orange-text) !important;
        border: 1px solid var(--color-badge-orange-border) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23E65100' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    /* Ticket ID Cell Hover & Inline Edit styling */
    .ticket-cell {
        position: relative;
        cursor: pointer;
        min-width: 140px;
    }

    .ticket-cell:hover {
        background-color: rgba(0, 113, 227, 0.03) !important;
    }

    .edit-ticket-btn {
        opacity: 0;
        transition: opacity 0.2s ease, color 0.2s ease;
        background: none;
        border: none;
        line-height: 1;
    }

    .ticket-cell:hover .edit-ticket-btn {
        opacity: 0.6;
    }

    .edit-ticket-btn:hover {
        opacity: 1 !important;
        color: var(--color-primary) !important;
    }
</style>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= site_url('tasks') ?>"
                class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
                style="width: 36px; height: 36px; padding: 0; display: inline-flex;" title="Back to Tasks">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h2 class="mb-0 fw-bold">Project: <?= esc($project_name) ?></h2>
                <p class="text-secondary small mb-0">Spreadsheet-style task board listing all logs and latest updates
                    for this project.</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= site_url('tasks/project/' . rawurlencode($project_name)) . (isset($me_mode) && $me_mode ? '?me_mode=0' : '?me_mode=1') ?>"
                class="<?= isset($me_mode) && $me_mode ? 'apple-btn-primary' : 'apple-btn-secondary' ?> btn-sm px-3 text-decoration-none d-inline-flex align-items-center gap-1.5 py-2"
                style="border-radius: 8px;">
                <i class="bi <?= isset($me_mode) && $me_mode ? 'bi-person-fill' : 'bi-person' ?> me-1"></i> Assigned To
                Me
            </a>
            <a href="<?= site_url('tasks/create?project=' . rawurlencode($project_name)) ?>"
                title="Create a new task for this project"
                class="apple-btn-primary btn-sm px-3 text-decoration-none d-inline-flex align-items-center gap-1.5 py-2"
                style="border-radius: 8px;">
                <i class="bi bi-plus-lg me-1"></i> Add Task
            </a>
        </div>
    </div>

    <!-- Sheet Tab Bar -->
    <div class="sheet-tab-bar overflow-x-auto gap-1" style="white-space: nowrap; -webkit-overflow-scrolling: touch;">
        <?php foreach ($project_names as $p): ?>
            <?php
            $pName = !empty($p) ? $p : 'Generic';
            $isActive = ($pName === $project_name);
            ?>
            <a href="<?= site_url('tasks/project/' . rawurlencode($pName)) ?>"
                class="sheet-tab <?= $isActive ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-spreadsheet-fill <?= $isActive ? 'text-success' : 'text-secondary' ?>"></i>
                <span><?= esc($pName) ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Spreadsheet Card -->
    <div class="spreadsheet-container">
        <!-- Header Controls -->
        <div class="spreadsheet-header-bar">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-success text-white px-2.5 py-1.5" style="border-radius: 6px; font-weight: 600;">
                    <i class="bi bi-grid-3x3-gap-fill me-1"></i>
                    <?= isset($pager) ? $pager->getTotal() : count($tasks) ?> Rows
                </span>
                <a href="<?= site_url('tasks/project-export/excel/' . rawurlencode($project_name)) ?>"
                    class="apple-btn-secondary btn-sm d-inline-flex align-items-center gap-1.5 py-1 px-2.5"
                    style="font-size: 0.8rem; border-radius: 6px; height: 28px; text-decoration: none;"
                    title="Export Excel">
                    <i class="bi bi-file-earmark-excel-fill text-success"></i>
                    <span>Export Excel</span>
                </a>
                <a href="<?= site_url('tasks/project-export/pdf/' . rawurlencode($project_name)) ?>" target="_blank"
                    class="apple-btn-secondary btn-sm d-inline-flex align-items-center gap-1.5 py-1 px-2.5"
                    style="font-size: 0.8rem; border-radius: 6px; height: 28px; text-decoration: none;"
                    title="Export PDF">
                    <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                    <span>Export PDF</span>
                </a>
            </div>

            <!-- Quick Client-side Search -->
            <div class="search-input-container">
                <i class="bi bi-search"></i>
                <input type="text" id="spreadsheetSearch" placeholder="Search rows...">
            </div>
        </div>

        <!-- Spreadsheet Grid Table -->
        <div class="table-responsive">
            <table class="spreadsheet-table" id="projectSheetTable">
                <thead>
                    <!-- Excel-style Column Letters -->
                    <tr class="col-letters-row">
                        <th class="row-index-col"></th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>F</th>
                        <th>G</th>
                        <th>H</th>
                    </tr>

                    <!-- Table Header titles -->
                    <tr class="col-headers-row">
                        <th class="row-index-col" style="border-bottom: 2px solid #d1d1d6 !important;"></th>
                        <th style="width: 120px;">Subsidiary</th>
                        <th>Task Title</th>
                        <th style="width: 140px;">Ticket ID</th>
                        <th style="width: 110px;">Priority</th>
                        <th style="width: 150px;">Assignee</th>
                        <th style="width: 120px;">Start Date</th>
                        <th style="width: 180px;">Status</th>
                        <th>Latest Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tasks)): ?>
                        <tr>
                            <td class="row-index-col">1</td>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 mb-2 d-block text-secondary" style="opacity: 0.5;"></i>
                                <span class="fw-semibold">No tasks found for this project</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $currentPage = isset($pager) ? $pager->getCurrentPage() : 1;
                        $perPage = 25;
                        $idx = ($currentPage - 1) * $perPage + 1;
                        foreach ($tasks as $task):
                            $subsidiary = Constants::ACTIVITY_LOG_SUBSIDIARIES[$task['subsidiary_id']] ?? 'Unknown';
                            $priority = Constants::PRIORITIES[$task['priority']] ?? 'Unknown';
                            $assignee = Constants::USERS[$task['assignee']] ?? Constants::USER_SYSTEM_LABEL;
                            $startDate = $task['start_date'] ? date('d-m-Y', strtotime($task['start_date'])) : '-';
                            ?>
                            <tr class="task-row">
                                <td class="row-index-col"><?= $idx++ ?></td>
                                <td>
                                    <span class="badge" style="<?= random_bg_style($subsidiary) ?>">
                                        <?= esc($subsidiary) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= site_url('tasks/view/' . $task['id']) ?>"
                                        class="apple-link-dark fw-semibold text-decoration-none">
                                        <?= esc($task['title']) ?>
                                    </a>
                                </td>
                                <td class="ticket-cell" data-log-id="<?= $task['id'] ?>">
                                    <div class="ticket-display d-flex align-items-center justify-content-between">
                                        <span class="ticket-link-wrapper">
                                            <?php if (!empty($task['ticket_id'])): ?>
                                                <a href="<?= Constants::JIRA_LINK . $task['ticket_id'] ?>" target="_blank"
                                                    class="apple-link fw-bold ticket-value">
                                                    <?= esc($task['ticket_id']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-secondary small ticket-value">—</span>
                                            <?php endif; ?>
                                        </span>
                                        <button class="btn btn-link p-0 edit-ticket-btn text-muted" title="Edit Ticket ID">
                                            <i class="bi bi-pencil-fill" style="font-size: 0.7rem;"></i>
                                        </button>
                                    </div>
                                    <div class="ticket-edit-wrapper d-none">
                                        <input type="text" class="form-control form-control-xs ticket-input"
                                            value="<?= esc($task['ticket_id'] ?? '') ?>"
                                            style="font-size: 0.8rem; padding: 2px 6px; height: 26px; border-radius: 4px; width: 100%;">
                                    </div>
                                </td>
                                <td>
                                    <span class="badge <?= priority_class($task['priority']) ?>">
                                        <?= esc($priority) ?>
                                    </span>
                                </td>
                                <td>
                                    <select
                                        class="badge status-select-badge <?= assignee_class($task['assignee']) ?> assignee-select"
                                        data-log-id="<?= $task['id'] ?>">
                                        <?php foreach (Constants::USERS as $val => $lbl): ?>
                                            <option value="<?= $val ?>" <?= $task['assignee'] == $val ? 'selected' : '' ?>
                                                class="bg-white text-dark text-start">
                                                <?= esc($lbl) ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option value="<?= Constants::USER_SYSTEM ?>"
                                            <?= $task['assignee'] == Constants::USER_SYSTEM ? 'selected' : '' ?>
                                            class="bg-white text-dark text-start">
                                            <?= esc(Constants::USER_SYSTEM_LABEL) ?>
                                        </option>
                                    </select>
                                </td>
                                <td class="text-secondary"><?= esc($startDate) ?></td>
                                <td>
                                    <!-- Dynamic AJAX status select badge -->
                                    <select
                                        class="badge status-select-badge <?= status_activity_logs_class($task['status']) ?> status-select"
                                        data-log-id="<?= $task['id'] ?>">
                                        <?php foreach (Constants::ACTIVITY_LOGS_STATUS as $val => $lbl): ?>
                                            <option value="<?= $val ?>" <?= $task['status'] == $val ? 'selected' : '' ?>
                                                class="bg-white text-dark text-start">
                                                <?= esc($lbl) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="comment-cell"
                                    title="<?= esc(strip_tags($task['last_comment'] ?? 'No comments posted yet.')) ?>"
                                    data-bs-toggle="tooltip" data-bs-placement="left">
                                    <?= esc(strip_tags($task['last_comment'] ?? '') ?: '—') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
        <div class="apple-pagination-container mt-2 mb-4">
            <?= $pager->links('default', 'apple_full') ?>
        </div>
    <?php endif; ?>
</div>

<!-- Custom Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
    <div id="toastNotification" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true"
        style="display: none; min-width: 280px; border-radius: 12px; backdrop-filter: blur(10px); box-shadow: 0 8px 32px rgba(0,0,0,0.15); transition: opacity 0.3s ease;">
        <div class="d-flex p-3 align-items-center">
            <div class="me-2" id="toastIcon">
                <!-- Icon will be set dynamically -->
            </div>
            <div class="toast-body flex-grow-1 fw-medium" id="toastMessage" style="font-size: 0.9rem;">
                <!-- Message will be set dynamically -->
            </div>
            <button type="button" class="btn-close ms-auto" style="box-shadow: none; opacity: 0.6;"
                onclick="$('#toastNotification').fadeOut(300);"></button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Enable Bootstrap tooltips for comment cells
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Reusable Premium Toast Notification function
        function showToast(type, message) {
            const toast = $('#toastNotification');
            const messageEl = $('#toastMessage');
            const iconEl = $('#toastIcon');

            messageEl.text(message);

            toast.removeClass('bg-success bg-danger bg-warning text-dark text-white border-0');

            if (type === 'success') {
                toast.css({
                    'background-color': 'var(--color-alert-success-bg)',
                    'color': 'var(--color-alert-success-text)',
                    'border': '1px solid rgba(29, 138, 68, 0.2)'
                });
                iconEl.html('<i class="bi bi-check-circle-fill fs-5" style="color: var(--color-alert-success-text);"></i>');
            } else if (type === 'error') {
                toast.css({
                    'background-color': 'var(--color-alert-danger-bg)',
                    'color': 'var(--color-alert-danger-text)',
                    'border': '1px solid rgba(222, 53, 53, 0.2)'
                });
                iconEl.html('<i class="bi bi-exclamation-triangle-fill fs-5" style="color: var(--color-alert-danger-text);"></i>');
            } else {
                toast.css({
                    'background-color': 'var(--color-alert-warning-bg)',
                    'color': 'var(--color-alert-warning-text)',
                    'border': '1px solid rgba(180, 107, 0, 0.2)'
                });
                iconEl.html('<i class="bi bi-exclamation-circle-fill fs-5" style="color: var(--color-alert-warning-text);"></i>');
            }

            toast.fadeIn(300);

            if (window.toastTimeout) {
                clearTimeout(window.toastTimeout);
            }
            window.toastTimeout = setTimeout(function () {
                toast.fadeOut(300);
            }, 4000);
        }

        // AJAX Inline Updates (Status, Assignee)
        let previousValue = '';

        $(document).on('focus', '.status-select, .assignee-select', function () {
            previousValue = $(this).val();
        }).on('change', '.status-select, .assignee-select', function () {
            const dropdown = $(this);
            const logId = dropdown.data('log-id');
            const newValue = dropdown.val();
            const csrfToken = $('input[name="csrf_test_name"]').val();

            let url = '';
            let postData = { csrf_test_name: csrfToken };

            if (dropdown.hasClass('assignee-select')) {
                url = '<?= site_url("tasks/update-assignee") ?>/' + logId;
                postData.assignee = newValue;
            } else {
                url = '<?= site_url("tasks/update-status") ?>/' + logId;
                postData.status = newValue;
            }

            dropdown.css('opacity', '0.6');

            $.ajax({
                url: url,
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function (response) {
                    dropdown.css('opacity', '1');
                    if (response.success) {
                        // Update classes
                        dropdown.removeClass('bg-secondary bg-warning bg-info bg-success bg-danger bg-purple bg-pink bg-green bg-blue bg-orange text-white text-dark');
                        dropdown.addClass(response.status_class);
                        showToast('success', response.message);
                        previousValue = newValue;
                    } else {
                        dropdown.val(previousValue);
                        showToast('error', response.message || 'Failed to update.');
                    }

                    if (response.csrf_hash) {
                        $('input[name="csrf_test_name"]').val(response.csrf_hash);
                    }
                },
                error: function (xhr, status, error) {
                    dropdown.css('opacity', '1');
                    dropdown.val(previousValue);
                    showToast('error', 'An error occurred while updating.');
                }
            });
        });

        // Inline Ticket ID Editing
        $(document).on('click', '.edit-ticket-btn', function (e) {
            e.stopPropagation();
            const cell = $(this).closest('.ticket-cell');
            startTicketEdit(cell);
        });

        $(document).on('dblclick', '.ticket-cell', function (e) {
            if ($(e.target).closest('a, button, input').length) return;
            startTicketEdit($(this));
        });

        function startTicketEdit(cell) {
            const display = cell.find('.ticket-display');
            const editWrapper = cell.find('.ticket-edit-wrapper');
            const input = editWrapper.find('.ticket-input');

            display.addClass('d-none');
            editWrapper.removeClass('d-none');

            // Save current value
            input.data('old-val', input.val());

            input.focus().select();
        }

        $(document).on('keydown', '.ticket-input', function (e) {
            const input = $(this);
            const cell = input.closest('.ticket-cell');

            if (e.key === 'Enter') {
                e.preventDefault();
                input.blur(); // Triggers blur which calls saveTicketEdit
            } else if (e.key === 'Escape') {
                e.preventDefault();
                cancelTicketEdit(cell);
            }
        });

        $(document).on('blur', '.ticket-input', function () {
            const input = $(this);
            const cell = input.closest('.ticket-cell');
            // Check if input is disabled (means already saving) or not currently visible
            if (!input.prop('disabled') && !input.closest('.ticket-edit-wrapper').hasClass('d-none')) {
                saveTicketEdit(cell);
            }
        });

        function cancelTicketEdit(cell) {
            const display = cell.find('.ticket-display');
            const editWrapper = cell.find('.ticket-edit-wrapper');
            const input = editWrapper.find('.ticket-input');

            input.val(input.data('old-val'));
            editWrapper.addClass('d-none');
            display.removeClass('d-none');
        }

        function saveTicketEdit(cell) {
            const display = cell.find('.ticket-display');
            const editWrapper = cell.find('.ticket-edit-wrapper');
            const input = editWrapper.find('.ticket-input');
            const logId = cell.data('log-id');
            const newValue = input.val().trim();
            const oldValue = input.data('old-val');

            if (newValue === oldValue) {
                editWrapper.addClass('d-none');
                display.removeClass('d-none');
                return;
            }

            input.prop('disabled', true);
            input.css('opacity', '0.6');

            const csrfToken = $('input[name="csrf_test_name"]').val();
            const url = '<?= site_url("tasks/update-ticket-id") ?>/' + logId;

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    ticket_id: newValue,
                    csrf_test_name: csrfToken
                },
                dataType: 'json',
                success: function (response) {
                    input.prop('disabled', false);
                    input.css('opacity', '1');

                    if (response.success) {
                        input.val(newValue);
                        input.data('old-val', newValue);

                        // Update display
                        const linkWrapper = cell.find('.ticket-link-wrapper');
                        if (newValue !== '') {
                            linkWrapper.html(
                                `<a href="${response.jira_link}" target="_blank" class="apple-link fw-bold ticket-value">${newValue}</a>`
                            );
                        } else {
                            linkWrapper.html('<span class="text-secondary small ticket-value">—</span>');
                        }

                        showToast('success', response.message);
                    } else {
                        input.val(oldValue);
                        showToast('error', response.message || 'Failed to update.');
                    }

                    if (response.csrf_hash) {
                        $('input[name="csrf_test_name"]').val(response.csrf_hash);
                    }

                    editWrapper.addClass('d-none');
                    display.removeClass('d-none');
                },
                error: function () {
                    input.prop('disabled', false);
                    input.css('opacity', '1');
                    input.val(oldValue);
                    showToast('error', 'An error occurred while updating Ticket ID.');

                    editWrapper.addClass('d-none');
                    display.removeClass('d-none');
                }
            });
        }

        // Realtime search filtering
        const searchInput = document.getElementById('spreadsheetSearch');
        const tableRows = document.querySelectorAll('#projectSheetTable tbody tr.task-row');

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase().trim();

                tableRows.forEach(row => {
                    let textParts = [];
                    row.querySelectorAll('td').forEach(td => {
                        // For the row index column, skip it so row numbers don't affect search
                        if (td.classList.contains('row-index-col')) return;

                        const select = td.querySelector('select');
                        if (select) {
                            const selectedOption = select.options[select.selectedIndex];
                            if (selectedOption) {
                                textParts.push(selectedOption.textContent);
                            }
                        } else {
                            textParts.push(td.textContent);
                        }
                    });
                    const searchText = textParts.join(' ').toLowerCase();

                    if (searchText.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Adjust row index numbers dynamically for visible rows
                const currentPage = <?= isset($pager) ? $pager->getCurrentPage() : 1 ?>;
                const perPage = 25;
                let visibleIndex = (currentPage - 1) * perPage + 1;
                tableRows.forEach(row => {
                    if (row.style.display !== 'none') {
                        row.querySelector('.row-index-col').textContent = visibleIndex++;
                    }
                });
            });
        }

        // Modal view details on comment cell double click
        const commentCells = document.querySelectorAll('.comment-cell');
        commentCells.forEach(cell => {
            cell.addEventListener('click', function () {
                const text = this.getAttribute('title');
                if (text && text !== 'No comments posted yet.' && text !== '—') {
                    alert("Latest Update:\n\n" + text);
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
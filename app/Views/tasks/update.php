<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $log['title'] ?> : Update
<?= $this->endSection() ?>

<?= $this->section('content') ?>



<?php
    use App\Models\Constants;
    $sysSettings = \App\Controllers\SettingsController::getSystemSettings();
    $hideAssigneeSystemWide = $sysSettings['hide_assignee_system_wide'] ?? false;

    $tagsModelInst = new \App\Models\TagsModel();
    $tagReqModelInst = new \App\Models\TagRequestsModel();
    $tasksModelInst = new \App\Models\TasksModel();

    $names1 = array_column($tagsModelInst->select('requester_name')->distinct()->where('requester_name !=', '')->where('requester_name IS NOT NULL')->findAll(), 'requester_name');
    $names2 = array_column($tagReqModelInst->select('requester_name')->distinct()->where('requester_name !=', '')->where('requester_name IS NOT NULL')->findAll(), 'requester_name');
    $names3 = array_column($tasksModelInst->select('requested_by')->distinct()->where('requested_by !=', '')->where('requested_by IS NOT NULL')->findAll(), 'requested_by');
    $requesterNamesList = array_unique(array_merge($names1, $names2, $names3));
    sort($requesterNamesList);
?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tasks') ?>" onclick="if (document.referrer) { window.history.back(); return false; }" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0; display: inline-flex;" title="Back to Tasks">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Update Task</h2>
            <p class="text-secondary small mb-0"><?= esc($log['title']) ?></p>
        </div>
    </div>

    <?php if (isset($validation)): ?>
        <div class="alert alert-danger mb-4">
            <?= $validation->listErrors() ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url("tasks/update/{$log['id']}") ?>" method="post">
        <?= csrf_field() ?>

        <div class="row g-4">
            <!-- Left Column: Title, Description & Requester/Campaign Details -->
            <div class="col-lg-8 col-md-7">
                <!-- Card 1: Title & Description -->
                <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                    <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold" style="font-size: 1rem;">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span>General Information</span>
                    </h5>

                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="form-label-sm fw-semibold text-secondary mb-1">Title</label>
                        <input type="text" name="title" id="title"
                            class="form-control fw-semibold"
                            style="font-size: 1.15rem; border-radius: 8px;"
                            value="<?= esc($log['title']) ?>"
                            placeholder="Enter a descriptive title..."
                            required>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label-sm fw-semibold text-secondary mb-1">Description</label>
                        <textarea name="description" id="description"
                            class="form-control"
                            rows="12"><?= esc($log['description']) ?></textarea>
                    </div>

                    <!-- Labels -->
                    <div class="mb-4">
                        <label class="form-label-sm fw-semibold text-secondary mb-1">Labels / Tags</label>
                        <select name="labels[]" class="form-select select2-multiple" multiple="multiple" data-placeholder="Select or type to create new labels" style="width: 100%;">
                            <?php 
                            $selectedLabels = [];
                            if (!empty($log['labels'])) {
                                $selectedLabels = array_filter(array_map('trim', explode(',', $log['labels'])));
                            }
                            $allLabels = $all_labels ?? [];
                            $allOptions = array_unique(array_merge($allLabels, $selectedLabels));
                            sort($allOptions);

                            foreach ($allOptions as $lbl): 
                                if (empty(trim($lbl))) continue;
                            ?>
                                <option value="<?= esc($lbl) ?>" <?= in_array($lbl, $selectedLabels) ? 'selected' : '' ?>>
                                    <?= esc($lbl) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text mt-1 text-muted" style="font-size: 0.75rem;">
                            Select existing labels or type to create new ones. Labels must not contain spaces.
                        </div>
                    </div>
                </div>

                <!-- Card 2: Requester & Campaign details (Horizontal Grid) -->
                <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                    <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold" style="font-size: 1rem;">
                        <i class="bi bi-person-fill"></i>
                        <span>Requester & Campaign</span>
                    </h5>
                    
                    <div class="row g-3">
                        <!-- Requested By -->
                        <div class="col-md-4">
                            <label for="requested_by" class="form-label-sm fw-semibold text-secondary mb-1">Requested By</label>
                            <input type="text" name="requested_by" id="requested_by"
                                class="form-control form-control-xs"
                                list="requested-by-list"
                                value="<?= old('requested_by', $log['requested_by'] ?? '') ?>">
                            <datalist id="requested-by-list">
                                <?php foreach ($requesterNamesList as $name): ?>
                                    <option value="<?= esc($name) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <!-- Requested Date -->
                        <div class="col-md-4">
                            <label for="requested_date" class="form-label-sm fw-semibold text-secondary mb-1">Requested Date</label>
                            <div class="position-relative">
                                <input type="text" name="requested_date" id="requested_date"
                                    class="form-control form-control-xs pe-5"
                                    value="<?= !empty($log['requested_date']) ? date('Y-m-d', strtotime($log['requested_date'])) : '' ?>">
                                <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div class="col-md-4">
                            <label for="start_date" class="form-label-sm fw-semibold text-secondary mb-1">Start Date</label>
                            <div class="position-relative">
                                <input type="text" name="start_date" id="start_date"
                                       class="form-control form-control-xs pe-5"
                                       value="<?= !empty($log['start_date']) ? date('Y-m-d', strtotime($log['start_date'])) : '' ?>">
                                <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="col-md-4">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Department</label>
                            <select name="department_id" id="department-select" class="form-select form-select-xs">
                                <option value="">-Select-</option>
                                <?php 
                                    $selectedDepartment = old('department_id', $log['department_id'] ?? '');
                                    if (!empty($selectedDepartment) && !in_array($selectedDepartment, $department_names)): 
                                ?>
                                    <option value="<?= esc($selectedDepartment) ?>" selected><?= esc($selectedDepartment) ?></option>
                                <?php endif; ?>
                                <?php foreach ($department_names as $dName): ?>
                                    <option value="<?= esc($dName) ?>" <?= $selectedDepartment === $dName ? 'selected' : '' ?>>
                                        <?= esc($dName) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Campaign Status -->
                        <div class="col-md-4">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Campaign Status</label>
                            <select name="campaign_status" class="form-select form-select-xs">
                                <option value="">-Select-</option>
                                <?php foreach (Constants::CAMPAIGN_STATUS as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                        <?= ($log['campaign_status'] == $value) ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Campaign ID -->
                        <div class="col-md-4">
                            <label for="campaign_id" class="form-label-sm fw-semibold text-secondary mb-1">Campaign ID</label>
                            <input type="text" name="campaign_id" id="campaign_id"
                                class="form-control form-control-xs"
                                value="<?= old('campaign_id', $log['campaign_id'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="<?= site_url('tasks') ?>" onclick="if (document.referrer) { window.history.back(); return false; }" title="Cancel" class="apple-btn-secondary btn-sm px-4 text-decoration-none">Cancel</a>
                    <button type="submit" class="apple-btn-primary btn-sm px-4">Update</button>
                </div>
            </div>

            <!-- Right Sidebar (Activity Settings) -->
            <div class="col-lg-4 col-md-5">
                <!-- Card 1: Activity settings -->
                <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                    <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold" style="font-size: 1rem;">
                        <i class="bi bi-gear-fill"></i>
                        <span>Task Settings</span>
                    </h5>
                    
                    <div class="row g-3">
                        <!-- Status -->
                        <div class="col-12">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Status</label>
                            <select name="status" class="form-select form-select-xs" required>
                                <?php foreach (Constants::ACTIVITY_LOGS_STATUS as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                            <?= ($log['status'] == $value) ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div class="col-12">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Priority</label>
                            <select name="priority" class="form-select form-select-xs" required>
                                <option value="">-Select-</option>
                                <?php foreach (Constants::PRIORITIES as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                            <?= ($log['priority'] == $value) ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Size -->
                        <div class="col-12">
                            <label for="size-select" class="form-label-sm fw-semibold text-secondary mb-1 d-flex align-items-center gap-1">
                                Size
                                <i class="bi bi-question-circle-fill text-muted" style="cursor: help;" title="T-Shirt Size Guide: Represents the project management estimation size. Hover or select a size for more details."></i>
                            </label>
                            <select name="size" id="size-select" class="form-select form-select-xs" required>
                                <option value="">-Select-</option>
                                <?php foreach (Constants::ACTIVITY_SIZES as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                        <?= ($log['size'] == $value) ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="size-help" class="form-text mt-1 text-muted fw-medium" style="font-size: 0.75rem; min-height: 1.25rem;">
                                Select a size to view its estimated effort and description.
                            </div>
                        </div>

                        <!-- Assignee -->
                        <?php if (!$hideAssigneeSystemWide): ?>
                        <div class="col-12">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Assignee</label>
                            <select name="assignee" class="form-select form-select-xs" required>
                                <?php foreach (Constants::USERS as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                            <?= ($log['assignee'] == $value) ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php else: ?>
                            <input type="hidden" name="assignee" value="<?= old('assignee', $log['assignee'] ?? Constants::USER_SYSTEM) ?>">
                        <?php endif; ?>

                        <!-- Subsidiary -->
                        <div class="col-12">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Subsidiary</label>
                            <select name="subsidiary_id" class="form-select form-select-xs searchable-select" required>
                                <?php foreach (Constants::ACTIVITY_LOG_SUBSIDIARIES as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                        <?= ($log['subsidiary_id'] == $value) ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Team -->
                        <div class="col-12">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Team</label>
                            <select name="team_id" class="form-select form-select-xs" required>
                                <?php foreach (Constants::TEAMS as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                        <?= ($log['team_id'] == $value) ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Project -->
                        <div class="col-12">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Project</label>
                            <select name="project" id="project-select" class="form-select form-select-xs" required>
                                <?php foreach ($project_names as $pName): ?>
                                    <option value="<?= esc($pName) ?>" <?= old('project', $log['project'] ?? 'Generic') == $pName ? 'selected' : '' ?>>
                                        <?= esc($pName) ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php if (!in_array($log['project'] ?? 'Generic', $project_names)): ?>
                                    <option value="<?= esc($log['project'] ?? 'Generic') ?>" selected><?= esc($log['project'] ?? 'Generic') ?></option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Type -->
                        <div class="col-12">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Type</label>
                            <select name="type" class="form-select form-select-xs" required>
                                <option value="">-Select-</option>
                                <?php foreach (Constants::ACTIVITY_TYPES as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                        <?= ($log['type'] == $value) ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Ticket ID -->
                        <div class="col-12">
                            <label for="ticket_id" class="form-label-sm fw-semibold text-secondary mb-1">Ticket ID</label>
                            <input type="text" name="ticket_id" id="ticket_id"
                                class="form-control form-control-xs"
                                value="<?= esc($log['ticket_id']) ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#requested_date", {
            dateFormat: "Y-m-d",
            disableMobile: "true",
            defaultDate: "<?= !empty($log['requested_date']) ? date('Y-m-d', strtotime($log['requested_date'])) : '' ?>"
        });

        flatpickr("#start_date", {
            dateFormat: "Y-m-d",
            disableMobile: "true",
            defaultDate: "<?= !empty($log['start_date']) ? date('Y-m-d', strtotime($log['start_date'])) : '' ?>"
        });

        $('.searchable-select').select2({
            width: '100%',
        });

        $('#project-select').select2({
            tags: true,
            width: '100%',
            placeholder: 'Select or type a project name...'
        });

        $('#department-select').select2({
            tags: true,
            width: '100%',
            placeholder: '-Select or type a department-',
            allowClear: true
        });

        const sizeDescriptions = {
            '1': 'S (Small): Minor tasks / quick fixes (estimated effort < 2 hours)',
            '2': 'M (Medium): Standard tag setups / simple troubleshooting (estimated effort: 2-5 hours)',
            '3': 'L (Large): New tag integrations / complex tracking rules (estimated effort: 1-2 days)',
            '4': 'XL (Extra Large): Major property setups / migrations / custom JS (estimated effort: 3-5 days)',
            '5': 'Sprint: Massive undertakings / entire sprint effort (estimated effort: 1-2 weeks)'
        };

        const sizeSelect = document.getElementById('size-select');
        const sizeHelp = document.getElementById('size-help');

        if (sizeSelect && sizeHelp) {
            const updateSizeHelp = () => {
                const val = sizeSelect.value;
                sizeHelp.innerHTML = val ? `<i class="bi bi-info-circle-fill text-primary"></i> ${sizeDescriptions[val]}` : 'Select a size to view its estimated effort and description.';
            };
            sizeSelect.addEventListener('change', updateSizeHelp);
            updateSizeHelp(); // Initial run
        }

        $(document).ready(function() {
            $('#description').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            var detailsHtml = `<?= $detailsHtml ?? null ?>`;
            $('#details').summernote('code', detailsHtml);
        });

        $('.select2-multiple').select2({
            tags: true,
            tokenSeparators: [',', ' '],
            width: '100%',
            placeholder: "Select or type to create new labels"
        });
    });
</script>

<?= $this->endSection() ?>

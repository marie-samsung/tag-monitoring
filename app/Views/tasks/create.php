<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Task: Create
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
/* Custom Tags Input UI Components */
.tags-input-container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    min-height: 38px;
    border-radius: 8px;
    border: 1px solid var(--color-border-input);
    background-color: var(--color-card-bg);
    cursor: text;
    transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
}

.tags-input-container.focused {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 0.25rem var(--color-primary-focus-shadow);
    outline: none;
}

.tags-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    align-items: center;
}

.tags-input-container .tag-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    font-size: 0.8rem;
    font-weight: 500;
    border-radius: 9999px;
    background-color: var(--color-badge-primary-bg);
    color: var(--color-badge-primary-text);
    border: 1px solid var(--color-badge-primary-border);
    animation: tagScaleIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    user-select: none;
}

.tags-input-container .tag-badge .tag-remove-btn {
    cursor: pointer;
    font-size: 0.85rem;
    opacity: 0.6;
    transition: opacity var(--transition-fast), transform var(--transition-fast);
    display: inline-flex;
    align-items: center;
}

.tags-input-container .tag-badge .tag-remove-btn:hover {
    opacity: 1;
    transform: scale(1.15);
}

.tags-input-container .tag-field {
    border: none !important;
    outline: none !important;
    padding: 0 !important;
    margin: 0 !important;
    flex-grow: 1;
    background: transparent !important;
    color: var(--color-text-base) !important;
    font-size: 0.875rem !important;
    min-width: 120px;
    height: 26px;
    box-shadow: none !important;
}

@keyframes tagScaleIn {
    0% {
        transform: scale(0.7);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes tagScaleOut {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    100% {
        transform: scale(0.7);
        opacity: 0;
    }
}

.tags-input-container .tag-badge.removing {
    animation: tagScaleOut 0.15s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>

<?php
    use App\Models\Constants;
    $sysSettings = \App\Controllers\SettingsController::getSystemSettings();
    $hideAssigneeSystemWide = $sysSettings['hide_assignee_system_wide'] ?? false;
?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tasks') ?>" onclick="if (document.referrer) { window.history.back(); return false; }" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0; display: inline-flex;" title="Back to Tasks">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Create a New Task</h2>
            <p class="text-secondary small mb-0">Record and track project management activities, support tasks, and campaign tag updates.</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mb-4">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('tasks/create') ?>" method="post">
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
                            value="<?= old('title', $log['title'] ?? '') ?>"
                            placeholder="Enter a descriptive title..."
                            required>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label-sm fw-semibold text-secondary mb-1">Description</label>
                        <textarea name="description" id="description"
                            class="form-control"
                            rows="12"><?= old('description', $log['description'] ?? '') ?></textarea>
                    </div>

                    <!-- Labels -->
                    <div class="mb-4">
                        <label class="form-label-sm fw-semibold text-secondary mb-1">Labels / Tags</label>
                        <div class="tags-input-container" id="labels-tags-input">
                            <div class="tags-wrapper"></div>
                            <input type="text" class="tag-field" placeholder="Add label (press space)...">
                            <input type="hidden" name="labels" id="labels-hidden-input" value="<?= old('labels', $log['labels'] ?? '') ?>">
                        </div>
                        <div class="form-text mt-1 text-muted" style="font-size: 0.75rem;">
                            Type a label and press <kbd class="px-1 bg-secondary text-white rounded">Space</kbd> or <kbd class="px-1 bg-secondary text-white rounded">Enter</kbd> to add it. Labels must not contain spaces.
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
                                value="<?= old('requested_by', $log['requested_by'] ?? '') ?>">
                        </div>

                        <!-- Requested Date -->
                        <div class="col-md-4">
                            <label for="requested_date" class="form-label-sm fw-semibold text-secondary mb-1">Requested Date</label>
                            <div class="position-relative">
                                <input type="text" name="requested_date" id="requested_date"
                                    class="form-control form-control-xs pe-5"
                                    value="<?= old('requested_date', $log['requested_date'] ?? '') ?>"
                                    required>
                                <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div class="col-md-4">
                            <label for="start_date" class="form-label-sm fw-semibold text-secondary mb-1">Start Date</label>
                            <div class="position-relative">
                                <input type="text" name="start_date" id="start_date"
                                       class="form-control form-control-xs pe-5"
                                       value="<?= old('start_date', $log['start_date'] ?? '') ?>"
                                       required>
                                <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="col-md-4">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Department</label>
                            <select name="department_id" id="department-select" class="form-select form-select-xs">
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
                                        <?= old('campaign_status', $log['campaign_status'] ?? '') == $value ? 'selected' : '' ?>>
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
                    <button type="submit" class="apple-btn-primary btn-sm px-4">Submit</button>
                </div>
            </div>

            <!-- Right Sidebar (Activity Settings) -->
            <div class="col-lg-4 col-md-5">
                <!-- Card 1: Activity Settings -->
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
                                            <?= old('status', $log['status'] ?? Constants::ACTIVITY_LOGS_STATUS_TO_DO) == $value ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div class="col-12">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Priority</label>
                            <select name="priority" class="form-select form-select-xs" required>
                                <?php foreach (Constants::PRIORITIES as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                            <?= old('priority', $log['priority'] ?? Constants::PRIORITY_DEFAULT) == $value ? 'selected' : '' ?>>
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
                                <?php foreach (Constants::ACTIVITY_SIZES as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                        <?= old('size', $log['size'] ?? Constants::ACTIVITY_SIZE_M) == $value ? 'selected' : '' ?>>
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
                                            <?= old('assignee', $log['assignee'] ?? session()->get('user_id')) == $value ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php else: ?>
                            <input type="hidden" name="assignee" value="<?= old('assignee', $log['assignee'] ?? session()->get('user_id') ?? Constants::USER_SYSTEM) ?>">
                        <?php endif; ?>

                        <!-- Subsidiary -->
                        <div class="col-12">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Subsidiary</label>
                            <select name="subsidiary_id" class="form-select form-select-xs searchable-select" required>
                                <option value="">-Select-</option>
                                <?php foreach (Constants::ACTIVITY_LOG_SUBSIDIARIES as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                             <?= old('subsidiary_id', $log['subsidiary_id'] ?? '') == $value ? 'selected' : '' ?>>
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
                                             <?= old('team_id', $log['team_id'] ?? Constants::USER_TEAMS[session()->get('user_id')]) == $value ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Project -->
                        <div class="col-12">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Project</label>
                            <select name="project" id="project-select" class="form-select form-select-xs" required>
                                <?php 
                                    $selectedProject = old('project', $log['project'] ?? 'Generic');
                                    if (!empty($selectedProject) && !in_array($selectedProject, $project_names)): 
                                ?>
                                    <option value="<?= esc($selectedProject) ?>" selected><?= esc($selectedProject) ?></option>
                                <?php endif; ?>
                                <?php foreach ($project_names as $pName): ?>
                                    <option value="<?= esc($pName) ?>" <?= $selectedProject == $pName ? 'selected' : '' ?>>
                                        <?= esc($pName) ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php if (!in_array('Generic', $project_names) && $selectedProject !== 'Generic'): ?>
                                    <option value="Generic">Generic</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Type -->
                        <div class="col-12">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Type</label>
                            <select name="type" class="form-select form-select-xs" required>
                                <?php foreach (Constants::ACTIVITY_TYPES as $value => $label): ?>
                                    <option value="<?= esc($value) ?>"
                                             <?= old('type', $log['type'] ?? Constants::ACTIVITY_TYPE_TASK) == $value ? 'selected' : '' ?>>
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
                                value="<?= old('ticket_id', $log['ticket_id'] ?? '') ?>">
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
            defaultDate: "<?= old('requested_date', $log['requested_date'] ?? date('Y-m-d')) ?>"
        });

        flatpickr("#start_date", {
            dateFormat: "Y-m-d",
            disableMobile: "true",
            defaultDate: "<?= old('start_date', $log['start_date'] ?? date('Y-m-d')) ?>"
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

        // Initialize Tags Input for Labels
        const tagsContainer = document.getElementById('labels-tags-input');
        if (tagsContainer) {
            const inputField = tagsContainer.querySelector('.tag-field');
            const hiddenInput = tagsContainer.querySelector('#labels-hidden-input');
            const tagsWrapper = tagsContainer.querySelector('.tags-wrapper');
            let tagsList = [];

            if (hiddenInput.value) {
                tagsList = hiddenInput.value.split(',').map(t => t.trim()).filter(t => t !== '');
                renderTags();
            }

            function renderTags() {
                tagsWrapper.innerHTML = '';
                tagsList.forEach((tag, index) => {
                    const badge = document.createElement('span');
                    badge.className = 'tag-badge';
                    badge.innerHTML = `
                        <i class="bi bi-tag-fill text-primary" style="font-size: 0.75rem; opacity: 0.7;"></i>
                        <span>${escapeHtml(tag)}</span>
                        <span class="tag-remove-btn" data-index="${index}"><i class="bi bi-x-lg" style="font-size: 0.75rem; margin-left: 3px;"></i></span>
                    `;
                    tagsWrapper.appendChild(badge);
                });
                hiddenInput.value = tagsList.join(',');
            }

            function addTag(tag) {
                tag = tag.replace(/[\s,]+/g, '').trim();
                if (tag === '') return;
                
                if (tagsList.includes(tag)) {
                    tagsContainer.classList.add('border-danger');
                    setTimeout(() => tagsContainer.classList.remove('border-danger'), 300);
                    return;
                }

                tagsList.push(tag);
                renderTags();
                inputField.value = '';
            }

            function removeTag(index) {
                const badge = tagsWrapper.children[index];
                if (badge) {
                    badge.classList.add('removing');
                    badge.addEventListener('animationend', () => {
                        tagsList.splice(index, 1);
                        renderTags();
                        inputField.focus();
                    });
                } else {
                    tagsList.splice(index, 1);
                    renderTags();
                    inputField.focus();
                }
            }

            tagsContainer.addEventListener('click', (e) => {
                if (e.target.closest('.tag-remove-btn')) {
                    const index = parseInt(e.target.closest('.tag-remove-btn').dataset.index);
                    removeTag(index);
                } else {
                    inputField.focus();
                }
            });

            inputField.addEventListener('focus', () => {
                tagsContainer.classList.add('focused');
            });

            inputField.addEventListener('blur', () => {
                tagsContainer.classList.remove('focused');
                if (inputField.value.trim() !== '') {
                    addTag(inputField.value);
                }
            });

            inputField.addEventListener('keydown', (e) => {
                if (e.key === ' ' || e.key === 'Spacebar') {
                    e.preventDefault();
                    addTag(inputField.value);
                } else if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    addTag(inputField.value);
                } else if (e.key === 'Backspace' && inputField.value === '') {
                    if (tagsList.length > 0) {
                        removeTag(tagsList.length - 1);
                    }
                }
            });

            inputField.addEventListener('paste', (e) => {
                e.preventDefault();
                const clipboardData = e.clipboardData || window.clipboardData;
                const pastedText = clipboardData.getData('text');
                const parts = pastedText.split(/[\s,]+/);
                parts.forEach(part => {
                    addTag(part);
                });
            });

            function escapeHtml(string) {
                const htmlEscapes = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                };
                return string.replace(/[&<>"']/g, (match) => htmlEscapes[match]);
            }
        }
    });
</script>

<?= $this->endSection() ?>

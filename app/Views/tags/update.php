<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= esc($tag['name']) ?> : Update
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tags') ?>" onclick="if (document.referrer) { window.history.back(); return false; }"
            class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
            style="width: 36px; height: 36px; padding: 0;" title="Back to Inventory">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Update Tag</h2>
            <p class="text-secondary small mb-0"><?= esc($tag['name']) ?></p>
        </div>
    </div>

    <?php
    use App\Models\Constants;
    use App\Models\PropertiesModel;
    use App\Models\VendorsModel;
    use App\Models\OrganizationsModel;
    use App\Models\TagsModel;

    $propertiesModel = new PropertiesModel();
    $propertiesList = $propertiesModel->select('id, name, is_shop')->findAll();

    $vendorsModel = new VendorsModel();
    $vendors = $vendorsModel->Options();

    $organizationsModel = new OrganizationsModel();
    $organizations = $organizationsModel->Options();

    $tagsModel = new TagsModel();
    $conditionsList = array_column($tagsModel->select('conditions')->distinct()->where('conditions !=', '')->where('conditions IS NOT NULL')->orderBy('conditions', 'ASC')->findAll(), 'conditions');

    $tagsModelInst = new \App\Models\TagsModel();
    $tagReqModelInst = new \App\Models\TagRequestsModel();
    $tasksModelInst = new \App\Models\TasksModel();

    $names1 = array_column($tagsModelInst->select('requester_name')->distinct()->where('requester_name !=', '')->where('requester_name IS NOT NULL')->findAll(), 'requester_name');
    $names2 = array_column($tagReqModelInst->select('requester_name')->distinct()->where('requester_name !=', '')->where('requester_name IS NOT NULL')->findAll(), 'requester_name');
    $names3 = array_column($tasksModelInst->select('requested_by')->distinct()->where('requested_by !=', '')->where('requested_by IS NOT NULL')->findAll(), 'requested_by');
    $requesterNamesList = array_unique(array_merge($names1, $names2, $names3));
    sort($requesterNamesList);

    $emails1 = array_column($tagsModelInst->select('requester_email')->distinct()->where('requester_email !=', '')->where('requester_email IS NOT NULL')->findAll(), 'requester_email');
    $emails2 = array_column($tagReqModelInst->select('requester_email')->distinct()->where('requester_email !=', '')->where('requester_email IS NOT NULL')->findAll(), 'requester_email');
    $requesterEmailsList = array_unique(array_merge($emails1, $emails2));
    sort($requesterEmailsList);

    $user = session()->get('user_name') ?? 'User';
    ?>

    <?php if (isset($validation)): ?>
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= $validation->listErrors() ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url("tags/update/{$tag['id']}") ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="vendor_id" value="<?= esc($tag['vendor_id']) ?>" />

        <div class="row g-4">
            <!-- Left Column: Form Details -->
            <div class="col-lg-5">

                <!-- Tag Details Section -->
                <div class="apple-card mb-4"
                    style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                    <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-tag-fill"></i>
                        <span>Tag Details</span>
                    </h5>

                    <div class="row g-3">
                        <!-- Property -->
                        <div class="col-md-6">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Property</label>
                            <select name="property_id" class="form-select searchable-select" required>
                                <?php foreach ($propertiesList as $prop): ?>
                                    <option value="<?= esc($prop['id']) ?>"
                                        data-is-shop="<?= $prop['is_shop'] == Constants::YES ? 'Shop' : 'AEM' ?>"
                                        <?= ($tag['property_id'] == $prop['id']) ? 'selected' : '' ?>>
                                        <?= esc($prop['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Tag Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label-sm fw-semibold text-secondary mb-1">Tag Name</label>
                            <select name="name" id="name" class="form-select advanced-select" required>
                                <option value="<?= esc($tag['name']) ?>" selected>
                                    <?= esc($tag['name']) ?>
                                </option>
                            </select>
                            <small class="form-text text-muted" style="font-size: 0.75rem;">
                                Tag should start with <strong>LC-</strong>
                            </small>
                        </div>

                        <!-- Conditions -->
                        <div class="col-md-6">
                            <label for="conditions"
                                class="form-label-sm fw-semibold text-secondary mb-1">Conditions</label>
                            <input type="text" name="conditions" id="conditions" class="form-control form-control-xs"
                                list="conditions-list" value="<?= esc($tag['conditions']) ?>" placeholder="e.g. (.*)"
                                required>
                            <datalist id="conditions-list">
                                <?php foreach ($conditionsList as $cond): ?>
                                    <option value="<?= esc($cond) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                            <small class="form-text text-muted" style="font-size: 0.75rem;">
                                RegEx or condition string (e.g. <code>(.*)</code>)
                            </small>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Status</label>
                            <select name="status" class="form-select form-select-xs" required>
                                <?php foreach (Constants::STATUS as $value => $label): ?>
                                    <option value="<?= esc($value) ?>" <?= ($tag['status'] == $value) ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Launch Expiry Date -->
                        <div class="col-md-6">
                            <label class="form-label-sm fw-semibold text-secondary mb-1" for="al_end_date">Launch Expiry
                                Date</label>
                            <div class="position-relative">
                                <input type="text" name="al_end_date" id="al_end_date"
                                    class="form-control form-control-xs pe-5"
                                    value="<?= esc(old('al_end_date', date('Y-m-d', strtotime($tag['al_end_date'])))) ?>"
                                    required>
                                <i class="bi bi-calendar-event text-muted position-absolute"
                                    style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                            </div>
                        </div>

                        <!-- Requested Date -->
                        <div class="col-md-6">
                            <label class="form-label-sm fw-semibold text-secondary mb-1" for="requested_date">Requested Date</label>
                            <div class="position-relative">
                                <input type="text" name="requested_date" id="requested_date"
                                    class="form-control form-control-xs pe-5"
                                    value="<?= !empty($tag['requested_date']) ? date('Y-m-d', strtotime($tag['requested_date'])) : '' ?>">
                                <i class="bi bi-calendar-event text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 0.875rem;"></i>
                            </div>
                        </div>

                        <!-- Launch Link -->
                        <div class="col-md-12">
                            <label for="ref_link" class="form-label-sm fw-semibold text-secondary mb-1">Launch
                                Link</label>
                            <a href="<?= $tag['ref_link'] ?>" target="_blank" rel="noopener" class="text-muted ms-1"
                               style="font-size: 0.75rem; text-decoration: none;" title="View in Adobe Launch">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <input type="text" name="ref_link" id="ref_link" class="form-control form-control-xs"
                                value="<?= esc($tag['ref_link']) ?>" placeholder="https://..." required>
                            <small class="form-text text-muted" style="font-size: 0.75rem;">
                                Must start with <strong>https://</strong>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Requester Details Section -->
                <div class="apple-card mb-4"
                    style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                    <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-person-fill"></i>
                        <span>Requester Details</span>
                    </h5>

                    <div class="row g-3">
                        <!-- Organization -->
                        <div class="col-md-4">
                            <label class="form-label-sm fw-semibold text-secondary mb-1">Organization</label>
                            <?php
                            $orgId = $tag['requester_organization'] ?? '';
                            $orgName = $orgId && isset($organizations[$orgId]) ? $organizations[$orgId] : $orgId;
                            ?>
                            <select name="requester_organization" id="requester_organization"
                                class="form-select advanced-select">
                                <option value="">-Select-</option>
                                <?php foreach ($organizations as $value => $label): ?>
                                    <option value="<?= esc($label) ?>" <?= old('requester_organization', $orgName) == $label ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Name -->
                        <div class="col-md-4">
                            <label class="form-label-sm fw-semibold text-secondary mb-1"
                                for="requester_name">Name</label>
                            <input type="text" name="requester_name" id="requester_name"
                                class="form-control form-control-xs"
                                list="requester-name-list"
                                value="<?= esc(old('requester_name', $tag['requester_name'] ?? '')) ?>"
                                placeholder="Requester Name">
                            <datalist id="requester-name-list">
                                <?php foreach ($requesterNamesList as $name): ?>
                                    <option value="<?= esc($name) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <!-- Email Address -->
                        <div class="col-md-4">
                            <label class="form-label-sm fw-semibold text-secondary mb-1" for="requester_email">Email Address</label>
                            <input type="email" name="requester_email" id="requester_email"
                                class="form-control form-control-xs"
                                list="requester-email-list"
                                value="<?= esc(old('requester_email', $tag['requester_email'] ?? '')) ?>"
                                placeholder="email@example.com">
                            <datalist id="requester-email-list">
                                <?php foreach ($requesterEmailsList as $email): ?>
                                    <option value="<?= esc($email) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top mb-4">
                    <a href="<?= site_url('tags') ?>"
                        onclick="if (document.referrer) { window.history.back(); return false; }" title="Cancel"
                        class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
                    <button type="submit" class="apple-btn-primary px-4">Update Tag</button>
                </div>

            </div> <!-- End Left Column -->

            <!-- Right Column: JS Code Section -->
            <div class="col-lg-7">
                <!-- JS Code Section -->
                <div class="accordion mb-4" id="jsCodeAccordion">
                    <div class="accordion-item apple-card p-0"
                        style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none; border-radius: 8px; overflow: hidden;">
                        <h4 class="accordion-header" id="headingJsCode">
                            <button
                                class="accordion-button fw-bold text-primary d-flex align-items-center gap-2"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseJsCode"
                                aria-expanded="true" aria-controls="collapseJsCode"
                                style="background-color: var(--color-bg-base); box-shadow: none;">
                                <i class="bi bi-code-slash"></i>
                                <span>Script Editor</span>
                            </button>
                        </h4>
                        <div id="collapseJsCode" class="accordion-collapse collapse show"
                            aria-labelledby="headingJsCode" data-bs-parent="#jsCodeAccordion">
                            <div class="accordion-body p-0">
                                <div id="jsEditor"
                                            style="height: 500px; width: 100%; border-top: 1px solid rgba(0,0,0,0.05); border-bottom: 1px solid rgba(0,0,0,0.05);">
                                        </div>
                                        <div id="jsValidationPanel" class="p-2 border-bottom"
                                            style="font-size: 0.85rem; max-height: 150px; overflow-y: auto; background-color: var(--color-bg-base);">
                                            <div class="text-muted"><i class="bi bi-hourglass-split me-1"></i>
                                                Validating...</div>
                                        </div>
                                        <div class="p-3 bg-light d-flex justify-content-between align-items-center">
                                            <div class="form-check form-switch ms-2 mb-0">
                                                <input class="form-check-input" type="checkbox" id="themeToggle"
                                                    style="cursor: pointer;">
                                                <label class="form-check-label text-muted" for="themeToggle"
                                                    style="font-size: 0.9rem; cursor: pointer;">
                                                    <i class="bi bi-moon-stars me-1"></i> Dark Mode
                                                </label>
                                            </div>
                                            <button type="button" class="apple-btn-primary btn-sm px-4"
                                                id="saveJsCodeBtn" style="border-radius: 20px;">
                                                <i class="bi bi-save me-1"></i> Save Script
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div> <!-- End Right Column -->
        </div> <!-- End Row -->
    </form>
</div>

<style>
/* Apple Style Chatbot */
.apple-chat-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 1050;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.apple-chat-window {
    width: 380px;
    height: 550px;
    min-width: 320px;
    min-height: 400px;
    max-width: 90vw;
    max-height: 85vh;
    resize: both;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid rgba(255, 255, 255, 0.5);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    margin-bottom: 16px;
    transition: opacity 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    transform-origin: bottom right;
    opacity: 0;
    transform: scale(0.95) translateY(10px);
    pointer-events: none;
}

.apple-chat-window.active {
    opacity: 1;
    transform: scale(1) translateY(0);
    pointer-events: auto;
}

.apple-chat-header {
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.5);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.apple-model-select {
    display: none; /* Select2 will replace this */
}

.apple-chat-header .select2-container--default .select2-selection--single {
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.1);
    background: rgba(255,255,255,0.8);
    height: 36px;
    display: flex;
    align-items: center;
}

.apple-chat-header .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #1d1d1f;
    font-size: 0.85rem;
    font-weight: 500;
    line-height: 36px;
    padding-left: 12px;
}

.apple-chat-header .select2-dropdown {
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.1);
    box-shadow: 0 10px 24px rgba(0,0,0,0.1);
    background: #ffffff;
    color: #1d1d1f;
    overflow: hidden;
    z-index: 9999;
}

.apple-chat-header .select2-results__option {
    padding: 8px 12px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.ai-model-item {
    padding: 2px 0;
}
.ai-model-title {
    font-weight: 600;
    color: #1d1d1f;
    font-size: 0.9rem;
}
.ai-model-desc {
    font-size: 0.75rem;
    color: #6c757d;
    white-space: normal;
    line-height: 1.3;
    margin-top: 3px;
}

/* Ensure text is readable when highlighted */
.select2-results__option--highlighted,
.select2-results__option[aria-selected="true"] {
    background-color: #007aff !important;
}
.select2-results__option--highlighted .ai-model-title,
.select2-results__option--highlighted .ai-model-desc,
.select2-results__option[aria-selected="true"] .ai-model-title,
.select2-results__option[aria-selected="true"] .ai-model-desc {
    color: #ffffff !important;
}

.apple-chat-header .select2-container {
    width: 240px !important;
    max-width: 100%;
}

.apple-chat-title {
    font-weight: 600;
    font-size: 1.1rem;
    color: #1d1d1f;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.apple-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.chat-message {
    max-width: 85%;
    padding: 12px 16px;
    border-radius: 18px;
    font-size: 0.95rem;
    line-height: 1.4;
    overflow-wrap: break-word;
    word-break: normal;
}

.chat-message p {
    margin-bottom: 0.5rem;
}
.chat-message p:last-child {
    margin-bottom: 0;
}
.chat-message pre {
    background: rgba(0,0,0,0.05);
    padding: 8px;
    border-radius: 8px;
    font-size: 0.85rem;
    overflow-x: auto;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}
.chat-message code {
    font-family: monospace;
    background: rgba(0,0,0,0.05);
    padding: 2px 4px;
    border-radius: 4px;
    font-size: 0.85rem;
}
.chat-message.user code {
    background: rgba(255,255,255,0.2);
}
.chat-message ul, .chat-message ol {
    margin-bottom: 0.5rem;
    padding-left: 1.5rem;
}

.chat-message.bot {
    background: #f5f5f7;
    color: #1d1d1f;
    align-self: flex-start;
    border-bottom-left-radius: 4px;
}

.chat-message.user {
    background: #007aff;
    color: #fff;
    align-self: flex-end;
    border-bottom-right-radius: 4px;
}

.typing-indicator {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    height: 18px;
    padding: 0 4px;
}

.typing-indicator span {
    width: 6px;
    height: 6px;
    background-color: #8e8e93;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out both;
}

.typing-indicator span:nth-child(1) {
    animation-delay: -0.32s;
}

.typing-indicator span:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes typing {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}

.apple-chat-footer {
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.5);
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    gap: 10px;
    align-items: center;
}

.apple-chat-input {
    flex: 1;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 20px;
    padding: 10px 16px;
    font-size: 0.95rem;
    outline: none;
    background: rgba(255,255,255,0.9);
    transition: border-color 0.2s;
}

.apple-chat-input:focus {
    border-color: #007aff;
}

.apple-chat-send {
    background: #007aff;
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
}

.apple-chat-send:hover {
    background: #0056b3;
}

.apple-chat-toggle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #1d1d1f;
    color: white;
    border: none;
    box-shadow: 0 4px 14px rgba(0,0,0,0.15);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: transform 0.2s, background 0.2s;
}

.apple-chat-toggle:hover {
    transform: scale(1.05);
    background: #333336;
}
</style>

<div class="apple-chat-widget">
    <div class="apple-chat-window" id="appleChatWindow">
        <div class="apple-chat-header">
            <h3 class="apple-chat-title">
                <i class="bi bi-robot"></i> JavaScript Assistant
            </h3>
            <select class="apple-model-select" id="aiModelSelect">
                <?php if (!empty($aiModels)): ?>
                    <?php foreach ($aiModels as $model): ?>
                        <?php 
                        $modelName = 'Unknown Model';
                        $modelDesc = '';
                        if (isset($model['name']) && is_array($model['name'])) {
                            foreach ($model['name'] as $nameObj) {
                                $langCode = strtolower($nameObj['languagecode'] ?? $nameObj['languageCode'] ?? $nameObj['language'] ?? '');
                                if ($langCode === 'en' || strpos($langCode, 'en') !== false) {
                                    $modelName = $nameObj['content'] ?? $nameObj['conent'] ?? $modelName;
                                    break;
                                }
                            }
                            if ($modelName === 'Unknown Model' && !empty($model['name'][0])) {
                                $modelName = $model['name'][0]['content'] ?? $model['name'][0]['conent'] ?? $modelName;
                            }
                        }
                        if (isset($model['description']) && is_array($model['description'])) {
                            foreach ($model['description'] as $descObj) {
                                $langCode = strtolower($descObj['languagecode'] ?? $descObj['languageCode'] ?? $descObj['language'] ?? '');
                                if ($langCode === 'en' || strpos($langCode, 'en') !== false) {
                                    $modelDesc = $descObj['content'] ?? $descObj['conent'] ?? '';
                                    break;
                                }
                            }
                            if (empty($modelDesc) && !empty($model['description'][0])) {
                                $modelDesc = $model['description'][0]['content'] ?? $model['description'][0]['conent'] ?? '';
                            }
                        }
                        ?>
                        <option value="<?= esc($model['modelId'] ?? '') ?>" data-description="<?= esc($modelDesc) ?>"><?= esc($modelName) ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No models available</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="apple-chat-messages" id="appleChatMessages">
            <div class="chat-message bot">Hello <?= $user ?>! Do you need any script assistance today? 😊</div>
        </div>
        <div class="apple-chat-footer">
            <input type="text" class="apple-chat-input" id="appleChatInput" placeholder="Type a message...">
            <button class="apple-chat-send" id="appleChatSend" title="Send">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>
    <button class="apple-chat-toggle" id="appleChatToggle" title="Toggle AI Chat">
        <i class="bi bi-chat-dots-fill"></i>
    </button>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const picker = flatpickr("#al_end_date", {
            dateFormat: "Y-m-d",
            disableMobile: "true",
            defaultDate: "<?= esc(old('al_end_date', date('Y-m-d', strtotime($tag['al_end_date'])))) ?>"
        });
        
        flatpickr("#requested_date", {
            dateFormat: "Y-m-d",
            disableMobile: "true",
            defaultDate: "<?= !empty($tag['requested_date']) ? date('Y-m-d', strtotime($tag['requested_date'])) : '' ?>"
        });

        $('.searchable-select').select2({
            width: '100%',
        });

        $('.advanced-select').select2({
            tags: true,
            width: '100%',
            allowClear: true,
            createTag: function (params) {
                let term = $.trim(params.term);

                if (term === '') {
                    return null;
                }

                return {
                    id: term,   // this will be sent to backend
                    text: term,
                    newOption: true
                };
            },
            templateResult: function (data) {
                if (data.newOption) {
                    return $('<span>Add: <strong>' + data.text + '</strong></span>');
                }
                return data.text;
            }
        });

        $('select[name="property_id"]').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var isShop = selectedOption.data('is-shop') || 'AEM';

            var $searchField = $('.select2-search__field');
            if ($searchField.length) {
                var val = $searchField.val() || '';
                if (val.startsWith('LC-')) {
                    var parts = val.split('-');
                    if (parts.length > 1 && (parts[1] === 'Shop' || parts[1] === 'AEM')) {
                        parts[1] = isShop;
                        $searchField.val(parts.join('-')).trigger('input');
                    }
                }
            }

            var currentVal = $('#name').val() || '';
            if (currentVal.startsWith('LC-')) {
                var parts = currentVal.split('-');
                if (parts.length > 1 && (parts[1] === 'Shop' || parts[1] === 'AEM')) {
                    parts[1] = isShop;
                    var newVal = parts.join('-');
                    if ($('#name option[value="' + newVal + '"]').length === 0) {
                        var newOption = new Option(newVal, newVal, true, true);
                        $('#name').append(newOption).trigger('change');
                    } else {
                        $('#name').val(newVal).trigger('change');
                    }
                }
            }
        });

        $('#name').on('select2:open', function () {
            setTimeout(function () {
                var $searchField = $('.select2-search__field');
                var currentVal = $('#name').val() || '';
                if ($searchField.length && !currentVal) {
                    var selectedOption = $('select[name="property_id"] option:selected');
                    var isShop = selectedOption.data('is-shop') || 'AEM';
                    $searchField.val('LC-' + isShop + '-').trigger('input');
                }
            }, 50);
        });
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Configure the Monaco AMD loader path
        require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs' } });

        require(['vs/editor/editor.main'], function () {
            // Set compiler options specifically for JavaScript autocomplete and diagnostics
            monaco.languages.typescript.javascriptDefaults.setCompilerOptions({
                target: monaco.languages.typescript.ScriptTarget.ES2020,
                allowNonTsExtensions: true,
                checkJs: true,
                allowReturnOutsideFunction: true, // Crucial for standalone JS tagging snippets
                lib: ['es2020', 'dom'] // Provide standard JS and DOM API definitions
            });

            // Set diagnostics options
            monaco.languages.typescript.javascriptDefaults.setDiagnosticsOptions({
                noSemanticValidation: false,
                noSyntaxValidation: false
            });

            // Add dedicated global definitions for common tagging scripts (Adobe Launch, Google Tag Manager, etc.)
            const tagGlobals = `
                /**
                 * Adobe Launch _satellite object for tracking and utility methods.
                 */
                declare const _satellite: {
                    track: (eventName: string, detail?: any) => void;
                    getVar: (variableName: string) => any;
                    setVar: (variableName: string, value: any) => void;
                    getVisitorId: () => any;
                    notify: (message: string, level?: number) => void;
                    cookie: {
                        get: (name: string) => string | null;
                        set: (name: string, value: string, options?: any) => void;
                        remove: (name: string) => void;
                    };
                    logger: {
                        log: (...args: any[]) => void;
                        warn: (...args: any[]) => void;
                        error: (...args: any[]) => void;
                    };
                };

                /**
                 * Google Tag Manager / Generic Data Layer array.
                 */
                declare const dataLayer: any[];

                /**
                 * Global Adobe object.
                 */
                declare const adobe: any;

                /**
                 * Global digitalData object (commonly used for data layers).
                 */
                declare const digitalData: any;

                /**
                 * Allow custom properties on the window object.
                 */
                interface Window {
                    [key: string]: any;
                }
            `;

            monaco.languages.typescript.javascriptDefaults.addExtraLib(
                tagGlobals,
                'ts:tag-globals.d.ts'
            );

            // Determine initial theme from local storage (default is vs-dark/twilight theme)
            var localTheme = localStorage.getItem('monacoTheme') || 'vs';
            var themeToggle = document.getElementById('themeToggle');

            if (localTheme === 'vs-dark' && themeToggle) {
                themeToggle.checked = true;
            }

            var initialCode = <?= json_encode($tag['js_code'] ?? '') ?>;
            var container = document.getElementById('jsEditor');

            if (!container) return; // In case the editor container doesn't exist

            // Create Monaco Editor
            var editor = monaco.editor.create(container, {
                value: initialCode,
                language: 'javascript',
                theme: localTheme,
                automaticLayout: true,
                minimap: { enabled: false },
                fontSize: 14,
                fontFamily: 'Fira Code, Menlo, Monaco, Consolas, monospace',
                lineHeight: 20,
                tabSize: 4,
                insertSpaces: true,
                wordWrap: 'on',
                cursorBlinking: 'smooth',
                smoothScrolling: true,
                padding: { top: 10, bottom: 10 },
                roundedSelection: true
            });

            // Make editor globally accessible if needed for saving
            window.editorInstance = editor;

            // Handle theme toggle changes
            if (themeToggle) {
                themeToggle.addEventListener('change', function () {
                    var newTheme = this.checked ? 'vs-dark' : 'vs';
                    monaco.editor.setTheme(newTheme);
                    localStorage.setItem('monacoTheme', newTheme);
                });
            }

            // Recalculate editor layout when the Bootstrap accordion is shown
            var collapseElement = document.getElementById('collapseJsCode');
            if (collapseElement) {
                collapseElement.addEventListener('shown.bs.collapse', function () {
                    editor.layout();
                });
            }

            // Track dirty state
            var isDirty = false;
            editor.onDidChangeModelContent(function () {
                isDirty = true;
            });

            // Prevent closing page if unsaved
            window.addEventListener('beforeunload', function (e) {
                if (isDirty) {
                    var msg = "You have unsaved changes in the JavaScript code. Are you sure you want to leave?";
                    e.returnValue = msg;
                    return msg;
                }
            });

            // Register Ctrl-S / Cmd-S save keybinding inside Monaco
            editor.addCommand(monaco.KeyMod.CtrlCmd | monaco.KeyCode.KeyS, function () {
                var btn = document.getElementById('saveJsCodeBtn');
                if (btn) btn.click();
            });

            // Listen to markers/errors dynamically for the validation panel
            monaco.editor.onDidChangeMarkers(function () {
                var model = editor.getModel();
                if (!model) return;
                var markers = monaco.editor.getModelMarkers({ resource: model.uri });
                var panel = document.getElementById('jsValidationPanel');
                if (!panel) return;

                if (markers.length === 0) {
                    panel.innerHTML = '<div class="text-success fw-medium"><i class="bi bi-check-circle-fill me-1"></i> No errors or warnings</div>';
                } else {
                    var html = '';
                    markers.forEach(function (marker) {
                        // severity: 8 = Error, 4 = Warning, 2 = Info, 1 = Hint
                        var isError = marker.severity === 8;
                        var icon = isError ? '<i class="bi bi-x-circle-fill text-danger me-1"></i>' : '<i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>';
                        var colorClass = isError ? 'text-danger' : 'text-warning';
                        html += `<div class="${colorClass} mb-1" style="font-family: monospace;">${icon} Line ${marker.startLineNumber}: ${marker.message}</div>`;
                    });
                    panel.innerHTML = html;
                }
            });

            // Handle Save action
            var saveBtn = document.getElementById('saveJsCodeBtn');
            if (saveBtn) {
                saveBtn.addEventListener('click', function () {
                    var model = editor.getModel();
                    var markers = model ? monaco.editor.getModelMarkers({ resource: model.uri }) : [];
                    var errors = markers.filter(function (m) { return m.severity === 8; });

                    var formatList = function (list) {
                        var html = list.map(item => `Line ${item.startLineNumber}: ${item.message}`).join('<br>');
                        return `<div style="max-height: 150px; overflow-y: auto; text-align: left; background: rgba(0,0,0,0.03); padding: 10px; border-radius: 4px; font-family: monospace; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.05); margin-top: 10px;">${html}</div>`;
                    };

                    // Warn about compile/syntax errors but allow saving
                    if (errors.length > 0) {
                        if (!confirm('Syntax Errors Detected:\n\nThere are JavaScript errors in your code. Are you sure you want to save anyway?')) {
                            return;
                        }
                    }

                    var btn = this;
                    var originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Saving...';
                    btn.disabled = true;

                    var code = editor.getValue();
                    var formData = new FormData();
                    formData.append('js_code', code);

                    fetch('<?= site_url('tags/save-js-code/' . $tag['id']) ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                isDirty = false;
                                window.themeAlert({
                                    title: 'Success',
                                    message: data.message,
                                    type: 'success'
                                });
                            } else {
                                window.themeAlert({
                                    title: 'Error',
                                    message: data.message || 'Failed to save code.',
                                    type: 'danger'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            window.themeAlert({
                                title: 'Error',
                                message: 'An unexpected error occurred.',
                                type: 'danger'
                            });
                        })
                        .finally(() => {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        });
                });
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const chatToggle = document.getElementById('appleChatToggle');
        const chatWindow = document.getElementById('appleChatWindow');
        const chatInput = document.getElementById('appleChatInput');
        const chatSend = document.getElementById('appleChatSend');
        const chatMessages = document.getElementById('appleChatMessages');
        const modelSelect = $('#aiModelSelect');

        // Make Chat Window Draggable
        const chatHeader = document.querySelector('.apple-chat-header');
        let isDragging = false;
        let dragOffsetX = 0;
        let dragOffsetY = 0;

        chatHeader.style.cursor = 'move';
        
        chatHeader.addEventListener('mousedown', (e) => {
            // Ignore drag if clicking on the select dropdown
            if (e.target.closest('.apple-model-select') || e.target.closest('.select2-container')) {
                return;
            }
            
            isDragging = true;
            const rect = chatWindow.getBoundingClientRect();
            
            // Switch to fixed positioning if not already
            if (chatWindow.style.position !== 'fixed') {
                chatWindow.style.position = 'fixed';
                chatWindow.style.margin = '0';
                chatWindow.style.bottom = 'auto';
                chatWindow.style.right = 'auto';
                chatWindow.style.left = rect.left + 'px';
                chatWindow.style.top = rect.top + 'px';
            }
            
            dragOffsetX = e.clientX - chatWindow.getBoundingClientRect().left;
            dragOffsetY = e.clientY - chatWindow.getBoundingClientRect().top;
            document.body.style.userSelect = 'none'; // Prevent text selection while dragging
        });

        document.addEventListener('mousemove', (e) => {
            if (isDragging) {
                chatWindow.style.left = (e.clientX - dragOffsetX) + 'px';
                chatWindow.style.top = (e.clientY - dragOffsetY) + 'px';
            }
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
            document.body.style.userSelect = '';
        });

        // Initialize Select2 for the AI models
        modelSelect.select2({
            width: '100%',
            dropdownParent: $('#appleChatWindow'),
            placeholder: "Select an AI model",
            templateResult: function (data) {
                if (!data.id) return data.text;
                var desc = $(data.element).data('description') || '';
                var descHtml = desc ? '<div class="ai-model-desc">' + desc + '</div>' : '';
                return $(
                    '<div class="ai-model-item">' +
                    '<div class="ai-model-title">' + data.text + '</div>' +
                    descHtml +
                    '</div>'
                );
            },
            templateSelection: function(data) {
                return data.text;
            }
        });

        // Toggle Chat Window
        chatToggle.addEventListener('click', () => {
            chatWindow.classList.toggle('active');
            if (chatWindow.classList.contains('active')) {
                chatInput.focus();
            }
        });

        // Handle Send Message
        function sendMessage() {
            const text = chatInput.value.trim();
            if (!text) return;

            const modelId = modelSelect.val();
            if (!modelId) {
                appendMessage('Please select a model first.', 'bot');
                return;
            }

            // Add user message to UI
            appendMessage(text, 'user');
            chatInput.value = '';

            // Get script content from monaco editor if available
            let scriptContent = '';
            if (window.editorInstance) {
                scriptContent = window.editorInstance.getValue();
            }

            // Show typing indicator
            const botMsgElem = appendMessage('', 'bot');
            botMsgElem.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';

            const chatEndpoint = '<?= site_url('tags/ai-chat') ?>'; 
            
            fetch(chatEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    modelId: modelId,
                    message: text,
                    context: scriptContent // Send current script context to the LLM
                })
            })
            .then(res => res.json())
            .then(data => {
                let reply = "I'm sorry, I couldn't process your request.";
                let reasoning = null;
                
                if (data && data.choices && data.choices.length > 0 && data.choices[0].message) {
                    reply = data.choices[0].message.content || reply;
                    reasoning = data.choices[0].message.reasoning_content || null;
                } else if (data.reply || data.message) {
                    reply = data.reply || data.message;
                }

                if (typeof marked !== 'undefined' && typeof DOMPurify !== 'undefined') {
                    botMsgElem.innerHTML = DOMPurify.sanitize(marked.parse(reply));
                } else {
                    botMsgElem.innerText = reply;
                }
                
                if (reasoning && botMsgElem.parentElement.classList.contains('chat-wrapper')) {
                    const infoIcon = document.createElement('i');
                    infoIcon.className = 'bi bi-info-circle-fill text-muted';
                    infoIcon.style.cursor = 'help';
                    infoIcon.style.fontSize = '1.1rem';
                    infoIcon.title = reasoning; // Hover to see reasoning
                    botMsgElem.parentElement.appendChild(infoIcon);
                }

                chatMessages.scrollTop = chatMessages.scrollHeight;
            })
            .catch(err => {
                console.error('Error sending message:', err);
                botMsgElem.innerText = "Error communicating with the LLM API. Check console for details.";
                chatMessages.scrollTop = chatMessages.scrollHeight;
            });
        }

        function appendMessage(text, sender) {
            const wrapper = document.createElement('div');
            wrapper.className = 'chat-wrapper';
            wrapper.style.display = 'flex';
            wrapper.style.alignItems = 'center';
            wrapper.style.gap = '8px';
            wrapper.style.alignSelf = sender === 'user' ? 'flex-end' : 'flex-start';
            wrapper.style.maxWidth = '100%';

            const msgDiv = document.createElement('div');
            msgDiv.className = `chat-message ${sender}`;
            msgDiv.innerText = text;
            msgDiv.style.alignSelf = 'auto'; // override class styling
            msgDiv.style.margin = '0';
            
            wrapper.appendChild(msgDiv);
            chatMessages.appendChild(wrapper);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            return msgDiv;
        }

        chatSend.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    });
</script>
<?= $this->endSection() ?>
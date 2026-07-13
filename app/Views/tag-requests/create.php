<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Tag Request: Create
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php 
    use App\Models\Constants;
    use App\Models\PropertiesModel;
    use App\Models\VendorsModel;
    use App\Models\OrganizationsModel;
    use App\Models\TasksModel;

    $propertiesModel = new PropertiesModel();
    $propertiesList = $propertiesModel->select('id, name, is_shop, subsidiary_id')->findAll();
    $property = isset($property_id) ? $propertiesModel->find($property_id) : null;

    $vendorsModel = new VendorsModel();
    $vendors = $vendorsModel->Options();

    $organizationsModel = new OrganizationsModel();
    $organizations = $organizationsModel->Options();

    $tasksModel = new TasksModel();

    $tagsModel = new \App\Models\TagsModel();
    $allTags = $tagsModel->select('tags.name as tag_name, properties.name as property_name, properties.subsidiary_id')
                         ->join('properties', 'properties.id = tags.property_id', 'left')
                         ->findAll();

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
?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="container alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (empty($tag_ids)): ?>
    <!-- #region | PART 1 - REQUEST CREATION -->
    <div class="container-fluid px-0">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <a href="<?= site_url('tag-requests') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back to Requests">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h2 class="mb-0 fw-bold">Add a Tag Request</h2>
                <p class="text-secondary small mb-0">Submit a request for tag deployment or rule validation across properties.</p>
            </div>
        </div>

        <?php if (!empty($extracted_data)): ?>
        <div class="alert alert-info mb-4">
            <i class="bi bi-info-circle-fill me-2"></i>
            Data has been extracted from the uploaded Excel file. Please review and complete the form before proceeding.
            <ul class="mb-0 mt-2">
                <li><strong>Extracted Vendor:</strong> <?= esc($extracted_data['vendor_name']) ?></li>
                <li><strong>Target Property/Sites:</strong> <?= esc($extracted_data['target_properties']) ?></li>
                <li><strong>Extracted Tag Script:</strong> <?= esc($extracted_data['tag_script']) ?></li>
                <li><strong>Background:</strong> <?= esc($extracted_data['background']) ?></li>
                <li><strong>Requestor Name:</strong> <?= esc($extracted_data['requester_name'] ?? '') ?></li>
                <li><strong>Requestor Email:</strong> <?= esc($extracted_data['requester_email'] ?? '') ?></li>
                <li><strong><?= Constants::PURPOSE_GOAL_NEW_TAG[0] ?></strong> <?= esc($extracted_data['q1']) ?></li>
                <li><strong><?= Constants::PURPOSE_GOAL_NEW_TAG[1] ?></strong> <?= esc($extracted_data['q2']) ?></li>
                <li><strong><?= Constants::PURPOSE_GOAL_NEW_TAG[2] ?></strong> <?= esc($extracted_data['q3']) ?></li>
                <li><strong><?= Constants::PURPOSE_GOAL_NEW_TAG[3] ?></strong> <?= esc($extracted_data['q4']) ?></li>
                <li><strong><?= Constants::PURPOSE_GOAL_NEW_TAG[4] ?></strong> <?= esc($extracted_data['q5']) ?></li>
            </ul>
        </div>
        <?php endif; ?>

        <form action="<?= site_url('tag-requests/create') ?>" method="post">
            <?= csrf_field() ?>
            <?php if (!empty($extracted_data)): ?>
                <input type="hidden" name="background" value="<?= esc($extracted_data['background'] ?? '') ?>">
                <input type="hidden" name="q1" value="<?= esc($extracted_data['q1'] ?? '') ?>">
                <input type="hidden" name="q2" value="<?= esc($extracted_data['q2'] ?? '') ?>">
                <input type="hidden" name="q3" value="<?= esc($extracted_data['q3'] ?? '') ?>">
                <input type="hidden" name="q4" value="<?= esc($extracted_data['q4'] ?? '') ?>">
                <input type="hidden" name="q5" value="<?= esc($extracted_data['q5'] ?? '') ?>">
                <input type="hidden" name="excel_file_path" value="<?= esc($extracted_data['excel_file_path'] ?? '') ?>">
                <input type="hidden" name="excel_file_name" value="<?= esc($extracted_data['excel_file_name'] ?? '') ?>">
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-8">

            <!-- Tag Details Section -->
            <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                    <i class="bi bi-tag-fill"></i>
                    <span>Request Details</span>
                </h5>
                
                <div class="row g-3">
                    <!-- Property -->
                    <div class="col-md-6">
                        <label class="form-label-sm fw-semibold text-secondary mb-1">Property</label>
                        <select name="property_id" class="form-select searchable-select" required>
                            <?php foreach ($propertiesList as $prop): ?>
                                <option value="<?= esc($prop['id']) ?>"
                                    data-is-shop="<?= $prop['is_shop'] == Constants::YES ? 'Shop' : 'AEM' ?>"
                                    data-subsidiary-id="<?= esc($prop['subsidiary_id'] ?? '') ?>"
                                    <?= old('property_id', $requests['property_id'] ?? ($extracted_data['property_id'] ?? '')) == $prop['id'] ? 'selected' : '' ?>>
                                    <?= esc($prop['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Request Type -->
                    <div class="col-md-6">
                        <label class="form-label-sm fw-semibold text-secondary mb-1">Request Type</label>
                        <select name="type" class="form-select form-select-xs" required>
                            <?php foreach (Constants::REQUEST_TYPES as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('type', $requests['type'] ?? 1) == $value ? 'selected' : '' ?>>
                                    <?= esc($label . ' Request') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tag(s) -->
                    <div class="col-md-12">
                        <label class="form-label-sm fw-semibold text-secondary mb-1">Tag(s)</label>
                        <select name="tags[]" class="form-select advanced-select" multiple required>
                        </select>
                        <small class="form-text text-muted" style="font-size: 0.75rem;">
                            Tag names should start with <strong>LC-</strong>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Requester Details Section -->
            <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                    <i class="bi bi-person-fill"></i>
                    <span>Requester Details</span>
                </h5>
                
                <div class="row g-3">
                    <!-- Organization -->
                    <div class="col-md-4">
                        <label class="form-label-sm fw-semibold text-secondary mb-1">Organization</label>
                        <select name="requester_organization" class="form-select advanced-select">
                            <?php foreach ($organizations as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('requester_organization', $tag['requester_organization'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Name -->
                    <div class="col-md-4">
                        <label class="form-label-sm fw-semibold text-secondary mb-1" for="requester_name">Name</label>
                        <input type="text" name="requester_name" id="requester_name"
                            class="form-control form-control-xs"
                            list="requester-name-list"
                            value="<?= old('requester_name', $old['requester_name'] ?? ($extracted_data['requester_name'] ?? '')) ?>"
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
                            value="<?= old('requester_email', $old['requester_email'] ?? ($extracted_data['requester_email'] ?? '')) ?>"
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
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="<?= site_url('tag-requests') ?>" title="Cancel" class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
                <button type="submit" class="apple-btn-primary px-4">Proceed</button>
            </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- UUID Generator Section -->
                    <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-primary d-flex align-items-center gap-2 fw-bold" style="font-size: 1rem;">
                                <i class="bi bi-key-fill"></i>
                                <span>UUID Generator</span>
                            </h5>
                            <a href="javascript:void(0)" onclick="generateUUID()" class="text-secondary" title="Regenerate UUID">
                                <i class="bi bi-arrow-clockwise fs-5"></i>
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <input type="text" id="uuid_display" class="form-control form-control-sm text-center fw-mono bg-light" readonly value="">
                            <a href="javascript:void(0)" onclick="copyUUID()" class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center" title="Copy UUID" style="width: 32px; height: 32px;">
                                <i class="bi bi-copy"></i>
                            </a>
                        </div>
                    </div>

                    <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                        <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                            <i class="bi bi-tags-fill"></i>
                            <span>Related Tag(s) on Vendor</span>
                        </h5>
                        <div id="related_tags_container">
                            <p class="text-muted small mb-0">Select a tag to see other tags with the same vendor.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- #endregion -->

<?php 
    elseif ($request_type == Constants::JIRA_TYPE_NEW_TAG):
        $property = $propertiesModel->find($property_id);
        $vendor = $vendorsModel->find($vendor_id);
        $siteCode = $propertiesModel->getSiteCode($property_id);
        $isShop = $propertiesModel->isShop($property_id);
        $reason = !empty($background) ? $background : ($vendor['reason'] ?? '-');
        $ssGTM = Constants::JIRA_SSGTM_TICKET;
        $jiraTitle = sprintf(
            '[LC Request] [%s] - [%s] - [New Tag]',
            $siteCode,
            $vendor['name']
        );

        $actionLink = empty($ticket_id) ? site_url('tag-requests/create/confirm')
            : site_url('tag-requests/view/' . $request_id);

        $detailsHtml = "
            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>1) Target Property :</b>
            <ul style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F;'>
                <li>". $isShop ." : ". $property['name'] ."</li>
            </ul>

            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>2) Target Page(s) :</b>
            <ul style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F;'>
                <li>". $isShop ." : <a href='". $property['website'] ."' target='_blank' style='color:#0071E3; text-decoration:none;'>". $property['website'] ."</a></li>
            </ul>

            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>2.2) Reason when the Target Page is for all :</b>
            <p style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F; padding-left:12px;'>". $reason ."</p>

            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>3) Current Status (as-is)</b>
            <p style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#86868B; font-style:italic; padding-left:12px;'>Insert Local Rule Monitoring File screenshots here...</p>

            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>4) Purpose & Goal :</b>
            <table cellpadding='8' cellspacing='0' style='border-collapse:collapse; width:100%; border:1px solid #e8e8ed; font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; margin-bottom:16px;'>
                <tbody>";

        $qAnswers = [
            $q1 ?? '',
            $q2 ?? '',
            $q3 ?? '',
            $q4 ?? '',
            $q5 ?? '',
        ];

        foreach (Constants::PURPOSE_GOAL_NEW_TAG as $index => $value) {
            $ans = !empty($qAnswers[$index]) ? $qAnswers[$index] : 'Insert answer here from the Media Tag Request form...';
            $detailsHtml .= "
                <tr style='border-bottom:1px solid #e8e8ed;'>
                    <td style='width:50%; color:#49494D; font-weight:500; border-right:1px solid #e8e8ed; padding:8px 12px;'>
                        ". $value ."
                    </td>
                    <td style='color:#1D1D1F; padding:8px 12px;'><i>
                        ". $ans ."
                    </i></td>
                </tr>
            ";
        }
        
        $detailsHtml .= "
            </tbody>
        </table>
        
        <br/>
        <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>5) ssGTM</b>
        <p style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F; padding-left:12px;'>
            For ssGTM requests, please refer to this ticket: 
            <a href='". $ssGTM ."' target='_blank' style='color:#0071E3; text-decoration:none;'> ssGTM </a> 
        </p>
        ";

?>

<!-- region | PART 2 - NEW TAG REQUEST CONFIRMATION -->
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= $ticket_id ? site_url('tag-requests/view/' . $request_id) : site_url('tag-requests') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold"><?= Constants::JIRA_TICKET_TYPES[$request_type] ?> Request</h2>
            <p class="text-secondary small mb-0 fw-semibold">
                <?php if ($ticket_id): ?>
                    Details & Template - <span class="text-danger fw-semibold"><?= esc($ticket_id) ?></span>
                <?php else: ?>
                    Confirmation & JIRA Integration
                <?php endif; ?>
            </p>
        </div>
    </div>

    <form action="<?= $actionLink ?>" method="post">
        <div class="row g-4 mt-1">
            <!-- Left Column: JIRA Info & Details -->
            <div class="col-lg-5 d-flex flex-column">
                <!-- JIRA Ticket Information -->
                <div class="apple-card mb-4">
                    <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-jira"></i>
                        <span>JIRA Ticket Information</span>
                    </h5>
                    
                    <div class="mb-3">
                        <label class="form-label-sm fw-semibold text-secondary mb-1">Proposed JIRA Title</label>
                        <div class="d-flex align-items-start gap-2">
                            <span id="jiraTitle" class="fw-bold text-dark fs-6" style="word-break: break-word;"><?= esc($jiraTitle) ?></span>
                            <a href="javascript:void(0)" onclick="copyJiraTitle()" class="text-primary text-decoration-none pt-1">
                                <i class="bi bi-copy fs-6"></i>
                            </a>
                            <span id="copyStatus" class="badge bg-success" style="opacity:0; transition: opacity .6s; font-size:10px;">
                                Copied!
                            </span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label-sm fw-semibold text-secondary mb-1">JIRA Ticket ID</label>
                        <?php if ($ticket_id == null) : ?>
                            <div class="d-flex gap-2">
                                <input type="hidden" name="request_id" value="<?= esc($request_id) ?>">
                                <input type="text" name="ticket_id" id="ticket_id"
                                    class="form-control form-control-xs"
                                    value="<?= old('ticket_id', $old['ticket_id'] ?? '') ?>"
                                    placeholder="GTA-XXXXX"
                                    required>
                                <a href="<?= Constants::JIRA_CREATE_ISSUE ?>" title="Create Issue in JIRA" class="apple-btn-secondary px-3 text-decoration-none btn-sm d-flex align-items-center justify-content-center text-nowrap" target="_blank">
                                    Create JIRA Ticket
                                </a>
                            </div>
                            <small class="form-text text-muted mt-1 d-block" style="font-size: 0.75rem;">
                                Format: GTA-XXXXX
                            </small>
                        <?php else: ?>
                            <div class="fw-bold text-dark fs-5 mb-2 d-flex align-items-center gap-2">
                                <?php $relatedTask = $tasksModel->where('ticket_id', $ticket_id)->first(); ?>
                                <?php if ($relatedTask): ?>
                                    <a href="<?= site_url('tasks/view/' . $relatedTask['id']) ?>" class="apple-link text-decoration-none" title="View related Task">
                                        <?= esc($ticket_id) ?>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= Constants::JIRA_LINK . $ticket_id ?>" target="_blank" rel="noopener" class="apple-link text-decoration-none">
                                        <?= esc($ticket_id) ?>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= Constants::JIRA_LINK . $ticket_id ?>" target="_blank" rel="noopener" class="text-muted" style="font-size: 0.85rem; text-decoration: none;" title="View in JIRA">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                            <input type="hidden" name="ticket_id" value="<?= esc($ticket_id) ?>">
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Target Summary Card -->
                <div class="apple-card flex-grow-1 mb-4">
                    <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Request Details</span>
                    </h5>
                    <div class="mb-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Property</label>
                        <span class="fw-semibold text-dark ms-1 group-hover-container align-items-center" style="font-size: 0.9rem;">
                            <a href="<?= esc((isset($property['ref_link']) ? $property['ref_link'] . Constants::AL_LIBRARY_CREATE : '#')) ?>" target="_blank" class="apple-link-dark text-decoration-none">
                                <?= esc($property['name'] ?? '—') ?>
                            </a>
                            <a href="javascript:void(0)" onclick="copyName('<?= esc($property['name'] ?? '—') ?>', this)" title="Copy property name" class="text-muted ms-2" style="transition: color 0.2s; text-decoration: none; line-height: 1;">
                                <i class="bi bi-copy" style="font-size: 0.85rem;"></i>
                            </a>
                        </span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Vendor</label>
                        <span class="fw-semibold text-dark ms-1" style="font-size: 0.9rem;">
                            <?= esc($vendor['name'] ?? '—') ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Description Editor -->
            <div class="col-lg-7 d-flex flex-column">
                <div class="apple-card flex-grow-1 d-flex flex-column mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-primary d-flex align-items-center gap-2 fw-bold">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            <span>Ticket Description (HTML)</span>
                        </h5>
                        <div>
                            <span id="copyDetailsStatus" class="badge bg-success me-2" style="opacity:0; transition: opacity .6s; font-size:10px;">
                                Copied!
                            </span>
                            <a href="javascript:void(0)" onclick="copyDetails()" class="apple-btn-secondary btn-sm px-3 text-decoration-none">
                                <i class="bi bi-copy me-1"></i> Copy HTML
                            </a>
                        </div>
                    </div>
                    <div class="flex-grow-1 mb-3">
                        <textarea id="details"></textarea>
                    </div>
                    
                    <!-- Action Buttons aligned inside the card bottom -->
                    <div class="d-flex justify-content-end gap-2 mt-auto pt-3 border-top">
                        <a href="<?= $ticket_id ? site_url('tag-requests/view/' . $request_id) : site_url('tag-requests') ?>" title="Cancel" class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
                        <?php if ($ticket_id == null) : ?>
                            <button type="submit" class="apple-btn-primary px-4">Confirm Request</button>
                        <?php else: ?>
                            <a href="<?= site_url('tag-requests/view/' . $request_id) ?>" class="apple-btn-primary px-4 text-decoration-none">Back to Request</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- #endregion -->

<?php elseif ($request_type == Constants::JIRA_TYPE_NEW_RULE) :
    $property = $propertiesModel->find($property_id);
    $vendor = $vendorsModel->find($vendor_id);
    $siteCode = $propertiesModel->getSiteCode($property_id);
    $isShop = $propertiesModel->isShop($property_id);
    $reason = !empty($background) ? $background : ($vendor['reason'] ?? '-');
    $purpose = $vendor['tag_purpose'] ?? '-';
    $today = date('dmY');
    $libraryName = !empty($ticket_id) ? $ticket_id . '_'. $today : '_' . $today;
    $ssGTM = Constants::JIRA_SSGTM_TICKET;
    $jiraTitle = sprintf(
            '[LC Request] [%s] - [%s] - [New Rule]',
            $siteCode,
            $vendor['name']
    );

    $actionLink = empty($ticket_id) ? site_url('tag-requests/create/confirm')
            : site_url('tag-requests/view/' . $request_id);

    $detailsHtml = "
            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>1) Target Property :</b>
            <ul style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F;'>
                <li>". $isShop ." : ". $property['name'] ."</li>
                <li>Library Name : ". $libraryName ."</li>
            </ul>
    
            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>2) Target Page(s) :</b>
            <ul style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F;'>
                <li>". $isShop ." : <a href='". $property['website'] ."' target='_blank' style='color:#0071E3; text-decoration:none;'>". $property['website'] ."</a></li>
            </ul>
    
            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>2.2) Reason when the Target Page is for all :</b>
            <p style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F; padding-left:12px;'>". $reason ."</p>
    
            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>3) Expiration Date (to-be) : </b><span style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F;'>". $al_end_date ."</span> <br/><br/>
    
            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>4) Status (to-be) :</b>
            <p style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#86868B; font-style:italic; padding-left:12px;'>Insert Local Rule Monitoring File screenshots here...</p>
            
            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>5) Purpose & Goal :</b>
            <p style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F; padding-left:12px;'>". $purpose ."</p>
            
            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>6) ssGTM</b>
            <p style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F; padding-left:12px;'>
                For ssGTM requests, please refer to this ticket: 
                <a href='". $ssGTM ."' target='_blank' style='color:#0071E3; text-decoration:none;'> ssGTM </a> 
            </p>
            
            <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>7) Required Attachment</b>
            <p style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F; padding-left:12px;'><b>① Test Result Screenshot image file(s)</b></p>
            <p style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#86868B; font-style:italic; padding-left:24px;'>Insert QA screenshots here...</p>
            
            <br/>
            
            <p style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F;'><b>② Integration Result: Fill in the checklist below to make sure the new tags aren't affecting your site's operation.</b></p>
            <table cellpadding='8' cellspacing='0' style='border-collapse:collapse; width:100%; border:1px solid #e8e8ed; font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; margin-bottom:16px;'>
                <tbody>";

            foreach (Constants::INTEGRATION_RESULT_PAGE_LOAD as $question => $value) {
                $detailsHtml .= "
                    <tr style='border-bottom:1px solid #e8e8ed;'>
                        <td style='width:50%; color:#49494D; font-weight:500; border-right:1px solid #e8e8ed; padding:8px 12px;'>". $question ."</td>
                        <td style='color:#1D1D1F; padding:8px 12px;'>". $value ."</td>
                    </tr>
                ";
            }

            $detailsHtml .= "
                </tbody>
            </table>
            
            <br/>
            
            <table cellpadding='8' cellspacing='0' style='border-collapse:collapse; width:100%; border:1px solid #e8e8ed; font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; margin-bottom:16px;'>
                <tbody>";

            foreach (Constants::INTEGRATION_RESULT_EVENT_CLICK as $question => $value) {
                $detailsHtml .= "
                    <tr style='border-bottom:1px solid #e8e8ed;'>
                        <td style='width:50%; color:#49494D; font-weight:500; border-right:1px solid #e8e8ed; padding:8px 12px;'>". $question ."</td>
                        <td style='color:#1D1D1F; padding:8px 12px;'>". $value ."</td>
                    </tr>
                ";
            }

            $detailsHtml .= "
                        </tbody>
                    </table>
                    
                    <br/>
                    
            <p style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F;'><b>③ Local Rule Monitoring Excel file [Local Rule Monitoring Excel file_v1.0_Subsidiary_sitecode_YYMMDD_general.xlsx]</b></p>
            ";
?>

<!-- region | PART 3 - NEW RULE REQUEST CONFIRMATION -->
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= $ticket_id ? site_url('tag-requests/view/' . $request_id) : site_url('tag-requests') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold"><?= Constants::JIRA_TICKET_TYPES[$request_type] ?> Request</h2>
            <p class="text-secondary small mb-0 fw-semibold">
                <?php if ($ticket_id): ?>
                    Details & Template - <span class="text-danger fw-semibold"><?= esc($ticket_id) ?></span>
                <?php else: ?>
                    Confirmation & JIRA Integration
                <?php endif; ?>
            </p>
        </div>
    </div>

    <form action="<?= $actionLink ?>" method="get">
        <div class="row g-4 mt-1">
            <!-- Left Column: JIRA Info & Details -->
            <div class="col-lg-5 d-flex flex-column">
                <!-- JIRA Ticket Information -->
                <div class="apple-card mb-4">
                    <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-jira"></i>
                        <span>JIRA Ticket Information</span>
                    </h5>
                    
                    <div class="mb-3">
                        <label class="form-label-sm fw-semibold text-secondary mb-1">Proposed JIRA Title</label>
                        <div class="d-flex align-items-start gap-2">
                            <span id="jiraTitle" class="fw-bold text-dark fs-6" style="word-break: break-word;"><?= esc($jiraTitle) ?></span>
                            <a href="javascript:void(0)" onclick="copyJiraTitle()" class="text-primary text-decoration-none pt-1">
                                <i class="bi bi-copy fs-6"></i>
                            </a>
                            <span id="copyStatus" class="badge bg-success" style="opacity:0; transition: opacity .6s; font-size:10px;">
                                Copied!
                            </span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label-sm fw-semibold text-secondary mb-1">JIRA Ticket ID</label>
                        <?php if ($ticket_id == null) : ?>
                            <div class="d-flex gap-2">
                                <input type="hidden" name="request_id" value="<?= esc($request_id) ?>">
                                <input type="text" name="ticket_id" id="ticket_id"
                                    class="form-control form-control-xs"
                                    value="<?= old('ticket_id', $old['ticket_id'] ?? '') ?>"
                                    placeholder="GTA-XXXXX"
                                    required>
                                <a href="<?= Constants::JIRA_CREATE_ISSUE ?>" title="Create Issue in JIRA" class="apple-btn-secondary px-3 text-decoration-none btn-sm d-flex align-items-center justify-content-center text-nowrap" target="_blank">
                                    Create JIRA Ticket
                                </a>
                            </div>
                            <small class="form-text text-muted mt-1 d-block" style="font-size: 0.75rem;">
                                Format: GTA-XXXXX
                            </small>
                        <?php else: ?>
                            <div class="fw-bold text-dark fs-5 mb-2 d-flex align-items-center gap-2">
                                <?php $relatedTask = $tasksModel->where('ticket_id', $ticket_id)->first(); ?>
                                <?php if ($relatedTask): ?>
                                    <a href="<?= site_url('tasks/view/' . $relatedTask['id']) ?>" class="apple-link text-decoration-none" title="View related Task">
                                        <?= esc($ticket_id) ?>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= Constants::JIRA_LINK . $ticket_id ?>" target="_blank" rel="noopener" class="apple-link text-decoration-none">
                                        <?= esc($ticket_id) ?>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= Constants::JIRA_LINK . $ticket_id ?>" target="_blank" rel="noopener" class="text-muted" style="font-size: 0.85rem; text-decoration: none;" title="View in JIRA">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                            <input type="hidden" name="ticket_id" value="<?= esc($ticket_id) ?>">
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Target Summary Card -->
                <div class="apple-card flex-grow-1 mb-4">
                    <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Request Details</span>
                    </h5>
                    <div class="mb-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Property</label>
                        <span class="fw-semibold text-dark ms-1 group-hover-container align-items-center" style="font-size: 0.9rem;">
                            <a href="<?= esc($property['ref_link'] ?? '#') ?>" target="_blank" class="apple-link-dark text-decoration-none">
                                <?= esc($property['name'] ?? '—') ?>
                            </a>
                            <a href="javascript:void(0)" onclick="copyName('<?= esc($property['name'] ?? '—') ?>', this)" title="Copy property name" class="text-muted ms-2" style="transition: color 0.2s; text-decoration: none; line-height: 1;">
                                <i class="bi bi-copy" style="font-size: 0.85rem;"></i>
                            </a>
                        </span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Vendor</label>
                        <span class="fw-semibold text-dark ms-1" style="font-size: 0.9rem;">
                            <?= esc($vendor['name'] ?? '—') ?>
                        </span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-xs fw-semibold text-secondary mb-1 d-block">Library Name</label>
                        <div class="d-flex align-items-center gap-2 ms-1">
                            <span class="fw-mono text-dark" style="font-size: 0.85rem;">
                                <code id="libraryNameVal"><?= esc($libraryName) ?></code>
                            </span>
                            <a href="javascript:void(0)" onclick="copyLibraryName()" class="text-primary text-decoration-none d-flex align-items-center" title="Copy Library Name">
                                <i class="bi bi-copy fs-6"></i>
                            </a>
                            <span id="copyLibraryStatus" class="badge bg-success" style="opacity:0; transition: opacity .6s; font-size:10px;">
                                Copied!
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Description Editor -->
            <div class="col-lg-7 d-flex flex-column">
                <div class="apple-card flex-grow-1 d-flex flex-column mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-primary d-flex align-items-center gap-2 fw-bold">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            <span>Ticket Description (HTML)</span>
                        </h5>
                        <div>
                            <span id="copyDetailsStatus" class="badge bg-success me-2" style="opacity:0; transition: opacity .6s; font-size:10px;">
                                Copied!
                            </span>
                            <a href="javascript:void(0)" onclick="copyDetails()" class="apple-btn-secondary btn-sm px-3 text-decoration-none">
                                <i class="bi bi-copy me-1"></i> Copy HTML
                            </a>
                        </div>
                    </div>
                    <div class="flex-grow-1 mb-3">
                        <textarea id="details"></textarea>
                    </div>
                    
                    <!-- Action Buttons aligned inside the card bottom -->
                    <div class="d-flex justify-content-end gap-2 mt-auto pt-3 border-top">
                        <a href="<?= $ticket_id ? site_url('tag-requests/view/' . $request_id) : site_url('tag-requests') ?>" title="Cancel" class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
                        <?php if ($ticket_id == null) : ?>
                            <button type="submit" class="apple-btn-primary px-4">Confirm Request</button>
                        <?php else: ?>
                            <a href="<?= site_url('tag-requests/view/' . $request_id) ?>" class="apple-btn-primary px-4 text-decoration-none">Back to Request</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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

        $('select[name="property_id"]').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var isShop = selectedOption.data('is-shop') || 'AEM';
            
            var $searchField = $('.select2-search__field');
            var val = $searchField.val() || '';
            
            if (val.startsWith('LC-')) {
                var parts = val.split('-');
                if (parts.length > 1 && (parts[1] === 'Shop' || parts[1] === 'AEM')) {
                    parts[1] = isShop;
                    $searchField.val(parts.join('-')).trigger('input');
                }
            }
            updateRelatedTags();
        });

        var tempSearchValue = '';
        $('select[name="tags[]"]').on('select2:closing', function (e) {
            tempSearchValue = $('.select2-search__field').val();
        });

        $('select[name="tags[]"]').on('select2:close', function (e) {
            if (tempSearchValue) {
                setTimeout(function() {
                    $('.select2-search__field').val(tempSearchValue);
                    updateRelatedTags();
                }, 10);
            }
        });

        var allExistingTags = <?= json_encode($allTags) ?>;

        window.copyTagText = function(text, el) {
            const temp = document.createElement('textarea');
            temp.value = text;
            temp.style.position = 'fixed';
            temp.style.left = '-9999px';
            document.body.appendChild(temp);
            temp.focus();
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);

            const originalClass = el.className;
            el.className = 'bi bi-check text-success';
            setTimeout(() => {
                el.className = originalClass;
            }, 1200);
        };

        function updateRelatedTags() {
            var selectedTags = $('select[name="tags[]"]').val() || [];
            var vendors = new Set();
            
            selectedTags.forEach(function(tag) {
                var matches = tag.match(/\(([^)]+)\)/g);
                if (matches) {
                    matches.forEach(function(match) {
                        var vendorName = match.replace(/[()]/g, '').trim();
                        if (vendorName) {
                            vendors.add(vendorName);
                        }
                    });
                }
            });

            var currentInput = $('.select2-search__field').val() || '';
            var partialMatch = currentInput.match(/\(([^)]*)/g);
            if (partialMatch) {
                partialMatch.forEach(function(match) {
                    var vendorName = match.replace('(', '').replace(')', '').trim();
                    if (vendorName) {
                        vendors.add(vendorName);
                    }
                });
            }
            
            var selectedPropertyOption = $('select[name="property_id"] option:selected');
            var selectedSubsidiaryId = selectedPropertyOption.data('subsidiary-id') || '';
            
            var relatedTags = [];
            vendors.forEach(function(vendor) {
                var vendorLower = vendor.toLowerCase();
                allExistingTags.forEach(function(existingTag) {
                    var existingLower = (existingTag.tag_name || '').toLowerCase();
                    if (existingLower.includes('(' + vendorLower)) {
                        if (!selectedSubsidiaryId || existingTag.subsidiary_id == selectedSubsidiaryId) {
                            relatedTags.push(existingTag);
                        }
                    }
                });
            });
            
            var groupedTags = {};
            relatedTags.forEach(function(tagObj) {
                var propName = tagObj.property_name || 'Unknown Property';
                var tagName = tagObj.tag_name;
                if (!groupedTags[propName]) {
                    groupedTags[propName] = new Set();
                }
                groupedTags[propName].add(tagName);
            });
            
            var $container = $('#related_tags_container');
            if (Object.keys(groupedTags).length > 0) {
                var html = '';
                Object.keys(groupedTags).sort().forEach(function(prop) {
                    html += '<div class="mb-2">';
                    html += '<div class="fw-bold text-dark small mb-1"><i class="bi bi-building me-1 text-muted"></i>' + prop + '</div>';
                    html += '<div class="ms-2">';
                    Array.from(groupedTags[prop]).forEach(function(t) {
                        html += '<div class="badge bg-secondary me-1 mb-1 d-inline-flex align-items-center gap-1" style="font-weight: 500; font-size: 0.8rem; user-select: text; max-width: 100%;">';
                        html += '<i class="bi bi-copy text-light-50 cursor-pointer flex-shrink-0" style="cursor: pointer; font-size: 0.75rem;" onclick="copyTagText(\'' + t.replace(/'/g, "\\'") + '\', this)" title="Copy Tag"></i>';
                        html += '<span class="text-truncate" style="min-width: 0;" title="' + t.replace(/"/g, '&quot;') + '">' + t + '</span>';
                        html += '</div>';
                    });
                    html += '</div></div>';
                });
                $container.html(html);
            } else {
                $container.html('<p class="text-muted small mb-0">No related tags found.</p>');
            }
        }

        $('select[name="tags[]"]').on('change', updateRelatedTags);
        $(document).on('input', '.select2-search__field', updateRelatedTags);

        var defaultTag = `<?= $extracted_data['default_tag'] ?? '' ?>`;
        if (defaultTag) {
            $('.select2-search__field').val(defaultTag).css('width', '100%');
            
            $('.advanced-select').on('select2:open', function() {
                var $searchField = $('.select2-search__field');
                if (!$searchField.val()) {
                    var selectedOption = $('select[name="property_id"] option:selected');
                    var isShop = selectedOption.data('is-shop') || 'AEM';
                    var originalTag = defaultTag;
                    var parts = originalTag.split('-');
                    if (parts.length > 1 && (parts[1] === 'Shop' || parts[1] === 'AEM')) {
                        parts[1] = isShop;
                        originalTag = parts.join('-');
                    }
                    $searchField.val(originalTag).trigger('input');
                }
            });
        }

        $(document).ready(function() {
            $('#details').summernote({
                height: 500,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    // ['font', ['strikethrough', 'superscript', 'subscript']],
                    // ['fontsize', ['fontsize']],
                    // ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    // ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            var detailsHtml = `<?= $detailsHtml ?? null ?>`;
            $('#details').summernote('code', detailsHtml);
        });
    });

    function copyJiraTitle() {
        const title = document.getElementById('jiraTitle').innerHTML;
        const status = document.getElementById('copyStatus');

        const temp = document.createElement('textarea');
        temp.value = title;

        temp.style.position = 'fixed';
        temp.style.left = '-9999px';
        document.body.appendChild(temp);

        temp.focus();
        temp.select();

        document.execCommand('copy');
        document.body.removeChild(temp);

        status.style.opacity = 1;
        setTimeout(() => status.style.opacity = 0, 1000);
    }

    window.copyName = function(text, el) {
        const temp = document.createElement('textarea');
        temp.value = text;

        temp.style.position = 'fixed';
        temp.style.left = '-9999px';

        document.body.appendChild(temp);

        temp.focus();
        temp.select();

        document.execCommand('copy');

        document.body.removeChild(temp);

        el.innerHTML = '<i class="bi bi-check-lg text-success fs-7"></i>';

        setTimeout(() => {
            el.innerHTML = '<i class="bi bi-copy text-secondary fs-7"></i>';
        }, 1200);
    };

    function copyLibraryName() {
        const libName = document.getElementById('libraryNameVal').innerText;
        const status = document.getElementById('copyLibraryStatus');

        const temp = document.createElement('textarea');
        temp.value = libName;

        temp.style.position = 'fixed';
        temp.style.left = '-9999px';
        document.body.appendChild(temp);

        temp.focus();
        temp.select();

        document.execCommand('copy');
        document.body.removeChild(temp);

        status.style.opacity = 1;
        setTimeout(() => status.style.opacity = 0, 1000);
    }

    function copyDetails() {
        const html = $('#details').summernote('code');

        const temp = document.createElement('div');
        temp.style.position = 'fixed';
        temp.style.left = '-9999px';
        temp.style.whiteSpace = 'pre-wrap';
        temp.innerHTML = html;
        document.body.appendChild(temp);

        const range = document.createRange();
        range.selectNodeContents(temp);

        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);

        document.execCommand('copy');

        document.body.removeChild(temp);

        const status = document.getElementById('copyDetailsStatus');
        status.style.opacity = 1;
        setTimeout(() => status.style.opacity = 0, 1000);
    }

    function generateUUID() {
        var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
        document.getElementById('uuid_display').value = uuid;
    }

    function copyUUID() {
        var copyText = document.getElementById("uuid_display");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
    }

    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('uuid_display')) {
            generateUUID();
        }
    });
</script>
<?= $this->endSection() ?>

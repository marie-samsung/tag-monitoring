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

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->Options();

    $vendorsModel = new VendorsModel();
    $vendors = $vendorsModel->Options();

    $organizationsModel = new OrganizationsModel();
    $organizations = $organizationsModel->Options();
?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="container alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (empty($tag_ids)): ?>
    <!-- #region | PART 1 - REQUEST CREATION -->
     <div class="container">
        <h2>Add a Tag Request</h2>    
    </div>
    <form action="<?= site_url('tag-requests/create') ?>" method="post">
        <?= csrf_field() ?>
        <div class="container">
            <div class="card p-3 mt-3">
                <div class="row mb-2">
                    <div class="col mb-2">
                        <label class="form-label-xs text-primary">Property</label>
                        <select name="property_id" class="form-select searchable-select" required>
                            <?php foreach ($properties as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('property_id', $requests['property_id'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col">
                        <label class="form-label-xs text-primary">Tag(s)</label>
                        <select name="tags[]" class="form-select advanced-select" multiple required></select>
                        <small class="form-label-xs text-muted">
                            Tag names should start with LC-
                        </small>
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label-xs text-primary">Request Type</label>
                        <select name="type" class="form-select" required>
                            <?php foreach (Constants::REQUEST_TYPES as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('type', $requests['type'] ?? 1) == $value ? 'selected' : '' ?>>
                                    <?= esc($label . ' Request') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col">
                        <label class="form-label-xs text-primary" for="al_end_date">Launch Expiry Date</label>
                        <input type="date"
                            name="al_end_date"
                            id="al_end_date"
                            class="form-control"
                            value="<?= esc(old('al_end_date', $request['al_end_date'] ?? '')) ?>"
                            required>
                    </div>
                </div>
            </div>

            <!-- #region | REQUESTER DETAILS -->
            <h5 class="mt-4">Requester Details</h5>
            <div class="card p-3">
                <div class="row mb-2">
                    <div class="col">
                        <label class="form-label-xs text-primary">Organization</label>
                        <select name="requester_organization" class="form-select advanced-select" required>
                            <?php foreach ($organizations as $value => $label): ?>
                                <option value="<?= esc($value) ?>"
                                    <?= old('requester_organization', $tag['requester_organization'] ?? '') == $value ? 'selected' : '' ?>>
                                    <?= esc($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col">
                        <label for="requester_name" class="form-label-xs text-primary">Name</label>
                        <input type="text" name="requester_name" id="requester_name"
                            class="form-control"
                            value="<?= old('requester_name', $old['requester_name'] ?? '') ?>"
                            required>
                    </div>

                    <div class="col">
                        <label for="requester_email" class="form-label-xs text-primary">Email Address</label>
                        <input type="text" name="requester_email" id="requester_email"
                            class="form-control"
                            value="<?= old('requester_email', $old['requester_email'] ?? '') ?>"
                            required>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="<?= site_url('tag-requests') ?>" title="Cancel" class="btn btn-danger btn-sm">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm">Proceed</button>
            </div>
            <!-- #endregion -->
        </div>
    </form>
    <!-- #endregion -->

<?php 
    elseif ($request_type == Constants::JIRA_TYPE_NEW_TAG):
        $property = $propertiesModel->find($property_id);
        $siteCode = $propertiesModel->getSiteCode($property_id);
        $jiraTitle = sprintf(
            '[LC Request] [%s] - [%s] - [New Tag]',
            $siteCode,
            $vendor_name
        );
?>
<!-- region | PART 2 - NEW TAG REQUEST CONFIRMATION -->
<form action="<?= site_url('tag-requests/create/confirm') ?>" method="post">
    <div class="container">
        <h4><?= Constants::JIRA_TICKET_TYPES[$request_type] ?> Request</h4>
        <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
            <h4 class="text-primary d-flex align-items-center gap-2">
                <span id="jiraTitle"><?= esc($jiraTitle) ?></span>
                <a href="javascript:void(0)" onclick="copyJiraTitle()">
                    <i class="bi bi-copy text-primary fs-5"></i>
                </a>
                <span id="copyStatus" style="opacity:0; transition: opacity .6s; font-size:10px; color:green;">
                    Copied!
                </span>
            </h4>

            <div class="d-flex justify-content-end">
                <div>
                    <a href="<?= site_url('tag-requests') ?>" title="Cancel" class="btn btn-secondary btn-sm">Cancel</a>
                    <button type="submit" 
                        class="btn btn-success btn-sm">
                        Confirm Request
                    </button>
                </div>
            </div>
        </div>

        <textarea id="details"></textarea>
        <div class="d-flex justify-content-between align-items-center mt-2 gap-2">
            <div class="d-flex align-items-start align-items-center gap-2">
                <div>
                    <input type="hidden" name="request_id" value="<?= $request_id ?>">
                    <input type="text" name="ticket_id" id="ticket_id"
                        class="form-control"
                        value="<?= old('ticket_id', $old['ticket_id'] ?? '') ?>"
                        required>
                </div>
                <a href="<?= Constants::JIRA_CREATE_ISSUE ?>" title="Create Issue in JIRA" class="btn btn-outline-primary btn-sm" target="_blank">
                    Create JIRA Ticket
                </a>
            </div>

            <div>
                <span id="copyDetailsStatus" style="opacity:0; transition: opacity .6s; font-size:10px; color:green;">
                    Copied!
                </span>

                <a href="javascript:void(0)" onclick="copyDetails()">
                    <i class="bi bi-copy text-primary fs-5"></i>
                </a>
            </div>
        </div>

        <small class="form-label-xs text-muted">
            JIRA ticket ID, GTA-XXXXX
        </small>
    </div>
</form>
<!-- #endregion -->

<?php elseif ($request_type == Constants::JIRA_TYPE_NEW_RULE):  ?>
<?php else: ?>
<?php endif; ?>

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

        $(document).ready(function() {
            $('#details').summernote({
                height: 300,
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
</script>

<?= $this->endSection() ?>

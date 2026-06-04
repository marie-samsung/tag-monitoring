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
    $propertiesList = $propertiesModel->select('id, name, is_shop')->findAll();

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
        <h2>Add a Tag Request (From Excel)</h2>
        <div class="alert alert-info">
            Data has been extracted from the uploaded Excel file. Please review and complete the form before proceeding.
            <?php if (!empty($extracted_data)): ?>
            <ul class="mb-0 mt-2">
                <li><strong>Extracted Vendor:</strong> <?= esc($extracted_data['vendor_name']) ?></li>
                <li><strong>Target Property/Sites:</strong> <?= esc($extracted_data['target_properties']) ?></li>
                <li><strong>Extracted Tag Script:</strong> <?= esc($extracted_data['tag_script']) ?></li>
            </ul>
            <?php endif; ?>
        </div>
    </div>
    <form action="<?= site_url('tag-requests/create') ?>" method="post">
        <?= csrf_field() ?>
        <?php if (!empty($extracted_data)): ?>
            <input type="hidden" name="excel_file_path" value="<?= esc($extracted_data['excel_file_path'] ?? '') ?>">
            <input type="hidden" name="excel_file_name" value="<?= esc($extracted_data['excel_file_name'] ?? '') ?>">
        <?php endif; ?>
        <div class="container">
            <div class="card p-3 mt-3">
                <div class="row mb-2">
                    <div class="col mb-2">
                        <label class="form-label-xs text-primary">Property</label>
                        <select name="property_id" class="form-select searchable-select" required>
                            <?php foreach ($propertiesList as $prop): ?>
                                <option value="<?= esc($prop['id']) ?>"
                                    data-is-shop="<?= $prop['is_shop'] == Constants::YES ? 'Shop' : 'AEM' ?>"
                                    <?= old('property_id', $requests['property_id'] ?? ($extracted_data['property_id'] ?? '')) == $prop['id'] ? 'selected' : '' ?>>
                                    <?= esc($prop['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col">
                        <label class="form-label-xs text-primary">Tag(s)</label>
                        <select name="tags[]" class="form-select advanced-select" multiple required>
                        </select>
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
                        <select name="requester_organization" class="form-select advanced-select">
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
                            value="<?= old('requester_name', $old['requester_name'] ?? ($extracted_data['requester_name'] ?? '')) ?>">
                    </div>

                    <div class="col">
                        <label for="requester_email" class="form-label-xs text-primary">Email Address</label>
                        <input type="text" name="requester_email" id="requester_email"
                            class="form-control"
                            value="<?= old('requester_email', $old['requester_email'] ?? ($extracted_data['requester_email'] ?? '')) ?>">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="<?= site_url('tag-requests') ?>" title="Cancel" class="apple-btn-secondary btn-sm">Cancel</a>
                <button type="submit" class="apple-btn-primary btn-sm">Proceed</button>
            </div>
            <!-- #endregion -->
        </div>
    </form>
    <!-- #endregion -->

<?php 
    elseif ($request_type == Constants::JIRA_TYPE_NEW_TAG):
        $property = $propertiesModel->find($property_id);
        $vendor = $vendorsModel->find($vendor_id);
        $siteCode = $propertiesModel->getSiteCode($property_id);
        $isShop = $propertiesModel->isShop($property_id);
        $reason = $vendor['reason'] ?? '-';
        $ssGTM = Constants::JIRA_SSGTM_TICKET;
        $jiraTitle = sprintf(
            '[LC Request] [%s] - [%s] - [New Tag]',
            $siteCode,
            $vendor_name
        );

        $detailsHtml = "
            <b style='color:#337ab7;'>1) Target Property :</b>
            <ul>
                <li>". $isShop ." : ". $property['name'] ."</li>
            </ul>

            <b style='color:#337ab7;'>2) Target Page(s) :</b>
            <ul>
                <li>". $isShop ." : <a href='". $property['website'] ."' target='_blank'>". $property['website'] ."</a></li>
            </ul>

            <b style='color:#337ab7;'>2.2) Reason when the Target Page is for all :</b>
            <p>". $reason ."</p>

            <b style='color:#337ab7;'>3) Current Status (as-is)</b>
            <p><i>Insert Local Rule Monitoring File screenshots here...</i></p>

            <b style='color:#337ab7;'>4) Purpose & Goal :</b>
            <table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse; width:100%;'>
                <tbody>";

        foreach (Constants::PURPOSE_GOAL_NEW_TAG as $value) {
            $detailsHtml .= "
                <tr>
                    <td style='width:50%;'><b>
                        ". $value ."
                    </b></td>
                    <td><i>
                        Insert answer here from the Media Tag Request form...
                    </i></td>
                </tr>
            ";
        }
        
        $detailsHtml .= "
            </tbody>
        </table>
        
        </br>
        <b style='color:#337ab7;'>5) ssGTM</b>
        <p>
            For ssGTM requests, please refer to this ticket: 
            <a href='". $ssGTM ."' target='_blank'> ssGTM </a> 
        </p>
        ";

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
                    <a href="<?= site_url('tag-requests') ?>" title="Cancel" class="apple-btn-secondary btn-sm">Cancel</a>
                    <button type="submit" 
                        class="apple-btn-primary btn-sm">
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
                <a href="<?= Constants::JIRA_CREATE_ISSUE ?>" title="Create Issue in JIRA" class="apple-btn-secondary btn-sm" target="_blank">
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

<?php elseif ($request_type == Constants::JIRA_TYPE_NEW_RULE) :
    $property = $propertiesModel->find($property_id);
    $vendor = $vendorsModel->find($vendor_id);
    $siteCode = $propertiesModel->getSiteCode($property_id);
    $isShop = $propertiesModel->isShop($property_id);
    $reason = $vendor['reason'] ?? '-';
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
            <b style='color:#337ab7;'>1) Target Property :</b>
            <ul>
                <li>". $isShop ." : ". $property['name'] ."</li>
                <li>Library Name : ". $libraryName ."</li>
            </ul>
    
            <b style='color:#337ab7;'>2) Target Page(s) :</b>
            <ul>
                <li>". $isShop ." : <a href='". $property['website'] ."' target='_blank'>". $property['website'] ."</a></li>
            </ul>
    
            <b style='color:#337ab7;'>2.2) Reason when the Target Page is for all :</b>
            <p>". $reason ."</p>
    
            <b style='color:#337ab7;'>3) Expiration Date (to-be) : </b>". $al_end_date ." <br/><br/>
    
            <b style='color:#337ab7;'>4) Status (to-be) :</b>
            <p><i>Insert Local Rule Monitoring File screenshots here...</i></p>
            
            <b style='color:#337ab7;'>5) Purpose & Goal :</b>
            <p>". $purpose ."</p>
            
            <b style='color:#337ab7;'>6) ssGTM</b>
            <p>
                For ssGTM requests, please refer to this ticket: 
                <a href='". $ssGTM ."' target='_blank'> ssGTM </a> 
            </p>
            
            <b style='color:#337ab7;'>7) Required Attachment</b>
            <p><b>① Test Result Screenshot image file(s)</b></p>
            <p><i>Insert QA screenshots here...</i></p>
            
            <br/>
            
            <p><b>② Integration Result: Fill in the checklist below to make sure the new tags aren't affecting your site's operation.</b></p>
            <table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse; width:100%;'>
                <tbody>";

            foreach (Constants::INTEGRATION_RESULT_PAGE_LOAD as $question => $value) {
                $detailsHtml .= "
                    <tr>
                        <td style='width:50%;'>". $question ."</td>
                        <td>". $value ."</td>
                    </tr>
                ";
            }

            $detailsHtml .= "
                </tbody>
            </table>
            
            </br>
            
            <table border='1' cellpadding='6' cellspacing='0' style='border-collapse:collapse; width:100%;'>
                <tbody>";

            foreach (Constants::INTEGRATION_RESULT_EVENT_CLICK as $question => $value) {
                $detailsHtml .= "
                    <tr>
                        <td style='width:50%;'>". $question ."</td>
                        <td>". $value ."</td>
                    </tr>
                ";
            }

            $detailsHtml .= "
                        </tbody>
                    </table>
                    
                    </br>
                    
            <p><b>③ Local Rule Monitoring Excel file [Local Rule Monitoring Excel file_v1.0_Subsidiary_sitecode_YYMMDD_general.xlsx]</b></p>
            ";
?>
<!-- region | PART 3 - NEW RULE REQUEST CONFIRMATION -->
<form action="<?= $actionLink ?>" method="get">
    <div class="container mb-5">
        <?php if ($ticket_id): ?>
            <h6 class="text-danger"><b><?= esc($ticket_id) ?></b></h6>
        <?php endif; ?>

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

            <?php if ($ticket_id == null) : ?>
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
            <?php else: ?>
                <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>">
            <?php endif; ?>

            <div>
                <a href="javascript:void(0)" onclick="copyDetails()">
                    <i class="bi bi-copy text-primary fs-5"></i>
                </a>

                <span id="copyDetailsStatus" style="opacity:0; transition: opacity .6s; font-size:10px; color:green;">
                    Copied!
                </span>
            </div>
        </div>

        <?php if ($ticket_id == null) : ?>
            <small class="form-label-xs text-muted">
                JIRA ticket ID, GTA-XXXXX
            </small>
        <?php endif; ?>
    </div>
</form>
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
        });

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
</script>
<?= $this->endSection() ?>

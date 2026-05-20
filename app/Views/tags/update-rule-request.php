<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<?php
    use App\Models\RenewalLogsModel;
    use App\Models\JiraHistoryModel;
    use App\Models\PropertiesModel;
    use App\Models\VendorsModel;
    use App\Models\Constants;

    $renewalsModel = new RenewalLogsModel();

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->WebsiteLinks();

    $vendorsModel = new VendorsModel();
    $vendors = $vendorsModel->Options();

    $jiraHistoryModel = new JiraHistoryModel();

    $propertyIds = array_unique(array_column($tags, 'property_id'));
    $vendorIds = array_unique(array_column($tags, 'vendor_id'));
    $vendor = [];
    foreach ($vendorIds as $vendorId) {
        $vendor[] = $vendors[$vendorId];
    }

    if ($propertyIds) {
        $siteCode = $propertiesModel->getSiteCode($propertyIds[0]);
        $is_shop = $propertiesModel->isShop($propertyIds[0]);
        $subsidiaryTicketId = $jiraHistoryModel->getLatestSubsidiary($properties[$propertyIds[0]]['subsidiary_id'])['ticket_id'] ?? null;
    }
    $jiraTitle = sprintf(
        '[LC Request] [%s] - [%s] - [Update Rule]',
        $siteCode,
        implode(', ', $vendor)
    );
?>

<?php if ($ticket_id): ?>
    <h6 class="text-danger container"><b><?= esc($ticket_id) ?></b></h6>
    <?= $this->section('title') ?>
    Update Rule: <?= esc($ticket_id) ?>
    <?= $this->endSection() ?>
<?php endif; ?>
<h4 class="container">Update Rule Request</h4>
<?= $this->section('title') ?>
Tag: Update Rule Request
<?= $this->endSection() ?>
<hr/>

<!-- #region | PART 1 - TICKET ID -->
<?php
    if ($ticket_id == null):
        $tag_ids = implode(',', array_column($tags, 'id'));
?>

<form action="<?= site_url('tags/update-rule-request') ?>" method="get">
    <div class="container">
        <div class="table-responsive mt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="text-primary d-flex align-items-center gap-2">
                    <span id="jiraTitle"><?= esc($jiraTitle) ?></span>
                    <a href="javascript:void(0)" onclick="copyJiraTitle()">
                        <i class="bi bi-copy text-primary fs-5"></i>
                    </a>
                    <span id="copyStatus" style="opacity:0; transition: opacity .6s; font-size:10px; color:green;">
                        Copied!
                    </span>
                </h4>
            </div>

            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Property</th>
                        <th>Name</th>
                        <th>Current End Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tags as $tag): ?>
                        <tr>
                            <td><?= esc($tag['id']) ?></td>
                            <td><?= esc($properties[$tag['property_id']]['name'] ?? 'Unknown') ?></td>
                            <td><?= esc($tag['name']) ?></td>
                            <td><?= esc(date('d-m-Y', strtotime($tag['al_end_date']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="d-flex gap-2 align-items-start align-items-center">
                <div>
                    <input type="hidden" name="tags" value="<?= $tag_ids ?>">
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
                <a href="<?= site_url('tags') ?>" title="Cancel" class="btn btn-danger btn-sm">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm">Proceed</button>
            </div>
        </div>

        <small class="form-label-xs text-muted">
            JIRA ticket ID, GTA-XXXXX
        </small>
    </div>
</form>
<!-- #endregion | PART 1 -->

<!-- #region | PART 2 - UPDATE RULE CONFIRMATION -->
<?php
    else:
        $propertyId = $tags[0]['property_id'];
        $vendorId = $tags[0]['vendor_id'];
        $today = date('dmY');
        $libraryName = $ticket_id . '_' . $today;
        $property = $propertiesModel->find($propertyId);
        $vendor = $vendorsModel->find($vendorId);
        $siteCode = $propertiesModel->getSiteCode($propertyId);
        $isShop = $propertiesModel->isShop($propertyId);
        $reason = $vendor['reason'] ?? '-';
        $purpose = $vendor['tag_purpose'] ?? '-';
        $today = date('dmY');
        $ssGTM = Constants::JIRA_SSGTM_TICKET;
        $jiraTitle = sprintf(
                '[LC Request] [%s] - [%s] - [Update Rule]',
                $siteCode,
                $vendor['name']
        );

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
    
            <b style='color:#337ab7;'>3) Expiration Date (to-be) : </b>". $tags[0]['al_end_date'] ." <br/><br/>
    
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

<form action="<?= site_url('tags/update-rule/confirm') ?>" method="post">
    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="text-primary d-flex align-items-center gap-2">
                <span id="jiraTitle"><?= esc($jiraTitle) ?></span>
                <a href="javascript:void(0)" onclick="copyJiraTitle()">
                    <i class="bi bi-copy text-primary fs-5"></i>
                </a>
                <span id="copyStatus" style="opacity:0; transition: opacity .6s; font-size:10px; color:green;">
                    Copied!
                </span>
            </h4>
        </div>

        <textarea id="details"></textarea>
        <div class="d-flex justify-content-end align-items-center mt-2 gap-2">
            <span id="copyDetailsStatus" style="opacity:0; transition: opacity .6s; font-size:10px; color:green;">
                Copied!
            </span>

            <a href="javascript:void(0)" onclick="copyDetails()">
                <i class="bi bi-copy text-primary fs-5"></i>
            </a>
        </div>

        <hr/>
        <!-- #region | TAGS -->
        <h4 class="mt-3 text-primary">Tags List</h4>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Property</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tags as $tag): ?>
                        <tr>
                            <td><?= esc($tag['id']) ?></td>
                            <td>
                                <a href="<?= esc($properties[$tag['property_id']]['ref_link']) ?>" target="_blank">
                                    <?= esc($properties[$tag['property_id']]['name'] ?? 'Unknown') ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?= esc($tag['ref_link']) ?>" target="_blank">
                                    <?= $tag['name'] ?>
                                </a>
                            </td>

                            <input type="hidden" name="tags[<?= $tag['id'] ?>][id]" value="<?= $tag['id'] ?>">
                            <input type="hidden" name="tags[<?= $tag['id'] ?>][library_name]" value="<?= $libraryName ?>">
                            <input type="hidden" name="tags[<?= $tag['id'] ?>][property_id]" value="<?= $tag['property_id'] ?>">
                            <input type="hidden" name="tags[<?= $tag['id'] ?>][vendor_id]" value="<?= $tag['vendor_id'] ?>">
                        </tr>
                    <?php endforeach; ?>

                    <input type="hidden" name="info[ticket_id]" value="<?= $ticket_id ?>">
                    <input type="hidden" name="info[library_name]" value="<?= $libraryName ?>">
                </tbody>
            </table>
        </div>
        <!-- #endregion | TAGS -->

        <div>
            <a href="<?= site_url('tags') ?>" title="Cancel" class="btn btn-secondary btn-sm">Cancel</a>
            <button type="submit"
                    class="btn btn-success btn-sm"
                    onclick="return confirm('Are you sure the information is correct?');">
                Create Update Rule Request
            </button>
        </div>
    </div>
</form>
<!-- #endregion | PART 2 -->

<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
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

    $(document).on('click', '.save-btn', function() {
        let btn = $(this);
        let tagId = btn.data('tag-id');

        let payload = {
            tag_id: tagId,
            updated_in_al: 1,
            csrf_test_name: '<?= csrf_hash() ?>'
        };

        $.post("<?= site_url('tags/renew/updateAL') ?>", payload, function(response) {
            if (response.status === 'ok') {
                btn.hide();
                btn.siblings('.updated-label').fadeIn();
            }
        }, 'json');
    });

    // function copyJiraTitle() {
    //     const text = document.getElementById('jiraTitle').innerText;
    //     const status = document.getElementById('copyStatus');
    //
    //     navigator.clipboard.writeText(text).then(() => {
    //         status.style.opacity = 1;
    //         setTimeout(() => {
    //             status.style.opacity = 0;
    //         }, 1000);
    //     });
    // }

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

    // function copyComment() {
    //     const comment = document.getElementById('comment');
    //     const status = document.getElementById('copyCommentStatus');
    //     let html = comment.innerHTML;
    //     html = html.replace(/<br\s*\/?>/gi, "\n");
    //     html = html.replace(/<a [^>]*href="([^"]+)"[^>]*>(.*?)<\/a>/gi, "$2 ($1)");
    //     const text = html
    //         .replace(/<br\s*\/?>/gi, "\n")
    //         .replace(/<a [^>]*href="([^"]+)"[^>]*>(.*?)<\/a>/gi, "$2 ($1)")
    //         .replace(/<[^>]+>/g, "")
    //         .trim();
    //     navigator.clipboard.writeText(text).then(() => {
    //         status.style.opacity = 1;
    //         setTimeout(() => {
    //             status.style.opacity = 0;
    //         }, 1000);
    //     });
    // }

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

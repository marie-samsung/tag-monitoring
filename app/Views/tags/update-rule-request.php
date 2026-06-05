<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= ($ticket_id) ? "Update Rule" : "Tag: Update Rule Request" ?>
<?= $this->endSection() ?>

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

    $today = date('dmY');
    $libraryName = $ticket_id . '_' . $today;

    $siteCodes = [];
    foreach ($propertyIds as $pId) {
        $siteCodes[] = $propertiesModel->getSiteCode($pId);
    }
    $siteCode = implode(', ', array_unique($siteCodes));

    $vendor = [];
    $reasons = [];
    $purposes = [];
    foreach ($vendorIds as $vendorId) {
        if (isset($vendors[$vendorId])) {
            $vendor[] = $vendors[$vendorId];
        }
        $v = $vendorsModel->find($vendorId);
        if ($v) {
            if (!empty($v['reason'])) {
                $reasons[] = $v['reason'];
            }
            if (!empty($v['tag_purpose'])) {
                $purposes[] = $v['tag_purpose'];
            }
        }
    }
    $vendorName = implode(', ', array_unique($vendor));
    $reason = !empty($reasons) ? implode('<br/>', array_unique($reasons)) : '-';
    $purpose = !empty($purposes) ? implode('<br/>', array_unique($purposes)) : '-';

    $expDates = [];
    foreach ($tags as $tag) {
        if (!empty($tag['al_end_date'])) {
            $expDates[] = $tag['al_end_date'];
        }
    }
    $expDateStr = !empty($expDates) ? implode(', ', array_unique($expDates)) : '-';

    $ssGTM = Constants::JIRA_SSGTM_TICKET;

    $jiraTitle = sprintf(
        '[LC Request] [%s] - [%s] - [Update Rule]',
        $siteCode,
        $vendorName
    );

    $htmlTitle = ($ticket_id) ? "Update Rule" : "Tag: Update Rule Request";
?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tags') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back to Inventory">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold"><?= esc($htmlTitle) ?></h2>
            <p class="text-danger small mb-0 fw-semibold"><span id="ticket-id-header"><?= esc($ticket_id) ?></span></p>
        </div>
    </div>

<?php
    $propertyListHtml = "";
    $pagesListHtml = "";
    foreach ($propertyIds as $pId) {
        $pIsShop = $propertiesModel->isShop($pId);
        $pName = $properties[$pId]['name'] ?? 'Unknown';
        $pWebsite = $properties[$pId]['website'] ?? 'Unknown';
        
        $propertyListHtml .= "<li>" . $pIsShop . " : " . esc($pName) . "</li>";
        $pagesListHtml .= "<li>" . $pIsShop . " : <a href='" . esc($pWebsite) . "' target='_blank'>" . esc($pWebsite) . "</a></li>";
    }

    $detailsHtml = "
        <b style='color:#337ab7;'>1) Target Property :</b>
        <ul>
            " . $propertyListHtml . "
            <li>Library Name : <span id='library-name-display'>". $libraryName ."</span></li>
        </ul>

        <b style='color:#337ab7;'>2) Target Page(s) :</b>
        <ul>
            " . $pagesListHtml . "
        </ul>

        <b style='color:#337ab7;'>2.2) Reason when the Target Page is for all :</b>
        <p>". $reason ."</p>

        <b style='color:#337ab7;'>3) Expiration Date (to-be) : </b>". $expDateStr ." <br/><br/>

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
    <div class="mt-3">
        <!-- Section 1: JIRA Ticket Information -->
        <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
            <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                <i class="bi bi-jira"></i>
                <span>JIRA Ticket Information</span>
            </h5>
            
            <div class="mb-3">
                <label class="form-label-sm fw-semibold text-secondary mb-1">Proposed JIRA Title</label>
                <div class="d-flex align-items-center gap-2">
                    <span id="jiraTitle" class="fw-bold text-dark fs-6"><?= esc($jiraTitle) ?></span>
                    <a href="javascript:void(0)" onclick="copyJiraTitle()" class="text-primary text-decoration-none">
                        <i class="bi bi-copy fs-6"></i>
                    </a>
                    <span id="copyStatus" class="badge bg-success" style="opacity:0; transition: opacity .6s; font-size:10px;">
                        Copied!
                    </span>
                </div>
            </div>

            <div class="row align-items-end g-3">
                <div class="col-md-6">
                    <label for="ticket_id" class="form-label-sm fw-semibold text-secondary mb-1">JIRA Ticket ID</label>
                    <div class="d-flex gap-2">
                        <input type="text" name="ticket_id" id="ticket_id"
                            class="form-control form-control-xs"
                            value="<?= old('ticket_id', $old['ticket_id'] ?? '') ?>"
                            placeholder="GTA-XXXXX"
                            required>
                        <a href="<?= Constants::JIRA_CREATE_ISSUE ?>" title="Create Issue in JIRA" class="apple-btn-secondary px-3 text-decoration-none btn-sm d-flex align-items-center justify-content-center text-nowrap" target="_blank">
                            Create JIRA Ticket
                        </a>
                    </div>
                    <small class="form-text text-muted" style="font-size: 0.75rem;">
                        Format: GTA-XXXXX
                    </small>
                </div>
            </div>
        </div>

        <!-- Section 2: Ticket Description (Summernote) -->
        <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
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
            <textarea id="details"></textarea>
        </div>

        <!-- Section 3: Tags List Table -->
        <div class="mb-4">
            <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                <i class="bi bi-tags-fill"></i>
                <span>Tags List</span>
            </h5>
            
            <div class="apple-table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 apple-table">
                        <thead>
                            <tr>
                                <th class="form-label-sm fw-semibold">ID</th>
                                <th class="form-label-sm fw-semibold">Property</th>
                                <th class="form-label-sm fw-semibold">Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tags as $tag): ?>
                                <tr>
                                    <td><?= esc($tag['id']) ?></td>
                                    <td>
                                        <a href="<?= esc($properties[$tag['property_id']]['ref_link'] ?? '#') ?>" target="_blank" class="apple-link-dark text-decoration-none fw-semibold">
                                            <?= esc($properties[$tag['property_id']]['name'] ?? 'Unknown') ?>
                                        </a>
                                    </td>
                                    <td class="form-label-sm">
                                        <a href="<?= esc($tag['ref_link'] ?? '#') ?>" target="_blank" class="apple-link-dark text-decoration-none fw-semibold">
                                            <?= esc($tag['name']) ?>
                                        </a>
                                    </td>

                                    <input type="hidden" name="tags[<?= $tag['id'] ?>][id]" value="<?= $tag['id'] ?>">
                                    <input type="hidden" name="tags[<?= $tag['id'] ?>][library_name]" class="library-name-input" value="<?= $libraryName ?>">
                                    <input type="hidden" name="tags[<?= $tag['id'] ?>][property_id]" value="<?= $tag['property_id'] ?>">
                                    <input type="hidden" name="tags[<?= $tag['id'] ?>][vendor_id]" value="<?= $tag['vendor_id'] ?>">
                                </tr>
                            <?php endforeach; ?>

                            <input type="hidden" name="info[ticket_id]" id="info_ticket_id" value="<?= $ticket_id ?>">
                            <input type="hidden" name="info[library_name]" class="library-name-input" value="<?= $libraryName ?>">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top mb-5">
            <a href="<?= site_url('tags') ?>" title="Cancel" class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
            <button type="submit" 
                class="apple-btn-primary px-4"
                onclick="return confirm('Are you sure the information is correct?');">
                Create Update Rule Request
            </button>
        </div>
    </div>
</form>
</div>
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

        // Dynamic update of Ticket ID and Library Name in real-time
        $('#ticket_id').on('input', function() {
            var ticketId = $(this).val().trim();
            var today = '<?= $today ?>';
            var newLibraryName = ticketId + '_' + today;

            // Update visible header
            $('#ticket-id-header').text(ticketId);

            // Update hidden form inputs
            $('#info_ticket_id').val(ticketId);
            $('.library-name-input').val(newLibraryName);

            // Update Library Name inside Summernote RTE editor
            var currentHtml = $('#details').summernote('code');
            var $temp = $('<div>').html(currentHtml);
            var $librarySpan = $temp.find('#library-name-display');
            if ($librarySpan.length > 0) {
                $librarySpan.text(newLibraryName);
                $('#details').summernote('code', $temp.html());
            }
        });
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

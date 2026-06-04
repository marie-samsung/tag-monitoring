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

<?= $this->section('title') ?>
<?= $htmlTitle ?>
<?= $this->endSection() ?>

    <h6 class="text-danger container"><b><span id="ticket-id-header"><?= esc($ticket_id) ?></span></b></h6>
<h4 class="container"><?= $htmlTitle ?></h4>
<hr/>

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
        <div class="d-flex justify-content-between align-items-center mt-2 gap-2">
            <div class="d-flex gap-2 align-items-start align-items-center">
                <div>
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

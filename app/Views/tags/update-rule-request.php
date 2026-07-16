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

    $createLibraryLink = '#';
    if (!empty($propertyIds)) {
        $firstPropId = reset($propertyIds);
        $rawRefLink = $properties[$firstPropId]['ref_link'] ?? '#';
        $baseRefLink = preg_replace('/(\/(overview|rules|data_elements|extensions|environments|hosts|settings|history)(\/|\?|$).*)$/i', '', $rawRefLink);
        $createLibraryLink = $baseRefLink . Constants::AL_LIBRARY_CREATE;
    }

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
    $propertiesByShop = [];
    foreach ($propertyIds as $pId) {
        $pIsShop = $propertiesModel->isShop($pId);
        $propertiesByShop[$pIsShop][] = $pId;
    }

    $propertyListHtml = "";
    $pagesListHtml = "";
    foreach ($propertiesByShop as $shopType => $props) {
        $propertyListHtml .= "<li>" . $shopType . " :<ul>";
        $pagesListHtml .= "<li>" . $shopType . " :<ul>";
        foreach ($props as $pId) {
            $pName = $properties[$pId]['name'] ?? 'Unknown';
            $pWebsite = $properties[$pId]['website'] ?? 'Unknown';
            
            $propertyListHtml .= "<li>" . esc($pName) . "</li>";
            $pagesListHtml .= "<li><a href='" . esc($pWebsite) . "' target='_blank'>" . esc($pWebsite) . "</a></li>";
        }
        $propertyListHtml .= "</ul></li>";
        $pagesListHtml .= "</ul></li>";
    }

    $detailsHtml = "
        <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>1) Target Property :</b>
        <ul style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F;'>
            " . $propertyListHtml . "
            <li>Library Name : <span id='library-name-display'>". $libraryName ."</span></li>
        </ul>

        <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>2) Target Page(s) :</b>
        <ul style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F;'>
            " . $pagesListHtml . "
        </ul>

        <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>2.2) Reason when the Target Page is for all :</b>
        <p style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F; padding-left:12px;'>". $reason ."</p>

        <b style='color:#0071E3; font-size:15px; font-family:-apple-system,BlinkMacSystemFont,sans-serif;'>3) Expiration Date (to-be) : </b><span style='font-family:-apple-system,BlinkMacSystemFont,sans-serif; font-size:14px; color:#1D1D1F;'>". $expDateStr ."</span> <br/><br/>

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

<form action="<?= site_url('tags/update-rule/confirm') ?>" method="post">
    <div class="row g-4 mt-1">
        <!-- Left Column: JIRA Info & Tags List -->
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

                <!-- Library Name -->
                <div class="mb-3">
                    <label class="form-label-sm fw-semibold text-secondary mb-1">Library Name</label>
                    <div class="d-flex align-items-center justify-content-between p-2 bg-light border rounded gap-2" style="min-height: 38px;">
                        <div class="d-flex align-items-center gap-2">
                            <span id="libraryNameVal" class="fw-bold text-dark fs-7"><?= esc($libraryName) ?></span>
                            <a href="javascript:void(0)" onclick="copyLibraryName()" class="text-primary text-decoration-none d-flex align-items-center" title="Copy Library Name">
                                <i class="bi bi-copy fs-6"></i>
                            </a>
                            <span id="copyLibraryStatus" class="badge bg-success" style="opacity:0; transition: opacity .6s; font-size:10px;">
                                Copied!
                            </span>
                        </div>
                        <a href="<?= esc($createLibraryLink ?? '#') ?>" target="_blank" rel="noopener" class="apple-btn-secondary px-3 py-1 text-decoration-none btn-sm d-inline-flex align-items-center gap-1 text-nowrap flex-shrink-0" style="font-size: 0.72rem; height: 26px;">
                            <i class="bi bi-plus-circle"></i> Create Library
                        </a>
                    </div>
                </div>

                <div class="mb-2">
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
                    <small class="form-text text-muted mt-1 d-block" style="font-size: 0.75rem;">
                        Format: GTA-XXXXX
                    </small>
                </div>
            </div>

            <!-- Tags List Table -->
            <?php 
                $count = 0; 
                $nextExpDate = '';
                if ($tags) {
                    $expDate = $nextExpDate = date('d-m-Y', strtotime($tags[0]['al_end_date'] ?? ''));
                }
            ?>
            <div class="d-flex flex-column flex-grow-1">
                <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                    <i class="bi bi-tags-fill"></i>
                    <span>Tags List</span>
                </h5>
                <div class="apple-table-container mb-4 flex-grow-1">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 apple-table">
                            <thead>
                                <tr>
                                    <th class="form-label-sm fw-semibold">#</th>
                                    <th class="form-label-sm fw-semibold">ID</th>
                                    <th class="form-label-sm fw-semibold">Property</th>
                                    <th class="form-label-sm fw-semibold">Name</th>
                                    <th class="form-label-sm fw-semibold text-center">Updated in AL?</th>
                                    <th class="form-label-sm fw-semibold">Next End Date</th>
                                    <th class="form-label-sm fw-semibold text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tags as $tag): $count++; ?>
                                    <tr data-vendor="<?= esc($vendors[$tag['vendor_id']] ?? '') ?>">
                                        <td class="fw-semibold"><?= $count ?></td>
                                        <td><?= esc($tag['id']) ?></td>
                                        <td>
                                            <a href="<?= esc($properties[$tag['property_id']]['ref_link'] . Constants::AL_LIBRARY_CREATE ?? '#') ?>" target="_blank" class="apple-link-dark text-decoration-none fw-semibold">
                                                <?= esc($properties[$tag['property_id']]['name'] ?? 'Unknown') ?>
                                            </a>
                                        </td>
                                        <td class="form-label-sm">
                                            <span class="group-hover-container align-items-center">
                                                <a href="<?= esc($tag['ref_link'] ?? '#') ?>" target="_blank" class="apple-link-dark">
                                                    <?= esc($tag['name']) ?>
                                                </a>
                                                <a href="javascript:void(0)" onclick="copyTagName('<?= esc($tag['name']) ?>', this)" title="Copy tag name" class="text-muted ms-2" style="transition: color 0.2s; text-decoration: none; line-height: 1;">
                                                    <i class="bi bi-copy" style="font-size: 0.85rem;"></i>
                                                </a>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($tag['status'] != Constants::STATUS_UPDATED_IN_AL): ?>
                                                <button 
                                                    type="button" 
                                                    class="apple-btn-primary btn-sm save-btn px-3" 
                                                    data-tag-id="<?= $tag['id'] ?>"
                                                >
                                                    Done
                                                </button>
                                                <span class="text-success small updated-label" style="display:none;">
                                                    <i class="bi bi-check-circle-fill"></i> Updated
                                                </span>
                                            <?php else: ?>
                                                 <span class="text-success small updated-label">
                                                    <i class="bi bi-check-circle-fill"></i> Updated
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <input type="text" name="tags[<?= $tag['id'] ?>][next_end_date]" class="form-control form-control-sm next-end-date-input" value="<?= $nextExpDate ? date('Y-m-d 00:00', strtotime($nextExpDate)) : '' ?>" style="width: 150px;" required>
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 p-1" onclick="applyDateToAll(this)" title="Apply this date to all tags">
                                                    <i class="bi bi-chevron-double-down"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0 p-1" onclick="copyNextEndDate(this)" title="Copy Date">
                                                    <i class="bi bi-copy"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger border-0" title="Remove" onclick="removeTagRow(this, <?= $tag['id'] ?>, '<?= esc($tag['name']) ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>

                                        <input type="hidden" name="tags[<?= $tag['id'] ?>][id]" value="<?= $tag['id'] ?>">
                                        <input type="hidden" name="tags[<?= $tag['id'] ?>][library_name]" class="library-name-input" value="<?= $libraryName ?>">
                                        <input type="hidden" name="tags[<?= $tag['id'] ?>][property_id]" value="<?= $tag['property_id'] ?>">
                                        <input type="hidden" name="tags[<?= $tag['id'] ?>][vendor_id]" value="<?= $tag['vendor_id'] ?>">
                                    </tr>
                                <?php endforeach; ?>

                                <input type="hidden" name="info[ticket_id]" id="info_ticket_id" value="<?= $ticket_id ?>">
                                <input type="hidden" name="info[al_end_date]" value="<?= $nextExpDate ?>">
                                <input type="hidden" name="info[library_name]" class="library-name-input" value="<?= $libraryName ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Ticket Description Editor -->
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
                    <a href="<?= site_url('tags') ?>" title="Cancel" class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
                    <button type="submit" class="apple-btn-primary px-4">Create Update Rule Request</button>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        flatpickr(".next-end-date-input", {
            dateFormat: "Y-m-d 00:00",
            altInput: true,
            altFormat: "Y-m-d 00:00",
            allowInput: true
        });
        $('#details').summernote({
            height: 520,
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

            // Update Library Name text inside JIRA Ticket Information
            $('#libraryNameVal').text(newLibraryName);

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

    function copyTagName(text, element) {
        const temp = document.createElement('textarea');
        temp.value = text;

        temp.style.position = 'fixed';
        temp.style.left = '-9999px';

        document.body.appendChild(temp);

        temp.focus();
        temp.select();

        document.execCommand('copy');

        document.body.removeChild(temp);

        element.innerHTML = '<i class="bi bi-check-lg text-success fs-7"></i>';

        setTimeout(() => {
            element.innerHTML = '<i class="bi bi-copy text-secondary fs-7"></i>';
        }, 1200);
    }

    function removeTagRow(button, tagId, tagName) {
        window.themeConfirm({
            title: 'Remove Tag',
            message: 'Are you sure you want to remove ' + tagName + ' from the update list?',
            confirmText: 'Remove',
            cancelText: 'Cancel',
            type: 'danger'
        }).then((confirmed) => {
            if (confirmed) {
                let row = $(button).closest('tr');
                let tbody = row.closest('tbody');
                let vendorToRemove = row.data('vendor');
                
                row.remove();

                // Re-index row numbers
                tbody.find('tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });

                // Update JIRA title
                if (vendorToRemove) {
                    let hasOtherTagsForVendor = false;
                    tbody.find('tr').each(function() {
                        if ($(this).data('vendor') == vendorToRemove) {
                            hasOtherTagsForVendor = true;
                            return false;
                        }
                    });

                    if (!hasOtherTagsForVendor) {
                        let titleEl = $('#jiraTitle');
                        let newTitle = titleEl.text().replace(/\] - \[(.*?)\] - \[Update Rule\]$/, function(match, vendorsGroup) {
                            let vendorList = vendorsGroup.split(', ');
                            vendorList = vendorList.filter(v => v !== vendorToRemove);
                            return '] - [' + vendorList.join(', ') + '] - [Update Rule]';
                        });
                        titleEl.text(newTitle).attr('title', newTitle);
                    }
                }

                if (tbody.find('tr').length === 0) {
                    let submitBtn = $('button[type="submit"]');
                    submitBtn.removeAttr('data-confirm');
                    submitBtn.off('click.emptyCheck').on('click.emptyCheck', function(e) {
                        e.preventDefault();
                        window.themeAlert({
                            title: 'Cannot Proceed',
                            message: 'No tags have been selected for update',
                            type: 'warning'
                        });
                    });
                }

                if (tagId) {
                    let urlParams = new URLSearchParams(window.location.search);
                    
                    // Handle comma-separated tags=1,2,3
                    if (urlParams.has('tags')) {
                        let tagsParam = urlParams.get('tags');
                        let tagsArray = tagsParam.split(',');
                        tagsArray = tagsArray.filter(id => id != tagId);
                        
                        if (tagsArray.length > 0) {
                            urlParams.set('tags', tagsArray.join(','));
                        } else {
                            urlParams.delete('tags');
                        }
                    }

                    // Handle array format tags[]=1&tags[]=2
                    if (urlParams.has('tags[]')) {
                        let tags = urlParams.getAll('tags[]');
                        urlParams.delete('tags[]');
                        tags.forEach(id => {
                            if (id != tagId) {
                                urlParams.append('tags[]', id);
                            }
                        });
                    }

                    // Handle indexed array format tags[0]=1&tags[1]=2
                    let keysToDelete = [];
                    for (let [key, value] of urlParams.entries()) {
                        if (key.match(/^tags\[\d+\]$/) && value == tagId) {
                            keysToDelete.push(key);
                        }
                    }
                    keysToDelete.forEach(key => urlParams.delete(key));

                    let newUrl = window.location.pathname;
                    let queryString = urlParams.toString();
                    if (queryString) {
                        newUrl += '?' + queryString;
                    }
                    window.history.replaceState(null, '', newUrl);
                }
            }
        });
    }

    function applyDateToAll(btn) {
        let inputEl = $(btn).siblings('.next-end-date-input')[0];
        if (inputEl && inputEl._flatpickr) {
            let selectedDate = inputEl._flatpickr.selectedDates[0];
            if (selectedDate) {
                $('.next-end-date-input').each(function() {
                    if (this._flatpickr && this !== inputEl) {
                        this._flatpickr.setDate(selectedDate);
                    }
                });
            }
        }
    }

    function copyNextEndDate(button) {
        let inputEl = $(button).siblings('input[name$="[next_end_date]"]')[0];
        let dateVal = inputEl ? inputEl.value : '';

        const temp = document.createElement('textarea');
        temp.value = dateVal;

        temp.style.position = 'fixed';
        temp.style.left = '-9999px';

        document.body.appendChild(temp);

        temp.focus();
        temp.select();

        document.execCommand('copy');

        document.body.removeChild(temp);

        const icon = button.querySelector('i');
        if (icon) {
            icon.className = 'bi bi-check-lg text-success';
            setTimeout(() => {
                icon.className = 'bi bi-copy';
            }, 1200);
        }
    }

</script>
<?= $this->endSection() ?>

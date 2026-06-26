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

        $rawRefLink = $properties[$propertyIds[0]]['ref_link'] ?? '#';
        $baseRefLink = preg_replace('/(\/(overview|rules|data_elements|extensions|environments|hosts|settings|history)(\/|\?|$).*)$/i', '', $rawRefLink);
        $createLibraryLink = $baseRefLink . Constants::AL_LIBRARY_CREATE;
    }
    $jiraTitle = sprintf(
        '[LC Request] [%s] - [%s] - [Date Extension Rule]',
        $siteCode,
        implode(', ', $vendor)
    );
?>


<?= $this->section('title') ?>
Tags Renewal: <?= $siteCode ?? null ?>
<?= $this->endSection() ?>

<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tags') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back to Inventory">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Renew Tags</h2>
            <p class="text-danger small mb-0 fw-semibold"><span id="ticket-id-header"><?= esc($ticket_id) ?></span></p>
        </div>
    </div>

<?php 
    $count = 0;
    $today = date('dmY');
    $libraryName = $ticket_id . '_' . $today;

    if ($tags) {
        $expDate = date('d-m-Y', strtotime($tags[0]['al_end_date'] ?? ''));
        $nextExpDate = date('d-m-Y', strtotime('+61 days', strtotime($expDate)));
    } else {
        return false;
    }

    $detailsHtml = '';
    // Target Property
    $detailsHtml .= '
        <b>1) Target Property</b>
        <ul>';
    foreach ($propertyIds as $property) {
        $propIsShop = $propertiesModel->isShop($property);
        $detailsHtml .= '<li>' . $propIsShop . ' : ' . esc($properties[$property]['name'] ?? 'Unknown') . '</li>';
    }
    $detailsHtml .= '
            <li>Library name: <b><span id="library-name-display">' . esc($libraryName) . '</span></b></li>
        </ul>';
    // Target Pages
    $detailsHtml .= '
        <b>2) Target Page(s)</b>
        <ul>';
    foreach ($propertyIds as $property) {
        $propIsShop = $propertiesModel->isShop($property);
        $detailsHtml .= '<li>' . $propIsShop . ' : <a href="' . esc($properties[$property]['website'] ?? 'Unknown') . '" target="_blank">' . esc($properties[$property]['website'] ?? 'Unknown') . '</a></li>';
    }
    $detailsHtml .= '</ul>';
    // Expiration Dates
    $detailsHtml .= '
        <b>3) Expiration Date</b>
        <ul>
            <li><b>as-is :</b> ' . esc($expDate) . '</li>
            <li><b>to-be :</b> ' . esc($nextExpDate) . '</li>
        </ul>';
    // Purpose & Goal + Table
    $detailsHtml .= '
        <b>4) Purpose & Goal</b>
        <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th>Tag Name</th>
                    <th>Renew<br/>(Y/N)</th>
                    <th>Renewal Justification</th>
                    <th>Duration<br/>of Usage</th>
                </tr>
            </thead>
            <tbody>';
    foreach ($tags as $tag) {
        $renewVal = !empty($tag['justification']['renew']) ? esc($tag['justification']['renew']) : 'Y';
        $justificationVal = !empty($tag['justification']['justification']) ? esc($tag['justification']['justification']) : '';
        $durationVal = !empty($tag['justification']['duration']) ? esc($tag['justification']['duration']) : '';
        
        $detailsHtml .= '
                <tr>
                    <td>' . esc($tag['name']) . '</td>
                    <td>' . $renewVal . '</td>
                    <td>' . $justificationVal . '</td>
                    <td>' . $durationVal . '</td>
                </tr>';
    }
    $detailsHtml .= '
            </tbody>
        </table>';

    $commentHtml = 'Hi <a href="' . Constants::JIRA_TAGGING_SUPPORT . '" target="_blank">Tagging Support</a>,
<br/>Kindly refer to the date extension approval confirmation here: 
<a href="' . Constants::JIRA_LINK . $subsidiaryTicketId . '" target="_blank">' . Constants::JIRA_LINK . $subsidiaryTicketId . '</a>
<br/><br/>Regards,
<br/>' . $_SESSION['user_name'];
?>

<form action="<?= site_url('tags/renew/confirm') ?>" method="post">
    <div class="row g-4 mt-1">
        <!-- Left Column: JIRA Info & Tags List -->
        <div class="col-lg-5 d-flex flex-column">
            <!-- JIRA Ticket Information -->
            <div class="apple-card mb-4">
                <h5 class="mb-3 text-primary d-flex align-items-center gap-2 fw-bold">
                    <i class="bi bi-jira"></i>
                    <span>JIRA Ticket Information</span>
                </h5>
                
                <!-- Proposed JIRA Title -->
                <div class="mb-3">
                    <label class="form-label-sm fw-semibold text-secondary mb-1">Proposed JIRA Title</label>
                    <div class="d-flex align-items-center justify-content-between p-2 bg-light border rounded gap-2" style="min-height: 38px;">
                        <span id="jiraTitle" class="fw-semibold text-dark fs-7 text-truncate" style="max-width: 80%;" title="<?= esc($jiraTitle) ?>"><?= esc($jiraTitle) ?></span>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <a href="javascript:void(0)" onclick="copyJiraTitle()" class="text-primary text-decoration-none d-flex align-items-center" title="Copy JIRA Title">
                                <i class="bi bi-copy fs-6"></i>
                            </a>
                            <span id="copyStatus" class="badge bg-success" style="opacity:0; transition: opacity .6s; font-size:10px;">
                                Copied!
                            </span>
                        </div>
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

                <!-- JIRA Ticket ID -->
                <div class="mb-3">
                    <label for="ticket_id" class="form-label-sm fw-semibold text-secondary mb-1">JIRA Ticket ID</label>
                    <div class="d-flex gap-2">
                        <input type="text" name="ticket_id" id="ticket_id"
                            class="form-control form-control-xs"
                            value="<?= old('ticket_id', $old['ticket_id'] ?? '') ?>"
                            placeholder="GTA-XXXXX"
                            style="height: 38px;"
                            required>
                        <a href="<?= Constants::JIRA_CREATE_ISSUE ?>" title="Create Issue in JIRA" class="apple-btn-secondary px-3 text-decoration-none btn-sm d-flex align-items-center justify-content-center text-nowrap" style="height: 38px; font-size: 0.75rem;" target="_blank">
                            Create JIRA Ticket
                        </a>
                    </div>
                    <small class="form-text text-muted mt-1 d-block" style="font-size: 0.72rem; padding-left: 2px;">
                        Format: GTA-XXXXX
                    </small>
                </div>
            </div>

            <!-- Tags List Table -->
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
                                    <tr data-vendor="<?= esc($vendors[$tag['vendor_id']]) ?>">
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
                                            <input type="text" name="tags[<?= $tag['id'] ?>][next_end_date]" class="form-control form-control-sm next-end-date-input" value="<?= date('Y-m-d', strtotime($nextExpDate)) ?>" style="width: 130px;" required>
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

        <!-- Right Column: Editors -->
        <div class="col-lg-7 d-flex flex-column">
            <!-- Ticket Description (Summernote) -->
            <div class="apple-card mb-4">
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

            <!-- JIRA Comments (Summernote) -->
            <div class="apple-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 text-primary d-flex align-items-center gap-2 fw-bold">
                        <i class="bi bi-chat-left-text-fill"></i>
                        <span>JIRA Comment</span>
                    </h5>
                    <div>
                        <span id="copyCommentStatus" class="badge bg-success me-2" style="opacity:0; transition: opacity .6s; font-size:10px;">
                            Copied!
                        </span>
                        <a href="javascript:void(0)" onclick="copyComment()" class="apple-btn-secondary btn-sm px-3 text-decoration-none">
                            <i class="bi bi-copy me-1"></i> Copy Comment
                        </a>
                    </div>
                </div>
                <textarea id="comment"></textarea>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-2 mt-auto pt-3 border-top mb-5">
                <a href="<?= site_url('tags') ?>" title="Cancel" class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
                <button type="submit" 
                    class="apple-btn-primary px-4"
                    data-confirm="Are you sure you want to renew these tags?"
                    data-confirm-title="Renew Tags"
                    data-confirm-type="warning"
                    data-confirm-text="Renew">
                    Renew Tags
                </button>
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
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            allowInput: true
        });
        $('#details').summernote({
            height: 360,
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

        $('#comment').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        var commentHtml = `<?= $commentHtml ?? null ?>`;
        $('#comment').summernote('code', commentHtml);

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

        // Trigger input event to ensure initial state sync (e.g. if ticket_id is prefilled)
        $('#ticket_id').trigger('input');
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

    function copyComment() {
        const html = $('#comment').summernote('code');

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

        const status = document.getElementById('copyCommentStatus');
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
            message: 'Are you sure you want to remove ' + tagName + ' from the renewal list?',
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
                        let newTitle = titleEl.text().replace(/\] - \[(.*?)\] - \[Date Extension Rule\]$/, function(match, vendorsGroup) {
                            let vendorList = vendorsGroup.split(', ');
                            vendorList = vendorList.filter(v => v !== vendorToRemove);
                            return '] - [' + vendorList.join(', ') + '] - [Date Extension Rule]';
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
                            message: 'No tags have been selected for renewal',
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

</script>
<?= $this->endSection() ?>

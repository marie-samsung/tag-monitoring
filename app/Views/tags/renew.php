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
        '[LC Request] [%s] - [%s] - [Date Extension Rule]',
        $siteCode,
        implode(', ', $vendor)
    );
?>

<?php if ($ticket_id): ?>
    <h6 class="text-danger container"><b><?= esc($ticket_id) ?></b></h6>
    <?= $this->section('title') ?>
    Renewal: <?= esc($ticket_id) ?>
    <?= $this->endSection() ?>
<?php else: ?>
    <?= $this->section('title') ?>
    Tag: Renew
    <?= $this->endSection() ?>
<?php endif; ?>

<h4 class="container">Renew Tags</h4>
<hr/>

<!-- #region | PART 1 - TICKET ID -->
<?php
    if ($ticket_id == null): 
        $tag_ids = implode(',', array_column($tags, 'id'));
        $count = 0;
?>

<form action="<?= site_url('tags/renew') ?>" method="get">
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

            <table class="table table-hover align-middle apple-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Property</th>
                        <th>Name</th>
                        <th>Current End Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tags as $tag):
                            $count++; ?>
                        <tr>
                            <td class="fw-semibold"><?= $count ?></td>
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
                <a href="<?= Constants::JIRA_CREATE_ISSUE ?>" title="Create Issue in JIRA" class="apple-btn-secondary btn-sm" target="_blank">
                    Create JIRA Ticket
                </a>
            </div>
            <div>
                <a href="<?= site_url('tags') ?>" title="Cancel" class="apple-btn-secondary btn-sm">Cancel</a>
                <button type="submit" class="apple-btn-primary btn-sm">Proceed</button>
            </div>
        </div>

        <small class="form-label-xs text-muted">
            JIRA ticket ID, GTA-XXXXX
        </small>
    </div>
</form>
<!-- #endregion | PART 1 -->

<!-- #region | PART 2 - RENEWAL CONFIRMATION -->
<?php 
    else:
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
            $detailsHtml .= '<li>' . $is_shop . ' : ' . esc($properties[$property]['name'] ?? 'Unknown') . '</li>';
        }
        $detailsHtml .= '
                <li>Library name: <b>' . esc($libraryName) . '</b></li>
            </ul>';
        // Target Pages
        $detailsHtml .= '
            <b>2) Target Page(s)</b>
            <ul>';
        foreach ($propertyIds as $property) {
            $detailsHtml .= '<li>' . $is_shop . ' : <a href="' . esc($properties[$property]['website'] ?? 'Unknown') . '" target="_blank">' . esc($properties[$property]['website'] ?? 'Unknown') . '</a></li>';
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
            $detailsHtml .= '
                    <tr>
                        <td>' . esc($tag['name']) . '</td>
                        <td>Y</td>
                        <td></td>
                        <td></td>
                    </tr>';
        }
        $detailsHtml .= '
                </tbody>
            </table>';
?>

<form action="<?= site_url('tags/renew/confirm') ?>" method="post">
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
            <table class="table table-hover align-middle apple-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Property</th>
                        <th>Name</th>
                        <th>Updated in AL?</th>
                        <th>Next End Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tags as $tag): $count++; ?>
                        <tr>
                            <td class="fw-semibold"><?= $count ?></td>
                            <td><?= esc($tag['id']) ?></td>
                            <td>
                                <a href="<?= esc($properties[$tag['property_id']]['ref_link']) ?>" target="_blank">
                                    <?= esc($properties[$tag['property_id']]['name'] ?? 'Unknown') ?>
                                </a>
                            </td>
                            <td class="form-label-sm">
                                <span class="group-hover-container align-items-center">
                                    <a href="<?= esc($tag['ref_link']) ?>" target="_blank">
                                        <?= $tag['name'] ?>
                                    </a>

                                    <a href="javascript:void(0)" onclick="copyTagName('<?= $tag['name'] ?>', this)" title="Copy tag name" class="text-muted ms-2" style="transition: color 0.2s; text-decoration: none; line-height: 1;">
                                        <i class="bi bi-copy" style="font-size: 0.85rem;"></i>
                                    </a>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($tag['status'] != Constants::STATUS_UPDATED_IN_AL): ?>
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-outline-primary save-btn" 
                                        data-tag-id="<?= $tag['id'] ?>"
                                    >
                                        Done
                                    </button>

                                    <span class="text-success small updated-label" style="display:none;">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>

                                <?php else: ?>
                                     <span class="text-success small updated-label">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($nextExpDate) ?></td>

                            <input type="hidden" name="tags[<?= $tag['id'] ?>][id]" value="<?= $tag['id'] ?>">
                            <input type="hidden" name="tags[<?= $tag['id'] ?>][library_name]" value="<?= $libraryName ?>">
                            <input type="hidden" name="tags[<?= $tag['id'] ?>][property_id]" value="<?= $tag['property_id'] ?>">
                            <input type="hidden" name="tags[<?= $tag['id'] ?>][vendor_id]" value="<?= $tag['vendor_id'] ?>">
                            <input type="hidden" name="tags[<?= $tag['id'] ?>][next_end_date]" value="<?= $nextExpDate ?>">
                        </tr>
                    <?php endforeach; ?>

                    <input type="hidden" name="info[ticket_id]" value="<?= $ticket_id ?>">
                    <input type="hidden" name="info[al_end_date]" value="<?= $nextExpDate ?>">
                    <input type="hidden" name="info[library_name]" value="<?= $libraryName ?>">
                </tbody>
            </table>
        </div>
        <!-- #endregion | TAGS -->

        <div class="d-flex justify-content-between mb-5">
            <div class="d-flex gap-2 align-items-end">
                <div id="comment" class="form-control" style="height: 140px; width: auto; overflow-y: auto;">
Hi TAGGING JIRA SUPPORT,
<br/>Kindly refer to the date extension approval confirmation here: <?= Constants::JIRA_LINK . $subsidiaryTicketId ?>
<br/><br/>Regards,
<br/>[Your Name]
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="javascript:void(0)" onclick="copyComment()">
                        <i class="bi bi-copy text-primary fs-5"></i>
                    </a>
                    <span id="copyCommentStatus" style="opacity:0; transition: opacity .6s; font-size:10px; color:green;">
                        Copied!
                    </span>
                </div>
            </div>
            <div>
                <a href="<?= site_url('tags') ?>" title="Cancel" class="apple-btn-secondary btn-sm">Cancel</a>
                <button type="submit" 
                    class="apple-btn-primary btn-sm"
                    onclick="return confirm('Are you sure you want to renew these tags?');">
                    Renew Tags
                </button>
            </div>
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

    function copyComment() {
        const comment = document.getElementById('comment');
        const status  = document.getElementById('copyCommentStatus');

        const html = comment.innerHTML;

        const text = html
            .replace(/<br\s*\/?>/gi, '\n')
            .replace(/<a[^>]*href="([^"]+)"[^>]*>(.*?)<\/a>/gi, (_, url, txt) => `  ( )`)
            .replace(/<[^>]+>/g, '')
            .trim();

        const temp = document.createElement('textarea');
        temp.value = text;

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

</script>
<?= $this->endSection() ?>

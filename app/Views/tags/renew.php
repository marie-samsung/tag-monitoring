<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<?php
    use App\Models\PropertiesModel;
    use App\Models\VendorsModel;
    use App\Models\RenewalLogsModel;
    use App\Models\Constants;

    $renewalsModel = new RenewalLogsModel();

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->WebsiteLinks();

    $vendorsModel = new VendorsModel();
    $vendors = $vendorsModel->Options();
?>

<?php if ($ticket_id): ?>
    <h6 class="text-danger container"><b><?= esc($ticket_id) ?></b></h6>
    <?= $this->section('title') ?>
    Renewal: <?= esc($ticket_id) ?>
    <?= $this->endSection() ?>
<?php endif; ?>
<h4 class="container">Renew Tags</h4>
<?= $this->section('title') ?>
Tag: Renew
<?= $this->endSection() ?>
<hr/>

<!-- #region | PART 1 - TICKET ID -->
<?php
    if ($ticket_id == null): 
        $tag_ids = implode(',', array_column($tags, 'id'));
?>

<form action="<?= site_url('tags/renew') ?>" method="get">
    <div class="container">
        <div class="table-responsive mt-3">
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
            <div>
                <input type="hidden" name="tags" value="<?= $tag_ids ?>">
                <input type="text" name="ticket_id" id="ticket_id"
                    class="form-control"
                    value="<?= old('ticket_id', $old['ticket_id'] ?? '') ?>"
                    required>
                <small class="form-label-xs text-muted">
                    JIRA ticket ID, GTA-XXXXX
                </small>
            </div>
            <div>
                <a href="<?= site_url('tags') ?>" title="Cancel" class="btn btn-danger btn-sm">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm">Proceed</button>
            </div>
        </div>
    </div>
</form>
<!-- #endregion | PART 1 -->

<!-- #region | PART 2 - RENEWAL CONFIRMATION -->
<?php 
    else:
        $propertyIds = array_unique(array_column($tags, 'property_id'));
        $today = date('dmY');
        $libraryName = $ticket_id . '_' . $today;

        if ($tags) {
            $expDate = date('d-m-Y', strtotime($tags[0]['al_end_date'] ?? ''));
            $nextExpDate = date('d-m-Y', strtotime('+61 days', strtotime($expDate)));
        } else {
            return false;
        }

        $vendorIds = array_unique(array_column($tags, 'vendor_id'));
        $vendor = [];
        foreach ($vendorIds as $vendorId) {
            $vendor[] = $vendors[$vendorId];
        }

        if ($propertyIds) {
            $siteCode = $propertiesModel->getSiteCode($propertyIds[0]);
            $is_shop = $propertiesModel->isShop($propertyIds[0]) ? 'Shop' : 'AEM';
        }
        $jiraTitle = sprintf(
            '[LC Request] [%s] [%s] [Date Extension Rule]',
            $siteCode,
            implode(', ', $vendor)
        );

        $detailsHtml = '<ol>';
        // Target Property
        $detailsHtml .= '
            <li><b>Target Property</b></li>
            <ul>';
        foreach ($propertyIds as $property) {
            $detailsHtml .= '<li>' . $is_shop . ' : ' . esc($properties[$property]['name'] ?? 'Unknown') . '</li>';
        }
        $detailsHtml .= '
                <li>Library name: <b>' . esc($libraryName) . '</b></li>
            </ul>';
        // Target Pages
        $detailsHtml .= '
            <li><b>Target Page(s)</b></li>
            <ul>';
        foreach ($propertyIds as $property) {
            $detailsHtml .= '<li>' . $is_shop . ' : <a href="' . esc($properties[$property]['website'] ?? 'Unknown') . '" target="_blank">' . esc($properties[$property]['website'] ?? 'Unknown') . '</a></li>';
        }
        $detailsHtml .= '</ul>';
        // Expiration Dates
        $detailsHtml .= '
            <li><b>Expiration Date</b></li>
            <ul>
                <li><b>as-is :</b> ' . esc($expDate) . '</li>
                <li><b>to-be :</b> ' . esc($nextExpDate) . '</li>
            </ul>';
        // Purpose & Goal + Table
        $detailsHtml .= '
            <li><b>Purpose & Goal</b></li>
            <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr>
                        <th>Tag Name</th>
                        <th>Renew? (Y/N)</th>
                        <th>Renewal Justification</th>
                        <th>End Date</th>
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
            </table>
        </ol>';
?>

<form action="<?= site_url('tags/renew/confirm') ?>" method="post">
    <div class="container mt-3">
        <h4 class="text-primary d-flex align-items-center gap-2">
            <span id="jiraTitle"><?= esc($jiraTitle) ?></span>
            <a href="javascript:void(0)" onclick="copyJiraTitle()">
                <i class="bi bi-copy text-secondary"></i>
            </a>
            <span id="copyStatus" style="opacity:0; transition: opacity .6s; font-size:10px; color:green;">
                Copied!
            </span>
        </h4>

        <textarea id="details"></textarea>
        <div class="d-flex justify-content-end align-items-center mt-2 gap-2">
            <span id="copyDetailsStatus" style="opacity:0; transition: opacity .6s; font-size:10px; color:green;">
                Copied!
            </span>

            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyDetails()">
                Copy Description
            </button>
        </div>

        <!-- #region | DATE EXTENSION DETAILS -->
        <!-- <div class="table-responsive mt-3">
            <table class="table table-hover table-bordered">
                <tr>
                    <th class="col-2">Target Property</th>
                    <td>
                        <ul>
                            <?php foreach ($propertyIds as $property): ?>
                                <li><?= esc($properties[$property]['name'] ?? 'Unknown') ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <span>Library name: <b><?= esc($libraryName) ?></b></span>   
                    </td>
                </tr>
                <tr>
                    <th>Target Page(s)</th>
                    <td><ul>
                        <?php foreach ($propertyIds as $property): ?>
                            <li><?= esc($properties[$property]['website'] ?? 'Unknown') ?></li>
                        <?php endforeach; ?>
                    </ul></td>
                </tr>
                <tr>
                    <th>Expiration Date</th>
                    <td><ul>
                        <li>As-Is: <b><?= esc($expDate) ?></b></li>
                        <li>To-Be: <b><?= esc($nextExpDate) ?></b></li>
                    </ul></td>
                </tr>
                <tr>
                    <th>Purpose & Goal</th>
                    <td>THIS FEATURE IS TBD</td>
                </tr>
            </table>
        </div> -->
        <!-- #endregion -->

        <!-- <ol>
            <li><b>Target Property</b></li>
            <ul>
                <?php foreach ($propertyIds as $property): ?>
                    <li><?= esc($properties[$property]['name'] ?? 'Unknown') ?></li>
                <?php endforeach; ?>
                <span>Library name: <b><?= esc($libraryName) ?></b></span> 
            </ul>

            <li><b>Target Page(s)</b></li>
            <ul>
                <?php foreach ($propertyIds as $property): ?>
                    <li>
                        <a href="<?= esc($properties[$property]['website']) ?>" target="_blank">
                            <?= esc($properties[$property]['website']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <li><b>Expiration Date</b></li>
            <ul>
                <li><b>as-is : </b><?= esc($expDate) ?></li>
                <li><b>to-be : </b><?= esc($nextExpDate) ?></li>
            </ul>

            <li><b>Purpose & Goal</b></li>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tag Name</th>
                        <th>Renew? (Y/N)</th>
                        <th>Renewal Justification</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tags as $tag): ?>
                        <tr>
                            <td><?= esc($tag['name']) ?></td>
                            <td>Y</td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </ol> -->

        <!-- #region | TAGS -->
        <h4 class="mt-5 mb-3 text-primary">Tags List</h4>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Property</th>
                        <th>Name</th>
                        <th>Updated in AL?</th>
                        <th>Next End Date</th>
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

        <div class="justify-content-end">
            <a href="<?= site_url('tags') ?>" title="Cancel" class="btn btn-secondary btn-sm">Cancel</a>
            <button type="submit" 
                class="btn btn-success btn-sm"
                onclick="return confirm('Are you sure you want to renew these tags?');">
                Renew Tags
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
                ['table', ['table']],   // ← THIS ENABLES TABLES
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
                btn.hide(); // hide Save button
                btn.siblings('.updated-label').fadeIn(); // show Updated text
            }
        }, 'json');
    });

    function copyJiraTitle() {
        const text = document.getElementById('jiraTitle').innerText;
        const status = document.getElementById('copyStatus');

        navigator.clipboard.writeText(text).then(() => {
            status.style.opacity = 1;
            setTimeout(() => {
                status.style.opacity = 0;
            }, 1000);
        });
    }

    // function copyDetails() {
    //     const html = $('#details').summernote('code');
    //     navigator.clipboard.writeText(html).then(() => {
    //         const status = document.getElementById('copyDetailsStatus');
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
<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<?php
    use App\Models\PropertiesModel;
    use App\Models\VendorsModel;
    use App\Models\RenewalLogsModel;

    $renewalsModel = new RenewalLogsModel();

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->WebsiteLinks();

    $vendorsModel = new VendorsModel();
    $vendors = $vendorsModel->Options();
?>

<?php if ($ticket_id): ?>
    <h6 class="text-danger"><b><?= esc($ticket_id) ?></b></h6>
    <?= $this->section('title') ?>
    Renewal: <?= esc($ticket_id) ?>
    <?= $this->endSection() ?>
<?php endif; ?>
<h4>Renew Tags</h4>
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
?>

<form action="<?= site_url('tags/renew/confirm') ?>" method="post">
    <div class="container mt-3">
        <!-- <h4 class="text-primary">Date Extension Details</h4> -->
        <!-- #region | DATE EXTENSION DETAILS -->
        <div class="table-responsive mt-3">
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
                    <!-- @TODO: Add purpose and goal here -->
                    <td>THIS FEATURE IS TBD</td>
                </tr>
            </table>
        </div>
        <!-- #endregion -->

        <!-- #region | TAGS -->
        <h4 class="my-3 text-primary">Tags List</h4>
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
                            <td>
                                <input type="checkbox">
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
                onclick="return confirm('Have you updated these tags in AL?');">
                Renew Tags
            </button>
        </div>
    </div>
</form>
<!-- #endregion | PART 2 -->

<?php endif; ?>

<?= $this->endSection() ?>
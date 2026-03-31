<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $tag['name'] ?> : Details
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php 
    use App\Models\Constants;
    use App\Models\PropertiesModel;
    use App\Models\VendorsModel;
    helper('custom_functions');

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->WebsiteLinks();

    $vendorsModel = new VendorsModel();
    $vendors = $vendorsModel->Options();
?>

<h3 class="container"><?= $tag['name'] ?></h3>

<div class="container d-flex mt-3 gap-3">
    <!-- #region | TAG DETAILS -->
    <div class="w-25 p-4 card">
        <div class="mb-2">
            <label class="form-label-xs">Property</label>
            <div class="form-control-plaintext p-0 ms-2">
                <a href="<?= esc($properties[$tag['property_id']]['ref_link'] ?? '#') ?>" target="_blank">
                    <?= esc($properties[$tag['property_id']]['name'] ?? '—') ?>
                </a>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Vendor</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($vendors[$tag['vendor_id']] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Tag Name</label>
            <span class="form-control-plaintext p-0 ms-2">
                <a href="<?= esc($tag['ref_link'] ?? '#') ?>" target="_blank">
                    <?= esc($tag['name']) ?>
                </a>
            </span>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Conditions</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($tag['conditions'] ?: '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Status</label>
            <div class="d-flex gap-2">
                <div class="col form-control-plaintext p-0 ms-2">
                    <span class="badge <?= status_class($tag['status']) ?>">
                        <?= esc(Constants::STATUS[$tag['status']] ?? 'Unknown') ?>
                    </span>
                </div>
                <?php if ($tag['status'] == Constants::STATUS_PENDING_VERIFICATION): ?>
                    <div class="col">
                        <a href="<?= site_url('tags/verify/' . $tag['id']) ?>" 
                            class="badge bg-primary text-decoration-none"
                            onclick="return confirm('Have you verified the end date matches in AL?');">
                            Verify
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Adobe Launch End Date</label>
            <div class="form-control-plaintext p-0 ms-2 <?= date_class($tag['al_end_date'], $tag['status']) ?>">
                <?= esc($tag['al_end_date'] ?: '—') ?>
            </div>
        </div>
        
        <hr>

        <div class="d-flex align-items-center gap-1">
            <a href="<?= site_url('tags') ?>" class="btn btn-secondary btn-sm px-2">Back</a>
            <a href="<?= site_url('tags/update/' . $tag['id']) ?>" class="btn btn-primary btn-sm">Edit</a>
        </div>
    </div>
    <!-- #endregion -->

    <!-- #region | JIRA HISTORY DETAILS -->
    <div class="w-75">
        <div class="d-flex justify-content-between mb-3">
            <h5>JIRA References</h5>
            <div>
                <a href="<?= site_url('jira/create?tag_id='.$tag['id']) ?>" target="_blank" title="Add a new JIRA ticket" class="btn btn-success btn-sm ms-auto px-2">Add Entry</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ticket ID</th>
                        <th>Type</th>
                        <th>Comments</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($jiraTickets as $jira): ?>
                        <tr>
                            <td><?= $jira['id'] ?: '-' ?></td>
                            <td>
                                <a href="<?= Constants::JIRA_LINK ?><?= $jira['ticket_id'] ?: '#' ?>" target="_blank">
                                    <?= $jira['ticket_id'] ?: '-' ?>
                                </a>
                            </td>
                            <td><?= Constants::JIRA_TICKET_TYPES[$jira['type']] ?: '-' ?></td>
                            <td><?= $jira['comments'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- #endregion -->
</div>

<?= $this->endSection() ?>

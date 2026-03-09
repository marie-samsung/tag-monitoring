<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<h3><?= $tag['name'] ?></h3>

<?php 
    use App\Models\Constants;
    use App\Models\PropertiesModel;
    use App\Models\VendorsModel;
    helper('custom_functions');

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->Options();

    $vendorsModel = new VendorsModel();
    $vendors = $vendorsModel->Options();
?>

<div class="container d-flex m-4 gap-3">
    <!-- #region | TAG DETAILS -->
    <div class="w-25 p-4 card">
        <div class="mb-2">
            <label class="form-label-xs">Property</label>
            <div class="form-control-plaintext fw-semibold p-0 ms-2">
                <?= esc($properties[$tag['property_id']] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Vendor</label>
            <div class="form-control-plaintext fw-semibold p-0 ms-2">
                <?= esc($vendors[$tag['vendor_id']] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Tag Name</label>
            <span class="form-control-plaintext fw-semibold p-0 ms-2">
                <?= esc($tag['name']) ?>
            </span>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Conditions</label>
            <div class="form-control-plaintext fw-semibold p-0 ms-2">
                <?= esc($tag['conditions'] ?: '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Status</label>
            <div class="form-control-plaintext fw-semibold p-0 ms-2">
                <span class="badge <?= status_class($tag['status']) ?>">
                    <?= esc(Constants::STATUS[$tag['status']] ?? 'Unknown') ?>
                </span>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Adobe Launch End Date</label>
            <div class="form-control-plaintext fw-semibold p-0 ms-2">
                <?= esc($tag['al_end_date'] ?: '—') ?>
            </div>
        </div>
        
        <hr>

        <div class="d-flex align-items-center gap-1">
            <a href="<?= site_url('tags') ?>" class="btn btn-secondary btn-sm px-2">Back</a>
            <a href="<?= site_url('tags/update/' . $tag['id']) ?>" class="btn btn-primary btn-sm">Edit</a>

            <?php if (!empty($tag['ref_link'])): ?>
                <a href="<?= esc($tag['ref_link']) ?>" target="_blank" class="btn btn-danger btn-sm">
                    Adobe Launch
                </a>
            <?php endif; ?>
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
                        <th>Launch Publishing File</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($jiraTickets as $jira): ?>
                        <tr>
                            <td><?= $jira['id'] ?: '-' ?></td>
                            <td>
                                <?= $jira['ticket_id'] ?: '-' ?>
                            </td>
                            <td class="col-3"><?= Constants::JIRA_TICKET_TYPES[$jira['type']] ?: '-' ?></td>
                            <td class="col-4"><?= $jira['comments'] ?></td>
                            <td class="col-3"><?= $jira['lp_path'] ?: '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- #endregion -->
</div>

<?= $this->endSection() ?>

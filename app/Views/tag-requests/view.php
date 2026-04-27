<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
[<?= $request['ticket_id']  ?>] Request Details
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
use App\Models\Constants;
use App\Models\TagsModel;
use App\Models\PropertiesModel;
use App\Models\VendorsModel;
use App\Models\OrganizationsModel;
helper('custom_functions');

$propertiesModel = new PropertiesModel();
$properties = $propertiesModel->WebsiteLinks();

$vendorsModel = new VendorsModel();
$vendors = $vendorsModel->Options();

$organizationsModel = new OrganizationsModel();
$organizations = $organizationsModel->Options();

$tagModel = new TagsModel();
?>

<div class="container">
    <h6 class="text-danger">
        <?= Constants::SUBSIDIARIES[$properties[$request['property_id']]['subsidiary_id']] ?>
    </h6>
    <h3>
        [<?= $vendors[$request['vendor_id']] ?>]
        <?= Constants::JIRA_TICKET_TYPES[$request['type']] ?>
    </h3>
</div>

<div class="container d-flex my-3 gap-1">
    <!-- #region | REQUEST DETAILS -->
    <div class="w-20 p-4 card">
        <div class="mb-2">
            <label class="form-label-xs">Property</label>
            <div class="form-control-plaintext p-0 ms-2">
                <a href="<?= esc($properties[$request['property_id']]['ref_link'] ?? '#') ?>" target="_blank">
                    <?= esc($properties[$request['property_id']]['name'] ?? '—') ?>
                </a>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Vendor</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($vendors[$request['vendor_id']] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Status</label>
            <div class="d-flex gap-2">
                <div class="col form-control-plaintext p-0 ms-2">
                    <span class="badge <?= status_request_class($request['status']) ?>">
                        <?= esc(Constants::STATUS_REQUESTS[$request['status']] ?? 'Unknown') ?>
                    </span>
                </div>
            </div>
        </div>

        <label class="form-label m-0 fw-semibold mt-2">Requester Details</label>
        <div class="mb-2">
            <label class="form-label-xs">Name</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($request['requester_name'] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Email Address</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($request['requester_email'] ?? '—') ?>
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label-xs">Organization</label>
            <div class="form-control-plaintext p-0 ms-2">
                <?= esc($organizations[$request['requester_organization']] ?? '—') ?>
            </div>
        </div>

        <hr>

        <div class="d-flex align-items-center gap-1">
            <a href="<?= site_url('tag-requests') ?>" class="btn btn-secondary btn-sm px-2">Back</a>
            <a href="<?= site_url('tag-requests/update/' . $request['id']) ?>" class="btn btn-primary btn-sm">Edit</a>
        </div>
    </div>
    <!-- #endregion -->

    <!-- #region | TAGS LIST -->
    <div class="card w-40 p-4">
        <h4>Tag(s)</h4>

        <table class="table table-bordered">
            <tbody>
                <?php foreach ($tags as $tag) : ?>
                    <tr>
                        <td>
                            <a href="<?= site_url('tags/view/' . $tag['id']) ?>" target="_blank">
                                <?= $tag['name'] ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- #endregion -->

    <!-- #region | FILES LIST -->
    <div class="card w-40 p-4">
        <h4>File(s)</h4>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>File Name</th>
                    <th>File Type</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        TBD
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- #endregion
</div>

<?= $this->endSection() ?>

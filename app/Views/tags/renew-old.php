<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<?php
    use App\Models\PropertiesModel;
    use App\Models\VendorsModel;
    use App\Models\RenewalLogsModel;

    $renewalsModel = new RenewalLogsModel();

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->Options();

    $vendorsModel = new VendorsModel();
    $vendors = $vendorsModel->Options();

    $today = date('d-m-Y');
    $next_end_date = date('d-m-Y', strtotime('+61 days', strtotime($today)));
    $requester = 'Marie Tiomico';
    $event = 'Window Loaded';
?>

<form action="<?= site_url('tags/renew/confirm') ?>" method="post">
    <div class="container">
        <!-- region | LP FILE -->
        <h4>Launch Publishing File</h4>
        <div class="table-responsive">
            <table class="table table-hover align-middle apple-table">
                <thead>
                    <tr>
                        <th>Request Date</th>
                        <th>Library Name</th>
                        <th>Site Code</th>
                        <th>Rule Name</th>
                        <th>Updated in AL?</th>
                        <th>Requester</th>
                        <th>Event</th>
                        <th>Conditions</th>
                        <th>Actions</th>
                        <th>Extensions</th>
                        <th>Expired Date</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($tags as $tag): ?>
                        <tr>
                            <?php
                                $property_value = $properties[$tag['property_id']] ?? '-';
                                $vendor_value = $vendors[$tag['vendor_id']] ?? '-';

                                $renewalsToday = $renewalsModel->countRenewalsToday($tag['property_id']);
                                $renewalsToday++;
                                $library_name = '3PARTYTAGS_RENEW_' . date('dmy') . '_' . $renewalsToday;
                            ?>

                            <td><?= $today ?></td>
                            <td><?= $library_name ?></td>
                            <td><?= $property_value ?></td>
                            <td>
                                <a href="<?= esc($tag['ref_link']) ?>" target="_blank">
                                    <?= $tag['name'] ?>
                                </a>
                            </td>
                            <td>
                                <input type="checkbox">
                            </td>
                            <td><?= $requester ?></td>
                            <td><?= $event ?></td>
                            <td><?= $tag['conditions'] ?></td>
                            <td><?= $vendor_value ?></td>
                            <td><?= 'N' ?></td>
                            <td><?= $next_end_date ?></td>

                            <!-- Hidden fields to pass data to controller when form is submitted -->
                            <input type="hidden" name="tags[<?= $tag['id'] ?>][id]" value="<?= $tag['id'] ?>">
                            <input type="hidden" name="tags[<?= $tag['id'] ?>][library_name]" value="<?= $library_name ?>">
                            <input type="hidden" name="tags[<?= $tag['id'] ?>][property_id]" value="<?= $tag['property_id'] ?>">
                            <input type="hidden" name="tags[<?= $tag['id'] ?>][vendor_id]" value="<?= $tag['vendor_id'] ?>">
                            <input type="hidden" name="tags[<?= $tag['id'] ?>][next_end_date]" value="<?= $next_end_date ?>">
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- endregion -->

        <div class="d-flex justify-content-between gap-2">
            <div>
                <input type="text" name="info[ticket_id]" id="ticket_id"
                    class="form-control"
                    value="<?= old('ticket_id', $old['ticket_id'] ?? '') ?>"
                    required>
                <small class="form-label-xs text-muted">
                    JIRA ticket ID, GTA-XXXXX
                </small>

                <input type="hidden" name="info[requester]" value="<?= $requester ?>">
            </div>
            <div>
                <a href="<?= site_url('tags') ?>" title="Cancel" class="apple-btn-secondary btn-sm">Cancel</a>
                <button type="submit" 
                    class="apple-btn-primary btn-sm"
                    data-confirm="Have you updated these tags in AL?"
                    data-confirm-title="Confirm Update"
                    data-confirm-type="warning"
                    data-confirm-text="Renew">
                    Renew Tags
                </button>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
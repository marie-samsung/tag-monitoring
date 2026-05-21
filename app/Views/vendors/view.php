<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $vendor['name'] ?> : Details
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    use App\Models\PropertiesModel;
    use App\Models\JiraHistoryModel;
    helper('custom_functions');

    $subsidiary = Constants::SUBSIDIARIES[$subsidiary_id] ?? 'Not Found';

    $propertiesModel = new PropertiesModel();
    $properties = $propertiesModel->WebsiteLinks();
?>

<?php if ($tags): ?>
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3><?= "[" . $subsidiary . "] " . $vendor['name'] ?></h3>
        <a href="<?= site_url('vendors/export/' . $vendor['id'] . '?' . http_build_query($_GET)) ?>" class="apple-btn-secondary btn-sm">
            Export as CSV
        </a>
    </div>

    <?php if (!empty($vendor['reason']) || !empty($vendor['tag_purpose'])) : ?>
        <div class="container-fluid justify-content-start mb-3">
            <?php if (!empty($vendor['reason'])) : ?>
                <span class="fw-semibold form-label-xs">Business Reason: </span>
                <span class="form-label-xs"><?= $vendor['reason'] ?></span>
            <?php endif; ?>

            <?php if (!empty($vendor['tag_purpose'])) : ?>
                <br>
                <span class="fw-semibold form-label-xs">Tag Purpose: </span>
                <span class="form-label-xs"><?= $vendor['tag_purpose'] ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="container-fluid">
        <div class="apple-table-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="form-label-sm fw-semibold">#</th>
                            <th class="form-label-sm fw-semibold">Property</th>
                            <th class="form-label-sm fw-semibold">Name</th>
                            <th class="form-label-sm fw-semibold">Status</th>
                            <th class="form-label-sm fw-semibold">Days</th>
                            <th class="form-label-sm fw-semibold">End Date</th>
                            <th class="form-label-sm fw-semibold">Updated</th>
                            <th class="form-label-sm fw-semibold">Actions</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php
                        foreach ($tags as $tag):
                            $ticketId = (new JiraHistoryModel())->getLatestTag($tag['id'])['ticket_id'] ?? null;
                    ?>
                        <tr>
                            <!-- ID -->
                            <td><?= $tag['id'] ?></td>
                            <!-- Property -->
                            <td>
                                <i class="bi bi-tag-fill" style="<?= random_text_style($tag['property_id']) ?>"></i>
                                <a href="<?= $properties[$tag['property_id']]['ref_link'] ?>" class="apple-link-dark form-label-sm ms-1">
                                    <?= $properties[$tag['property_id']]['name'] ?>
                                </a>
                            </td>
                            <!-- Name -->
                            <td class="form-label-sm">
                                <a href="<?= $tag['ref_link'] ?>" class="apple-link-dark">
                                    <?= esc($tag['name']) ?>
                                </a>
                            </td>
                            <!-- Status -->
                            <td>
                                <span class="badge <?= status_class($tag['status']) ?>">
                                    <?= esc(status_label($tag['status'])) ?>
                                </span>
                                <?php if ($tag['status'] == Constants::STATUS_PENDING_VERIFICATION): ?>
                                    <br>
                                    <a href="<?= Constants::JIRA_LINK . $ticketId ?>" class="apple-link ms-1" style="font-size: 0.8rem;">
                                        <?= $ticketId ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <!-- Days -->
                            <?php
                                $alEndDate = strtotime($tag['al_end_date']);
                                $now = time();
                                $diffDays = ceil(($alEndDate - $now) / 86400);
                            ?>
                            <td class="<?= days_class($diffDays) ?> form-label-sm">
                                <?= esc($diffDays) ?>
                            </td>
                            <!-- End Date -->
                            <td class="<?= date_class($tag['al_end_date'], $tag['status']) ?> form-label-sm" style="width:max-content; white-space:nowrap;">
                                <?= esc(date('d-m-Y', strtotime($tag['al_end_date']))) ?>
                                <?php if ($tag['requested_date']) : ?>
                                    <br><small class="text-muted">[<?= esc(date('d-m-Y', strtotime($tag['requested_date']))) ?>]</small>
                                <?php endif; ?>
                            </td>
                            <!-- Updated At -->
                            <td class="form-label-sm" style="width:max-content; white-space:nowrap;"><?= esc(date('d-m-Y', strtotime($tag['updated_at']))) ?></td>
                            <!-- Actions -->
                            <td class="text-nowrap align-middle">
                                <a href="<?= site_url('tags/view/'.$tag['id']) ?>" title="View tag details" class="apple-btn-secondary btn-sm">View</a>
                                <a href="<?= site_url('tags/update/'.$tag['id']) ?>" title="Update tag details" class="apple-btn-primary btn-sm">Update</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
<?php else: ?>

    <div class="container alert alert-warning text-center" role="alert">
        [<?= $subsidiary ?>] [<?= $vendor['name'] ?>] No tags found on this section.
    </div>

<?php endif; ?>

<?= $this->endSection() ?>
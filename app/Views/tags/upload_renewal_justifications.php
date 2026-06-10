<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Upload Renewal Justifications
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tags') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0;" title="Back to Inventory">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Upload Renewal Justifications</h2>
            <p class="text-secondary small mb-0">Import and parse an Excel sheet (.xlsx) to bulk update renewal justifications in the database.</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($extracted_rows)): ?>
        <!-- Preview of Extracted Data -->
        <div class="alert alert-info mb-4">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Data Extracted Successfully!</strong> Please review the summary and table below before updating.
        </div>

        <?php 
        $validCount = 0;
        $invalidTagsCount = 0;
        $propertyNotFoundCount = 0;
        foreach ($extracted_rows as $row) {
            if ($row['status'] === 'Valid') {
                $validCount++;
            } elseif ($row['status'] === 'Tag Not Found') {
                $invalidTagsCount++;
            } elseif ($row['status'] === 'Property Not Found') {
                $propertyNotFoundCount++;
            }
        }
        $hasInvalid = ($invalidTagsCount > 0 || $propertyNotFoundCount > 0);
        ?>

        <!-- Extraction Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="apple-card p-3 d-flex align-items-center gap-3 animate-fade-in" style="background-color: #f4fbf7; border: 1px solid #d1f2e0; box-shadow: var(--shadow-sm); border-radius: 12px; transition: transform var(--transition-fast);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-success text-white" style="width: 48px; height: 48px; min-width: 48px;">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary mb-1 small uppercase fw-bold" style="letter-spacing: 0.5px; font-size: 0.75rem;">Renewal Justifications Extracted</h6>
                        <h3 class="mb-0 fw-bold text-success" style="font-size: 1.6rem;"><?= $validCount ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="apple-card p-3 d-flex align-items-center gap-3 animate-fade-in" style="background-color: #fff8f8; border: 1px solid #fcdad7; box-shadow: var(--shadow-sm); border-radius: 12px; transition: transform var(--transition-fast);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger text-white" style="width: 48px; height: 48px; min-width: 48px;">
                        <i class="bi bi-x-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary mb-1 small uppercase fw-bold" style="letter-spacing: 0.5px; font-size: 0.75rem;">Invalid Tags Found</h6>
                        <h3 class="mb-0 fw-bold text-danger" style="font-size: 1.6rem;"><?= $invalidTagsCount ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="apple-card p-3 d-flex align-items-center gap-3 animate-fade-in" style="background-color: #fffbf0; border: 1px solid #ffeeba; box-shadow: var(--shadow-sm); border-radius: 12px; transition: transform var(--transition-fast);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning text-white" style="width: 48px; height: 48px; min-width: 48px;">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary mb-1 small uppercase fw-bold" style="letter-spacing: 0.5px; font-size: 0.75rem;">Property Not Found</h6>
                        <h3 class="mb-0 fw-bold text-warning" style="font-size: 1.6rem;"><?= $propertyNotFoundCount ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($hasInvalid): ?>
            <div class="alert alert-warning mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Warning:</strong> Some rows are invalid (highlighted in red) and will be skipped during insertion.
            </div>
        <?php endif; ?>

        <div class="apple-card mb-4" style="background-color: #ffffff; border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: var(--shadow-sm);">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-excel text-success fs-4"></i>
                <span>Parsed Justification Data</span>
            </h5>
            
            <div class="apple-table-container">
                <table class="table table-hover apple-table mb-0 align-middle">
                    <thead>
                        <tr class="bg-light">
                            <th style="font-weight: 600; padding: 12px 16px;">Property Name</th>
                            <th style="font-weight: 600; padding: 12px 16px;">Tag Name</th>
                            <th style="font-weight: 600; padding: 12px 16px; width: 80px;">Renew</th>
                            <th style="font-weight: 600; padding: 12px 16px;">Justification</th>
                            <th style="font-weight: 600; padding: 12px 16px; width: 100px;">Duration</th>
                            <th style="font-weight: 600; padding: 12px 16px; width: 150px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($extracted_rows as $row): ?>
                            <tr>
                                <td style="padding: 12px 16px; font-weight: 500;"><?= esc($row['property_name']) ?></td>
                                <td style="padding: 12px 16px;"><code><?= esc($row['tag_name']) ?></code></td>
                                <td style="padding: 12px 16px;"><?= esc($row['renew']) ?></td>
                                <td style="padding: 12px 16px;"><?= esc($row['justification']) ?></td>
                                <td style="padding: 12px 16px;"><?= esc($row['duration']) ?></td>
                                <td style="padding: 12px 16px;">
                                    <?php if ($row['status'] === 'Valid'): ?>
                                        <span class="badge bg-success">Valid</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><?= esc($row['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <form action="<?= site_url('tags/confirm-renewal-justifications') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="temp_file_path" value="<?= esc($temp_file_path) ?>">

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="<?= site_url('tags/upload-renewal-justifications') ?>" class="apple-btn-secondary px-4 text-decoration-none d-flex align-items-center justify-content-center">Cancel & Upload Another File</a>
                <button type="submit" class="apple-btn-primary px-4">Confirm and Update Justifications</button>
            </div>
        </form>
    <?php else: ?>
        <form action="<?= site_url('tags/parse-renewal-justifications') ?>" method="post" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to update the renewal justifications?');" style="max-width: 600px;">
            <?= csrf_field() ?>
            
            <!-- Upload Card Container -->
            <div class="apple-card mb-4" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
                <h5 class="mb-4 text-primary d-flex align-items-center gap-2 fw-bold">
                    <i class="bi bi-file-earmark-excel-fill"></i>
                    <span>File Upload</span>
                </h5>
                
                <div class="mb-3">
                    <label for="excel_file" class="form-label-sm fw-semibold text-secondary mb-2">Select Excel File (.xlsx)</label>
                    <input class="form-control form-control-xs bg-white" type="file" id="excel_file" name="excel_file" accept=".xlsx" required style="border-radius: 8px; border: 1px solid var(--color-border-input); padding: 6px 12px; height: auto;">
                    <div class="form-text text-muted mt-2" style="font-size: 0.75rem;">
                        Please select the completed <strong>Renewal Justification</strong> Excel template.
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="<?= site_url('tags') ?>" title="Cancel" class="apple-btn-secondary px-4 text-decoration-none">Cancel</a>
                <button type="submit" class="apple-btn-primary px-4">Upload and Extract</button>
            </div>
        </form>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>


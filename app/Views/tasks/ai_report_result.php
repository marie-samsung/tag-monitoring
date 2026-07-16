<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
AI Report Results
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .ai-report-card {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        margin-bottom: 20px;
    }
    
    .ai-report-card h5 {
        color: #0047b3; /* Samsung blue tint */
        font-weight: 700;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eaeaea;
    }

    .ai-report-field {
        margin-bottom: 10px;
    }

    .ai-report-field strong {
        color: #333;
        font-weight: 600;
    }

    .export-container {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.015);
        margin-bottom: 30px;
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div>
            <h2 class="mb-0 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-robot text-primary"></i> AI Report Generated
            </h2>
            <p class="text-secondary small mb-0">Week: <?= esc($week ?? 'Unknown') ?></p>
        </div>
        <a href="<?= site_url('tasks/ai-report') ?>" class="apple-btn-secondary btn-sm px-3 text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i> Back to Generator
        </a>
    </div>

    <div class="export-container d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-1">Export this Report</h5>
            <p class="text-muted small mb-0">Download the generated report as a Word document or PDF.</p>
        </div>
        <form method="post" action="<?= site_url('tasks/ai-report/download') ?>" class="d-flex gap-3">
            <?= csrf_field() ?>
            <input type="hidden" name="reportText" value="<?= esc($reportText) ?>">
            <input type="hidden" name="week" value="<?= esc($week) ?>">
            
            <button type="submit" name="format" value="word" class="apple-btn-primary px-4 py-2" style="border-radius: 10px;">
                <i class="bi bi-file-earmark-word-fill me-2"></i> Word (.docx)
            </button>
            <button type="submit" name="format" value="pdf" class="btn btn-outline-danger px-4 py-2" style="border-radius: 10px;">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i> PDF
            </button>
        </form>
    </div>

    <div class="row">
        <?php
            // Parse the raw text into blocks based on the square marker
            $blocks = explode('▣', $reportText);
            foreach ($blocks as $block) {
                $block = trim($block);
                if (empty($block)) continue;
                
                // Extract Title (first line)
                $lines = explode("\n", $block);
                $title = array_shift($lines);
                
                // Parse remaining fields
                $fields = [];
                $currentField = null;
                $currentValue = '';
                
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line)) continue;
                    
                    // Match pattern: "- Field Name: Value" or "Field Name: Value"
                    if (preg_match('/^(?:-\s*)?([^:]+):\s*(.*)$/', $line, $matches)) {
                        // If we were building a previous field, save it
                        if ($currentField !== null) {
                            $fields[$currentField] = trim($currentValue);
                        }
                        
                        $currentField = trim($matches[1]);
                        $currentValue = trim($matches[2]) . " ";
                    } else {
                        // Append to current field value
                        if ($currentField !== null) {
                            $currentValue .= $line . " ";
                        }
                    }
                }
                
                // Save last field
                if ($currentField !== null) {
                    $fields[$currentField] = trim($currentValue);
                }
        ?>
            <div class="col-12 col-xl-6 mb-4">
                <div class="ai-report-card h-100">
                    <h5>▣ <?= esc(trim($title)) ?></h5>
                    
                    <?php if (empty($fields)): ?>
                        <!-- Fallback if regex didn't parse fields strictly, just show raw lines -->
                        <div class="ai-report-field">
                            <?= nl2br(esc(implode("\n", $lines))) ?>
                        </div>
                    <?php else: ?>
                        <?php foreach ($fields as $fieldName => $fieldValue): ?>
                            <div class="ai-report-field">
                                <strong><?= esc($fieldName) ?>:</strong>
                                <span class="text-secondary"><?= esc($fieldValue) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?= $this->endSection() ?>

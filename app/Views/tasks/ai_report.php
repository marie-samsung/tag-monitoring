<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
AI Report Generation
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .ai-report-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.015);
        max-width: 700px;
        margin: 0 auto;
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div>
            <h2 class="mb-0 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-robot text-primary"></i> AI Report Generation
            </h2>
            <p class="text-secondary small mb-0">Generate an automated weekly performance report using Samsung Fabrix AI.</p>
        </div>
        <a href="<?= site_url('tasks') ?>" class="apple-btn-secondary btn-sm px-3 text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i> Back to Tasks
        </a>
    </div>

    <div class="ai-report-card mb-4">
        <form id="aiReportForm" method="post" action="<?= site_url('tasks/ai-report/generate') ?>">
            <?= csrf_field() ?>
            
            <div class="mb-4">
                <label class="form-label fw-semibold text-dark">Select AI Model</label>
                <select name="model_id" class="form-select" required style="max-width: 400px; border-radius: 10px;">
                    <option value="" disabled selected>Choose a model...</option>
                    <?php if (!empty($models)): ?>
                        <?php foreach ($models as $m): 
                            // Extract english name if available, fallback to ko, else empty
                            $modelName = 'Unknown Model';
                            if (!empty($m['name']) && is_array($m['name'])) {
                                foreach ($m['name'] as $n) {
                                    if (isset($n['languageCode']) && $n['languageCode'] === 'en') {
                                        $modelName = $n['content'];
                                        break;
                                    } elseif (isset($n['languageCode']) && $n['languageCode'] === 'ko') {
                                        $modelName = $n['content']; // Fallback to ko
                                    }
                                }
                            }
                            
                            $modelDesc = '';
                            if (!empty($m['description']) && is_array($m['description'])) {
                                foreach ($m['description'] as $d) {
                                    if (isset($d['languageCode']) && $d['languageCode'] === 'en') {
                                        $modelDesc = $d['content'];
                                        break;
                                    } elseif (isset($d['languageCode']) && $d['languageCode'] === 'ko') {
                                        $modelDesc = $d['content']; // Fallback to ko
                                    }
                                }
                            }
                        ?>
                            <option value="<?= esc($m['modelId']) ?>" title="<?= esc($modelDesc) ?>">
                                <?= esc($modelName) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="form-text mt-2 text-muted">Select which LLM model to use for analyzing the tasks. Hover over options to see descriptions.</div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-dark">Select Week</label>
                <input type="week" name="week" id="weekInput" class="form-control" value="<?= esc($week ?? '') ?>" required style="max-width: 300px; border-radius: 10px;">
                <div class="form-text mt-2 text-muted">The AI will analyze tasks updated or completed within this specific week.</div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-dark">Select Projects to Include</label>
                <div class="d-flex flex-wrap gap-3 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="projects[]" value="DWF" id="projDWF" checked>
                        <label class="form-check-label" for="projDWF">DWF</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="projects[]" value="Media Tagging" id="projMedia" checked>
                        <label class="form-check-label" for="projMedia">Media Tagging</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="projects[]" value="CRM" id="projCRM" checked>
                        <label class="form-check-label" for="projCRM">CRM</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="projects[]" value="Spoke" id="projSpoke" checked>
                        <label class="form-check-label" for="projSpoke">Spoke</label>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 pt-3 border-top mt-4">
                <button type="button" class="apple-btn-primary px-4 py-2 flex-grow-1" onclick="generateReport('word')" style="border-radius: 10px;">
                    <i class="bi bi-file-earmark-word-fill me-2"></i> Export as Word (.docx)
                </button>
                <button type="button" class="btn btn-outline-danger px-4 py-2 flex-grow-1" onclick="generateReport('pdf')" style="border-radius: 10px;">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Export as PDF
                </button>
            </div>
            <input type="hidden" name="format" id="exportFormat" value="word">
        </form>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(255,255,255,0.8); backdrop-filter: blur(5px); z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
    <h4 class="mt-3 fw-bold text-dark">Analyzing data with Fabrix AI...</h4>
    <p class="text-secondary">This may take a few seconds. Please do not close this window.</p>
</div>

<script>
    // Initialize week to current if empty
    document.addEventListener('DOMContentLoaded', () => {
        const weekInput = document.getElementById('weekInput');
        if (!weekInput.value) {
            const today = new Date();
            const startOfYear = new Date(today.getFullYear(), 0, 1);
            const pastDays = (today - startOfYear) / 86400000;
            const weekNumber = Math.ceil((pastDays + startOfYear.getDay() + 1) / 7);
            const weekStr = weekNumber.toString().padStart(2, '0');
            weekInput.value = `${today.getFullYear()}-W${weekStr}`;
        }
    });

    function generateReport(format) {
        document.getElementById('exportFormat').value = format;
        
        // Ensure at least one project is selected
        const checkboxes = document.querySelectorAll('input[name="projects[]"]:checked');
        if (checkboxes.length === 0) {
            alert("Please select at least one project.");
            return;
        }

        // Show loading spinner
        document.getElementById('loadingOverlay').classList.remove('d-none');
        
        // Submit form
        document.getElementById('aiReportForm').submit();

        // 10 seconds is usually enough for the file dialog to pop up.
        setTimeout(() => {
            document.getElementById('loadingOverlay').classList.add('d-none');
        }, 10000);
    }
</script>

<?= $this->endSection() ?>

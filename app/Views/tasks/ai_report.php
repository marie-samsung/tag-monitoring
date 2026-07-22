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
                <label class="form-label fw-semibold text-dark mb-3">Select AI Model</label>
                <div class="row g-3">
                    <?php if (!empty($models)): ?>
                        <?php foreach ($models as $idx => $m): 
                            $modelName = 'Unknown Model';
                            if (!empty($m['name']) && is_array($m['name'])) {
                                foreach ($m['name'] as $n) {
                                    if (isset($n['languageCode']) && $n['languageCode'] === 'en') {
                                        $modelName = $n['content'];
                                        break;
                                    } elseif (isset($n['languageCode']) && $n['languageCode'] === 'ko') {
                                        $modelName = $n['content'];
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
                                        $modelDesc = $d['content'];
                                    }
                                }
                            }
                        ?>
                        <div class="col-md-6">
                            <input type="radio" class="btn-check" name="model_id" id="model_<?= esc($m['modelId']) ?>" value="<?= esc($m['modelId']) ?>" <?= $idx === 0 ? 'checked' : '' ?> required>
                            <label class="btn btn-outline-primary w-100 text-start p-3 h-100 d-flex flex-column" for="model_<?= esc($m['modelId']) ?>" style="border-radius: 12px; transition: all 0.2s;">
                                <div class="fw-bold mb-2 fs-6 d-flex align-items-center">
                                    <i class="bi bi-cpu text-primary me-2 fs-5"></i>
                                    <?= esc($modelName) ?>
                                </div>
                                <div class="small text-muted" style="white-space: normal; line-height: 1.4;">
                                    <?= esc($modelDesc) ?>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-muted">No models found. Please check your API configuration.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-dark">Select Week</label>
                <input type="week" name="week" id="weekInput" class="form-control" value="<?= esc($week ?? '') ?>" required style="max-width: 300px; border-radius: 10px;">
                <div class="form-text mt-2 text-muted">The AI will analyze tasks updated or completed within this specific week.</div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-dark">Select Project</label>
                <select name="projects[]" class="form-select" required style="max-width: 300px; border-radius: 10px;">
                    <option value="" disabled selected>Choose a project...</option>
                    <option value="DWF">DWF</option>
                    <option value="Media Tagging">Media Tagging</option>
                    <option value="CRM">CRM</option>
                    <option value="Spoke">Spoke</option>
                </select>
                <div class="form-text mt-2 text-muted">Select the project you want to generate a report for.</div>
            </div>

            <div class="d-flex align-items-center gap-3 pt-3 border-top mt-4">
                <button type="submit" class="apple-btn-primary px-4 py-2 flex-grow-1" onclick="document.getElementById('loadingOverlay').classList.remove('d-none');" style="border-radius: 10px;">
                    <i class="bi bi-robot me-2"></i> Generate AI Report
                </button>
            </div>
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
    // Handle form submission properly for loading overlay
    document.getElementById('aiReportForm').addEventListener('submit', function(e) {
        document.getElementById('loadingOverlay').classList.remove('d-none');
    });
</script>

<?= $this->endSection() ?>

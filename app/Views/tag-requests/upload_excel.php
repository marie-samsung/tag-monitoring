<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Upload Tag Request Form
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <a href="<?= site_url('tag-requests') ?>" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; padding: 0; display: inline-flex;" title="Back to Requests">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="mb-0 fw-bold">Upload Tag Request</h2>
            <p class="text-secondary small mb-0">Select or drop a filled "New Media Tag Request Card" excel template to extract tag details.</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mb-4">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="apple-card" style="background-color: var(--color-bg-base); border: 1px solid rgba(0, 0, 0, 0.05); box-shadow: none;">
        <form action="<?= site_url('tag-requests/parse-excel') ?>" method="post" enctype="multipart/form-data" id="uploadForm">
            <?= csrf_field() ?>
            
            <!-- Drag & Drop Zone -->
            <div class="upload-zone p-5 text-center mb-4 border border-2 border-dashed rounded-3 bg-white" id="uploadZone" style="cursor: pointer; transition: all var(--transition-fast); border-color: rgba(0, 0, 0, 0.15) !important;">
                <div class="upload-icon-wrapper mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background-color: var(--color-badge-primary-bg);">
                    <i class="bi bi-file-earmark-excel text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="mt-2 fw-bold">Upload Tag Request Excel</h5>
                <p class="text-secondary small mb-3">Drag & drop your <strong>.xlsx</strong> file here, or click to browse files</p>
                <input type="file" id="excel_file" name="excel_file" accept=".xlsx" required style="display: none;">
                <button type="button" class="apple-btn-secondary btn-sm px-4" id="browseButton">
                    Choose File
                </button>
                <div class="form-text mt-3 text-muted">Only Excel template files (.xlsx) are accepted.</div>
                
                <!-- Selected File Display -->
                <div class="mt-3 p-2 bg-light border rounded-3 d-inline-flex align-items-center gap-2 d-none" id="fileSelectedDisplay">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <span class="fw-semibold text-dark small" id="fileSelectedName"></span>
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2 text-decoration-none fw-bold" id="btnRemoveFile" style="font-size: 1.1rem; line-height: 1;" title="Remove file">&times;</button>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= site_url('tag-requests') ?>" class="apple-btn-secondary btn-sm px-4 text-decoration-none d-flex align-items-center justify-content-center">Cancel</a>
                <button type="submit" class="apple-btn-primary btn-sm px-4">Upload and Extract</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const uploadZone = document.getElementById('uploadZone');
    const browseButton = document.getElementById('browseButton');
    const excelFileInput = document.getElementById('excel_file');
    const fileSelectedDisplay = document.getElementById('fileSelectedDisplay');
    const fileSelectedName = document.getElementById('fileSelectedName');
    const btnRemoveFile = document.getElementById('btnRemoveFile');
    const uploadForm = document.getElementById('uploadForm');

    const triggerInputClick = (e) => {
        if (e.target !== btnRemoveFile && !btnRemoveFile.contains(e.target)) {
            excelFileInput.click();
        }
    };

    uploadZone.addEventListener('click', triggerInputClick);
    browseButton.addEventListener('click', (e) => {
        e.stopPropagation();
        excelFileInput.click();
    });

    excelFileInput.addEventListener('change', () => {
        if (excelFileInput.files.length > 0) {
            fileSelectedName.textContent = excelFileInput.files[0].name;
            fileSelectedDisplay.classList.remove('d-none');
            browseButton.classList.add('d-none');
        } else {
            fileSelectedName.textContent = '';
            fileSelectedDisplay.classList.add('d-none');
            browseButton.classList.remove('d-none');
        }
    });

    btnRemoveFile.addEventListener('click', (e) => {
        e.stopPropagation();
        excelFileInput.value = '';
        excelFileInput.dispatchEvent(new Event('change'));
    });

    // Drag and drop event listeners
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.style.borderColor = 'var(--color-primary)';
        uploadZone.style.backgroundColor = 'var(--color-badge-primary-bg)';
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadZone.addEventListener(eventName, (e) => {
            uploadZone.style.borderColor = 'rgba(0, 0, 0, 0.15)';
            uploadZone.style.backgroundColor = '#ffffff';
        });
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        if (e.dataTransfer.files.length > 0) {
            const file = e.dataTransfer.files[0];
            if (file.name.endsWith('.xlsx')) {
                excelFileInput.files = e.dataTransfer.files;
                excelFileInput.dispatchEvent(new Event('change'));
            } else {
                alert('Please upload a valid Excel file (.xlsx)');
            }
        }
    });
});
</script>
<?= $this->endSection() ?>

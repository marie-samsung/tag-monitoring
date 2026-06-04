<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $log['title'] ?> : Details
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    use App\Models\Constants;
    helper('custom_functions');

    $subsidiary = Constants::ACTIVITY_LOG_SUBSIDIARIES[$log['subsidiary_id']] ?? null;
    $status = Constants::ACTIVITY_LOGS_STATUS[$log['status']] ?? 'Unknown';
    $type = Constants::ACTIVITY_TYPES[$log['type']] ?? 'Unknown';
    $project = Constants::PROJECTS[$log['project_id']] ?? 'Unknown';
    $assignee = Constants::USERS[$log['assignee']] ?? Constants::USER_SYSTEM_LABEL;
    $size = Constants::ACTIVITY_SIZES[$log['size']] ?? 'Unknown';
    $priority = Constants::PRIORITIES[$log['priority']] ?? 'Unknown';
    $isAdmin = (session()->get('user_role') === Constants::USER_ROLE_ADMIN);
?>

<div class="container-fluid mb-4 pb-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h3 class="mb-1 fw-bold"><?= esc($log['title']) ?></h3>
        <div class="d-flex gap-2 justify-content-start align-items-center flex-wrap mt-2">
            <span class="badge" style="<?= random_bg_style($subsidiary) ?>" title="Subsidiary"><?= $subsidiary ?></span>
            <span class="badge" style="<?= random_bg_style($project) ?>" title="Project"><?= $project ?></span>
            <span class="badge" style="<?= random_bg_style($type) ?>" title="Activity Log Type"><?= $type ?></span>
            <?php if (!$isAdmin): ?>
            <span class="badge <?= assignee_class($log['assignee']) ?>" title="Assigned To"><?= $assignee ?></span>
            <?php endif; ?>
            <span class="badge <?= size_class($log['size']) ?>" title="Size"><?= $size ?></span>
            <span class="badge <?= priority_class($log['priority']) ?>" title="Priority"><?= $priority ?></span>
            <span class="badge <?= status_activity_logs_class($log['status']) ?>" title="Status"><?= $status ?></span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('activity-logs') ?>" class="apple-btn-secondary btn-sm px-3">Back</a>
        <a href="<?= site_url('activity-logs/update/' . $log['id']) ?>" class="apple-btn-primary btn-sm px-3">Edit</a>
    </div>
</div>

<div class="row my-3 g-3">
    <!-- LOG DETAILS & ATTACHMENTS -->
    <div class="<?= !$isAdmin ? 'col-lg-6 col-md-12' : 'col-lg-8 col-md-12 mx-auto' ?>">
        <div class="apple-card p-4 h-100">
            <h5 class="fw-bold mb-3 text-secondary" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Log Details</h5>
            
            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Title</span>
                <div class="fw-semibold mt-1" style="font-size: 0.95rem; color: var(--color-text-base);">
                    <?= esc($log['title'] ?: '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Description</span>
                <div class="mt-1" style="font-size: 0.9rem; line-height: 1.5; color: var(--color-text-base);">
                    <?= $log['description'] ?: '—' ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Ticket ID</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?php if (!empty($log['ticket_id'])) : ?>
                        <a href="<?= Constants::JIRA_LINK . $log['ticket_id'] ?>" target="_blank" class="apple-link">
                            <?= esc($log['ticket_id']) ?>
                        </a>
                    <?php else: ?>
                        <span class="text-muted">—</span>
                    <?php endif; ?>
                </div>
            </div>

            <h5 class="fw-bold mt-4 mb-3 text-secondary" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Request Details</h5>
            
            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Requested By</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['requested_by'] ?: '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Requested Date</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['requested_date'] ?: '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Finished Date</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['finished_date'] ?: '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Created At</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['created_at'] ?: '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Updated At</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['updated_at'] ?: '—') ?>
                </div>
            </div>

            <h5 class="fw-bold mt-4 mb-3 text-secondary" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Campaign Details</h5>
            
            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Department</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc(Constants::DWF_DEPARTMENTS[$log['department_id']] ?? '—') ?>
                </div>
            </div>
            
            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Campaign Status</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc(Constants::CAMPAIGN_STATUS[$log['campaign_status']] ?? '—') ?>
                </div>
            </div>

            <div class="mb-3 pb-2 border-bottom border-light">
                <span class="d-block text-muted fw-medium" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Campaign ID</span>
                <div class="mt-1" style="font-size: 0.9rem; color: var(--color-text-base);">
                    <?= esc($log['campaign_id'] ?: '—') ?>
                </div>
            </div>

            <!-- ATTACHMENTS -->
            <div class="mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-secondary" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Attachment(s)</h5>
                    <i class="bi bi-info-circle-fill text-primary" title="Clicking the name will download the file" style="cursor: help;"></i>
                </div>

                <div class="mb-3">
                    <?php if (!empty($files)) : ?>
                        <div class="apple-table-container">
                            <table class="table table-hover align-middle mb-0 apple-table">
                                <tbody>
                                    <?php foreach ($files as $file) : ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-file-earmark-arrow-down-fill text-primary fs-5"></i>
                                                    <div class="text-truncate" style="max-width: 400px;">
                                                        <a href="<?= base_url('download.php?file=' . rawurlencode($file['file_path'])) ?>" download="<?= $file['file_name'] ?>" rel="noopener" class="apple-link-dark fw-medium text-decoration-none">
                                                            <?= esc($file['file_name']) ?>
                                                        </a>
                                                        <span class="text-muted ms-2" style="font-size: 0.75rem;">(<?= esc($file['created_at']) ?>)</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info py-2 px-3 m-0" style="font-size: 0.85rem;">No files attached to this log.</div>
                    <?php endif; ?>
                </div>

                <form action="<?= site_url('activity-logs/upload') ?>" method="post" enctype="multipart/form-data" class="mt-3 pt-3 border-top">
                    <input type="hidden" name="log_id" value="<?= esc($log['id']) ?>">
                    <input type="hidden" name="created_by" value="<?= $isAdmin ? session()->get('user_id') : esc($log['assignee']) ?>">
                    <div class="d-flex flex-column gap-2">
                        <label for="file_path" class="form-label-xs text-primary fw-medium">Upload Attachment</label>
                        <div class="d-flex gap-2 align-items-center justify-content-between">
                            <input type="file" name="file_path" class="form-control form-control-xs" required style="max-width: 70%;">
                            <button type="submit" class="apple-btn-primary btn-sm px-3" onclick="return confirm('Confirm uploading of this file?');">
                                Upload
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                <a href="<?= site_url('activity-logs') ?>" class="apple-btn-secondary btn-sm px-3">Back</a>
                <a href="<?= site_url('activity-logs/update/' . $log['id']) ?>" class="apple-btn-primary btn-sm px-3">Edit</a>
            </div>
        </div>
    </div>

    <!-- COMMENTS -->
    <?php if (!$isAdmin): ?>
    <div class="col-lg-6 col-md-12">
        <div class="apple-card p-4 h-100">
            <h5 class="fw-bold mb-3 text-secondary" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Updates</h5>

            <div id="comments-container" class="overflow-auto pe-1 mb-3" style="max-height: 700px;">
                <?php if (!empty($comments)) :
                     foreach ($comments as $comment) : ?>
                        <?php if ($comment['commented_by'] != Constants::USER_SYSTEM) : ?>
                            <div class="mb-3 p-3 rounded-3 comment-item" style="background: #F5F5F7; border: 1px solid rgba(0, 0, 0, 0.03); display: block;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold text-primary" style="font-size: 0.8rem;">
                                        <?= esc(Constants::USERS[$comment['commented_by']] ?? 'Unknown') ?>
                                    </span>
                                    <span class="text-muted" style="font-size: 0.75rem;"><?= esc($comment['created_at']) ?></span>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--color-text-base);"><?= $comment['comment'] ?></div>
                            </div>
                        <?php else: ?>
                            <div class="mb-3 p-3 rounded-3 comment-item" style="background: var(--color-alert-info-bg); border: 1px solid rgba(0, 113, 227, 0.08); display: block;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold text-info" style="font-size: 0.8rem;">
                                        <?= esc(Constants::USER_SYSTEM_LABEL) ?>
                                    </span>
                                    <span class="text-muted" style="font-size: 0.75rem;"><?= esc($comment['created_at']) ?></span>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--color-text-base);"><?= $comment['comment'] ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div id="no-comments-alert" class="alert alert-info py-2 px-3">No update(s) for this log yet.</div>
                <?php endif; ?>
            </div>

            <form id="comment-form" action="<?= site_url('activity-logs/comment') ?>" method="post" class="mt-3 pt-3 border-top">
                <?= csrf_field() ?>
                <div>
                    <label for="comment" class="form-label-xs text-primary fw-medium mb-2">Post an Update</label>
                    <textarea name="comment" id="comment"
                               class="form-control"
                               rows="4"><?= old('comment', $log['comment'] ?? '') ?></textarea>
                </div>
                <div class="d-flex gap-2 mt-2 align-items-center">
                    <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
                    <input type="hidden" name="commented_by" value="<?= session()->get('user_id') ?>">
                    <button type="submit" class="apple-btn-primary btn-sm px-3">Post</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $(document).ready(function() {
            $('#comment').summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            $('#comment-form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                
                // Ensure summernote is not empty
                if ($('#comment').summernote('isEmpty')) {
                    alert('Please write an update before posting.');
                    return;
                }
                
                var formData = form.serialize();
                
                // Disable button to prevent double post
                var submitBtn = form.find('button[type="submit"]');
                submitBtn.prop('disabled', true).text('Posting...');
                
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        submitBtn.prop('disabled', false).text('Post');
                        
                        if (response.success) {
                            // Reset Summernote editor
                            $('#comment').summernote('reset');
                            
                            // Hide empty states
                            $('#no-comments-alert').hide();
                            
                            // Build animated comment block
                            var normalBg = response.comment.is_system 
                                ? 'var(--color-alert-info-bg)'
                                : '#F5F5F7';
                            var normalBorder = response.comment.is_system
                                ? 'rgba(0, 113, 227, 0.08)'
                                : 'rgba(0, 0, 0, 0.03)';
                                
                            var highlightBg = '#FFF9E6'; // Soft amber/yellow glow
                            var highlightBorder = '#FFE28A'; // Soft yellow border
                            var textClass = response.comment.is_system ? 'text-info' : 'text-primary';
                            
                            // Start with height, padding, margin, border at 0 and transition all of them for a smooth push down effect
                            var commentHtml = `
                                <div class="mb-3 p-3 rounded-3 comment-item" 
                                     style="background-color: ${highlightBg}; border: 1px solid transparent; opacity: 0; max-height: 0; padding-top: 0; padding-bottom: 0; margin-top: 0; margin-bottom: 0; overflow: hidden; transform: translateY(-15px); transition: max-height 0.5s cubic-bezier(0.16, 1, 0.3, 1), padding 0.5s cubic-bezier(0.16, 1, 0.3, 1), margin 0.5s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.5s cubic-bezier(0.16, 1, 0.3, 1);">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold ${textClass}" style="font-size: 0.8rem;">
                                            ${response.comment.user_label}
                                        </span>
                                        <span class="text-muted" style="font-size: 0.75rem;">Just now</span>
                                    </div>
                                    <div style="font-size: 0.875rem; color: var(--color-text-base);">${response.comment.comment}</div>
                                </div>
                            `;
                            
                            var $newComment = $(commentHtml);
                            $('#comments-container').prepend($newComment);
                            
                            // 1. Smoothly scroll browser window to the updates card (faster 250ms)
                            var cardTop = $('#comments-container').closest('.apple-card').offset().top;
                            $('html, body').animate({
                                scrollTop: cardTop - 100 // offset for sticky nav bar
                            }, 250);
                            
                            // 2. Smoothly scroll internal comments container to top (faster 250ms)
                            $('#comments-container').animate({ scrollTop: 0 }, 250);
                            
                            // 3. Trigger the entry CSS transition with a delay of 380ms (so scroll completes first)
                            setTimeout(function() {
                                $newComment.css({
                                    'max-height': '500px', // large value to hold comment text
                                    'padding-top': '16px', // equivalent to p-3 (1rem)
                                    'padding-bottom': '16px',
                                    'margin-bottom': '16px', // equivalent to mb-3 (1rem)
                                    'opacity': '1',
                                    'border-color': highlightBorder,
                                    'transform': 'translateY(0)'
                                });
                                
                                // After transition completes, clean up constraints so content isn't clipped
                                setTimeout(function() {
                                    $newComment.css({
                                        'max-height': 'none',
                                        'overflow': 'visible'
                                    });
                                }, 500);
                                
                                // 4. Smoothly fade out the yellow highlight back to normal after 3 seconds
                                setTimeout(function() {
                                    $newComment.css({
                                        'transition': 'background-color 3s ease, border-color 3s ease',
                                        'background-color': normalBg,
                                        'border-color': normalBorder
                                    });
                                }, 3000);
                            }, 380);
                        } else {
                            alert(response.message || 'An error occurred.');
                        }
                        
                        // Always update CSRF token
                        if (response.csrf_hash) {
                            $('input[name="csrf_test_name"]').val(response.csrf_hash);
                        }
                    },
                    error: function(xhr, status, error) {
                        submitBtn.prop('disabled', false).text('Post');
                        console.error('AJAX Error:', error);
                        alert('Failed to post update. Please try again.');
                    }
                });
            });
        });
    });
</script>

<?= $this->endSection() ?>

<?php use App\Models\Constants;

$segment = service('uri')->getSegment(1);
$permissions = session()->get('permissions') ?? [];
?>

<!-- <nav class="nav flex-column py-2">
    <a class="nav-link d-flex align-items-center gap-2 <?= $segment === 'tags' ? 'active' : '' ?>"
       href="<?= site_url('/tags') ?>">
        <i class="bi bi-tags"></i> Tags
    </a>

    <a class="nav-link d-flex align-items-center gap-2 <?= $segment === 'properties' ? 'active' : '' ?>"
       href="<?= site_url('/properties') ?>">
        <i class="bi bi-building"></i> Properties
    </a>

    <a class="nav-link d-flex align-items-center gap-2 <?= $segment === 'vendors' ? 'active' : '' ?>"
       href="<?= site_url('/vendors') ?>">
        <i class="bi bi-truck"></i> Vendors
    </a>
</nav> -->


<div class="d-flex justify-content-between align-items-center w-100 px-4 gap-4 py-2">
    <!-- Branding logo (aligned to the left) -->
    <div class="d-flex align-items-center gap-2" style="flex: 1; min-width: fit-content;">
        <a href="<?= site_url('/') ?>" class="fw-bold text-decoration-none apple-link-dark d-flex align-items-center gap-2" style="font-size: 1.1rem; letter-spacing: -0.5px;">
            <i class="bi bi-shield-lock-fill text-primary"></i>
            <span>Martech Management System</span>
        </a>
    </div>

    <!-- Main Navigation pills (centered) -->
    <ul class="nav nav-pills justify-content-center apple-segmented-control flex-nowrap" style="white-space: nowrap; overflow-x: auto;" id="appleNav">
        <li class="sliding-glass-pill" id="navSlidingPill"></li>
        
        <?php if ($permissions['dashboard'] ?? false): ?>
        <li class="nav-item">
            <a class="nav-link <?= $segment === 'dashboard' ? 'active' : '' ?>" href="<?= site_url('/dashboard') ?>">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
        </li>
        <?php endif; ?>

        <?php if ($permissions['tags'] ?? false): ?>
        <li class="nav-item">
            <a class="nav-link <?= $segment === 'tags' ? 'active' : '' ?>" href="<?= site_url('/tags') ?>">
                <i class="bi bi-tags-fill"></i> Tags
            </a>
        </li>
        <?php endif; ?>

        <?php if ($permissions['tag-requests'] ?? false): ?>
        <li class="nav-item">
            <a class="nav-link <?= $segment === 'tag-requests' ? 'active' : '' ?>" href="<?= site_url('/tag-requests') ?>">
                <i class="bi bi-file-earmark-plus-fill"></i> Tag Requests
            </a>
        </li>
        <?php endif; ?>

        <?php if ($permissions['properties'] ?? false): ?>
        <li class="nav-item">
            <a class="nav-link <?= $segment === 'properties' ? 'active' : '' ?>" href="<?= site_url('/properties') ?>">
                <i class="bi bi-building-fill"></i> Properties
            </a>
        </li>
        <?php endif; ?>

        <?php if ($permissions['vendors'] ?? false): ?>
        <li class="nav-item">
            <a class="nav-link <?= $segment === 'vendors' ? 'active' : '' ?>" href="<?= site_url('/vendors') ?>">
                <i class="bi bi-truck-front-fill"></i> Vendors
            </a>
        </li>
        <?php endif; ?>

        <?php if ($permissions['jira'] ?? false): ?>
        <li class="nav-item">
            <a class="nav-link <?= $segment === 'jira' ? 'active' : '' ?>" href="<?= site_url('/jira') ?>">
                <i class="bi bi-ticket-perforated-fill"></i> Jira Tickets
            </a>
        </li>
        <?php endif; ?>

        <?php if ($permissions['activity-logs'] ?? false): ?>
        <li class="nav-item">
            <a class="nav-link <?= $segment === 'activity-logs' ? 'active' : '' ?>" href="<?= site_url('/activity-logs') ?>">
                <i class="bi bi-card-list"></i> Activity Logs
            </a>
        </li>
        <?php endif; ?>
    </ul>

    <!-- Logged in User Profile Info & Logout Button (aligned to the right) -->
    <div class="d-flex align-items-center justify-content-end gap-3" style="flex: 1; min-width: fit-content;">
        <?php if (session()->get('isLoggedIn')): ?>
            <span class="text-secondary small d-none d-md-inline-block">
                <i class="bi bi-person-circle text-primary"></i> 
                <strong><?= esc(session()->get('user_name')) ?></strong>
            </span>
            <a href="<?= site_url('logout') ?>" class="apple-btn-secondary btn-sm text-danger d-flex align-items-center gap-1" style="padding: 4px 12px; font-size: 0.8rem; border-radius: 8px;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const nav = document.getElementById('appleNav');
    const pill = document.getElementById('navSlidingPill');
    if (!nav || !pill) return;
    
    const links = nav.querySelectorAll('.nav-link');
    
    // Find the currently active link on page load
    let activeLink = nav.querySelector('.nav-link.active');
    let hasMovedOnce = false;

    function movePill(target, instant = false) {
        if (!target) return;
        const targetRect = target.getBoundingClientRect();
        const navRect = nav.getBoundingClientRect();
        
        // Calculate relative position inside the ul
        const offsetLeft = targetRect.left - navRect.left;
        
        if (instant) {
            pill.style.transition = 'none';
        }
        
        pill.style.opacity = '1';
        pill.style.transform = `translateX(${offsetLeft}px)`;
        pill.style.width = `${targetRect.width}px`;

        if (instant) {
            pill.offsetHeight; // Force reflow
            pill.style.transition = '';
        }
        
        hasMovedOnce = true;
    }

    // Initialize pill position instantly without animation
    if (activeLink) {
        // Need a slight delay to ensure fonts/layout are fully rendered
        setTimeout(() => movePill(activeLink, true), 150);
    }

    let leaveTimeout;
    let enterTimeout;

    links.forEach(link => {
        link.addEventListener('mouseenter', (e) => {
            clearTimeout(leaveTimeout);
            clearTimeout(enterTimeout);
            const target = e.currentTarget;
            enterTimeout = setTimeout(() => {
                // If the pill was completely hidden (e.g., no active link), instantly snap it to the hovered item first
                movePill(target, !hasMovedOnce);
            }, 75); // 75ms delay prevents jitter if scrubbing across links quickly
        });

        link.addEventListener('mouseleave', () => {
            clearTimeout(enterTimeout);
        });
    });

    nav.addEventListener('mouseleave', () => {
        leaveTimeout = setTimeout(() => {
            if (activeLink) {
                movePill(activeLink);
            } else {
                pill.style.opacity = '0';
                hasMovedOnce = false; // Reset so next hover snaps instantly
            }
        }, 400); // 400ms delay feels patient and premium
    });
});
</script>

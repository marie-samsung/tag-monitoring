<?php use App\Models\Constants;

$segment = service('uri')->getSegment(1);
$permissions = session()->get('permissions') ?? [];
$uri = service('uri');

// Build the array of all permitted menu items in order
$menuItems = [];

// 1. Dashboard
if ($permissions['dashboard'] ?? false) {
    $menuItems[] = [
        'type' => 'link',
        'id' => 'navDashboard',
        'label' => 'Dashboard',
        'icon' => 'bi bi-grid-1x2-fill',
        'dropdownIcon' => 'bi bi-grid-1x2-fill text-secondary',
        'url' => site_url('/dashboard'),
        'active' => ($segment === 'dashboard'),
    ];
}

// 2. Tags Dropdown (Inventory, Requests, Properties)
$hasTagsPermissions = ($permissions['tags'] ?? false) || ($permissions['tag-requests'] ?? false) || ($permissions['properties'] ?? false);
if ($hasTagsPermissions) {
    $subItems = [];
    if ($permissions['tags'] ?? false) {
        $subItems[] = [
            'label' => 'Inventory',
            'icon' => 'bi bi-tags-fill text-primary',
            'url' => site_url('/tags'),
            'active' => ($segment === 'tags' && ($uri->getTotalSegments() < 2 || ($uri->getSegment(2) !== 'renewal-justifications' && $uri->getSegment(2) !== 'upload-renewal-justifications'))),
        ];
        $subItems[] = [
            'label' => 'Renewal Justifications',
            'icon' => 'bi bi-file-earmark-check-fill text-warning',
            'url' => site_url('/tags/renewal-justifications'),
            'active' => ($segment === 'tags' && $uri->getTotalSegments() >= 2 && ($uri->getSegment(2) === 'renewal-justifications' || $uri->getSegment(2) === 'upload-renewal-justifications')),
        ];
    }
    if ($permissions['tag-requests'] ?? false) {
        $subItems[] = [
            'label' => 'Requests',
            'icon' => 'bi bi-file-earmark-plus-fill text-success',
            'url' => site_url('/tag-requests'),
            'active' => ($segment === 'tag-requests'),
        ];
    }
    if ($permissions['properties'] ?? false) {
        $subItems[] = [
            'label' => 'Properties',
            'icon' => 'bi bi-building-fill text-info',
            'url' => site_url('/properties'),
            'active' => ($segment === 'properties'),
        ];
    }
    
    $menuItems[] = [
        'type' => 'dropdown',
        'id' => 'navbarTags',
        'label' => 'Tags',
        'icon' => 'bi bi-tags-fill',
        'active' => ($segment === 'tags' || $segment === 'tag-requests' || $segment === 'properties'),
        'subItems' => $subItems,
    ];
}

// 3. Jira Tickets
if ($permissions['jira'] ?? false) {
    $menuItems[] = [
        'type' => 'link',
        'id' => 'navJira',
        'label' => 'Jira Tickets',
        'icon' => 'bi bi-ticket-perforated-fill',
        'dropdownIcon' => 'bi bi-ticket-perforated-fill text-primary',
        'url' => site_url('/jira'),
        'active' => ($segment === 'jira'),
    ];
}

// 4. Vendors (placed just before Workspace dropdown)
if ($permissions['vendors'] ?? false) {
    $menuItems[] = [
        'type' => 'link',
        'id' => 'navVendors',
        'label' => 'Vendors',
        'icon' => 'bi bi-truck',
        'dropdownIcon' => 'bi bi-truck-front-fill text-success',
        'url' => site_url('/vendors'),
        'active' => ($segment === 'vendors'),
    ];
}

// 5. Workspace Dropdown (Tasks, Projects)
if ($permissions['tasks'] ?? false) {
    $uri = service('uri');
    $isProjectsActive = ($segment === 'tasks' && $uri->getTotalSegments() >= 2 && $uri->getSegment(2) === 'project');
    $isTasksActive = ($segment === 'tasks' && !$isProjectsActive);
    
    $subItems = [
        [
            'label' => 'Tasks',
            'icon' => 'bi bi-card-checklist text-primary',
            'url' => site_url('/tasks'),
            'active' => $isTasksActive,
        ],
        [
            'label' => 'Projects',
            'icon' => 'bi bi-folder-fill text-success',
            'url' => site_url('/tasks/project/Generic'),
            'active' => $isProjectsActive,
        ]
    ];
    
    $menuItems[] = [
        'type' => 'dropdown',
        'id' => 'navbarTasks',
        'label' => 'Workspace',
        'icon' => 'bi bi-card-list',
        'active' => ($segment === 'tasks'),
        'subItems' => $subItems,
    ];
}

// Partition items based on max limit of 5
$maxVisible = 5;
$visibleItems = [];
$overflowItems = [];

if (count($menuItems) <= $maxVisible) {
    $visibleItems = $menuItems;
} else {
    // Leave room for the 3-dots dropdown as the 5th item
    $visibleItems = array_slice($menuItems, 0, $maxVisible - 1);
    $overflowItems = array_slice($menuItems, $maxVisible - 1);
}

// Check if any overflow item is active to highlight the 3-dots dropdown
$isOverflowActive = false;
foreach ($overflowItems as $item) {
    if ($item['active']) {
        $isOverflowActive = true;
        break;
    }
}
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


<style>
    /* Responsive Navigation Header styling */
    @media (max-width: 991px) {
        .apple-nav-wrapper {
            flex-direction: column;
            gap: 16px !important;
            padding: 16px !important;
            text-align: center;
        }
        .apple-nav-wrapper > div {
            justify-content: center !important;
        }
        .apple-segmented-control {
            flex-wrap: wrap !important;
            justify-content: center !important;
            width: 100% !important;
        }
        .apple-segmented-control .nav-item {
            flex: 1 1 auto;
            text-align: center;
        }
        .sliding-glass-pill {
            display: none !important; /* Hide sliding pill on wrap to avoid glitches */
        }
        .apple-segmented-control .nav-link.active {
            background-color: var(--color-primary) !important;
            color: #fff !important;
            border-radius: 8px;
        }
        .user-profile-section {
            width: 100%;
            justify-content: center !important;
        }
    }
    
    .apple-profile-link {
        background-color: var(--color-secondary-bg) !important;
        color: var(--color-text-base) !important;
        transition: all 0.2s ease;
        padding: 0 12px 0 0 !important;
    }
    
    .apple-profile-link:hover {
        background-color: var(--color-secondary-hover) !important;
        color: var(--color-primary) !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center w-100 px-4 gap-4 py-2 apple-nav-wrapper">
    <!-- Branding logo (aligned to the left) -->
    <div class="d-flex align-items-center gap-2" style="flex: 1; min-width: fit-content;">
        <a href="<?= site_url('/') ?>" class="fw-bold text-decoration-none apple-link-dark d-flex align-items-center gap-2" style="font-size: 1.1rem; letter-spacing: -0.5px;">
            <i class="bi bi-shield-lock-fill text-primary"></i>
            <span>Martech TMS</span>
        </a>
    </div>

    <!-- Main Navigation pills (centered) -->
    <ul class="nav nav-pills justify-content-center apple-segmented-control flex-nowrap" style="white-space: nowrap; overflow: visible;" id="appleNav">
        <li class="sliding-glass-pill" id="navSlidingPill"></li>
        
        <?php foreach ($visibleItems as $item): ?>
            <?php if ($item['type'] === 'link'): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $item['active'] ? 'active' : '' ?>" href="<?= $item['url'] ?>" id="<?= $item['id'] ?>">
                        <i class="<?= $item['icon'] ?>"></i> <?= esc($item['label']) ?>
                    </a>
                </li>
            <?php elseif ($item['type'] === 'dropdown'): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $item['active'] ? 'active' : '' ?>" href="<?= !empty($item['subItems']) ? $item['subItems'][0]['url'] : '#' ?>" id="<?= $item['id'] ?>" role="button" aria-expanded="false">
                        <i class="<?= $item['icon'] ?>"></i> <?= esc($item['label']) ?> <i class="bi bi-chevron-down" style="font-size: 0.7rem;"></i>
                    </a>
                    <ul class="dropdown-menu shadow" aria-labelledby="<?= $item['id'] ?>" style="border-radius: 12px; border: 1px solid var(--color-border-input); padding: 6px 0;">
                        <?php foreach ($item['subItems'] as $sub): ?>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2 <?= $sub['active'] ? 'active' : '' ?>" href="<?= $sub['url'] ?>" style="font-size: 0.85rem;">
                                    <i class="<?= $sub['icon'] ?>"></i> <?= esc($sub['label']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if (!empty($overflowItems)): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle <?= $isOverflowActive ? 'active' : '' ?>" href="#" id="navbarMore" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="More Options">
                    <i class="bi bi-three-dots" style="font-size: 1.1rem; line-height: 1;"></i>
                </a>
                <ul class="dropdown-menu shadow dropdown-menu-end" aria-labelledby="navbarMore" style="border-radius: 12px; border: 1px solid var(--color-border-input); padding: 6px 0; min-width: 160px;">
                    <?php foreach ($overflowItems as $item): ?>
                        <?php if ($item['type'] === 'link'): ?>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2 <?= $item['active'] ? 'active' : '' ?>" href="<?= $item['url'] ?>" style="font-size: 0.85rem;">
                                    <i class="<?= $item['dropdownIcon'] ?? $item['icon'] ?>"></i> <?= esc($item['label']) ?>
                                </a>
                            </li>
                        <?php elseif ($item['type'] === 'dropdown'): ?>
                            <li class="dropdown-header text-uppercase px-3 pt-2 pb-1 text-muted" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: 1px solid rgba(0,0,0,0.03);">
                                <?= esc($item['label']) ?>
                            </li>
                            <?php foreach ($item['subItems'] as $sub): ?>
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-2 <?= $sub['active'] ? 'active' : '' ?>" href="<?= $sub['url'] ?>" style="font-size: 0.85rem; padding-left: 24px;">
                                        <i class="<?= $sub['icon'] ?>"></i> <?= esc($sub['label']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endif; ?>
    </ul>

    <div class="d-flex align-items-center justify-content-end gap-3" style="flex: 1; min-width: fit-content;">
        <?php if (session()->get('isLoggedIn')): ?>
            <?php $hasPhoto = !empty(session()->get('profile_photo')); ?>
            <a href="<?= site_url('profile') ?>" class="apple-btn-secondary btn-sm d-flex align-items-center gap-2 text-decoration-none apple-profile-link <?= $hasPhoto ? 'has-photo' : '' ?>" title="My Profile" data-bs-toggle="tooltip" data-bs-placement="bottom" style="height: 32px; font-size: 0.82rem; overflow: hidden;">
                <?php if ($hasPhoto): ?>
                    <img src="<?= base_url('uploads/profile_photos/' . esc(session()->get('profile_photo'))) ?>" alt="Avatar" style="width: 32px; height: 32px; object-fit: cover; border-radius: 0;">
                <?php else: ?>
                    <i class="bi bi-person-circle text-primary" style="font-size: 1.05rem; line-height: 1;"></i> 
                <?php endif; ?>
                <strong><?= esc(session()->get('user_name')) ?></strong>
            </a>
            <?php if (session()->get('permissions')['settings'] ?? false): ?>
                <a href="<?= site_url('settings') ?>" class="apple-btn-secondary btn-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0; <?= $segment === 'settings' ? 'border: 1px solid var(--color-primary); color: var(--color-primary); background: var(--color-primary-subtle);' : 'color: var(--color-text-secondary);' ?>" title="System Settings" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    <i class="bi bi-gear-fill" style="font-size: 1rem;"></i>
                </a>
            <?php endif; ?>
            <a href="<?= site_url('logout') ?>" class="apple-btn-secondary btn-sm text-danger d-flex align-items-center gap-1.5" style="height: 32px; padding: 0 12px; font-size: 0.82rem;">
                <i class="bi bi-box-arrow-right" style="font-size: 1rem; line-height: 1;"></i> <strong>Logout</strong>
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

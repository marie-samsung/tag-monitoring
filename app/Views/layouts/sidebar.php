<?php use App\Models\Constants;

$segment = service('uri')->getSegment(1);
$permissions = session()->get('permissions') ?? [];
$uri = service('uri');

// Build the array of all permitted menu items in order
$menuItems = [];

// 1. Dashboard & Reports Dropdown
$hasReportsPermission = $permissions['reports'] ?? false;
if ($permissions['dashboard'] ?? false) {
    if ($hasReportsPermission) {
        $menuItems[] = [
            'type' => 'dropdown',
            'id' => 'navbarDashboard',
            'label' => 'Dashboard',
            'icon' => 'bi bi-grid-1x2-fill',
            'active' => ($segment === 'dashboard' || $segment === 'reports'),
            'subItems' => [
                [
                    'label' => 'Overview',
                    'icon' => 'bi bi-grid-1x2-fill text-primary',
                    'url' => site_url('/dashboard'),
                    'active' => ($segment === 'dashboard'),
                ],
                [
                    'label' => 'Weekly Reports',
                    'icon' => 'bi bi-file-earmark-bar-graph-fill text-info',
                    'url' => site_url('/reports'),
                    'active' => ($segment === 'reports'),
                ]
            ],
        ];
    } else {
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
} elseif ($hasReportsPermission) {
    $menuItems[] = [
        'type' => 'link',
        'id' => 'navReports',
        'label' => 'Weekly Reports',
        'icon' => 'bi bi-file-earmark-bar-graph-fill',
        'dropdownIcon' => 'bi bi-file-earmark-bar-graph-fill text-info',
        'url' => site_url('/reports'),
        'active' => ($segment === 'reports'),
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
            'active' => ($segment === 'tags' && ($uri->getTotalSegments() < 2 || ($uri->getSegment(2) !== 'renewal-justifications' && $uri->getSegment(2) !== 'upload-renewal-justifications' && $uri->getSegment(2) !== 'repository'))),
        ];
        $subItems[] = [
            'label' => 'Repository',
            'icon' => 'bi bi-code-square text-secondary',
            'url' => site_url('/tags/repository'),
            'active' => ($segment === 'tags' && $uri->getTotalSegments() >= 2 && $uri->getSegment(2) === 'repository'),
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

        .apple-nav-wrapper>div {
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
            display: none !important;
            /* Hide sliding pill on wrap to avoid glitches */
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
        <a href="<?= site_url('/') ?>"
            class="fw-bold text-decoration-none apple-link-dark d-flex align-items-center gap-2"
            style="font-size: 1.1rem; letter-spacing: -0.5px;">
            <i class="bi bi-shield-lock-fill text-primary"></i>
            <span>Martech TMS</span>
        </a>
    </div>

    <!-- Main Navigation pills (centered) -->
    <ul class="nav nav-pills justify-content-center apple-segmented-control flex-nowrap"
        style="white-space: nowrap; overflow: visible;" id="appleNav">
        <li class="sliding-glass-pill" id="navSlidingPill"></li>

        <?php foreach ($visibleItems as $item): ?>
            <?php if ($item['type'] === 'link'): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $item['active'] ? 'active' : '' ?>" href="<?= $item['url'] ?>"
                        id="<?= $item['id'] ?>">
                        <i class="<?= $item['icon'] ?>"></i> <?= esc($item['label']) ?>
                    </a>
                </li>
            <?php elseif ($item['type'] === 'dropdown'): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $item['active'] ? 'active' : '' ?>"
                        href="<?= !empty($item['subItems']) ? $item['subItems'][0]['url'] : '#' ?>" id="<?= $item['id'] ?>"
                        role="button" aria-expanded="false">
                        <i class="<?= $item['icon'] ?>"></i> <?= esc($item['label']) ?> <i class="bi bi-chevron-down"
                            style="font-size: 0.7rem;"></i>
                    </a>
                    <ul class="dropdown-menu shadow" aria-labelledby="<?= $item['id'] ?>"
                        style="border-radius: 12px; border: 1px solid var(--color-border-input); padding: 6px 0;">
                        <?php foreach ($item['subItems'] as $sub): ?>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2 <?= $sub['active'] ? 'active' : '' ?>"
                                    href="<?= $sub['url'] ?>" style="font-size: 0.85rem;">
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
                <a class="nav-link dropdown-toggle <?= $isOverflowActive ? 'active' : '' ?>" href="#" id="navbarMore"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false" title="More Options">
                    <i class="bi bi-three-dots" style="font-size: 1.1rem; line-height: 1;"></i>
                </a>
                <ul class="dropdown-menu shadow dropdown-menu-end" aria-labelledby="navbarMore"
                    style="border-radius: 12px; border: 1px solid var(--color-border-input); padding: 6px 0; min-width: 160px;">
                    <?php foreach ($overflowItems as $item): ?>
                        <?php if ($item['type'] === 'link'): ?>
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2 <?= $item['active'] ? 'active' : '' ?>"
                                    href="<?= $item['url'] ?>" style="font-size: 0.85rem;">
                                    <i class="<?= $item['dropdownIcon'] ?? $item['icon'] ?>"></i> <?= esc($item['label']) ?>
                                </a>
                            </li>
                        <?php elseif ($item['type'] === 'dropdown'): ?>
                            <li class="dropdown-header text-uppercase px-3 pt-2 pb-1 text-muted"
                                style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: 1px solid rgba(0,0,0,0.03);">
                                <?= esc($item['label']) ?>
                            </li>
                            <?php foreach ($item['subItems'] as $sub): ?>
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-2 <?= $sub['active'] ? 'active' : '' ?>"
                                        href="<?= $sub['url'] ?>" style="font-size: 0.85rem; padding-left: 24px;">
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
            <a href="<?= site_url('profile') ?>"
                class="apple-btn-secondary btn-sm d-flex align-items-center gap-2 text-decoration-none apple-profile-link <?= $hasPhoto ? 'has-photo' : '' ?>"
                title="My Profile" data-bs-toggle="tooltip" data-bs-placement="bottom"
                style="height: 32px; font-size: 0.82rem; overflow: hidden;">
                <?php if ($hasPhoto): ?>
                    <img src="<?= base_url('uploads/profile_photos/' . esc(session()->get('profile_photo'))) ?>" alt="Avatar"
                        style="width: 32px; height: 32px; object-fit: cover; border-radius: 0;">
                <?php else: ?>
                    <i class="bi bi-person-circle text-primary" style="font-size: 1.05rem; line-height: 1;"></i>
                <?php endif; ?>
                <strong><?= esc(session()->get('user_name')) ?></strong>
            </a>
            <?php if (session()->get('permissions')['settings'] ?? false): ?>
                <a href="<?= site_url('settings') ?>"
                    class="apple-btn-secondary btn-sm d-flex align-items-center justify-content-center"
                    style="width: 32px; height: 32px; padding: 0; <?= $segment === 'settings' ? 'border: 1px solid var(--color-primary); color: var(--color-primary); background: var(--color-primary-subtle);' : 'color: var(--color-text-secondary);' ?>"
                    title="System Settings" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    <i class="bi bi-gear-fill" style="font-size: 1rem;"></i>
                </a>
            <?php endif; ?>
            <!-- Notifications Dropdown -->
            <div class="dropdown">
                <a href="#"
                    class="apple-btn-secondary btn-sm d-flex align-items-center justify-content-center position-relative"
                    id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                    style="width: 32px; height: 32px; padding: 0; color: var(--color-text-secondary);"
                    title="Notifications">
                    <i class="bi bi-bell-fill" style="font-size: 1rem;"></i>
                    <span id="notificationBadge"
                        class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"
                        style="display: none;">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationsDropdown"
                    style="width: 320px; max-height: 400px; overflow-y: auto; border-radius: 12px; border: 1px solid var(--color-border-input); padding: 0;">
                    <li class="dropdown-header text-uppercase px-3 pt-3 pb-2 text-muted d-flex justify-content-between align-items-center"
                        style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; border-bottom: 1px solid rgba(0,0,0,0.05); background: var(--color-secondary-bg); border-top-left-radius: 12px; border-top-right-radius: 12px;">
                        <span>Notifications</span>
                        <div>
                            <button class="btn btn-sm btn-link p-0 text-muted" id="markAllReadBtn" title="Mark all as read" style="font-size: 0.85rem; display: none;"><i class="bi bi-envelope-open-fill"></i></button>
                            <button class="btn btn-sm btn-link p-0 text-muted" id="markAllUnreadBtn" title="Mark all as unread" style="font-size: 0.85rem; display: none;"><i class="bi bi-envelope-fill"></i></button>
                        </div>
                    </li>
                    <div id="notificationsList">
                        <li><span class="dropdown-item py-3 text-center text-muted"
                                style="font-size: 0.85rem;">Loading...</span></li>
                    </div>
                </ul>
            </div>
            <a href="<?= site_url('logout') ?>"
                class="apple-btn-secondary btn-sm text-danger d-flex align-items-center gap-1.5"
                style="height: 32px; padding: 0 12px; font-size: 0.82rem;">
                <i class="bi bi-box-arrow-right" style="font-size: 1rem; line-height: 1;"></i> <strong>Logout</strong>
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Global Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
    <div id="globalToastNotification" class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" style="display: none; min-width: 280px; border-radius: 12px; backdrop-filter: blur(10px); box-shadow: 0 8px 32px rgba(0,0,0,0.15); transition: opacity 0.3s ease;">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-start gap-2">
                <i class="bi bi-bell-fill fs-5 mt-1" id="globalToastIcon"></i>
                <div class="d-flex flex-column">
                    <strong id="globalToastTitle" style="font-size: 0.9rem; line-height: 1.2;">New Notification</strong>
                    <span id="globalToastMessage" style="font-size: 0.8rem; font-weight: 400; opacity: 0.9; margin-top: 2px;"></span>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white ms-auto me-2 mt-2" style="box-shadow: none; opacity: 0.8;" onclick="$('#globalToastNotification').fadeOut(300);"></button>
        </div>
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

        // Notifications Logic
        window.knownNotificationIds = window.knownNotificationIds || [];
        let isInitialLoad = true;

        const showGlobalToast = (title, message) => {
            $('#globalToastTitle').text(title);
            $('#globalToastMessage').text(message);
            const toast = $('#globalToastNotification');
            
            toast.css('display', 'block').hide().fadeIn(300);
            
            setTimeout(() => {
                toast.fadeOut(400);
            }, 5000);
        };

        const fetchNotifications = (limit = 15) => {
            $.ajax({
                url: '<?= site_url("notifications/unread") ?>?limit=' + limit,
                method: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        const hasUnread = (res.count > 0) || (res.notifications && res.notifications.some(n => n.is_read == 0));
                        const totalNotifications = res.notifications ? res.notifications.length : 0;

                        if (res.count > 0) {
                            $('#notificationBadge').show();
                        } else {
                            $('#notificationBadge').hide();
                        }

                        if (totalNotifications === 0) {
                            $('#markAllReadBtn').hide();
                            $('#markAllUnreadBtn').hide();
                        } else if (hasUnread) {
                            $('#markAllReadBtn').show();
                            $('#markAllUnreadBtn').hide();
                        } else {
                            $('#markAllReadBtn').hide();
                            $('#markAllUnreadBtn').show();
                        }

                        let html = '';
                        if (res.notifications.length === 0) {
                            html = '<li><span class="dropdown-item py-3 text-center text-muted" style="font-size: 0.85rem;">No notifications</span></li>';
                        } else {
                            let newNotifications = [];
                            res.notifications.forEach(n => {
                                let isUnread = n.is_read == 0;
                                let bgStyle = isUnread ? 'background-color: var(--color-primary-subtle);' : 'background-color: transparent; opacity: 0.75;';
                                let iconClass = isUnread ? 'bi-person-circle text-primary' : 'bi-person-circle text-muted';

                                if (isUnread && !window.knownNotificationIds.includes(n.id)) {
                                    newNotifications.push(n);
                                    window.knownNotificationIds.push(n.id);
                                }

                                html += `
                                <li>
                                    <div class="dropdown-item py-2 border-bottom notification-item-wrapper d-flex align-items-center justify-content-between" style="white-space: normal; ${bgStyle} transition: all 0.2s ease; padding-right: 10px;">
                                        <a href="#" class="notification-item text-decoration-none d-flex align-items-start gap-2 flex-grow-1" data-id="${n.id}" data-task="${n.task_id}" data-message="${n.message.replace(/"/g, '&quot;')}">
                                            <i class="bi ${iconClass} mt-1"></i>
                                            <div>
                                                <div style="font-size: 0.85rem; line-height: 1.3; color: var(--color-text-base);">
                                                    <strong style="${isUnread ? '' : 'font-weight: 500;'}">${n.sender_name}</strong> ${n.message}
                                                </div>
                                                <div class="text-muted" style="font-size: 0.7rem; margin-top: 2px;">
                                                    ${n.time_ago}
                                                </div>
                                            </div>
                                        </a>
                                        <button class="btn btn-sm btn-link toggle-read-btn p-1 ms-2" data-id="${n.id}" title="${isUnread ? 'Mark as read' : 'Mark as unread'}" style="text-decoration: none;">
                                            <i class="bi ${isUnread ? 'bi-envelope-fill text-primary' : 'bi-envelope-open text-muted'}" style="font-size: 0.9rem;"></i>
                                        </button>
                                    </div>
                                </li>
                            `;
                            });

                            if (limit !== 'all' && res.notifications.length >= 15) {
                                html += `
                                    <li class="border-top text-center" style="background: var(--color-secondary-bg); border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                                        <button class="btn btn-sm btn-link text-decoration-none w-100 py-2" id="viewAllNotificationsBtn" style="font-size: 0.8rem; font-weight: 500;">View All</button>
                                    </li>
                                `;
                            }

                            if (!isInitialLoad && newNotifications.length > 0) {
                                // Show a popup for the most recent new notification
                                const latestNotif = newNotifications[0];
                                showGlobalToast(latestNotif.sender_name, latestNotif.message);
                            }
                        }
                        $('#notificationsList').html(html);
                        isInitialLoad = false;
                    }
                }
            });
        };

        fetchNotifications();
        setInterval(() => fetchNotifications(), 15000);

        $(document).on('click', '#viewAllNotificationsBtn', function (e) {
            e.preventDefault();
            e.stopPropagation();
            fetchNotifications('all');
        });

        $(document).on('click', '.toggle-read-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const btn = $(this);
            const notifId = btn.data('id');

            let csrfVal = '';
            if ($('input[name="csrf_test_name"]').length > 0) {
                csrfVal = $('input[name="csrf_test_name"]').val();
            }

            $.ajax({
                url: '<?= site_url("notifications/toggle-read/") ?>' + notifId,
                method: 'POST',
                data: { csrf_test_name: csrfVal },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        // Refresh notifications to update styles and count
                        fetchNotifications();
                    }
                }
            });
        });

        $(document).on('click', '#markAllReadBtn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            let csrfVal = '';
            if ($('input[name="csrf_test_name"]').length > 0) {
                csrfVal = $('input[name="csrf_test_name"]').val();
            }

            $.ajax({
                url: '<?= site_url("notifications/mark-all-read") ?>',
                method: 'POST',
                data: { csrf_test_name: csrfVal },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        fetchNotifications();
                    }
                }
            });
        });

        $(document).on('click', '#markAllUnreadBtn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            let csrfVal = '';
            if ($('input[name="csrf_test_name"]').length > 0) {
                csrfVal = $('input[name="csrf_test_name"]').val();
            }

            $.ajax({
                url: '<?= site_url("notifications/mark-all-unread") ?>',
                method: 'POST',
                data: { csrf_test_name: csrfVal },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        fetchNotifications();
                    }
                }
            });
        });

        $(document).on('click', '.notification-item', function (e) {
            e.preventDefault();
            const notifId = $(this).data('id');
            const taskId = $(this).data('task');
            const message = $(this).data('message') || '';

            let csrfVal = '';
            if ($('input[name="csrf_test_name"]').length > 0) {
                csrfVal = $('input[name="csrf_test_name"]').val();
            }

            let redirectUrl = '<?= site_url("tasks/view/") ?>' + taskId;
            if (message.includes('API Logs')) {
                localStorage.setItem('activeSettingsTab', 'api-logs');
                redirectUrl = '<?= site_url("settings") ?>';
            }

            $.ajax({
                url: '<?= site_url("notifications/mark-read/") ?>' + notifId,
                method: 'POST',
                data: { csrf_test_name: csrfVal },
                dataType: 'json',
                success: function (res) {
                    window.location.href = redirectUrl;
                },
                error: function () {
                    window.location.href = redirectUrl;
                }
            });
        });
    });
</script>
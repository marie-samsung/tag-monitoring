<?php use App\Models\Constants;

$segment = service('uri')->getSegment(1); ?>

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
<?php if (Constants::IS_DEV) : ?>
    <div class="d-flex justify-content-center mb-2">
        <span class="badge bg-warning" style="font-size: 0.65rem; letter-spacing: 1px; padding: 0.35em 0.7em;">DEVELOPMENT MODE</span>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-center w-100">
<ul class="nav nav-pills justify-content-center apple-segmented-control">
    <li class="nav-item">
        <a class="nav-link <?= $segment === 'tags' ? 'active' : '' ?>" href="<?= site_url('/tags') ?>">
            <i class="bi bi-tags-fill"></i> Tags
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= $segment === 'tag-requests' ? 'active' : '' ?>" href="<?= site_url('/tag-requests') ?>">
            <i class="bi bi-file-earmark-plus-fill"></i> Tag Requests
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= $segment === 'properties' ? 'active' : '' ?>" href="<?= site_url('/properties') ?>">
            <i class="bi bi-building-fill-gear"></i> Properties
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= $segment === 'vendors' ? 'active' : '' ?>" href="<?= site_url('/vendors') ?>">
            <i class="bi bi-truck"></i> Vendors
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= $segment === 'jira' ? 'active' : '' ?>" href="<?= site_url('/jira') ?>">
            <i class="bi bi-ticket-perforated-fill"></i> Jira Tickets
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= $segment === 'activity-log' ? 'active' : '' ?>" href="<?= site_url('/activity-log') ?>">
            <i class="bi bi-card-list"></i> Activity Logs
        </a>
    </li>
</ul>
</div>

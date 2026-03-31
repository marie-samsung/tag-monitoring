<?php $segment = service('uri')->getSegment(1); ?>

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

<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link <?= $segment === 'tags' ? 'active' : '' ?>" href="<?= site_url('/tags') ?>">
            <i class="bi bi-tags"></i> Tags
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= $segment === 'properties' ? 'active' : '' ?>" href="<?= site_url('/properties') ?>">
            <i class="bi bi-building"></i> Properties
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= $segment === 'vendors' ? 'active' : '' ?>" href="<?= site_url('/vendors') ?>">
            <i class="bi bi-truck"></i> Vendors
        </a>
    </li>
</ul>
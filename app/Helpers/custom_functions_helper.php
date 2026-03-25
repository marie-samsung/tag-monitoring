<?php

function status_label(int $code): string
{
    $map = [
        1 => 'Enabled',
        2 => 'Disabled',
        3 => 'Expired',
        4 => 'Pending Verification',
    ];
    return $map[$code] ?? 'Unknown';
}

function status_class(int $code): string
{
    return match ($code) {
        1 => 'bg-success text-white',   // Enabled
        2 => 'bg-danger text-white',    // Disabled
        3 => 'bg-secondary text-white', // Expired
        4 => 'bg-warning text-dark', // Pending Verification
        default => 'bg-secondary text-dark',
    };
}

function bool_label(int $value): string
{
    return $value === 1 ? 'Yes' : 'No';
}

function bool_class(int $value): string
{
    return match ($value) {
        1 => 'bg-success text-white',   // Yes
        0 => 'bg-secondary text-white',    // No
    };
}

function date_class(string|null $date): string
{
    if (empty($date)) {
        return ''; // No date provided
    }

    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return 'bg-secondary text-white'; // Invalid date
    }

    $now = time();

    if ($timestamp < $now) {
        return 'text-muted'; // Past date
    } elseif ($timestamp - $now <= 11 * 86400) {
        return 'text-danger';
    } elseif ($timestamp - $now <= 16 * 86400) {
        return 'text-warning';
    } else {
        return 'text-success';
    }
}

<?php

function status_label(int $code): string
{
    $map = [
        2 => 'Disabled',
        1 => 'Enabled',
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
        4 => 'bg-warning text-white', // Pending Verification
        default => 'bg-secondary text-white',
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

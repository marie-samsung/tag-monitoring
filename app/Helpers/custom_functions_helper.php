<?php

function status_label(int $code): string
{
    $map = [
        0 => 'Disabled',
        1 => 'Enabled',
        2 => 'Expired',
    ];
    return $map[$code] ?? 'Unknown';
}

function status_class(int $code): string
{
    return match ($code) {
        1 => 'bg-success text-white',   // Enabled
        0 => 'bg-danger text-white',    // Disabled
        default => 'bg-secondary text-white',
    };
}

function is_epp(int $value): string
{
    return $value ? 'Yes' : 'No';
}

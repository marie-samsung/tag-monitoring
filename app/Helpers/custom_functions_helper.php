<?php

use App\Models\Constants;

function status_label(int $code): string
{
    $map = [
        1 => 'Enabled',
        2 => 'Disabled',
        3 => 'Expired',
        4 => 'Pending Verification',
        5 => 'Updated in AL'
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
        5 => 'bg-info text-dark', // Updated in AL
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

function date_class(string|null $date, int $status): string
{
    if (empty($date)) {
        return ''; // No date provided
    }

    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return 'bg-secondary text-white'; // Invalid date
    }

    $now = time();

    if ($status == Constants::STATUS_PENDING_VERIFICATION) {
        if ($timestamp < $now) {
            return 'text-danger'; // Past date but pending verification
        } else {
            return 'text-warning'; // Future date but pending verification
        }
    } elseif ($status == Constants::STATUS_EXPIRED) {
        return 'text-muted'; // Expired status
    } elseif ($status == Constants::STATUS_DISABLED) {
        return 'text-secondary'; // Disabled status
    } else {
        if ($timestamp - $now <= 11 * 86400) {
            return 'text-danger'; // Past date
        } elseif ($timestamp - $now <= 16 * 86400) {
            return 'text-warning'; // Future date
        } else {
            return 'text-success'; // Distant future date
        }
    }
}

function days_class(int $days): string
{
    if ($days <= 11) {
        return 'text-danger'; // Past date
    } elseif ($days <= 16) {
        return 'text-warning'; // Future date
    } else {
        return 'text-success'; // Distant future date
    }
}

function random_bg_style($value): string
{
    $hash = md5($value);
    $color = substr($hash, 0, 6);

    return "background-color:#{$color}; color:white;";
}

function random_text_style($value): string
{
    $hash = md5($value);
    $color = substr($hash, 0, 6);

    return "color:#{$color};";
}

function date_ymd(string|null $date): string
{
    if (empty($date)) {
        return 'N/A';
    }

    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return 'Invalid date';
    }

    return date('Y-m-d', $timestamp);
}

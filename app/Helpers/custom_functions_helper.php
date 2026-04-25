<?php

use App\Models\Constants;

function status_label(int $code): string
{
    $map = [
        Constants::STATUS_ENABLED => 'Enabled',
        Constants::STATUS_DISABLED => 'Disabled',
        Constants::STATUS_EXPIRED => 'Expired',
        Constants::STATUS_PENDING_VERIFICATION => 'Pending Verification',
        Constants::STATUS_UPDATED_IN_AL => 'Updated in AL',
        Constants::STATUS_NEW_TAG_SUBMITTED => 'New Tag Request Submitted',
        Constants::STATUS_NEW_RULE_SUBMITTED => 'New Rule Request Submitted',
    ];
    return $map[$code] ?? 'Unknown';
}

function status_class(int $code): string
{
    return match ($code) {
        Constants::STATUS_ENABLED => 'bg-success text-white',
        Constants::STATUS_DISABLED => 'bg-danger text-white',
        Constants::STATUS_EXPIRED => 'bg-secondary text-white',
        Constants::STATUS_PENDING_VERIFICATION => 'bg-warning text-dark',
        Constants::STATUS_UPDATED_IN_AL => 'bg-info text-dark',
        Constants::STATUS_NEW_TAG_SUBMITTED => 'bg-warning text-dark',
        Constants::STATUS_NEW_RULE_SUBMITTED => 'bg-secondary text-white',
        default => 'bg-secondary text-dark',
    };
}

function status_request_class(int $code): string
{
    return match ($code) {
        Constants::STATUS_REQUEST_HQ_REVIEW => 'bg-warning text-dark',
        Constants::STATUS_REQUEST_PENDING_ITEMS => 'bg-info text-dark',
        Constants::STATUS_REQUEST_APPROVED => 'bg-success text-white',
        Constants::STATUS_REQUEST_PUBLISHED => 'bg-success text-white',
        Constants::STATUS_REQUEST_REJECTED => 'bg-danger text-white',
        default => 'bg-secondary text-dark',
    };
}

function bool_label(int $value): string
{
    return $value === Constants::YES ? 'Yes' : 'No';
}

function bool_class(int $value): string
{
    return match ($value) {
        Constants::YES => 'bg-success text-white',   // Yes
        Constants::NO => 'bg-secondary text-white',    // No
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

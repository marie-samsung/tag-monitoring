<?php

use App\Models\Constants;

function status_label(int $id): string
{
    $map = [
        Constants::STATUS_ENABLED => 'Enabled',
        Constants::STATUS_DISABLED => 'Disabled',
        Constants::STATUS_EXPIRED => 'Expired',
        Constants::STATUS_PENDING_VERIFICATION => 'Pending Verification',
        Constants::STATUS_UPDATED_IN_AL => 'Updated in AL',
        Constants::STATUS_NEW_TAG_SUBMITTED => 'New Tag Request Submitted',
        Constants::STATUS_NEW_RULE_SUBMITTED => 'New Rule Request Submitted',
        Constants::STATUS_ON_HOLD => 'On-Hold / Blocked',
    ];
    return $map[$id] ?? 'Unknown';
}

function status_class(int $id): string
{
    return match ($id) {
        Constants::STATUS_ENABLED => 'bg-success text-white',
        Constants::STATUS_DISABLED, Constants::STATUS_ON_HOLD => 'bg-danger text-white',
        Constants::STATUS_EXPIRED, Constants::STATUS_NEW_RULE_SUBMITTED => 'bg-secondary text-white',
        Constants::STATUS_PENDING_VERIFICATION, Constants::STATUS_NEW_TAG_SUBMITTED => 'bg-warning text-dark',
        Constants::STATUS_UPDATED_IN_AL => 'bg-info text-dark',
        default => 'bg-secondary text-dark',
    };
}

function status_activity_logs_class(int $id): string
{
    return match ($id) {
        Constants::ACTIVITY_LOGS_STATUS_TO_DO => 'bg-secondary text-white',
        Constants::ACTIVITY_LOGS_STATUS_INVESTIGATION, Constants::ACTIVITY_LOGS_STATUS_IMPLEMENTATION, Constants::ACTIVITY_LOGS_STATUS_REVIEW => 'bg-warning text-dark',
        Constants::ACTIVITY_LOGS_STATUS_QA => 'bg-info text-dark',
        Constants::ACTIVITY_LOGS_STATUS_COMPLETED => 'bg-success text-white',
        Constants::ACTIVITY_LOGS_STATUS_BLOCKED => 'bg-danger text-white',
    };
}

function status_request_class(int $id): string
{
    return match ($id) {
        Constants::STATUS_REQUEST_HQ_REVIEW, Constants::STATUS_REQUEST_NEW_RULE_REQUEST_SUBMITTED => 'bg-warning text-dark',
        Constants::STATUS_REQUEST_PENDING_ITEMS => 'bg-info text-dark',
        Constants::STATUS_REQUEST_APPROVED, Constants::STATUS_REQUEST_PUBLISHED => 'bg-success text-white',
        Constants::STATUS_REQUEST_REJECTED => 'bg-danger text-white',
        default => 'bg-secondary text-dark',
    };
}

function assignee_class(int $id): string
{
    return match ($id) {
        Constants::USER_SYSTEM, Constants::USER_OTHER => 'bg-secondary text-white',
        Constants::USER_MARIE => 'bg-purple text-white',
        Constants::USER_KHRISTINE => 'bg-pink text-white',
        Constants::USER_LUCKY => 'bg-green text-white',
        Constants::USER_JANITHA => 'bg-blue text-dark',
        Constants::USER_GEORGE => 'bg-orange text-dark',
    };
}

function priority_class(int $id): string
{
    return match ($id) {
        Constants::PRIORITY_LOW => 'bg-secondary text-white',
        Constants::PRIORITY_MEDIUM => 'bg-warning text-dark',
        Constants::PRIORITY_HIGH, Constants::PRIORITY_CRITICAL, Constants::PRIORITY_BLOCKER => 'bg-danger text-white',
    };
}

function size_class(int $id): string
{
    return match ($id) {
        Constants::ACTIVITY_SIZE_S => 'bg-secondary text-white',
        Constants::ACTIVITY_SIZE_M => 'bg-warning text-dark',
        Constants::ACTIVITY_SIZE_L, Constants::ACTIVITY_SIZE_XL, Constants::ACTIVITY_SIZE_SPRINT => 'bg-danger text-white',
    };
}

function size_description(int|null $id): string
{
    if (empty($id)) {
        return 'Unknown size';
    }
    return match ($id) {
        Constants::ACTIVITY_SIZE_S => 'S (Small): Minor tasks / quick fixes (effort < 2 hours)',
        Constants::ACTIVITY_SIZE_M => 'M (Medium): Standard tag setups / simple troubleshooting (effort: 2-5 hours)',
        Constants::ACTIVITY_SIZE_L => 'L (Large): New tag integrations / complex tracking rules (effort: 1-2 days)',
        Constants::ACTIVITY_SIZE_XL => 'XL (Extra Large): Major property setups / migrations / custom JS (effort: 3-5 days)',
        Constants::ACTIVITY_SIZE_SPRINT => 'Sprint: Massive undertakings / entire sprint effort (effort: 1-2 weeks)',
        default => 'Unknown size',
    };
}

function size_tooltip(int|null $id): string
{
    if (empty($id)) {
        return 'T-Shirt Size';
    }
    return match ($id) {
        Constants::ACTIVITY_SIZE_S => 'S: Small task (effort < 2h)',
        Constants::ACTIVITY_SIZE_M => 'M: Medium task (effort 2-5h)',
        Constants::ACTIVITY_SIZE_L => 'L: Large task (effort 1-2d)',
        Constants::ACTIVITY_SIZE_XL => 'XL: Extra Large task (effort 3-5d)',
        Constants::ACTIVITY_SIZE_SPRINT => 'Sprint: Sprint-level effort (effort 1-2w)',
        default => 'T-Shirt Size',
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

    // Convert hex to RGB
    $r = hexdec(substr($color, 0, 2));
    $g = hexdec(substr($color, 2, 2));
    $b = hexdec(substr($color, 4, 2));

    // Calculate perceived brightness
    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

    // Choose text color
    $textColor = $brightness > 128 ? 'black' : 'white';

    return "background-color:#{$color}; color:{$textColor};";
}

function random_text_style($value): string
{
    $hash = md5($value);
    $color = substr($hash, 0, 6);

    return "color:#{$color};";
}

function date_ymd(string|null $date = null): string
{
    if (empty($date)) {
        return date('Y-m-d');
    }

    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return 'Invalid date';
    }

    return date('Y-m-d', $timestamp);
}

function format_comment_time(string|null $datetime): string
{
    if (empty($datetime)) {
        return '';
    }

    $timestamp = strtotime($datetime);
    if ($timestamp === false) {
        return esc($datetime);
    }

    $now = time();
    $diff = $now - $timestamp;

    // Guard against small future timezone/clock drift offsets
    if ($diff < 0) {
        $diff = 0;
    }

    if ($diff < 60) {
        return 'Just now';
    }

    if ($diff < 3600) {
        $minutes = floor($diff / 60);
        return $minutes . ' ' . ($minutes == 1 ? 'minute' : 'minutes') . ' ago';
    }

    if ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' ' . ($hours == 1 ? 'hour' : 'hours') . ' ago';
    }

    return date('d-m-Y H:i', $timestamp);
}


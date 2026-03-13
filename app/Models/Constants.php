<?php

namespace App\Models;

class Constants
{
    public const LP_WRITE_PATH = WRITEPATH . 'uploads/lp/';
    public const LP_PATH = 'uploads/lp/';

    public const JIRA_TICKET_TYPES = [
        1 => 'Date Extension Rule',
        2 => 'New Tag',
        3 => 'New Rule',
        4 => 'Master Approval',
        5 => 'Other',
    ];

    public const STATUS = [
        1 => 'Enabled',
        2 => 'Disabled',
        3 => 'Expired',
        4 => 'Pending Verification',
    ];

    public const ACC_TYPE = [
        0 => 'System',
        1 => 'Admin',
        2 => 'User',
    ];

    public const IS_EPP = [
        0 => 'No',
        1 => 'Yes',
    ];

    public const IS_ACTIVE = [
        0 => 'Inactive',
        1 => 'Active',
    ];

    public const CONDITIONS = [
        '(.*)',
        '(.*orderConfirmation)',
        '(.*unpacked)',
    ];

    public const DATE_DISPLAY_FORMATS = [
        'Y-m-d'       => 'YYYY‑MM‑DD',
        'd/m/Y'       => 'DD/MM/YYYY',
        'm-d-Y'       => 'MM‑DD‑YYYY',
        'Y-m-d H:i'   => 'YYYY‑MM‑DD HH:MM (24h)',
        'd M Y'       => 'DD Mon YYYY',
    ];
}

<?php

namespace App\Models;

class Constants
{
    public const JIRA_LINK = 'https://jira.secext.samsung.net/browse/';
    public const JIRA_CREATE_ISSUE = 'https://jira.secext.samsung.net/secure/CreateIssue!default.jspa';
    public const LP_WRITE_PATH = WRITEPATH . 'uploads/lp/';
    public const LP_PATH = 'uploads/lp/';
    public const JIRA_TYPE_DATE_EXTENSION_RULE = 1;
    public const JIRA_TYPE_NEW_TAG = 2;
    public const JIRA_TYPE_NEW_RULE = 3;
    public const JIRA_TYPE_MASTER_APPROVAL = 4;
    public const JIRA_TYPE_OTHER = 5;

    public const JIRA_TICKET_TYPES = [
        self::JIRA_TYPE_DATE_EXTENSION_RULE => 'Date Extension Rule',
        self::JIRA_TYPE_NEW_TAG => 'New Tag',
        self::JIRA_TYPE_NEW_RULE => 'New Rule',
        self::JIRA_TYPE_MASTER_APPROVAL => 'Master Approval',
        self::JIRA_TYPE_OTHER => 'Other',
    ];

    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 2;
    public const STATUS_EXPIRED = 3;
    public const STATUS_PENDING_VERIFICATION = 4;
    public const STATUS_UPDATED_IN_AL = 5;

    public const YES = 1;
    public const NO = 0;

    public const STATUS = [
        self::STATUS_ENABLED => 'Enabled',
        self::STATUS_DISABLED => 'Disabled',
        self::STATUS_EXPIRED => 'Expired',
        self::STATUS_PENDING_VERIFICATION => 'Pending Verification',
        self::STATUS_UPDATED_IN_AL => 'Updated in AL',
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
}

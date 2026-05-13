<?php

namespace App\Models;

class Constants
{
    public const IS_DEV = (ENVIRONMENT !== 'production');
    public const JIRA_LINK = 'https://jira.secext.samsung.net/browse/';
    public const JIRA_CREATE_ISSUE = 'https://jira.secext.samsung.net/secure/CreateIssue!default.jspa';
    public const JIRA_SSGTM_TICKET = 'https://jira.secext.samsung.net/browse/GTA-31005';
    public const LP_WRITE_PATH = WRITEPATH . 'uploads/lp/';
    public const LP_PATH = 'uploads/lp/';
    public const NEW_TAG_FILE_WRITE_PATH = WRITEPATH . 'uploads/new_tag_requests/';
    public const JIRA_TYPE_DATE_EXTENSION_RULE = 1;
    public const JIRA_TYPE_NEW_TAG = 2;
    public const JIRA_TYPE_NEW_RULE = 3;
    public const JIRA_TYPE_MASTER_APPROVAL = 4;
    public const JIRA_TYPE_UPDATE_RULE = 5;
    public const JIRA_TYPE_MAINTENANCE_TICKET = 6;
    public const JIRA_TYPE_OTHER = 7;
    public const JIRA_TICKET_TYPES = [
        self::JIRA_TYPE_DATE_EXTENSION_RULE => 'Date Extension Rule',
        self::JIRA_TYPE_NEW_TAG => 'New Tag',
        self::JIRA_TYPE_NEW_RULE => 'New Rule',
        self::JIRA_TYPE_MASTER_APPROVAL => 'Master Approval',
        self::JIRA_TYPE_UPDATE_RULE => 'Update Rule',
        self::JIRA_TYPE_MAINTENANCE_TICKET => 'Maintenance Ticket',
        self::JIRA_TYPE_OTHER => 'Other',
    ];
    public const JIRA_CREATE_TICKET_TYPES =[
        self::JIRA_TYPE_MASTER_APPROVAL => 'Master Approval',
        self::JIRA_TYPE_MAINTENANCE_TICKET => 'Maintenance Ticket',
        self::JIRA_TYPE_OTHER => 'Other',
    ];

    public const REQUEST_TYPES = [
        self::JIRA_TYPE_NEW_TAG => 'New Tag',
        self::JIRA_TYPE_NEW_RULE => 'New Rule',
    ];

    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 2;
    public const STATUS_EXPIRED = 3;
    public const STATUS_PENDING_VERIFICATION = 4;
    public const STATUS_UPDATED_IN_AL = 5;
    public const STATUS_NEW_TAG_SUBMITTED = 6;
    public const STATUS_NEW_RULE_SUBMITTED = 7;
    public const STATUS = [
        self::STATUS_ENABLED => 'Enabled',
        self::STATUS_DISABLED => 'Disabled',
        self::STATUS_EXPIRED => 'Expired',
        self::STATUS_PENDING_VERIFICATION => 'Pending Verification',
        self::STATUS_UPDATED_IN_AL => 'Updated in AL',
        self::STATUS_NEW_TAG_SUBMITTED => 'New Tag Request Submitted',
        self::STATUS_NEW_RULE_SUBMITTED => 'New Rule Request Submitted',
    ];

    public const STATUS_REQUEST_HQ_REVIEW = 1;
    public const STATUS_REQUEST_PENDING_ITEMS = 2;
    public const STATUS_REQUEST_NEW_RULE_REQUEST_SUBMITTED = 3;
    public const STATUS_REQUEST_APPROVED = 4;
    public const STATUS_REQUEST_REJECTED = 5;
    public const STATUS_REQUEST_PUBLISHED = 6;
    public const STATUS_REQUESTS = [
        self::STATUS_REQUEST_HQ_REVIEW => 'HQ Review',
        self::STATUS_REQUEST_PENDING_ITEMS => 'On-Hold Req',
        self::STATUS_REQUEST_NEW_RULE_REQUEST_SUBMITTED => 'New Rule Request Submitted',
        self::STATUS_REQUEST_APPROVED => 'Approved',
        self::STATUS_REQUEST_REJECTED => 'Rejected',
        self::STATUS_REQUEST_PUBLISHED => 'Published',
    ];

    public const SUBSIDIARY_SGE = 1;
    public const SUBSIDIARY_SESAR = 2;
    public const SUBSIDIARY_SETK = 3;
    public const SUBSIDIARY_SEIL = 4;
    public const SUBSIDIARY_SEILPS = 5;
    public const SUBSIDIARY_SELV = 6;
    public const SUBSIDIARY_SEEG = 7;
    public const SUBSIDIARY_SEMAG = 8;
    public const SUBSIDIARY_SEPAK = 9;
    public const SUBSIDIARY_MENAO = 10;
    public const SUBSIDIARIES = [
        self::SUBSIDIARY_SGE => 'SGE',
        self::SUBSIDIARY_SESAR => 'SESAR',
        self::SUBSIDIARY_SETK => 'SETK',
        self::SUBSIDIARY_SEIL => 'SEIL',
        self::SUBSIDIARY_SEILPS => 'SEILPS',
        self::SUBSIDIARY_SELV => 'SELV',
        self::SUBSIDIARY_SEEG => 'SEEG',
        self::SUBSIDIARY_SEMAG => 'SEMAG',
        self::SUBSIDIARY_SEPAK => 'SEPAK',
        self::SUBSIDIARY_MENAO => 'MENAO',
    ];

    public const ATTACHMENT_APPROVAL = 1;
    public const ATTACHMENT_EMAIL_REQUEST = 2;
    public const ATTACHMENT_REQUEST_FORM = 3;
    public const ATTACHMENT_DOCUMENTATION = 4;
    public const ATTACHMENT_OTHER = 5;
    public const ATTACHMENTS = [
        self::ATTACHMENT_APPROVAL => 'Approval',
        self::ATTACHMENT_EMAIL_REQUEST => 'Email Request',
        self::ATTACHMENT_REQUEST_FORM => 'Request Form',
        self::ATTACHMENT_DOCUMENTATION => 'Documentation',
        self::ATTACHMENT_OTHER => 'Other',
    ];
    
    public const YES = 1;
    public const NO = 0;
    public const YES_NO = [
        self::NO => 'No',
        self::YES => 'Yes',
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

    public const PURPOSE_GOAL_NEW_TAG = [
        'What is the official website of the platform/company?',
        'What is the duration of usage? Will it be used for a specific campaign or as part of an ongoing effort without a defined end date?',
        'Any KPIs applied to review this tag bi-monthly as we need to extend every 2 months?',
        'Performance thresholds that will determine renewal or deactivation of this tag?',
        'What is your internal process for reviewing and validating these KPIs every two months?',
    ];
}

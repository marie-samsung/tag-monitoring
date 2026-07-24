<?php

namespace App\Models;

class Constants
{
    public const IS_DEV = (ENVIRONMENT !== 'production');
    public const JIRA_LINK = 'https://jira.secext.samsung.net/browse/';
    public const JIRA_CREATE_ISSUE = 'https://jira.secext.samsung.net/secure/CreateIssue!default.jspa';
    public const JIRA_SSGTM_TICKET = 'https://jira.secext.samsung.net/browse/GTA-31005';
    public const JIRA_TAGGING_SUPPORT = 'https://jira.secext.samsung.net/secure/ViewProfile.jspa?name=tagging.jira%40concentrix.com';
    public const MT_JIRA_LINK = 'https://devops.sdsdev.co.kr/jira/browse/';

    // https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules?filter%5Bname%5D=CONTAINS+LC-Shop-AD%28Meta%29-CM-WL-SDSME_1240253317108112&page%5Bnumber%5D=1
    public const AL_RULES_SEARCH_PARAMETER = 'rules?filter%5Bname%5D=CONTAINS+';
    public const AL_LIBRARY_CREATE = 'publishing/new';
    public const LP_WRITE_PATH = WRITEPATH . 'uploads/lp/';
    public const LP_PATH = 'uploads/lp/';
    public const NEW_TAG_FILE_WRITE_PATH = WRITEPATH . 'uploads/new_tag_requests/';
    public const ACTIVITY_LOGS_FILE_WRITE_PATH = WRITEPATH . 'uploads/activity_logs/';
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
    public const JIRA_CREATE_TICKET_TYPES = [
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
    public const STATUS_ENDED = 8;
    public const STATUS_ON_HOLD = 9;
    public const STATUS_DEFAULT = 145;
    public const STATUS_ALL = 999;
    public const STATUS = [
        self::STATUS_DEFAULT => 'Default',
        self::STATUS_ENABLED => 'Enabled',
        self::STATUS_DISABLED => 'Disabled',
        self::STATUS_EXPIRED => 'Expired',
        self::STATUS_PENDING_VERIFICATION => 'Pending Verification',
        self::STATUS_UPDATED_IN_AL => 'Updated in AL',
        self::STATUS_NEW_TAG_SUBMITTED => 'New Tag Request Submitted',
        self::STATUS_NEW_RULE_SUBMITTED => 'New Rule Request Submitted',
        self::STATUS_ENDED => 'Ended',
        self::STATUS_ON_HOLD => 'On-Hold / Blocked',
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
    public const SUBSIDIARY_AFRICA_RHQ = 11;
    public const SUBSIDIARY_IRAN_RHQ = 12;
    public const SUBSIDIARY_SDS = 13;
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
        //        self::SUBSIDIARY_MENAO => 'MENAO',
    ];

    public const ACTIVITY_LOG_SUBSIDIARIES = [
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
        self::SUBSIDIARY_AFRICA_RHQ => 'Africa RHQ',
        self::SUBSIDIARY_IRAN_RHQ => 'Iran RHQ',
        self::SUBSIDIARY_SDS => 'SDS',
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

    public const INTEGRATION_RESULT_PAGE_LOAD = [
        'If the <b>Page Load Rule</b> is included' => 'Confirm (Y/N)',
        '1) Check the rule is normally generated' => 'Y / N',
        '2) Confirm that no script errors occur' => 'Y / N',
        '3) Check that the page loads normally' => 'Y / N',
        '4) Confirm that all contents in the page are loaded normally' => 'Y / N',
    ];

    public const INTEGRATION_RESULT_EVENT_CLICK = [
        'If the <b>Event Click Rule</b> is included' => 'Confirm (Y/N)',
        '1) Check whether the rule is normally generated' => 'Y / N',
        '2) Check that no script errors occur' => 'Y / N',
        '3) When an event occurs, whether the functions corresponding to the event operate normally as before' => 'Y / N',
        '4) After the event occurs, there is no impact on the expected User Journey' => 'Y / N',
    ];

    public const TEAM_MEDIA_TAGGING = 1;
    public const TEAM_DWF = 2;
    public const TEAM_CRM = 3;
    public const TEAM_SPOKE = 4;
    public const TEAM_OTHER = 5;
    public const TEAMS = [
        self::TEAM_MEDIA_TAGGING => 'Media Tagging',
        self::TEAM_DWF => 'DWF',
        self::TEAM_CRM => 'CRM 2.0',
        self::TEAM_SPOKE => 'Spoke',
//        self::TEAM_OTHER => 'Other',
    ];

    public const PROJECT_MEDIA_TAGGING = 1;
    public const PROJECT_DWF = 2;
    public const PROJECT_CRM = 3;
    public const PROJECT_SPOKE = 4;
    public const PROJECT_OTHER = 5;
    public const PROJECTS = self::TEAMS;

    public const USER_SYSTEM = 0;
    public const USER_SYSTEM_LABEL = 'System';
    public const USER_MARIE = 1;
    public const USER_KHRISTINE = 2;
    public const USER_LUCKY = 3;
    public const USER_JANITHA = 4;
    public const USER_GEORGE = 5;
    public const USER_OTHER = 6;
    public const USERS = [
        self::USER_MARIE => 'Marie',
        self::USER_KHRISTINE => 'Khristine',
        self::USER_LUCKY => 'Lucky',
        self::USER_JANITHA => 'Janitha',
        self::USER_GEORGE => 'George',
        self::USER_OTHER => 'Other',
    ];

    public const USER_ROLE_MEDIA_TAGGING = 1;
    public const USER_ROLE_MARTECH = 2;
    public const USER_ROLE_ADMIN = 3;
    public const USER_ROLES = [
        self::USER_ROLE_MEDIA_TAGGING => 'Media Tagging',
        self::USER_ROLE_MARTECH => 'Martech',
        self::USER_ROLE_ADMIN => 'Admin',
    ];

    public const USER_ACCESS_LEVELS = [
        self::USER_MARIE => self::USER_ROLE_MEDIA_TAGGING,
        self::USER_LUCKY => self::USER_ROLE_ADMIN,
        self::USER_GEORGE => self::USER_ROLE_MEDIA_TAGGING,
        self::USER_KHRISTINE => self::USER_ROLE_MARTECH,
        self::USER_JANITHA => self::USER_ROLE_MARTECH,
        self::USER_OTHER => self::USER_ROLE_ADMIN,
    ];

    public const USER_TEAMS = [
        self::USER_MARIE => self::TEAM_MEDIA_TAGGING,
        self::USER_LUCKY => self::TEAM_MEDIA_TAGGING,
        self::USER_GEORGE => self::TEAM_MEDIA_TAGGING,
        self::USER_KHRISTINE => self::TEAM_DWF,
        self::USER_JANITHA => self::TEAM_CRM,
        self::USER_OTHER => self::TEAM_CRM,
    ];

    public const USER_ROLES_ACCESS = [
        self::USER_ROLE_MEDIA_TAGGING => [
            'dashboard' => true,
            'tags' => true,
            'tag-requests' => true,
            'properties' => true,
            'vendors' => true,
            'jira' => true,
            'tasks' => true,
            'settings' => false,
            'reports' => true,
        ],
        self::USER_ROLE_MARTECH => [
            'dashboard' => true,
            'tags' => false,
            'tag-requests' => false,
            'properties' => false,
            'vendors' => false,
            'jira' => false,
            'tasks' => true,
            'settings' => false,
            'reports' => false,
        ],
        self::USER_ROLE_ADMIN => [
            'dashboard' => true,
            'tags' => true,
            'tag-requests' => true,
            'properties' => true,
            'vendors' => true,
            'jira' => true,
            'tasks' => true,
            'settings' => true,
            'reports' => true,
        ],
    ];

    public const ACTIVITY_LOGS_STATUS_TO_DO = 1;
    public const ACTIVITY_LOGS_STATUS_INVESTIGATION = 2;
    public const ACTIVITY_LOGS_STATUS_IMPLEMENTATION = 3;
    public const ACTIVITY_LOGS_STATUS_QA = 4;
    public const ACTIVITY_LOGS_STATUS_REVIEW = 5;
    public const ACTIVITY_LOGS_STATUS_COMPLETED = 6;
    public const ACTIVITY_LOGS_STATUS_BLOCKED = 7;
    public const ACTIVITY_LOGS_STATUS = [
        self::ACTIVITY_LOGS_STATUS_TO_DO => 'Not yet started',
        self::ACTIVITY_LOGS_STATUS_INVESTIGATION => 'In Progress - Investigation',
        self::ACTIVITY_LOGS_STATUS_IMPLEMENTATION => 'In Progress - Implementation',
        self::ACTIVITY_LOGS_STATUS_QA => 'Testing - QA',
        self::ACTIVITY_LOGS_STATUS_REVIEW => 'Pending Review',
        self::ACTIVITY_LOGS_STATUS_COMPLETED => 'Completed',
        self::ACTIVITY_LOGS_STATUS_BLOCKED => 'Blocked/On Hold',
    ];

    public const TAG_REQUEST_STATUS_REFERENCE_MAPPING = [
        self::STATUS_REQUEST_HQ_REVIEW => [
            'task' => self::ACTIVITY_LOGS_STATUS_REVIEW,
            'tag' => self::STATUS_PENDING_VERIFICATION,
        ],
        self::STATUS_REQUEST_NEW_RULE_REQUEST_SUBMITTED => [
            'task' => self::ACTIVITY_LOGS_STATUS_REVIEW,
            'tag' => self::STATUS_PENDING_VERIFICATION,
        ],
        self::STATUS_REQUEST_PENDING_ITEMS => [
            'task' => self::ACTIVITY_LOGS_STATUS_BLOCKED,
            'tag' => self::STATUS_ON_HOLD,
        ],
        self::STATUS_REQUEST_APPROVED => [
            'task' => self::ACTIVITY_LOGS_STATUS_COMPLETED,
            'tag' => self::STATUS_NEW_TAG_SUBMITTED,
        ],
        self::STATUS_REQUEST_PUBLISHED => [
            'task' => self::ACTIVITY_LOGS_STATUS_COMPLETED,
            'tag' => self::STATUS_ENABLED,
        ],
    ];

    public const TASK_STATUS_REFERENCE_MAPPING = [
        self::ACTIVITY_LOGS_STATUS_REVIEW => [
            'request' => self::STATUS_REQUEST_HQ_REVIEW,
            'tag' => self::STATUS_PENDING_VERIFICATION
        ],
        self::ACTIVITY_LOGS_STATUS_COMPLETED => [
            'request' => self::STATUS_REQUEST_PUBLISHED,
            'tag' => self::STATUS_ENABLED
        ],
        self::ACTIVITY_LOGS_STATUS_BLOCKED => [
            'request' => self::STATUS_REQUEST_PENDING_ITEMS,
            'tag' => self::STATUS_ON_HOLD
        ],
    ];

    public const ACTIVITY_TYPE_TASK = 1;
    public const ACTIVITY_TYPE_SUPPORT = 2;
    public const ACTIVITY_TYPE_TROUBLESHOOTING = 3;
    public const ACTIVITY_TYPE_REQUEST = 4;
    public const ACTIVITY_TYPE_MEETING = 5;
    public const ACTIVITY_TYPE_RENEWAL = 6;
    public const ACTIVITY_TYPE_OTHER = 7;
    public const ACTIVITY_TYPES = [
        self::ACTIVITY_TYPE_TASK => 'Task',
        self::ACTIVITY_TYPE_SUPPORT => 'Support',
        self::ACTIVITY_TYPE_TROUBLESHOOTING => 'Troubleshooting',
        self::ACTIVITY_TYPE_REQUEST => 'Request',
        self::ACTIVITY_TYPE_MEETING => 'Meeting',
        self::ACTIVITY_TYPE_RENEWAL => 'Renewal',
        self::ACTIVITY_TYPE_OTHER => 'Other',
    ];

    public const ACTIVITY_SIZE_S = 1;
    public const ACTIVITY_SIZE_M = 2;
    public const ACTIVITY_SIZE_L = 3;
    public const ACTIVITY_SIZE_XL = 4;
    public const ACTIVITY_SIZE_SPRINT = 5;
    public const ACTIVITY_SIZES = [
        self::ACTIVITY_SIZE_S => 'S',
        self::ACTIVITY_SIZE_M => 'M',
        self::ACTIVITY_SIZE_L => 'L',
        self::ACTIVITY_SIZE_XL => 'XL',
        self::ACTIVITY_SIZE_SPRINT => 'Sprint',
    ];

    public const ACTIVITY_SIZES_DESCRIPTIONS = [
        self::ACTIVITY_SIZE_S => 'S (Small): Minor tasks / quick fixes (estimated effort < 2 hours)',
        self::ACTIVITY_SIZE_M => 'M (Medium): Standard tag setups / simple troubleshooting (estimated effort: 2-5 hours)',
        self::ACTIVITY_SIZE_L => 'L (Large): New tag integrations / complex tracking rules (estimated effort: 1-2 days)',
        self::ACTIVITY_SIZE_XL => 'XL (Extra Large): Major property setups / migrations / custom JS (estimated effort: 3-5 days)',
        self::ACTIVITY_SIZE_SPRINT => 'Sprint: Massive undertakings / entire sprint effort (estimated effort: 1-2 weeks)',
    ];

    public const PRIORITY_LOW = 1;
    public const PRIORITY_MEDIUM = 2;
    public const PRIORITY_HIGH = 3;
    public const PRIORITY_CRITICAL = 4;
    public const PRIORITY_BLOCKER = 5;
    public const PRIORITY_DEFAULT = 10;
    public const PRIORITIES = [
        self::PRIORITY_DEFAULT => 'Default',
        self::PRIORITY_LOW => 'Low',
        self::PRIORITY_MEDIUM => 'Medium',
        self::PRIORITY_HIGH => 'High',
        self::PRIORITY_CRITICAL => 'Critical',
        self::PRIORITY_BLOCKER => 'Blocker',
    ];

    public const CAMPAIGN_STATUS_NEW = 1;
    public const CAMPAIGN_STATUS_EXISTING = 2;
    public const CAMPAIGN_STATUS = [
        self::CAMPAIGN_STATUS_NEW => 'New',
        self::CAMPAIGN_STATUS_EXISTING => 'Existing',
    ];

    public const COMMENT_TYPE_COMMENT = 1;
    public const COMMENT_TYPE_UPDATE = 2;
    public const COMMENT_TYPES = [
        self::COMMENT_TYPE_COMMENT => 'Comment',
        self::COMMENT_TYPE_UPDATE => 'Update',
    ];
}

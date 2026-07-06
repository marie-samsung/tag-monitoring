<?php

namespace App\Models;
use App\Models\Constants;

class JiraMapping
{
    public const PRIORITY_LOW = 'Low';
    public const PRIORITY_MEDIUM = 'Medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_CRITICAL = 'Critical';
    public const PRIORITY_BLOCKER = 'Blocker';
    public const PRIORITY_MINOR = 'Minor';

    public const PRIORITIES = [
        Constants::PRIORITY_LOW => self::PRIORITY_LOW,
        Constants::PRIORITY_MEDIUM => self::PRIORITY_MEDIUM,
        Constants::PRIORITY_HIGH => self::PRIORITY_HIGH,
        Constants::PRIORITY_CRITICAL => self::PRIORITY_CRITICAL,
        Constants::PRIORITY_BLOCKER => self::PRIORITY_BLOCKER,
        Constants::PRIORITY_DEFAULT => self::PRIORITY_MINOR,
    ];

    public const ESTIMATE_S = '1h';
    public const ESTIMATE_M = '3h';
    public const ESTIMATE_L = '1d';
    public const ESTIMATE_XL = '3d';
    public const ESTIMATE_SPRINT = '1w';

    public const ESTIMATES = [
        Constants::ACTIVITY_SIZE_S => self::ESTIMATE_S,
        Constants::ACTIVITY_SIZE_M => self::ESTIMATE_M,
        Constants::ACTIVITY_SIZE_L => self::ESTIMATE_L,
        Constants::ACTIVITY_SIZE_XL => self::ESTIMATE_XL,
        Constants::ACTIVITY_SIZE_SPRINT => self::ESTIMATE_SPRINT,
    ];

    public const JIRA_STATUS_TODO = 'To Do';
    public const JIRA_STATUS_IN_PROGRESS = 'In Progress';
    public const JIRA_STATUS_CLOSED = 'Closed';

    public const STATUS_MAPPING = [
        Constants::ACTIVITY_LOGS_STATUS_TO_DO => self::JIRA_STATUS_TODO,
        Constants::ACTIVITY_LOGS_STATUS_INVESTIGATION => self::JIRA_STATUS_IN_PROGRESS,
        Constants::ACTIVITY_LOGS_STATUS_IMPLEMENTATION => self::JIRA_STATUS_IN_PROGRESS,
        Constants::ACTIVITY_LOGS_STATUS_QA => self::JIRA_STATUS_IN_PROGRESS,
        Constants::ACTIVITY_LOGS_STATUS_REVIEW => self::JIRA_STATUS_IN_PROGRESS,
        Constants::ACTIVITY_LOGS_STATUS_COMPLETED => self::JIRA_STATUS_CLOSED,
        Constants::ACTIVITY_LOGS_STATUS_BLOCKED => self::JIRA_STATUS_TODO,
    ];

    public const TRANSITIONS = [
        self::JIRA_STATUS_TODO => [
            self::JIRA_STATUS_IN_PROGRESS => '11',
            self::JIRA_STATUS_CLOSED => '51',
        ],
        self::JIRA_STATUS_IN_PROGRESS => [
            self::JIRA_STATUS_TODO => '21',
            self::JIRA_STATUS_CLOSED => '31',
        ],
        self::JIRA_STATUS_CLOSED => [
            self::JIRA_STATUS_IN_PROGRESS => '41',
            self::JIRA_STATUS_TODO => '61',
        ]
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 25/06/2017
 * Time: 15:43
 */

namespace App\Helpers;

class Constants
{
    const ADMIN_SLUG = "admin";
    const AUTH_LOGIN_LOG_NAME = 'login_logs';
    const AUTH_LOGOUT_LOG_NAME = 'logout_logs';
    const ADMIN_FAUCET_LOG_NAME = 'admin_faucet_logs';
    const USER_FAUCET_LOG_NAME = 'user_faucet_logs';
    const ADMIN_USER_MANAGEMENT_LOG = 'admin_user_management_logs';
    const USER_MANAGEMENT_LOG = 'user_management_logs';
    const ADMIN_PAYMENT_PROCESSOR_LOG = 'admin_payment_processor_logs';
    const DATE_FORMAT_DMY = 'd-m-Y';
    const TIME_FORMAT_HIS = 'H:i:s';
    const DATETIME_FORMAT_DMY_HIS = self::DATE_FORMAT_DMY .  ' ' . self::TIME_FORMAT_HIS;
    const FAUCET_NAME_PLACEHOLDER = "[faucet_name]";
    const FAUCET_URL_PLACEHOLDER = "[faucet_url]";
    const FAUCET_MIN_PAYOUT_PLACEHOLDER = "[faucet_min_payout]";
    const FAUCET_MAX_PAYOUT_PLACEHOLDER = "[faucet_max_payout]";
    const FAUCET_INTERVAL_PLACEHOLDER = "[faucet_interval]";
    const ALERT_TITLE_PLACEHOLDER = '[alert_title]';
    const ALERT_URL_PLACEHOLDER = '[alert_url]';
    const ALERT_SUMMARY_PLACEHOLDER = '[alert_summary]';
    const ALERT_PUBLISHED_AT_PLACEHOLDER = '[alert_published_at]';
    const ALERT_LOG_NAME = 'alert_management_logs';
}

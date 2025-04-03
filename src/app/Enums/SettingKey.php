<?php

namespace App\Enums;

use App\Enums\EnumTrait;

enum SettingKey: string
{
    use EnumTrait;

    ## Global 

    # Email Verification related keys
    case EMAIL_CONTACT_VERIFICATION     = "email_contact_verification";
    case VERIFY_EMAIL_ADDITIONAL_CHECKS = "verify_email_additional_checks";

    case INVALID_SYNTAX                 = "invalid_syntax";
    case INVALID_SYNTAX_MESSAGE         = "invalid_syntax_message";

    case INVALID_DOMAIN                 = "invalid_domain";
    case INVALID_DOMAIN_MESSAGE         = "invalid_domain_message";

    case DISPOSABLE_DOMAIN              = "disposable_domain";
    case DISPOSABLE_DOMAIN_LIST         = "disposable_domain_list";
    case DISPOSABLE_DOMAIN_MESSAGE      = "disposable_domain_message";

    case DOMAIN_TYPOS                   = "domain_typos";
    case DOMAIN_TYPO_MESSAGE            = "domain_typo_message";

    case COMMON_DOMAIN                  = "common_domain";

    case ROLE_BASED_EMAIL               = "role_based_email";
    case EMAIL_ROLE_LIST                = "email_role_list";
    case ROLE_BASED_MESSAGE             = "role_based_message";

    case CHECK_TLD                      = "check_tld";
    case TLD_LIST                       = "tld_list";
    case TLD_MESSAGE                    = "tld_message";

    # Contact Related Keys
    case CONTACT_META_DATA  = "contact_meta_data";

    # Admin

    # Setting Group 

    # Setting Sub-Group

    # Setting Keys

    // Array keys 
    case MEMBER_AUTHENTICATION  = "member_authentication";
    case SOCIAL_LOGIN_WITH      = "social_login_with";

    //Active or Inactive Keys
    case LOGIN                          = "login";
    case REGISTRATION                   = "registration";
    case MAINTENANCE_MODE               = "maintenance_mode";
    case ONBOARDING_BONUS               = "onboarding_bonus";
    case EMAIL_OTP_VERIFICATION         = "email_otp_verification";
    case REGISTRATION_OTP_VERIFICATION  = "registration_otp_verification";
    

    //Other Keys
    case SITE_NAME                  = "site_name";
    case AUTH_HEADING               = "auth_heading";
    case PAGINATE_NUMBER            = "paginate_number";
    case MAINTENANCE_MODE_MESSAGE   = "maintenance_mode_message";
    case ONBOARDING_BONUS_PLAN      = "onboarding_bonus_plan";
    
    
    # User Keys
    
    # Static values
    case SUPPORT_URL        = "https://support.igensolutionsltd.com/";
    case EMAIL_CONTACT      = "email_contact";
    case ALL                = "all";
    case ELEMENT_CONTENT    = "element_content";
    case ROUTE_USER_DASHBOARD = "/user/dashboard";

    # Events
}

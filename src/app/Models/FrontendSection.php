<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

class FrontendSection extends Model
{
    use HasFactory, Notifiable, Filterable;
    protected $guarded = [];

    protected static function booted() {

        static::creating(function (Model $model) {
            
            $model->uid = Str::uuid();
        });
    }
    const SERVICE_MENU_CONTENT       = 'service_menu.%.fixed_content';
    const SERVICE_MENU_MULTI_CONTENT = 'service_menu.%.multiple_static_content';
    const SERVICE_MENU_ELEMENT       = 'service_menu.%.element_content';

    const BANNER_CONTENT = 'banner.fixed_content';
    const BANNER_ELEMENT = 'banner.element_content';

    const CLIENT_CONTENT       = 'client.fixed_content';
    const CLIENT_MULTI_CONTENT = 'client.multiple_static_content';
    const CLIENT_ELEMENT       = 'client.element_content';

    const FEATURE_CONTENT       = 'feature.fixed_content';
    const FEATURE_MULTI_CONTENT = 'feature.multiple_static_content';
    const FEATURE_ELEMENT       = 'feature.element_content';

    const WORKFLOW_CONTENT       = 'workflow.fixed_content';
    const WORKFLOW_MULTI_CONTENT = 'workflow.multiple_static_content';
    const WORKFLOW_ELEMENT       = 'workflow.element_content';

    const FEEDBACK_CONTENT       = 'feedback.fixed_content';
    const FEEDBACK_MULTI_CONTENT = 'feedback.multiple_static_content';
    const FEEDBACK_ELEMENT       = 'feedback.element_content';

    const ADVANTAGE_CONTENT       = 'advantage.fixed_content';
    const ADVANTAGE_MULTI_CONTENT = 'advantage.multiple_static_content';
    const ADVANTAGE_ELEMENT       = 'advantage.element_content';

    const FAQ_CONTENT       = 'faq.fixed_content';
    const FAQ_MULTI_CONTENT = 'faq.multiple_static_content';
    const FAQ_ELEMENT       = 'faq.element_content';

    const USER_AUTH_CONTENT       = 'user_auth.fixed_content';
    const USER_AUTH_MULTI_CONTENT = 'user_auth.multiple_static_content';
    const USER_AUTH_ELEMENT       = 'user_auth.element_content';

    const PLAN_CONTENT       = 'plan.fixed_content';

    const PRICING_BREADCRUMB       = 'plan_breadcrumb.fixed_content';

    const ABOUT_BREADCRUMB       = 'about_breadcrumb.fixed_content';
    const ABOUT_OVERVIEW       = 'about_overview.fixed_content';

    const CONNECT_SECTION       = 'connect_section.fixed_content';
    const CONNECT_ELEMENT       = 'connect_section.element_content';

    const CONTACT_BREADCRUMB       = 'contact_breadcrumb.fixed_content';
    const GET_IN_TOUCH       = 'get_in_touch_section.fixed_content';

    const GATEWAY_CONTENT       = 'gateway.fixed_content';
    const GATEWAY_MULTI_CONTENT = 'gateway.multiple_static_content';
    const GATEWAY_ELEMENT       = 'gateway.element_content';

    const PAYMENT_GATEWAY_ELEMENT = 'payment.gateway.element_content';

    const FOOTER_CONTENT = 'footer.fixed_content';

    const SOCIAL_ICON = 'social_icon.element_content';
    
    const POLICY_PAGES = 'policy_pages.element_content';
    const POLICY_CONTENT = 'policy_pages.fixed_content';

    const BLOG = 'blog.fixed_content';
    const BLOG_BREADCRUMB = 'blog_breadcrumb.fixed_content';

    const SERVICE_MENU = 'service_menu.common.fixed_content';

    const UNSUBSCRIPTION_PAGE = 'unsubscription_page.fixed_content';

    protected $fillable = [
        'uid',
        'section_key',
        'section_value',
        'status',
    ];

    protected $casts = [
        'section_value' => 'json'
    ];
}

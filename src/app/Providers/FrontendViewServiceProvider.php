<?php

namespace App\Providers;

use App\Enums\StatusEnum;
use App\Models\Blog;
use App\Models\FrontendSection;
use App\Models\PricingPlan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\SMSlog;
use App\Models\EmailLog;

class FrontendViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        try {
            //Frontend
            $frontendContents = FrontendSection::latest()->get();

            $userId = null;

            if(Auth::check()){
                $userId = Auth::user()->id;
            }
            
           View::composer(['frontend.sections.topbar'], function ($view) use ($frontendContents) {
                $view->with([
                    'service_menu_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_menu\.[^.]+\.fixed_content$/', $item->section_key);
                    })->all(),
                    'service_menu_multi_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_menu\.[^.]+\.multiple_static_content$/', $item->section_key);
                    })->all(),
                    'service_menu_element' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_menu\.[^.]+\.element_content$/', $item->section_key);
                    })->all(),
                ]);
            });

            //Service Section Data
            View::composer(['frontend.service.section.breadcrumb_banner', ], function ($view) use ($frontendContents) {
                $view->with([
                    'service_breadcrumb_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_breadcrumb\.[^.]+\.fixed_content$/', $item->section_key);
                    })->all(),
                    'service_breadcrumb_multi_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_breadcrumb\.[^.]+\.multiple_static_content$/', $item->section_key);
                    })->all(),
                    'service_breadcrumb_element' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_breadcrumb\.[^.]+\.element_content$/', $item->section_key);
                    })->all(),
                ]);
            });

            View::composer(['frontend.service.section.overview', ], function ($view) use ($frontendContents) {

                $view->with([
                    'service_overview_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_overview\.[^.]+\.fixed_content$/', $item->section_key);
                    })->all(),
                    'service_overview_multi_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_overview\.[^.]+\.multiple_static_content$/', $item->section_key);
                    })->all()
                ]);
            });

            View::composer(['frontend.service.section.feature', ], function ($view) use ($frontendContents) {

                $view->with([
                    'service_feature_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_feature\.[^.]+\.fixed_content$/', $item->section_key);
                    })->all(),
                    'service_feature_multi_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_feature\.[^.]+\.multiple_static_content$/', $item->section_key);
                    })->all(),
                    'service_feature_element' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_feature\.[^.]+\.element_content$/', $item->section_key);
                    })->all(),
                ]);
            });

            View::composer(['frontend.service.section.details', ], function ($view) use ($frontendContents) {

                $view->with([
                    'service_details_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_details\.[^.]+\.fixed_content$/', $item->section_key);
                    })->all(),
                    'service_details_multi_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_details\.[^.]+\.multiple_static_content$/', $item->section_key);
                    })->all(),
                    'service_details_element' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_details\.[^.]+\.element_content$/', $item->section_key);
                    })->all(),
                ]);
            });

            View::composer(['frontend.service.section.highlight', ], function ($view) use ($frontendContents) {

                $view->with([
                    'service_highlight_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_highlight\.[^.]+\.fixed_content$/', $item->section_key);
                    })->all(),
                    'service_highlight_multi_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_highlight\.[^.]+\.multiple_static_content$/', $item->section_key);
                    })->all(),
                    'service_highlight_element' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_highlight\.[^.]+\.element_content$/', $item->section_key);
                    })->all(),
                ]);
            });



            View::composer(['frontend.sections.banner', ], function ($view) use ($frontendContents) {
                $view->with([
                    'users' => User::orderBy('id','DESC')->latest()->get(),
                    'social_icons'=> $frontendContents->where('section_key', FrontendSection::SOCIAL_ICON),
                    'banner_content'=> $frontendContents->where('section_key', FrontendSection::BANNER_CONTENT)->first(),
                    'banner_element'=> $frontendContents->where('section_key', FrontendSection::BANNER_ELEMENT),
                ]);
            });

            View::composer(['frontend.sections.client', ], function ($view) use ($frontendContents) {
                $view->with([
                    'client_content'=> $frontendContents->where('section_key', FrontendSection::CLIENT_CONTENT)->first(),
                    'client_multi_content'=> $frontendContents->where('section_key', FrontendSection::CLIENT_MULTI_CONTENT)->first(),
                ]);
            });

            View::composer(['frontend.sections.feature', ], function ($view) use ($frontendContents) {
                $view->with([
                    'feature_content'=> $frontendContents->where('section_key', FrontendSection::FEATURE_CONTENT)->first(),
                    'feature_multi_content'=> $frontendContents->where('section_key', FrontendSection::FEATURE_MULTI_CONTENT)->first(),
                    'feature_element'=> $frontendContents->where('section_key', FrontendSection::FEATURE_ELEMENT),
                ]);
            });

            View::composer(['frontend.sections.workflow', ], function ($view) use ($frontendContents) {
                $view->with([
                    'workflow_content'=> $frontendContents->where('section_key', FrontendSection::WORKFLOW_CONTENT)->first(),
                    'workflow_multi_content'=> $frontendContents->where('section_key', FrontendSection::WORKFLOW_MULTI_CONTENT)->first(),
                    'workflow_element'=> $frontendContents->where('section_key', FrontendSection::WORKFLOW_ELEMENT),
                ]);
            });

            View::composer(['frontend.sections.feedback', ], function ($view) use ($frontendContents) {
                $view->with([
                    'feedback_content'=> $frontendContents->where('section_key', FrontendSection::FEEDBACK_CONTENT)->first(),
                    'feedback_multi_content'=> $frontendContents->where('section_key', FrontendSection::FEEDBACK_MULTI_CONTENT)->first(),
                    'feedback_element'=> $frontendContents->where('section_key', FrontendSection::FEEDBACK_ELEMENT),
                ]);
            });

            View::composer(['admin.auth.feedback', ], function ($view) use ($frontendContents) {
                $view->with([
                    'feedback_content'=> $frontendContents->where('section_key', FrontendSection::FEEDBACK_CONTENT)->first(),
                    'feedback_multi_content'=> $frontendContents->where('section_key', FrontendSection::FEEDBACK_MULTI_CONTENT)->first(),
                    'feedback_element'=> $frontendContents->where('section_key', FrontendSection::FEEDBACK_ELEMENT),
                ]);
            });

            View::composer(['frontend.sections.advantage', ], function ($view) use ($frontendContents) {
                $view->with([
                    'advantage_content'=> $frontendContents->where('section_key', FrontendSection::ADVANTAGE_CONTENT)->first(),
                    'advantage_multi_content'=> $frontendContents->where('section_key', FrontendSection::ADVANTAGE_MULTI_CONTENT)->first(),
                    'advantage_element'=> $frontendContents->where('section_key', FrontendSection::ADVANTAGE_ELEMENT),
                ]);
            });

            View::composer(['frontend.sections.faq', ], function ($view) use ($frontendContents) {
                $view->with([
                    'faq_content'=> $frontendContents->where('section_key', FrontendSection::FAQ_CONTENT)->first(),
                    'faq_multi_content'=> $frontendContents->where('section_key', FrontendSection::FAQ_MULTI_CONTENT)->first(),
                    'faq_element'=> $frontendContents->where('section_key', FrontendSection::FAQ_ELEMENT),
                ]);
            });

            View::composer(['frontend.sections.plan', ], function ($view) use ($frontendContents) {

                $pricingPlans = PricingPlan::where('status', StatusEnum::TRUE->status())
                                                ->where('amount', '>=', 1)
                                                ->orderBy('amount', 'ASC')
                                                ->get();
                $recommendedStatusRow = $pricingPlans->firstWhere('recommended_status', StatusEnum::TRUE->status());
                if ($recommendedStatusRow) {
                    $pricingPlans = $pricingPlans->filter(function ($item) use ($recommendedStatusRow) {
                        return $item->id !== $recommendedStatusRow->id;
                    })->values();
                }
                if ($recommendedStatusRow) {
                    $pricingPlans->splice(1, 0, [$recommendedStatusRow]);
                }
                $view->with([
                    'plan_content' => $frontendContents->where('section_key', FrontendSection::PLAN_CONTENT)->first(),
                    'plans'        => $pricingPlans,
                ]);
            });

            View::composer(['frontend.sections.gateway', ], function ($view) use ($frontendContents) {
                $view->with([
                    'gateway_content'=> $frontendContents->where('section_key', FrontendSection::GATEWAY_CONTENT)->first(),
                    'gateway_multi_content'=> $frontendContents->where('section_key', FrontendSection::GATEWAY_MULTI_CONTENT)->first(),
                    'gateway_element'=> $frontendContents->where('section_key', FrontendSection::GATEWAY_ELEMENT),
                ]);
            });

            View::composer(['frontend.sections.footer', ], function ($view) use ($frontendContents) {
                $view->with([
                    'footer_content'=> $frontendContents->where('section_key', FrontendSection::FOOTER_CONTENT)->first(),
                    'social_element'=> $frontendContents->where('section_key', FrontendSection::SOCIAL_ICON),
                    'pages'=> $frontendContents->where('section_key', FrontendSection::POLICY_PAGES),
                ]);
            });

            View::composer(['frontend.pricing.section.breadcrumb_banner', ], function ($view) use ($frontendContents) {

                $view->with([
                    'pricing_content' => $frontendContents->where('section_key', FrontendSection::PRICING_BREADCRUMB)->first(),
                ]);
            });

            View::composer(['frontend.about.section.breadcrumb_banner', ], function ($view) use ($frontendContents) {

                $view->with([
                    'about_content' => $frontendContents->where('section_key', FrontendSection::ABOUT_BREADCRUMB)->first(),
                ]);
            });

            View::composer(['frontend.sections.unsubscribe-success', ], function ($view) use ($frontendContents) {

                $view->with([
                    'unsubscribe_content' => $frontendContents->where('section_key', FrontendSection::UNSUBSCRIPTION_PAGE)->first(),
                ]);
            });

            View::composer(['frontend.about.section.overview', ], function ($view) use ($frontendContents) {

                $view->with([
                    'about_overview' => $frontendContents->where('section_key', FrontendSection::ABOUT_OVERVIEW)->first(),
                ]);
            });
            
            View::composer(['frontend.about.section.connect', ], function ($view) use ($frontendContents) {

                $view->with([
                    'connect_content' => $frontendContents->where('section_key', FrontendSection::CONNECT_SECTION)->first(),
                    'connect_element' => $frontendContents->where('section_key', FrontendSection::CONNECT_ELEMENT),
                ]);
            });

            View::composer(['frontend.contact.section.breadcrumb_banner', ], function ($view) use ($frontendContents) {

                $view->with([
                    'contact_content' => $frontendContents->where('section_key', FrontendSection::CONTACT_BREADCRUMB)->first(),
                ]);
            });
            View::composer(['frontend.contact.section.get_in_touch', ], function ($view) use ($frontendContents) {

                $view->with([
                    'contact_content' => $frontendContents->where('section_key', FrontendSection::GET_IN_TOUCH)->first(),
                ]);
            });

            View::composer(['frontend.sections.blog', ], function ($view) use ($frontendContents) {

                $view->with([
                    'blog_content' => $frontendContents->where('section_key', FrontendSection::BLOG)->first(),
                    'blogs'        => Blog::where('status', StatusEnum::TRUE->status())->latest()->get()
                ]);
            });
            View::composer(['frontend.blog.section.breadcrumb_banner', ], function ($view) use ($frontendContents) {

                $view->with([
                    'blog_content' => $frontendContents->where('section_key', FrontendSection::BLOG_BREADCRUMB)->first(),
                ]);
            });

            View::composer(['frontend.sections.service', ], function ($view) use ($frontendContents) {

                $view->with([
                    'service_menu_common' => $frontendContents->where('section_key', FrontendSection::SERVICE_MENU)->first(),
                    'service_breadcrumb_content' => $frontendContents->filter(function ($item) {
                        return preg_match('/^service_breadcrumb\.[^.]+\.fixed_content$/', $item->section_key);
                    })->all(),
                ]);
            });

            View::composer(['frontend.policy.section.breadcrumb_banner', ], function ($view) use ($frontendContents) {

                $view->with([
                    'page_content' => $frontendContents->where('section_key', FrontendSection::POLICY_CONTENT)->first(),
                ]);
            });
            View::composer(['frontend.auth.partials.content', ], function ($view) use ($frontendContents) {
                $view->with([
                    'user_auth_content'=> $frontendContents->where('section_key', FrontendSection::USER_AUTH_CONTENT)->first(),
                    'user_auth_multi_content'=> $frontendContents->where('section_key', FrontendSection::USER_AUTH_MULTI_CONTENT)->first(),
                    'user_auth_element'=> $frontendContents->where('section_key', FrontendSection::USER_AUTH_ELEMENT),
                ]);
            });

            
        }catch (\Exception $exception){

        }

    }
}

<?php
namespace App\Http\Controllers\Admin\Core;

use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Contact;
use App\Models\User;
use App\Models\Subscription;
use App\Models\CommunicationLog;
use App\Models\AndroidApiSimInfo;
use App\Models\PaymentLog;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Gateway;
use App\Service\Admin\Core\FileService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard() {

        Session::put("menu_active", false);
        $title       = translate("Welcome Back").", ". auth()->guard('admin')->user()->username;
        $customers   = User::where('status','!=','3')
                        ->orderBy('id', 'DESC')
                        ->take(site_settings("paginate_number"))
                        ->get();

        $paymentLogs = PaymentLog::orderBy('id', 'DESC')
                        ->where('status', '!=', 0)
                        ->with('user', 'paymentGateway','paymentGateway')
                        ->take(site_settings("paginate_number"))
                        ->get();
        $logs = [
            "sms" => [

                'all'     => CommunicationLog::where('type', ServiceType::SMS->value)->count(),
                'success' => CommunicationLog::where('type', ServiceType::SMS->value)->where('status', CommunicationStatusEnum::DELIVERED->value)->count(),
                'pending' => CommunicationLog::where('type', ServiceType::SMS->value)->where('status', CommunicationStatusEnum::PENDING->value)->count(),
                'failed'  => CommunicationLog::where('type', ServiceType::SMS->value)->where('status', CommunicationStatusEnum::FAIL->value)->count(),
            ],
            "email" => [

                'all'     => CommunicationLog::where('type', ServiceType::EMAIL->value)->count(),
                'success' => CommunicationLog::where('type', ServiceType::EMAIL->value)->where('status', CommunicationStatusEnum::DELIVERED->value)->count(),
                'pending' => CommunicationLog::where('type', ServiceType::EMAIL->value)->where('status', CommunicationStatusEnum::PENDING->value)->count(),
                'failed'  => CommunicationLog::where('type', ServiceType::EMAIL->value)->where('status', CommunicationStatusEnum::FAIL->value)->count(),
            ],
            "whats_app" => [
                
                'all'     => CommunicationLog::where('type', ServiceType::WHATSAPP->value)->count(),
                'success' => CommunicationLog::where('type', ServiceType::WHATSAPP->value)->where('status',  CommunicationStatusEnum::DELIVERED->value)->count(),
                'pending' => CommunicationLog::where('type', ServiceType::WHATSAPP->value)->where('status', CommunicationStatusEnum::PENDING->value)->count(),
                'failed'  => CommunicationLog::where('type', ServiceType::WHATSAPP->value)->where('status', CommunicationStatusEnum::FAIL->value)->count(),
            ]
        ];
        [$paymentReport, $paymentReportMonths] = $this->paymentReport();
        $smsWhatsAppReport                     = $this->smsWhatsAppReports();
        
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        $totalUsers = User::where('created_at', '>=', $sixMonthsAgo)->get();
        $subscribers = Subscription::where('created_at', '>=', $sixMonthsAgo)
                                        ->where(function($query) {
                                            $query->where('status', SubscriptionStatus::RUNNING->value)
                                                    ->orWhere('status', SubscriptionStatus::RENEWED->value);
                                        })->get();
                                
        $userData = [];
        foreach ($totalUsers as $user) {
            $createdAt = Carbon::parse($user->created_at)->toDateString();
            if (!isset($userData[$createdAt])) {
                $userData[$createdAt] = 0;
            }
            $userData[$createdAt]++;
        }

        $subscriptionData = [];
        foreach ($subscribers as $subscriber) {
            $createdAt = Carbon::parse($subscriber->created_at)->toDateString();
            if (!isset($subscriptionData[$createdAt])) {
                $subscriptionData[$createdAt] = 0;
            }
            $subscriptionData[$createdAt]++;
        }

        $totalUser = [
            'dates' => array_keys($userData),
            'newUsers' => array_values($userData),
            'subscriptions' => array_values($subscriptionData),
        ];

        return view('admin.dashboard', compact(
            'title',
            'customers',
            'paymentLogs',
            'logs',
            'totalUser',
            'paymentReport',
            'paymentReportMonths',
            'smsWhatsAppReport',
        ));
    }

    /**
     * @return View
     */
    public function profile(Request $request): View {

        Session::put("menu_active", false);
        $activeTab = $request->session()->get('active_tab', 'details');
        $title     = translate("Admin Information");
        $admin     = auth()->guard('admin')->user();
        return view('admin.profile', compact('title', 'admin', 'activeTab'));
    }

    /**
     * @throws ValidationException
     */
    public function profileUpdate(AdminProfileRequest $request) {

        $status = 'error';
        $message = 'Something went wrong';
        try {
            Session::put("menu_active", false);
            $fileService = new FileService();
            
            $admin = Auth::guard('admin')->user();
            $admin->name = $request->input('name');
            $admin->username = $request->input('username');
            $admin->email = $request->input('email');

            if ($request->hasFile('image')) {
                
                $admin->image = $fileService->uploadFile($request->file('image'), "admin_profile");
            }
            $admin->save();
            $status  = 'success';
            $message = translate('Your profile has been updated.');
            
        } catch (\Exception $e) {
            
            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return tabId('details', redirect()->back()->withNotify($notify));
    }

    public function passwordUpdate(Request $request) {

        try {
            Session::put("menu_active", false);
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:5|confirmed',
            ]);

            $admin = Auth::guard('admin')->user();

            if (!Hash::check($request->current_password, $admin->password)) {

                $notify[] = ['error', 'Password do not match !!'];
                return tabId('passwordUpdate', redirect()->back()->withNotify($notify));
            }

            $admin->password = Hash::make($request->password);
            $admin->save();
            $notify[] = ['success', 'Password changed successfully.'];
            return tabId('passwordUpdate', redirect()->back()->withNotify($notify));

        } catch (\Exception $e) {

            $notify[] = ['error', 'Something went wrong.'];
            return tabId('passwordUpdate', redirect()->back()->withNotify($notify));
        }
    }



    
    public function smsWhatsAppReports(): array {

        $smsWhatsAppReports = [
            'sms' => [],
            'whatsapp' => [],
            'email' => [],
        ];

        for ($i = 0; $i < 12; $i++) {
            $date = now()->subMonths($i);
            $month = $date->format('M');

            $smsCount = CommunicationLog::where('type', ServiceType::SMS->value)->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $emailCount = CommunicationLog::where('type', ServiceType::EMAIL->value)->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $whatsappCount = CommunicationLog::where('type', ServiceType::WHATSAPP->value)->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            array_unshift($smsWhatsAppReports['sms'], $smsCount);
            array_unshift($smsWhatsAppReports['whatsapp'], $whatsappCount);
            array_unshift($smsWhatsAppReports['email'], $emailCount);
        }

        return $smsWhatsAppReports;
    }


    private function paymentReport(): array
    {
        $paymentReport = [
            'amount' => [],
            'charge' => [],
            'month' => [],
        ];

        for ($i = 0; $i < 12; $i++) {
            $date = now()->subMonths($i);
            $month = $date->format('M');

            $paymentData = PaymentLog::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->get();

            $totalAmount = $paymentData->sum('amount');
            $totalCharge = $paymentData->sum('charge');

            array_unshift($paymentReport['amount'], $totalAmount);
            array_unshift($paymentReport['charge'], $totalCharge);
            array_unshift($paymentReport['month'], $month);
        }

        $quotedMonthsArray = array_map(function ($month) {
            return '"' . $month . '"';
        }, $paymentReport['month']);

        $paymentReportMonths = implode(',', $quotedMonthsArray);

        return [$paymentReport,$paymentReportMonths];
    }

    

    

   

    public function generateApiKey()
    {
        $title = "Generate Api Key";
        $admin = Auth::guard('admin')->user();
        return view('admin.generate_api_key', compact('title', 'admin'));
    }

    public function saveGenerateApiKey(Request $request): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        $admin->api_key  = $request->has('api_key') ? $request->input('api_key') : $admin->api_key ;
        $admin->save();

        return response()->json([
            'message' => 'New Api Key Has been Generate'
        ]);
    }


    public function selectSearch(Request $request){
        
        $searchData = trim($request->term);
        $contacts   = Contact::select('id','email_contact as text')->whereNull('user_id')->with('group')->where('email_contact','LIKE',  '%' . $searchData. '%')->latest()->simplePaginate(10);
        $pages      = true;

        if (empty($contacts->nextPageUrl())) {

            $pages = false;
        }
        $results = array(
          "results" => $contacts->items(),
          "pagination" => array(
            "more" => $pages
          )
        );

        return response()->json($results);
    }
    

    public function selectGateway(Request $request, $type = null) {

        $rows = [];

        if($type == "sms" || $type == "email") {
            
            $rows = Gateway::whereNull('user_id')->where('type', $request->type)->latest()->get();

        } elseif($type == "android") {

            $rows = AndroidApiSimInfo::where('android_gateway_id', $request->type)->latest()->get();
        } 
        return response()->json($rows);
    }
}

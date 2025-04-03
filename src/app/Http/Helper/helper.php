<?php

use App\Enums\AndroidApiSimEnum;
use App\Enums\CampaignRepeatEnum;
use App\Enums\CampaignStatusEnum;
use App\Enums\CommunicationStatusEnum;
use App\Enums\ContactAttributeEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\PriorityStatusEnum;
use App\Enums\ServiceType;
use App\Enums\SettingKey;
use App\Enums\StatusEnum;
use App\Enums\SubscriptionStatus;
use App\Enums\TicketStatusEnum;
use App\Models\PricingPlan;
use App\Models\Setting;
use App\Models\Translation;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

	if(!function_exists('optimize_clear')) {

		function optimize_clear() {

			Artisan::call('optimize:clear');
		}
	}

	if(!function_exists('transformToCamelCase')) {

		function transformToCamelCase($string) {

			$string = preg_replace('/[\d_]/', '', $string);
			$camelCaseString = strtolower($string);
			$camelCaseString = str_replace('_', ' ', $camelCaseString);
			$camelCaseString = ucwords($camelCaseString);
			$camelCaseString = str_replace(' ', '', $camelCaseString);
			$camelCaseString = lcfirst($camelCaseString);
			return $camelCaseString;
		}
	}
	
	if (!function_exists('site_settings')) {
    
		function site_settings(string $key = null, mixed $default = null): string|array|null {
			
			$settings = Cache::remember("site_settings", 24 * 60, function () {
				
				return Setting::pluck("value", 'key')->toArray();
			});
			try {

				if (isset($settings[$key]) || isset(config('site_settings')[$key])) {
					return Arr::get($settings, $key, config('site_settings')[$key] ?? trans('default.no_result_found'));
				}
			} catch (\Throwable $th) {
			}
			return $default;
		}
	}

	if (!function_exists('paginateNumber')) {

		function paginateNumber($number = 7) { 

			return $number;
		}
	}

	if (!function_exists('filterContactNumber')) {

		function filterContactNumber($contact): string {

			return preg_replace('/[^0-9]/', '', trim(str_replace('+', '', $contact)));
		}
	}
    
	if (!function_exists('filterContactNumber')) { 

		function build_post_fields( $data,$existingKeys='',&$returnArray=[]) {
			if(($data instanceof CURLFile) or !(is_array($data) or is_object($data))) {
				$returnArray[$existingKeys]=$data;
				return $returnArray;
			}
			else{
				foreach ($data as $key => $item) {
					build_post_fields($item,$existingKeys?$existingKeys."[$key]":$key,$returnArray);
				}
				return $returnArray;
			}
		}
	}
	
	function filePath(): array {
	    $path['profile'] = [
	        'admin'=> [
	            'path'=>'assets/file/dashboard/image/profile',
	            'size'=>'400x400'
	        ],
	        'user'=> [
	            'path'=>'assets/file/images/user/profile',
	            'size'=>'400x400'
        	],
	    ];
		$path["contact"] = [
			'path'=>'assets/file/contact/temporary',
		];
        $path['import'] = [
            'path'=>'assets/file/import',
        ];
	    $path['payment_file'] = [
	        'path' => 'assets/file/payment/data',
	    ];
	    $path['email_uploaded_file'] = [
	        'path' => 'assets/file/email_uploaded_file',
	    ];
	    $path['payment_method'] = [
            'path'=>'assets/file/images/payment_method',
            'size'=>'600x600'
	    ];
	    $path['panel_logo'] = [
	        'path' => 'assets/file/images/logoIcon',
			'size'=>'1200x400'
	    ];
	    $path['site_logo'] = [
	        'path' => 'assets/file/images/logoIcon',
			
	    ];
	    $path['admin_bg'] = [
	        'path' => 'assets/file/images/adminBg',
	    ];
	    $path['admin_card'] = [
	        'path' => 'assets/file/images/adminCard',
	    ];
        $path['frontend'] = [
            'path' => 'assets/file/images/frontend',
        ];
	    $path['ticket'] = [
	        'path' => 'assets/file/ticket',
	    ];
	    $path['favicon'] = [
	        'size' => '128x128',
	    ];
	    $path['site_icon'] = [
	        'size' => '100x100',
	    ];
	    $path['demo'] = [
            'path'=>'assets/file/sms',
            'path_email'=>'assets/file/email',
            'path_whatsapp'=>'assets/file/whatsapp',
	    ];
		$path['whatsapp'] = [
            'path_document'=>'assets/file/whatsapp/document',
            'path_audio'=>'assets/file/whatsapp/audio',
            'path_image'=>'assets/file/whatsapp/image',
            'path_video'=>'assets/file/whatsapp/video',
            'path_others'=>'assets/file/whatsapp/others',
	    ];
	    return $path;
	}

	function menuActive($routeName, $type = null): string {

		if (is_array($routeName) &&  in_array(Route::currentRouteName(), $routeName)) {
			
			return 'active';
		} else {
			if (request()->routeIs($routeName)) {
				
				return 'active';
			} else {
			}
		}
		
		return '';
	}

	function menuShow($routeName, $type = null): string {

		if (is_array($routeName) && in_array(Route::currentRouteName(), $routeName)) {
			
			return 'show';
		} else {
			
			if (request()->routeIs($routeName)) {
				
				return 'show';
			} else {
			}
		}
		return '';
	}

	function shortAmount($amount, $length = 2) {

        return round($amount, $length);
	}

	function diffForHumans($date): string {

	    return Carbon::parse($date)->diffForHumans();
	}


	function getDateTime($date, $format = 'Y-m-d h:i A')
	{
	    return Carbon::parse($date)->translatedFormat($format);
	}

	function slug($name): string
    {
	   	return Str::slug($name);
	}

	function trxNumber(): string
    {
		$random = strtoupper(Str::random(10));
		return $random;
	}

	function randomNumber(): int
    {
		return mt_rand(1,10000000);
	}

	function uploadNewFile($file, $location, $old = null): string
    {
	   	if(!file_exists($location)){
			mkdir($location, 0777, true);
		}
	    if(!$location) throw new Exception('File could not been created.');
	    if ($old) {
	    	if(file_exists($location.'/'.$old) && is_file($location.'/'.$old)){
				@unlink($old.'/'.$old);
			}
	    }
	    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
	    $file->move($location,$filename);
	    return $filename;
	}

	function showImage($image, $size=null): string
    {
	    if(file_exists($image) && is_file($image)) {
			
	        return asset($image);
	    }
	    if($size) {
	        return route('default.image',$size);
	    } else {
			return (asset('assets/file/default.jpg'));
		}
	   
	}

	function number($amount, $length = 2)
    {
	    $amount = round($amount, $length);
	    return $amount;
	}

	function textSorted($text): string
    {
	    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
	}

	function limit($text, $length): string
    {
		$value = Str::limit($text, $length);
		return $value;
	}

	function serverExtensionCheck($name): bool
    {
        if (!extension_loaded($name)) {
            return $response = false;
        }else {
            return $response = true;
        }
    }

	function checkFolderPermission($name): bool
    {
		$perm = substr(sprintf('%o', fileperms($name)), -4);
		if ($perm >= '0775') {
			$response = true;
		} else {
			$response = false;
		}
		return $response;
	}

	function  charactersLeft()
	{
		$user = auth()->user();
		return $user->credit * 160;
	}

	function  charactersLeftWa()
	{
		$user = auth()->user();
		return $user->whatsapp_credit * 320;
	}


	function curlContent($url): bool|string
    {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    return $result;
	}


	function labelName($text): string
    {
	    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
	}


	function uploadImage($file, $location, $size = null, $old = null, $thumb = null): string
    {
	    if(!file_exists($location)){
			mkdir($location, 0755, true);
		}
		if($old){
			if(file_exists($location.'/'.$old) && is_file($location.'/'.$old)){
				@unlink($location.'/'.$old);
			}
		}
	    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
	    $image = Image::make($file);
	    if ($size) {
	        $size = explode('x', strtolower($size));
	        $image->resize($size[0], $size[1]);
	    }
	    $image->save($location . '/' . $filename);
	    if ($thumb) {
	        $thumb = explode('x', $thumb);
	        Image::make($file)->resize($thumb[0], $thumb[1])->save($location . '/thumb_' . $filename);
	    }
	    return $filename;
	}


	if (!function_exists('tabId')) {

		function tabId($id, $instance = null) {
	
			session(['active_tab' => $id]);
	
			if ($instance instanceof Redirector) {

				return $instance->to(app('url')->previous());
			} elseif ($instance instanceof RedirectResponse) {

				$instance->setTargetUrl(app('url')->previous());
				return $instance;
			}
	
			return redirect()->back();
		}
	}

	if (!function_exists('get_status_bg')) {
        function get_status_bg($status)
        {
            $status = strtolower($status);
            switch ($status) {
                case 'all':
                    $value = "<span class=\"badge badge-soft-all align-middle\">
                                <i class=\"bi bi-check-circle me-1\"></i> All
                              </span>";
                    return $value;
                    break;
                case ($status == 'success' || $status == 'completed' ):
                    $status = ucFirst($status);
                    $value = "<span class=\"badge badge-soft-success align-middle\">
                                <i class=\"bi bi-check-circle me-1\"></i> $status
                              </span>";
                    return $value;
                    break;
                case ($status == 'active' || $status == 'yes' ) :
                    $status = ucFirst($status);
                    $value = "<span class=\"badge badge-soft-success align-middle\">
                                <i class=\"bi bi-check-circle me-1\"></i>    $status
                              </span>";
                    return $value;
                    break;
                case 'pending':
                    $value = "<span class=\"badge badge-soft-warning align-middle\">
                                <i class=\"bi bi-check2-all me-1\"></i> Pending
                              </span>";
                    return $value;
                    break;
                case ($status == 'processing' || $status == 'ongoing' ):
                    $status = ucFirst($status);
                    $value = "<span class=\"badge badge-soft-info align-middle\">
                                <i class=\"bi bi-capslock me-1\"></i> $status
                              </span>";
                    return $value;
                    break;
                case ($status == 'failed' || $status == 'fail' || $status == 'no' ):
                    $status = ucFirst($status);
                    $value = "<span class=\"badge badge-soft-danger align-middle\">
                                <i class=\"bi bi-exclamation-octagon me-1\"></i>  $status
                              </span>";
                    return $value;
                    break;

                case  'schedule':
                    $status = ucFirst($status);


                    $value = "<span class=\"badge badge-soft-danger align-middle\">
                                <i class=\"bi bi-exclamation-octagon me-1\"></i> $status
                                </span>";
                    return $value;
                    break;

                case  'deactive':
                    $status = ucFirst($status);


                    $value = "<span class=\"badge badge-soft-danger align-middle\">
                                <i class=\"bi bi-exclamation-octagon me-1\"></i> Inactive
                              </span>";
                    return $value;
                    break;

                default:
                    $value = "<span class=\"badge badge-soft-secondary align-middle\">
                                <i class=\"bi bi-exclamation-triangle me-1\"></i> Undefined
                              </span>";
                    return $value;
                    break;
            }
        }
    }


	/**
     * current months total day
     */
    function days_in_month($month,$year){
		return cal_days_in_month(CAL_GREGORIAN, $month,$year);
	}

	 /**
	  * current months total day
	  */
	  function days_in_year(){
		 $year = date("Y");
		 $days=0;
		 for($month=1;$month<=12;$month++){
			 $days = $days + days_in_month($month,$year );
		 }
		 return $days;
	  }


	function buildDomDocument($text)
	{
	    $dom = new \DOMDocument();
	    libxml_use_internal_errors(true);
	    $dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $text);
	    libxml_use_internal_errors(false);
	    $imageFile = $dom->getElementsByTagName('img');
        if ($imageFile) {
            foreach($imageFile as $item => $image){
                $data = $image->getAttribute('src');
                $check_b64_data = preg_match("/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).base64,.*/", $data);
                if ($check_b64_data) {
                    list($type, $data) = explode(';', $data);
                    list(, $data)      = explode(',', $data);
                    $imgeData = base64_decode($data);
                    $image_name= time().$item.'.png';
                    $save_path       = filePath()['email_uploaded_file']['path'];
                    try {
						if (!file_exists($save_path)) {
							mkdir($save_path, 0777, true);
						}
                        Image::make($imgeData)->save($save_path.'/'.$image_name);
                        $getpath = asset('assets/file/email_uploaded_file/'.$image_name);
                        $image->removeAttribute('src');
                        $image->setAttribute('src', $getpath);
                    } catch (Exception $e) {
						
                    }
                }
            }
        }
	    $html = $dom->saveHTML();

		$html = html_entity_decode($html, ENT_COMPAT, 'UTF-8');
	    return $html;
	}


	if (!function_exists('carbon')) {
		/**
		 * @param string|null $date
		 * @return Carbon
		 */
		function carbon(?string $date = null): Carbon
		{
			if (!$date) {
				return Carbon::now();
			}

			return (new Carbon($date));
		}
	}

    if (!function_exists('translate')){

		function translate(string | null $keyWord, string $lang_code = null) :string
		{
		   try {
			  $lang_code = $lang_code ? $lang_code : App::getLocale();
			  $lang_key = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', strtolower($keyWord)));
			  $translate_data = Cache::remember('translations-'.$lang_code,now()->addHour(), function () use($lang_code) {
				 return Translation::where('code', $lang_code)->pluck('value', 'key')->toArray();
			  });
  
			  if (!array_key_exists($lang_key,$translate_data)) {
				 $translate_val = str_replace(array("\r", "\n", "\r\n"), "", $keyWord);
				 Translation::create([
					'code'=>$lang_code,
					'key'=> $lang_key,
					'value'=> $translate_val
				 ]);
				 $keyWord = $translate_val;
				 Cache::forget('translations-'.$lang_code);
			  }
  
			  else{
				 $keyWord = $translate_data[$lang_key];
			  }
  
		   } catch (\Throwable $th) {
  
		   }
  
		   return ucwords(strip_tags($keyWord));
		}
	 }


/**
 * @param $langCode
 * @return bool|string
 */
    function getLangFile($langCode): bool|string
    {
        return file_get_contents(resource_path(config('constants.options.langFilePath')). $langCode.'.json');
    }


    /**
     *
     */
    function offensiveMsgBlock($requestMessage)
	{
		$path = base_path('lang/globalworld/offensive.json');
        $offensiveData = json_decode(file_get_contents($path), true);
		$message = explode(' ', $requestMessage);
		foreach ($offensiveData as $key => $value) {
			foreach($message as $msgKey => $item){
				if(strtolower($item) == strtolower($key)){
					$message[$msgKey] = $value;
					Session::put('offsensiveNotify', "& We found some offsensive word");
				}
			}
		}
		$message = implode(' ',$message);

		return $message;
	}

    function download_from_url(string $url, string $prefix = ''): ?string
    {
        if (! $stream = @fopen($url, 'r')) {
            throw new \Exception('Can not open file from ' . $url);
        }

        $tempFile = tempnam(sys_get_temp_dir(), $prefix);

        if (file_put_contents($tempFile, $stream)) {
            return $tempFile;
        }

        return null;
    }

	function logStatus($status)
	{
        switch ($status) {
            case 1:
                $status = 'Pending';
                break;
            case 3:
                $status = 'Fail';
                break;
			case 4:
				$status = 'Success';
				break;
            default:
                $status = 'Schedule';
                break;
        }
	    return $status;
	}



    function convertTime($seconds): string
    {
		$hours = floor($seconds / 3600);
		$minutes = floor(($seconds % 3600) / 60);
		$seconds = $seconds % 60;

		$result = '';

		if ($hours > 0) {
		    $result .= $hours . ' hour';
		    if ($hours > 1) {
		      $result .= 's';
		    }
		    $result .= ' ';
		}

		if ($minutes > 0) {
		    $result .= $minutes . ' minute';
		    if ($minutes > 1) {
		      $result .= 's';
		    }
		    $result .= ' ';
		}

		if ($seconds > 0) {
		    $result .= $seconds . ' second';
		    if ($seconds > 1) {
		      $result .= 's';
		    }
		}

		return $result;
	}


	if (!function_exists('subscription_status')) { 

		function getFrontendSection($default = false)
		{
			$jsonUrl 	= resource_path('data/frontend_section.json');
			$sections = json_decode(file_get_contents($jsonUrl), true);
			$formattedSections = [];
			foreach ($sections as $key => $value) {
				$newKey = textFormat(['_'], $key, '-'); 
				$formattedSections[strtolower($newKey)] = $value;  
			}
			if ($default) ksort($formattedSections);
			return $formattedSections;
		}
	}


    function setInputLabel(string $text): string
    {
        $text = preg_replace('/[^A-Za-z0-9 ]/', ' ', $text);
        return ucfirst($text);
    }

    function getArrayValue($arr,  $key ="", $default = [])
    {
        return \Illuminate\Support\Arr::get((array)$arr, $key, '');
    }

    function getTranslatedArrayValue($arr,  $key ="", $default = [])
    {
        return translate(\Illuminate\Support\Arr::get((array)$arr, $key, ''));
    }

	if (!function_exists('str_unique')) {
        /**
         * @param int $length
         * @return string
         */
        function str_unique(int $length = 30): string
        {
            $side = rand(0,1);
            $salt = rand(0,9);
            $len = $length - 1;
            $string = \Illuminate\Support\Str::random($len <= 0 ? 7 : $len);
            $separatorPos = (int) ceil($length/4);
            $string = $side === 0 ? ($salt . $string) : ($string . $salt);
            $string = substr_replace($string, '-', $separatorPos, 0);
            return substr_replace($string, '-', negative_value($separatorPos), 0);
        }
    }
	if (!function_exists('negative_value')) {
        /**
         * @param int|float $value
         * @param $float
         * @return int|float
         */
        function negative_value(int|float $value, $float = false): int|float
        {
            if ($float) {
                $value = (float) $value;
            }
            return 0 - abs($value);
        }
    }

	if(!function_exists('convert_unit')) {
		
		/**
         * @param int|float $value
         * @param $float
         * @return string
         */

		 function convert_unit(int|float $bytes, $decimals = 2) {
			$size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
		
			$factor = floor((strlen($bytes) - 1) / 3);
		
			return sprintf("%.{$decimals}f", $bytes / (1024 ** $factor)) . @$size[$factor];
		}
	}

	if(!function_exists('planAccess')) {

		/**
         * @param mixed $user
         * @return mixed
         */

		function planAccess(mixed $user) {

			$plan_type = [
				"user"  => StatusEnum::FALSE->status(),
				"admin" => StatusEnum::TRUE->status()
			];

			$gateway_type = array_keys(config("planaccess.pricing_plan"));
          
			$user_plan = $user->runningSubscription()?->currentPlan();
			
			if($user_plan) { 
				$allowed_access = [];
				$allowed_access["type"] = $user_plan->type;
					
				foreach((array)$gateway_type as $gateway) {

					$gateway_info = (array)$user_plan->$gateway;
					

					if(array_key_exists("android", $gateway_info)){

						$android_data = (array)$gateway_info["android"];
						if($allowed_access["type"] == StatusEnum::TRUE->status()) {
							unset($android_data["gateway_limit"]);
						}
						$allowed_access["android"] = $android_data;
						unset($gateway_info["android"]);
						
					}
				
					unset($gateway_info["credits"]);
					$allowed_access[$gateway] = $gateway_info;
				}
				return $allowed_access;
			} else {
            	return [];
            }
		}
	}

	if(!function_exists('checkCredit')) {

		/**
         * @param mixed $user
		 * 
		 * @param string $service_type
		 * 
         * @return boolean
         */

		function checkCredit(mixed $user, string $service_type, $credit_count = 1) {
            if (!$user) {
                return false; // or handle the null case as appropriate
            }

            $column = $service_type."_credit";
            $pass = true;

            if ($user->{$column} && (int)$user->{$column} < $credit_count && (int)$user->{$column} != -1) {
                $pass = false;
            }

            return $pass;
        }

	}

	if (!function_exists('code_correction')){
		function code_correction(string $code):string
		{
			if(!stripos($code, "#")) {
				$code = "#".$code;
			}
			
			return  "$code";
		}
	}

	if(!function_exists("nameSplit")) {
		function nameSplit(string $fullName = null):array {
			$data = [];
			$matches = [];
			if (preg_match('/^([a-z]+\.)?\s*?(\S+)(?:\s*?(\S*))?$/i', $fullName, $matches)) {
				$prefix = isset($matches[1]) ? $matches[1] : '';
				$firstName = $matches[2];
				$lastName = isset($matches[3]) ? $matches[3] : '';
			} else {
			
				$prefix = '';
				$firstName = $fullName;
				$lastName = '';
			}

			$data["prefix"]    = $prefix;
			$data["first_name"] = $firstName;
			$data["last_name"]  = $lastName;
		
			return $data;
		}
	}

	if(!function_exists("textFormat")) {

		function textFormat(array $symbols = null, $data, string $replace_with = null) {
			
			
			$convertedString = ucwords(str_replace($symbols, $replace_with ? $replace_with : ' ', $data));
			
			return $convertedString;
		}
	}

	if(!function_exists("slice_array_pagination")) {
		function slice_array_pagination(array $data = null) {
			
			$data = new Illuminate\Pagination\LengthAwarePaginator(
                array_slice($data ?? [], (request()->get('page', 1) - 1) * paginateNumber(site_settings("paginate_number")), paginateNumber(site_settings("paginate_number"))),
                count($data ?? []),
                paginateNumber(site_settings("paginate_number")),
                request()->get('page', 1),
                ['path' => url()->current()]
            );
			return $data;
		}
	}

	if(!function_exists("generateText")) {
		function generateText($type) {
			
            $words = [
                "first_name" => ['David', 'charlie', 'tony', 'steve', 'natasha', 'walter', 'jesse'],
                "last_name" => ['warner', 'nelson', 'murdock', 'adams'],
                "object" => ['bus', 'flower', 'house', 'ball', 'keys'],
                "email" => ['random61@mail.com', 'random45@mail.com', 'random43@mail.com', 'random42@mail.com', 'random41@mail.com'],
            ];

			$extract = $words[$type];
            shuffle($extract);
            return ucfirst($extract[0]);
        }
	}

	if(!function_exists("generateDemoFile")) {

		function generateDemoFile($type, $conditionExlude = [], $allow_attribute = false) {

            
        }
	}



	if(!function_exists("textSpinner")) {

		function textSpinner(string $text) {

			$pattern = '/{([^{}]+)}/';
			while (preg_match($pattern, $text)) {
				$text = preg_replace_callback($pattern, function ($matches) {
					$options = explode('|', $matches[1]);

					return $options[ random_int(0, count($options) - 1) ];
				}, $text);
			}
			
			return $text;
		}
	}

	if (!function_exists("get_property_key")) {

		function get_property_key($needle, $haystack){
			return array_search(strtolower($needle), array_map('strtolower', $haystack));
		}
	}

	if (!function_exists("storeCloudMediaAndGetLink")) {

		function storeCloudMediaAndGetLink($requestKey, $file) {

			$fileType = explode('_', $requestKey)[0];
			$fileFieldType = explode('_', $requestKey)[1] . '_' . explode('_', $requestKey)[2];

			$directory = '';
			switch ($fileType) {
				case 'image':
					$directory = storage_path('../../assets/file/cloud_api/header/image');
					break;
				case 'video':
					$directory = storage_path('../../assets/file/cloud_api/header/video');
					break;
				case 'document':
					$directory = storage_path('../../assets/file/cloud_api/header/document');
					break;
				default:
					return null;
			}
			
			if (!File::isDirectory($directory)) {
				File::makeDirectory($directory, 0755, true, true);
			}

			$fileName = $file->getClientOriginalName();
			$filePath = $file->move($directory, $fileName);
			
			return asset('storage/assets/file/cloud_api/header/' . $fileType . '/' . $fileName);
		}
	}

	/**
	 *
	 * @param float $number
	 * @param int $precision
	 * @return string
	 */
	if(!function_exists("formatNumber")) {
		
		function formatNumber($number, $precision = 2) {

			if (!is_numeric($number)) {
				return "Invalid number format";
			}

			$number = (float) $number;
			$negative = $number < 0;
			$number = abs($number);

			if ($number < 1000) {
				return ($negative ? "-" : "") . round($number, $precision);
			}

			$formatters = [
				1000000000000000000000000 => ['Y', 1000000000000000000000000],
				1000000000000000000000 => ['Z', 1000000000000000000000],
				1000000000000000 => ['E', 1000000000000000],
				1000000000000 => ['P', 1000000000000],
				1000000000 => ['T', 1000000000],
				1000000 => ['B', 1000000],
				1000 => ['K', 1000],
			];

			foreach ($formatters as $divisor => $formatter) {
				list($suffix, $divisor) = $formatter;
				if ($number >= $divisor) {
					$formattedNumber = round($number / $divisor, $precision);
					return ($negative ? "-" : "") . $formattedNumber . $suffix;
				}
			}

			return ($negative ? "-" : "") . round($number, $precision);
		}
	}

	/**
	 * Truncate a string to a specified length and append an ellipsis if necessary.
	 *
	 * @param string 
	 * @param int
	 * @return string 
	 */
	if (!function_exists('truncate_string')) {
		
		function truncate_string($string, $length = 20) {
			
			if (strlen($string) > $length) {
				return substr($string, 0, $length) . '...';
			}
			return $string;
		}
	}

	/**
	 * 
	 * @param array 
	 * @return string|null 
	 */
	if (!function_exists('getDefaultCurrencyCode')) {
		
		function getDefaultCurrencyCode($data)
		{
			foreach ($data as $key => $value) {
				if (isset($value['is_default']) && $value['is_default'] == 1) {
					return $key;
				}
			}
			return null;
		}
	}

	/**
	 * 
	 * @param array 
	 * @return string|null 
	 */
	if (!function_exists('getDefaultCurrencySymbol')) {
		
		function getDefaultCurrencySymbol($data)
		{
			foreach ($data as $key => $value) {
				if (isset($value['is_default']) && $value['is_default'] == 1) {
					return $value['symbol'];
				}
			}
			return null;
		}
	}

	if (!function_exists('hexToRgba')) {

		/**
		 * Convert hex color to RGBA.
		 *
		 * @param string $hex The hex color code.
		 * @param float  $alpha The alpha value (0 to 1).
		 * @return string The RGBA color string.
		 * @throws Exception If the hex color code is invalid.
		 */
		function hexToRgba($hex, $alpha = 0.2) {

			$hex = ltrim($hex, '#');
			if (strlen($hex) === 6) {

				list($r, $g, $b) = array(

					hexdec(substr($hex, 0, 2)),
					hexdec(substr($hex, 2, 2)),
					hexdec(substr($hex, 4, 2))
				);
			} elseif (strlen($hex) === 3) {
				
				list($r, $g, $b) = array(
					hexdec(str_repeat(substr($hex, 0, 1), 2)),
					hexdec(str_repeat(substr($hex, 1, 1), 2)),
					hexdec(str_repeat(substr($hex, 2, 1), 2))
				);
			} else {
				throw new Exception("Invalid hex color code.");
			}
			if ($alpha < 0) {

				$alpha = 0;
			} elseif ($alpha > 1) {

				$alpha = 1;
			}
			return sprintf('rgba(%d, %d, %d, %.2f)', $r, $g, $b, $alpha);
		}
	}

	if (!function_exists('rgbaToHex')) {

		/**
		 * Convert RGBA color to hex color.
		 *
		 * @param string $rgba The RGBA color string.
		 * @return string The hex color code.
		 * @throws Exception If the RGBA color string is invalid.
		 */
		function rgbaToHex($rgba) {
			// Extract the RGBA values
			if (preg_match('/rgba?\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]*\.?[0-9]+)\s*\)/', $rgba, $matches)) {
				$r = intval($matches[1]);
				$g = intval($matches[2]);
				$b = intval($matches[3]);
				$alpha = floatval($matches[4]);
	
				// Ensure the RGB values are within the valid range
				if ($r < 0 || $r > 255 || $g < 0 || $g > 255 || $b < 0 || $b > 255 || $alpha < 0 || $alpha > 1) {
					throw new Exception("Invalid RGBA color string.");
				}
	
				// Convert the RGB values to hex
				$hex = sprintf('#%02X%02X%02X', $r, $g, $b);
	
				return $hex;
			} else {
				throw new Exception("Invalid RGBA color string.");
			}
		}
	}


	if (!function_exists('t2k')) {

		function t2k(string $text ,?string $replace = "_") :string {
		   return strtolower(strip_tags(str_replace(' ', $replace, $text)));
		}
	 }

	if (!function_exists('campaign_repeat_status')){
		function campaign_repeat_status(mixed  $status) :string
		{
         $badges  = [
            CampaignRepeatEnum::DAY->value   => "warning-soft",
            CampaignRepeatEnum::WEEK->value  => "success-soft",
            CampaignRepeatEnum::MONTH->value => "danger-solid",
            CampaignRepeatEnum::YEAR->value  => "info-soft"
         ];

         $class  = Arr::get($badges , $status , 'primary-soft');
         $status = ucfirst(t2k(Arr::get(array_flip(CampaignRepeatEnum::toArray()), $status, 'Hour')));
         return "<span class=\"i-badge $class\">$status</span>";

		}
   	}
	if (!function_exists('campaign_status')) {

		function campaign_status(mixed  $status) :string {

			$badges  = [
				CampaignStatusEnum::CANCEL->value  	 => "dark-soft",
				CampaignStatusEnum::ACTIVE->value    => "primary-soft",
				CampaignStatusEnum::DEACTIVE->value  => "danger-solid",
				CampaignStatusEnum::COMPLETED->value => "success-soft",
				CampaignStatusEnum::ONGOING->value   => "info-soft"
			];
			$class  = Arr::get($badges , $status , 'primary-soft');
			$status = ucfirst(t2k(Arr::get(array_flip(CampaignStatusEnum::toArray()), $status, 'Cancel')));
			return "<span class=\"i-badge dot pill $class\">$status</span>";
		}
   	}
	if (!function_exists('communication_status')) {

		function communication_status(mixed  $status) :string {
			
			$badges  = [
				// CommunicationStatusEnum::CANCEL->value     => "dark-soft",
				CommunicationStatusEnum::PENDING->value    => "primary-soft",
				CommunicationStatusEnum::SCHEDULE->value   => "info-soft",
				CommunicationStatusEnum::FAIL->value 	   => "danger-solid",
				CommunicationStatusEnum::DELIVERED->value  => "success-soft",
				CommunicationStatusEnum::PROCESSING->value => "warning-soft"
			];
			$class  = Arr::get($badges , $status , 'primary-soft');
			$status = ucfirst(t2k(Arr::get(array_flip(CommunicationStatusEnum::toArray()), $status, 'PENDING')));
			return "<span class=\"i-badge dot pill $class\">$status</span>";
		}
   	}
	if (!function_exists('contact_meta')) {

		function contact_meta(mixed $status) :string {
			
			$badges  = [
				ContactAttributeEnum::DATE->value     => "warning-soft",
				ContactAttributeEnum::BOOLEAN->value  => "danger-soft",
				ContactAttributeEnum::NUMBER->value   => "info-soft",
				ContactAttributeEnum::TEXT->value 	   => "success-soft"
			];
			$class  = Arr::get($badges , $status , 'primary-soft');
			$status = ucfirst(t2k(Arr::get(array_flip(ContactAttributeEnum::toArray()), $status, 'Date')));
			return "<span class=\"i-badge dot pill $class\">$status</span>";
		}
   	}

	if (!function_exists('payment_status')) {

		function payment_status(mixed  $status) :string {

			$badges  = [
				PaymentStatusEnum::PENDING->value     => "warning-soft",
				PaymentStatusEnum::SUCCESS->value    => "success-soft",
				PaymentStatusEnum::FAILED->value   => "danger-solid",
				PaymentStatusEnum::PROCESSING->value 	   => "info-soft",
				PaymentStatusEnum::CANCEL->value 	   => "dark-soft"
			];
			$class  = Arr::get($badges , $status , 'primary-soft');
			$status = ucfirst(t2k(Arr::get(array_flip(PaymentStatusEnum::toArray()), $status, 'Cancel')));
			return "<span class=\"i-badge dot pill $class\">$status</span>";
		}
   	}
	if (!function_exists('priority_status')) {

		function priority_status(mixed  $status) :string {

			$badges  = [
				PriorityStatusEnum::LOW->value     => "warning-soft",
				PriorityStatusEnum::MEDIUM->value    => "success-soft",
				PriorityStatusEnum::HIGH->value   => "danger-solid"
			];
			$class  = Arr::get($badges , $status , 'warning-soft');
			$status = ucfirst(t2k(Arr::get(array_flip(PriorityStatusEnum::toArray()), $status, 'Low')));
			return "<span class=\"i-badge dot pill $class\">$status</span>";
		}
   	}

	if (!function_exists('service_type')) {

		function service_type(mixed $status) :string {

			$badges  = [
				ServiceType::SMS->value     => "info-soft",
				ServiceType::WHATSAPP->value    => "success-soft",
				ServiceType::EMAIL->value   => "danger-solid"
			];
			$class  = Arr::get($badges , $status , 'warning-soft');
			$status = ucfirst(t2k(Arr::get(array_flip(ServiceType::toArray()), $status, 'Sms')));
			return "<span class=\"i-badge dot pill $class\">$status</span>";
		}
   	}

	if (!function_exists('android_sim_status')) {

		function android_sim_status(mixed $status) :string {

			$badges  = [
				AndroidApiSimEnum::ACTIVE->value     => "success-soft",
				AndroidApiSimEnum::INACTIVE->value   => "danger-solid",
			];
			$class  = Arr::get($badges , $status , 'danger-solid');
			$status = ucfirst(t2k(Arr::get(array_flip(AndroidApiSimEnum::toArray()), $status, 'INACTIVE')));
			return "<span class=\"i-badge dot pill $class\">$status</span>";
		}
   	}
	if (!function_exists('subscription_status')) {

		function subscription_status(mixed $status) :string {
			
			$badges  = [
				SubscriptionStatus::RUNNING->value => "primary-soft",
				SubscriptionStatus::EXPIRED->value => "warning-soft",
				SubscriptionStatus::REQUESTED->value => "info-soft",
				SubscriptionStatus::INACTIVE->value => "danger-solid",
				SubscriptionStatus::RENEWED->value => "success-soft",
			];
			$class  = Arr::get($badges , $status , 'danger-solid');
			$status = ucfirst(t2k(Arr::get(array_flip(SubscriptionStatus::toArray()), $status, 'INACTIVE')));
			return "<span class=\"i-badge dot pill $class\">$status</span>";
		}
   	}
	if (!function_exists('support_ticket_status')) {

		function support_ticket_status(mixed $status) :string {

			$badges  = [
				TicketStatusEnum::RUNNING->value => "primary-soft",
				TicketStatusEnum::ANSWERED->value => "success-soft",
				TicketStatusEnum::REPLIED->value => "info-soft",
				TicketStatusEnum::CLOSED->value => "dark-soft",
			];
			$class  = Arr::get($badges , $status , 'danger-solid');
			$status = ucfirst(t2k(Arr::get(array_flip(TicketStatusEnum::toArray()), $status, 'INACTIVE')));
			return "<span class=\"i-badge dot pill $class\">$status</span>";
		}
   	}
	   function build_post_fields( $data,$existingKeys='',&$returnArray=[]){
	    if(($data instanceof CURLFile) or !(is_array($data) or is_object($data))){
	        $returnArray[$existingKeys]=$data;
	        return $returnArray;
	    }
	    else{
	        foreach ($data as $key => $item) {
	            build_post_fields($item,$existingKeys?$existingKeys."[$key]":$key,$returnArray);
	        }
	        return $returnArray;
	    }
	}

	if (!function_exists('str_ends_with')) {

		function str_ends_with($haystack, $needle) {

			return $needle !== '' && substr($haystack, -strlen($needle)) === $needle;
		}
	}

	if (! function_exists('filterDuplicateContacts')) {
		function filterDuplicateContacts(array $contacts): array {
			
		    return array_unique($contacts, SORT_REGULAR);
		}
	}

	if (!function_exists('getEnvironmentMessage')) {

		/**
		 * Get an environment-specific message.
		 *
		 * @param string $devMessage The message to show in development (e.g., exception message)
		 * @param string $prodMessage The message to show in production (defaults to "Something went wrong")
		 * @return string The appropriate message based on the environment
		 */
		function getEnvironmentMessage(string $devMessage, ?string $prodMessage = null): string
		{
			$supportURL   	= SettingKey::SUPPORT_URL->value;
			$prodMessage  	= $prodMessage 
							? $prodMessage
							: translate("Please contact support at: "). $supportURL;

		    	return App::environment('development') 
					? $devMessage 
					: $prodMessage;
		}
	}

	if (!function_exists('demo_mode_enabled')) {
		/**
		 * Check if demo mode is globally enabled.
		 */
		function demo_mode_enabled(): bool
		{
		    return config('demo.enabled', false);
		}
	 }
	 
	if (!function_exists('demo_feature_enabled')) {
		/**
		 * Check if a specific feature is in demo mode.
		 */
		function demo_feature_enabled(string $feature): bool
		{
			return demo_mode_enabled() && config("demo.features.{$feature}.enabled", false);
		}
	}
	 
	if (!function_exists('demo_restriction_active')) {
		/**
		 * Check if a specific restriction is active for a feature.
		 */
		function demo_restriction_active(string $feature, string $restriction): bool
		{
			return demo_feature_enabled($feature) && 
					(config("demo.features.{$feature}.restrictions.{$restriction}", false) === true);
		}
	}
	 
	if (!function_exists('demo_get_message')) {
		/**
		 * Retrieve the appropriate demo mode message based on feature and restriction.
		 */
		function demo_get_message(string $feature, ?string $restriction = null): string
		{
			// Check restriction-specific message first
			if ($restriction && 
				($message = config("demo.features.{$feature}.messages.restrictions.{$restriction}"))) 
					return $message;
			
		
			// Fallback to feature-specific default message
			if ($message = config("demo.features.{$feature}.messages.default")) 
				return $message;
			
		
			// Fallback to global message
			return config('demo.messages.global', 'Demo mode is active.');
		}
	}

	if(!function_exists('returnBackWithResponse')) {
		
		/**
		 * returnBackWithResponse
		 *
		 * @param string $status
		 * @param mixed string
		 * 
		 * @return RedirectResponse
		 */
		function returnBackWithResponse(string $status = "error", string $message = "Invalid Data") : RedirectResponse {

			$notify[] = [$status, translate($message)];
			return back()->withNotify($notify);
		}
	}

	if(!function_exists('returnRedirectWithResponse')) {

		/**
		 * returnRedirectWithResponse
		 *
		 * @param string $route
		 * @param string $status
		 * @param mixed string
		 * 
		 * @return RedirectResponse
		 */
		function returnRedirectWithResponse(string $route, string $status = "error", string $message = "Invalid Data") : RedirectResponse {

			$notify[] = [$status, translate($message)];
			return redirect($route)->withNotify($notify);
		}
	}

	if (!function_exists('getAuthUser')) {

		/**
		 * Summary of getAuthUser
		 * @param string $guard
		 * @param array $load
		 * @return mixed
		 */
		function getAuthUser(string $guard = 'admin', array $load = []): mixed
		{
			return auth()?->guard($guard)
						?->user()
						?->load($load);
		}
	  
	}
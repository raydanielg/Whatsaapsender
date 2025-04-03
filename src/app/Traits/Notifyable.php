<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

trait Notifyable
{
    protected function notify(array $notifications) :void {

        foreach ($notifications as $notification => $config) {

            if (notify($notification)) {

                $action = $config['action'];
                $params = $config['params'];
        
                foreach ($params as $paramSet) {

                    if ($paramSet) {

                        call_user_func_array($action, $paramSet);
                    }
                }
            }
        }
    }
}
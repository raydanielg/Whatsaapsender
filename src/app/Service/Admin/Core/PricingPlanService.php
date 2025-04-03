<?php
namespace App\Service\Admin\Core;

use App\Enums\AndroidApiSimEnum;
use App\Enums\StatusEnum;
use App\Models\AndroidApi;
use App\Models\Gateway;
use App\Models\PricingPlan;
use App\Models\Subscription;

class PricingPlanService
{
    public function planLog() {

        return PricingPlan::search(['name'])
                            ->filter(['status'])
                            ->orderBy('recommended_status', 'DESC')
                            ->latest()
                            ->date()
                            ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                            ->appends(request()->all());
    }

    public function statusUpdate($request) {
        
        $status = "error";
        $message = "Something went wrong";
        
        try {
            $status   = true;
            $reload   = false;
            $message  = translate('Pricing plan status updated successfully');
            $gateway  = PricingPlan::where("id",$request->input('id'))->first();
            $column   = $request->input("column");
            
            if($column != "recommended_status" && $request->value == StatusEnum::TRUE->status()) {
                
                $gateway->status = StatusEnum::FALSE->status();
                if($gateway->recommended_status == StatusEnum::TRUE->status()) {

                    $gateway->recommended_status = StatusEnum::FALSE->status();
                    $reload = true;
                }
                $gateway->update();

            } elseif($column != "recommended_status" && $request->value == StatusEnum::FALSE->status()) {

                $gateway->status = StatusEnum::TRUE->status();
                $gateway->update();

            } elseif($column == "recommended_status") {
                
                $reload = true;
                $message  = translate('Recommended plan updated successfully');
                PricingPlan::where('id', '!=',$request->id)->update(["recommended_status" => StatusEnum::FALSE->status()]);
                $gateway->$column = StatusEnum::TRUE->status();
                $gateway->status  = StatusEnum::TRUE->status();
                
                $gateway->update();
            } else {

                $status = false;
                $message = translate("Something went wrong while updating this gateway");
            }

        } catch (\Exception $error) {

            $status  = false;
            $message = $error->getMessage();
        }

        return json_encode([
            'reload'  => $reload,
            'status'  => $status,
            'message' => $message
        ]);
    }

    /**
     * 
     * @param $id
     *
     * @return array
     */
    public function deleteplan($id): array {

        $plan = $this->fetchWithId($id);
        
        if($plan && $plan->recommended_status != StatusEnum::TRUE->status()) {
            
            $plan->delete();
            Subscription::where('plan_id',$id)->delete();
            $status  = 'success';
            $message = translate("plan ").$plan->name.translate(' has been deleted successfully from admin panel');
        } elseif($plan->recommended_status == StatusEnum::TRUE->status()) {

            $status  = 'error';
            $message = translate("Can not delete recommended plan. Please inactive the plan or make another plan recommended in order to delete this plan."); 
        } else {

            $status  = 'error';
            $message = translate("plan couldn't be found"); 
        }
        return [
            
            $status, 
            $message
        ];
    }

    /**
     * 
     * @param string $id
     *
     * @return plan $plan
     */
    public function fetchWithId($id) {

        return PricingPlan::where("id", $id)->first();
    }

    public function updatePlanRelatedModels($planId)
    {
        $users = Subscription::where('plan_id', $planId)
            ->whereIn('status', [Subscription::RUNNING, Subscription::RENEWED])
            ->pluck('user_id');

        if ($users->isNotEmpty()) {

            Gateway::whereIn('user_id', $users)->update(['status' => StatusEnum::FALSE->status()]);
            AndroidApi::whereIn('user_id', $users)->update(['status' => AndroidApiSimEnum::INACTIVE]);
        }
    }
}

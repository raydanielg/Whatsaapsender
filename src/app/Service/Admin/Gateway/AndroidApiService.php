<?php
namespace App\Service\Admin\Gateway;

use App\Enums\AndroidApiSimEnum;
use App\Enums\StatusEnum;
use App\Models\AndroidApi;
use App\Models\AndroidApiSimInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AndroidApiService
{
    public function save($request, $user_id = null) {
        
        $data = $this->param($request, $user_id);
        AndroidApi::updateOrCreate([
            'id' => $request->input('id'),
        ], $data);
    }

    public function param($request, $user_id) {

        $data = [
            'name' 			=> $request->input('name'),
            'admin_id' 		=> !$user_id ? auth()->guard('admin')->user()->id : null,
            'user_id'       => $user_id,
            'show_password' => $request->input('password'),
            'password' 		=> Hash::make($request->input('password'))
        ];
        return $data;
    }

    public function statusUpdate($request) {
        
        try {
            $status      = true;
            $reload      = true;
            $message     = translate('Android gateway Status has been updated');
            $android_api = AndroidApi::where("id",$request->input('id'))->first();
            
            if($request->value == StatusEnum::TRUE->status()) {
                
                $android_api->status = StatusEnum::FALSE->status();
                $android_api->update();
                AndroidApiSimInfo::where("android_gateway_id", $request->input('id'))->update([
                    
                    'status' => StatusEnum::FALSE->status()
                ]);
            } else {

                $android_api->status = StatusEnum::TRUE->status();
                $android_api->update();
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
     * Bulk action update/delete
     *
     * @param $request
     * @param array $modelData
     * @return array
     */
    public function bulkAction($request, array $modelData) {

        $status  = 'success';
        $message = translate("Successfully performed bulk action");
        $model   = $modelData['model'];
        $ids     = $request->input('ids', []);

        if (empty($ids)) {

            return ['error', translate("No items selected")];
        }
        
        $type = $request->input('type');
        
        try {
            DB::beginTransaction();

            if ($type === 'delete') {

                foreach ($ids as $id) {

                    $item = $model::find($id);
                    if ($item) {

                        $this->deleteWithRelations($item);
                    }
                }
               
                $message = translate("Successfully deleted selected items");
            } elseif ($type === 'status') {

                $statusValue = $request->input('status');
                
                foreach ($ids as $id) {

                    $item = $model::find($id);
                    if ($item && $statusValue == StatusEnum::FALSE->status()) {

                        $this->updateStatusWithRelation($item, $statusValue);
                    }
                }
                $model::whereIn('id', $ids)->update([
                    'status' => $statusValue
                ]);
                $message = translate("Successfully updated status for selected items");
            }
            DB::commit();
        } catch (\Exception $exception) {

            DB::rollBack();
            return ['error', translate("Server Error: ") . $exception->getMessage()];
        }

        return [$status, $message];
    }

    /**
     * Delete model with its relations
     *
     * @param Model $model
     * @return void
     */
    private function deleteWithRelations($model) {

        if (method_exists($model, 'getRelationships')) {

            foreach ($model->getRelationships() as $relation) {

                $relatedItems = $model->$relation()->get();
                foreach ($relatedItems as $relatedItem) {
                    
                    $relatedItem->delete();
                }
            }
        }
        $model->delete();
    }
    /**
     * Delete model with its relations
     *
     * @param Model $model
     * @return void
     */
    private function updateStatusWithRelation($model, $statusValue) {

        if (method_exists($model, 'getRelationships')) {

            foreach ($model->getRelationships() as $relation) {

                $relatedItems = $model->$relation()->get();
                foreach ($relatedItems as $relatedItem) {
                    
                    if($statusValue == StatusEnum::TRUE->status()) {

                        $relatedItem::whereIn('id', $relatedItem)->update([
                            'status' => AndroidApiSimEnum::ACTIVE->value
                        ]);
                    } else {

                        $relatedItem::whereIn('id', $relatedItem)->update([
                            'status' => AndroidApiSimEnum::INACTIVE->value
                        ]);
                    }
                    
                }
            }
        }
    }

    /**
     * Bulk action update/delete
     *
     * @param $request
     * @param array $modelData
     * @return array
     */
    public function simBulkAction($request, array $modelData) {

        $status  = 'success';
        $message = translate("Successfully performed bulk action");
        $model   = $modelData['model'];
        $ids     = $request->input('ids', []);

        if (empty($ids)) {

            return ['error', translate("No items selected")];
        }
        $type = $request->input('type');
       
        try {
            DB::beginTransaction();

            if ($type === 'delete') {

                foreach ($ids as $id) {

                    $item = $model::find($id);
                    if ($item) {
    
                        $item->delete();
                    }
                }
                $message = translate("Successfully deleted selected items");
            } elseif ($type === 'status') {

                $statusValue = $request->input('status');
                $model::whereIn('id', $ids)->update([
                    'status' => $statusValue
                ]);
                $message = translate("Successfully updated status for selected items");
            }
            DB::commit();
        } catch (\Exception $exception) {

            DB::rollBack();
            return ['error', translate("Server Error: ") . $exception->getMessage()];
        }

        return [$status, $message];
    }
}

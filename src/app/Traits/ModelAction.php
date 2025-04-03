<?php

namespace App\Traits;

use App\Enums\Common\Status;
use App\Enums\StatusEnum;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait ModelAction
{
    /**
     * bulkAction
     *
     * @param Request $request
     * @param string|null $dependent_column
     * @param array $modelData
     * 
     * @return RedirectResponse
     */
    private function bulkAction(Request $request, string $dependent_column = null, array $modelData): RedirectResponse {

        $status  = 'success';
        $message = translate("Successfully performed bulk action");
        $model   = $modelData['model'];
        $ids     = $request->input('ids', []);
        
        if (empty($ids)) {
            
            $notify[] = ['error', translate("No items selected")];
            return back()->withNotify($notify);
        }
        
        $type = $request->input('type');
        
        DB::beginTransaction();

        if ($type === 'delete') {

            foreach ($ids as $id) {

                $item = $model::where(Arr::get($modelData, 'filterable_attributes', []))
                                    ->where("id", $id)
                                    ->first();
                if ($item) {

                    $this->deleteWithRelations($item);
                }
            }
            $message = translate("Successfully deleted selected items");
        } elseif ($type === 'status') {
            
            $statusValue = $request->input('status');
            
            $model::whereIn('id', $ids)->update([
                'status' => $statusValue
            ]);
            if($dependent_column && $statusValue == StatusEnum::FALSE->status()) {

                $model::whereIn('id', $ids)->update([
                    $dependent_column => $statusValue
                ]);
            }
            $message = translate("Successfully updated status for selected items");
        }
        DB::commit();
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     * Delete model with its relations
     *
     * @param Model $model
     * @return void
     */
    private function deleteWithRelations(Model $model): void {

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
     * Validate the status update request dynamically.
     *
     * @param Request $request
     * @param string $tableName The table name for existence check
     * @param string $keyColumn The column name for existence check (default: 'uid')
     * @param array $additionalRules Additional validation rules to merge
     * @return array Validated data with normalized value
     * @throws ValidationException
     */
    public function validateStatusUpdate(
        Request $request,
        string $tableName,
        string $keyColumn = 'uid',
        array $additionalRules = []
    ): array {

        $rules = array_merge([
            $keyColumn => ['required', 'string', "exists:{$tableName},{$keyColumn}"],
            'column'   => ['required', 'string'],
            'value'    => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, [0, 1, '0', '1']) && !in_array($value, Status::getValues())) {
                        $fail(translate('Invalid Request'));
                    }
                },
            ],
        ], $additionalRules);
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
            throw new ValidationException($validator, response()->json([
                'status'  => false,
                'message' => $validator->errors()
            ]));
        }
        
        $value = $request->input('value');
        if (in_array($value, [0, '0'])) {
            $value = Status::INACTIVE;
        } elseif (in_array($value, [1, '1'])) {
            $value = Status::ACTIVE;
        }

        return array_merge($request->all(), ['value' => $value]);
    }

    /**
     * Update the status of a model.
     *
     * @param array $request
     * @param array $actionData
     * @return string JSON-encoded response
     */
    public function statusUpdate(array $request, array $actionData): string
    {
        $status  = true;
        $reload  = Arr::get($actionData, 'reload', false);
        $message = Arr::get($actionData, 'message', translate('Status Updated'));

        $model  = Arr::get($actionData, 'model');
        $data   = $model::where(Arr::get($actionData, 'filterable_attributes', []))
                            ->when(Arr::get($actionData, 'recycle', false), 
                                fn(Builder $q): Builder => 
                                    $q->withTrashed())
                            ->firstOrFail();

        $column = Arr::get($actionData, 'column', 'status');
        $value  = Arr::get($request, 'value');
        $data->$column = $value;
        $data->save();

        return json_encode([
            'reload'  => $reload,
            'status'  => $status,
            'message' => $message,
        ]);
    }
}

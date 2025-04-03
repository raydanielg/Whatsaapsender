<?php

namespace App\Rules;

use App\Models\AndroidApi;
use Illuminate\Contracts\Validation\Rule;

class UniqueForIndividuals implements Rule
{
    protected $isAdmin;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->isAdmin = auth()->check() && auth()->user()->admin_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->isAdmin) {
            $adminId = auth()->user()->admin_id ?? null;
            $existingEntryForAdmin = AndroidApi::where('name', $value)
                ->where('admin_id', $adminId)
                ->exists();

            return !$existingEntryForAdmin;
        } else {
            $userId = auth()->user()->id ?? null;
            $existingEntryForUser = AndroidApi::where('name', $value)
                ->where('user_id', $userId)
                ->exists();

            return !$existingEntryForUser;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be unique.';
    }
}

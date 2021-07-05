<?php

namespace App\Rules;

use App\Models\Slide;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class OwnsSlide implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return Slide::where('id',$value)->where('owner_id',Auth::id())->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You do not have permission to access this slide.';
    }
}

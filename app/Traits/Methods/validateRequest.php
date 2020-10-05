<?php namespace App\Traits\Methods;

use Illuminate\Http\Request;

trait validateRequest
{
    public function validateRequest(Request $request) {
        $this->validate($request, [
            'name_ru'  => 'required|max:150',
        ]);
    }
}
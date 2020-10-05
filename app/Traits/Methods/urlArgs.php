<?php namespace App\Traits\Methods;

use App\Library\Str;

trait urlArgs
{
    public static function urlArgs($request) {
        $url_args = Str::urlArgs($request) + [
                    'search_id'       => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_name'    => $request->input('search_name'),
                ];
        
        if (!$url_args['search_id']) {
            $url_args['search_id'] = NULL;
        }
        
        return $url_args;
    }    
}
<?php

use Illuminate\Support\Str;

if (! function_exists('number_with_space')) {
    function number_with_space($num) {
        return number_format($num, 0, '', ' ');
    }
}

if (! function_exists('show_route')) {
    function show_route($model, $args_by_get=null)
    {
        return route_for_model($model, 'show', $args_by_get);
    }
}

if (! function_exists('route_for_model')) {
    function route_for_model($model, $route, $args_by_get=null/*, $resource = null*/)
    {
/*        $resource = $resource ?? plural_from_model($model);

        return route("{$resource}.show", $model);*/
        return route(single_from_model($model).".{$route}", $model).$args_by_get;
    }
}
if (! function_exists('single_from_model')) {
    function single_from_model($model)
    {
        return snake_case(class_basename($model));
    }
}

if (! function_exists('plural_from_model')) {
    function plural_from_model($model)
    {
        $plural = Str::plural(class_basename($model));

        return Str::camel($plural);
    }
}

if (! function_exists('user_can_edit')) {
    function user_can_edit()
    {
        return User::checkAccess('dict.edit');
    }
}

if (! function_exists('output_array_for_script')) {
    function output_array_for_script($arr, $tab='', $div="<br>")
    {
        print $tab."[";
        foreach ($arr as $k => $v) {
            print (is_int($k) ? $k : '"'.$k.'"')." => ";
            if (!is_array($v)) {
                print is_int($v) ? $v : '"'.$v.'"';
            } else {
                output_array_for_script($v, " ", "");
            }
            if ($k != array_key_last($arr)) {
                print ','.$div.$tab;
            }
        }
        print "]";
    }
}
        

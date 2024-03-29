<?php
    if (!isset($args)) {
        $args = [];
    }

    if (isset($url_args)) {
        $args = array_merge($args,$url_args);
    }

    $format = '<a href="%s" data-toggle="tooltip" data-delete="%s" title="%s"';

    if (isset($is_button) && $is_button) {
        $format .= ' class="btn btn-xs btn-danger"';
    }

    $format .= '><i class="fa fa-trash fa-lg '. ($class ?? ''). '"></i> %s</a>';
    if (isset($obj_name)) {
        $link = URL::route($route, array_merge($args,[$obj_name=>$obj->id]));
    } else {
        $link = URL::route($route, $obj, $args);
    }
    $token = csrf_token();

    if (isset($without_text) && $without_text) {
        $title = '';
    } else {
        $title = \Lang::get('messages.delete');
    }

    print sprintf($format, $link, $token, $title, $title);

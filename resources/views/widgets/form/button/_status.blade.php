<?php
        $route .= $status ? 0 : 1;
        if (isset($args_by_get)) {
            $route .= $args_by_get;
        } elseif (isset($url_args) && sizeof($url_args)) {
            $tmp=[];
            foreach ($url_args as $a=>$v) {
                if ($v!='') {
                    $tmp[] = "$a=$v";
                }
            }
            if (sizeof ($tmp)) {
                $route .= "?".implode('&',$tmp);
            }
        }
        $ico = $status ? 'eye' : 'eye-slash';
        $color = $status ? 'success' : 'danger';
        $format = '<a  href="%s" class="btn btn-%s btn-xs btn-detail">'
                . '<i class="fa fa-%s fa-lg" title="%s"></i></a>';
        print sprintf($format, $route, $color, $ico, trans('messages.set_status'.$status));

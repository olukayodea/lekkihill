<?php

use function PHPSTORM_META\type;

class common {
    public function getLink($status, $title=false) {
        if ($status == "INACTIVE") {
            $tag = "Activate";
            $style = ' style="color:green"';
            $faa = "fa-play";
        } else if ($status == "ACTIVE") {
            $tag = "Deactivate";
            $style = ' style="color:red"';
            $faa = "fa-stop";
        }

        if ($title == true) {
            $tagTitle = "&nbsp;".$tag;
            $style = "";
            $size = " fa-lg";
        }

        return '<i class="fas '.$faa.$size.'" title="'.$tag.'"'.$style.'></i>'.$tagTitle;
    }

    public function getuser($id, $return="user_nicename") {
        $data = get_user_by('id', $id);

        return $data->$return;
    }

    function returnUrl($type=false, $message=false) {
        if ($type == "done") {
            $link = "&done=".urlencode($message);
        } else {
            $link = "&error=".urlencode($message);
        }
        if (isset($_REQUEST['return'])) {
            wp_redirect( admin_url('admin.php?page='.$_REQUEST['return'].$link) );
            exit;
        }
    }
    
    function addS($word, $count) {
        if ($count > 1) {
            $two = substr($word, -2); 
            $one = substr($word, -1); 
            if (($two == "ss") || ($two == "sh") || ($two == "ch")) {
                return $word."es";
            } else if (($one == "s") || ($one == "x") || ($one == "z")) {
                return $word."es";
            } else if ($two == "lf") {
                return $word."ves";
            } else if ($two == "ay") {
                return $word."s";
            } else if ($one == "y") {
                return $word."ies";
            } else {
                return $word."s";
            }
        } else {
            return $word;
        }
    }

    function cleanData(&$str) {
        if($str == 't') $str = 'TRUE';
        if($str == 'f') $str = 'FALSE';
        if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
            $str = "'$str";
        }
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        $str = mb_convert_encoding($str, 'UTF-16LE', 'UTF-8');
    }
}
?>
<?php

use function PHPSTORM_META\type;

class common {
    public static function validateSession($request) {
        return users::getDetails($request);
    }

    public static function getLink($status, $title=false) {
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
    
    public static function createRandomPassword($len = 7) { 
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"; 
        srand((double)microtime()*1000000); 
        $i = 0; 
        $pass = '' ; 
        $count = strlen($chars);
        while ($i <= $len) { 
            $num = rand() % $count; 
            $tmp = substr($chars, $num, 1); 
            $pass = $pass . $tmp; 
            $i++; 
        } 
        return $pass; 
    }

    public static function getuser($id, $return="user_nicename") {
        $data = get_user_by('id', $id);

        return $data->$return;
    }
    
    private static function authenticatedUser($token) {
        global $users;
        $id = $users->getSingle($token, "ID", "user_token");
        return $id;
    }

    public function authenticate($header) {
        $split = explode("_", base64_decode($header['api_token'][0]));
        $token = $split[1];
        if ($header['api_key'][0] == $split[0]) {
            $id = self::authenticatedUser($token);
            if ($id != false) {
                $return['status'] = "200";
                $return['message'] = "OK";
                $return['ID'] = $id;
            } else {
                $return['status'] = "404";
                $return['message'] = "User is not Authorized";
            }
        } else {
            $return['status'] = "401";
            $return['message'] = "Unauthorized";
        }
        return $return;
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
		
    function get_time_stamp($post_time) {
        if (($post_time == "") || ($post_time <1)) {
            return false;
        } else {
            $difference = time() - $post_time;
            $periods = array("sec", "min", "hour", "day", "week",
            "month", "years", "decade","century","millenium");
            $lengths = array("60","60","24","7","4.35","12","10","100","1000");
            
            if ($difference >= 0) { // this was in the past
                $ending = "ago";
            } else { // this was in the future
                $difference = -$difference;
                $ending = "time";
            }
            
            for($j = 0; $difference >= $lengths[$j]; $j++)
            $difference = $difference/$lengths[$j];
            $difference = round($difference);
            
            if($difference != 1) $periods[$j].= "s";
            $text = "$difference $periods[$j] $ending";
            return $text;
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
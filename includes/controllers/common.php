<?php
class common {
    public function getLink($status) {
        if ($status == "INACTIVE") {
            return '<i class="fas fa-play" title="Activate" style="color:green"></i>';
        } else if ($status == "ACTIVE") {
            return '<i class="fas fa-stop" title="Deactivate" style="color:red"></i>';
        }
    }

    public function getuser($id, $return="user_nicename") {
        $data = get_user_by('id', $id);

        return $data->$return;
    }
}
?>
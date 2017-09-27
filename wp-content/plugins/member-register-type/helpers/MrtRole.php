<?php

class MrtRole {

    private $mrt_user;
    private $current_user_id;
    private $role;

    function __construct() {
        
    }

    function hasrole($capability, $param = null, $condition = 'AND') {

        if ($capability == null) {
            return is_user_logged_in();
        }

        $this->capability = $capability;

        if (is_user_logged_in()) {
            $this->current_user_id = get_current_user_id();
            $this->mrt_user = new MrtUser($this->current_user_id);
            $this->role = $this->mrt_user->user_role;
        } else {
            $this->role = 'public';
            $this->current_user_id = 0;
        }

        $this->system_roles = Stock::system_roles();

        switch ($this->capability) {
            // Special cases for role capability check
            case 'user_edit_dashboard':
                return $this->user_edit_dashboard($param);
                break;

            case 'user_delete':
            case 'user_edit_role':
                $user_id = $param;
                if ($user_id != $this->current_user_id && $this->in_system_roles()) {
                    return true;
                }

                break;

            default :
                if ($this->in_system_roles()) {
                    return true;
                }
        }

        return false;
    }

    function user_edit_dashboard($param = null) {

        if ($this->role == 'administrator') {
            return true;
        }

        if (is_null($param)) {
            return false;
        }

        $user_id = $param;

        if (!is_numeric($user_id)) {
            return false;
        }

        $profile_user = new MrtUser($user_id);

        if (!isset($profile_user->user->ID)) {
            return false;
        }

        if ($user_id == $this->current_user_id) {
            return true;
        }

        if ($this->in_system_roles()) {
            return true;
        }
        
        return false;
    }

    function in_system_roles() {
        return in_array($this->capability, $this->system_roles[$this->role]['capabilities']);
    }

}

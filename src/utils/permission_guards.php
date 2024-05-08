<?php

function is_admin($user) {
    return $user['role_id'] === 1;
}

function is_user($user) {
    return $user['role_id'] === 2;
}

function is_admin_or_user($user) {
    return is_admin($user) || is_user($user);
}

?>
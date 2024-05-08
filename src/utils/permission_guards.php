<?php

function is_admin($user) {
    return $user['role'] === 'admin';
}

function is_user($user) {
    return $user['role'] === 'user';
}

function is_admin_or_user($user) {
    return is_admin($user) || is_user($user);
}

?>
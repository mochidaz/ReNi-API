<?php

function handle_json_request($data) {
    $data = json_decode($data, true);

    if ($data === null) {
        return [
            'error' => 'Invalid JSON'
        ];
    }

    return $data;
}

?>
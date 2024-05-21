<?php

include_once __DIR__ . '/../responses/response.php';

interface Middleware {
    
    public function handle($request, $next, $connection = null): ?Response;

}

?>
<?
include_once __DIR__ . '/../responses/response.php';
include_once __DIR__ . '/../src/users/auth.php';

class AuthorizationMiddleware implements Middleware {
    public function handle($request, $next, $connection): ?Response {
        $headers = getallheaders();
        $api_key = $headers['HTTP_API_KEY'];

        if (!$api_key) {
            return new Response(401, ['message' => 'Unauthorized']);
        }

        $user = get_user_by_apikey($api_key, $connection);

        if (!$user) {
            return new Response(401, ['message' => 'Unauthorized']);
        }

        return $next($request);
    }
}

?>
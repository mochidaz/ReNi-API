<?php

include_once __DIR__ .'/users/register.php';
include_once __DIR__ .'/db/conn.php';
include_once __DIR__ .'/users/auth.php';
include_once __DIR__ .'/utils/permission_guards.php';
include_once __DIR__ .'/utils/router.php';
include_once __DIR__ .'/panen/submit.php';
include_once __DIR__ .'/utils/json.php';

router('GET', '/', function () {
    echo json_encode(['message' => 'Hello, World!']);
}, Permission::Any);

router('GET', '/hello/{name}', function ($params) {
    echo json_encode(['message' => 'Hello, ' . $params['name'] . '!']);
}, Permission::Admin);

router('POST', '/users/register', function () {
    global $connection;
    $no_ktp = $_POST['no_ktp'];
    $nama = $_POST['nama'];
    $role_id = 2;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (register($no_ktp, $nama, $role_id, $password, $connection)) {
        $response['message'] = 'Register berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Register gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Any);

router('POST', '/users/login', function () {
    global $connection;

    $data = json_decode(file_get_contents('php://input'), true);

    $no_ktp = $data['no_ktp'];
    $password = $data['password'];

    echo $no_ktp;
    echo $password;

    $apiKey = login($no_ktp, $password, $connection);

    if ($apiKey) {
        $response['message'] = 'Login berhasil';
        $response['success'] = true;
        $response['api_key'] = $apiKey;
    } else {
        $response['message'] = 'Login gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Any);

router('GET', '/hi', function() {
    global $connection;

    $api_key = $_SERVER['HTTP_API_KEY'];

    $message = $_GET['pesan'];

    $stmt = $connection->prepare ('SELECT * FROM users WHERE token = :api_key');

    $stmt->bindParam(':api_key', $api_key);

    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'message' => 'Hi, ' . $user['name'] . '!' . ' Pesan dari anda: ' . $message,
    ]);
}, Permission::User);

router('POST', '/panen', function() {
    global $connection;

    if(submit_panen($connection, [
        'pangan_id' => $_POST['pangan_id'],
        'user_id' => $_POST['user_id'],
        'tanggal_penanaman' => $_POST['tanggal_penanaman'],
        'tanggal_panen' => $_POST['tanggal_panen'],
        'hasil_panen' => $_POST['hasil_panen'],
        'luas_penanaman' => $_POST['luas_penanaman'],
    ])) {
        $response['message'] = 'Submit panen berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Submit panen gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::User);

router('GET', '/whoami', function() {
    global $connection;

    $api_key = $_SERVER['HTTP_API_KEY'];

    $stmt = $connection->prepare('SELECT * FROM users WHERE token = :api_key');

    $stmt->bindParam(':api_key', $api_key);

    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($user);
}, Permission::User);


router('GET', '/panen', function() {
    global $connection;

    $panen = get_panen($connection);

    echo json_encode($panen);
}, Permission::User);
    

function buildRouter($routes)
{
    return function () use ($routes) {
        foreach ($routes as $route) {
            router($route[0], $route[1], $route[2], $route[3], false);
        }
    };
}
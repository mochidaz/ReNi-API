<?php

include_once __DIR__ .'/users/register.php';
include_once __DIR__ .'/db/conn.php';
include_once __DIR__ .'/users/auth.php';
include_once __DIR__ .'/utils/permission_guards.php';
include_once __DIR__ .'/utils/router.php';
include_once __DIR__ .'/panen/submit.php';
include_once __DIR__ .'/utils/json.php';
include_once __DIR__ .'/users/users_data.php';
include_once __DIR__ .'/utils/files.php';
include_once __DIR__ .'/region/submit_daerah.php';
include_once __DIR__ .'/region/get_daerah.php';
include_once __DIR__ .'/users/lahan.php';
include_once __DIR__ .'/pangan/pangan.php';
include_once __DIR__ .'/ruang_tani/post.php';
include_once __DIR__ .'/infos/soil_info.php';
include_once __DIR__ .'/infos/water_info.php';
include_once __DIR__ .'/infos/temperature_info.php';
include_once __DIR__ .'/region/delete_daerah.php';
include_once __DIR__ .'/region/update_daerah.php';
include_once __DIR__ .'/utils/parser.php';

router('GET', '/', function () {
    echo json_encode(['message' => 'Hello, World!']);
}, Permission::Any);

router('GET', '/hello/{name}', function ($params) {
    echo json_encode(['message' => 'Hello, ' . $params['name'] . '!']);
}, Permission::Admin);

router('POST', '/users/register', function () {
    global $connection;
    $no_ktp = $_POST['no_ktp'];
    $nama = $_POST['name'];
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

    $no_ktp = $_POST['no_ktp'];
    $password = $_POST['password'];

    $apiKey = login($no_ktp, $password, $connection);

    if ($apiKey) {
        $response['message'] = 'Login berhasil';
        $response['success'] = true;
        $response['api_key'] = $apiKey;
        $response['role_name'] = get_user_by_apikey($apiKey, $connection)['role_name'];
        $response['no_ktp'] = get_user_by_apikey($apiKey, $connection)['no_ktp'];
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

    $user = get_user_by_apikey($_SERVER['HTTP_API_KEY'], $connection);

    if(submit_panen($connection, [
        'pangan_id' => $_POST['pangan_id'],
        'user_id' => $user['no_ktp'],
        'tanggal_penanaman' => $_POST['tanggal_penanaman'],
        'tanggal_panen' => $_POST['tanggal_panen'],
        'hasil_panen' => $_POST['hasil_panen'],
        'luas_penanaman' => $_POST['luas_penanaman'],
        'lahan_id' => $_POST['lahan_id'],
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

    $stmt = $connection->prepare('SELECT *, role.name as role_name FROM users INNER JOIN role ON users.role_id = role.id WHERE users.token = :api_key');

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

router('GET', '/users/panen', function($params) {
    global $connection;

    $user = get_user_by_apikey($_SERVER['HTTP_API_KEY'], $connection);

    $panen = get_panen_by_user($connection, $user['no_ktp']);

    $response = [
        'message' => 'Data panen user dengan no_ktp ' . $user['no_ktp'],
        'success'=> true,
        'status' => 200,
        'data' => $panen,
    ];

    echo json_encode($response);
}, Permission::User);

router('POST', '/users/data', function() {
    global $connection;

    $user = get_user_by_apikey($_SERVER['HTTP_API_KEY'], $connection);

    // multipart/form-data
//    $query = $db->prepare('INSERT INTO user_data (user_id, address, phone, profile_photo, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');

    $file = $_FILES['profile_photo'];

    $imgName = uploadImage($file, '../media/profile_photo/');
    
    if ($imgName['success'] === false) {
        echo json_encode($imgName);
        return;
    }

    $data = [
        'user_id' => $user['no_ktp'],
        'address' => $_POST['address'],
        'phone' => $_POST['phone'],
        'profile_photo' => $imgName['file_path'],        
    ];
    
    submit_user_data($data['user_id'], $data['address'],
    $data['phone'], $data['profile_photo'], $connection);
    
    echo json_encode([
        'message' => 'Data user berhasil disimpan',
        'success' => true,
    ]);
}, Permission::User);

router('GET', '/users/data', function() {
    global $connection;

    if (isset($_GET['no_ktp'])) {
        $user_data = get_user_data($_GET['no_ktp'], $connection);
    } else {
        $user_data = get_bulk_user_data($connection);
    }

    echo json_encode($user_data);
}, Permission::User);

router('POST', '/users/lahan', function() {
    global $connection;

    $user = get_user_by_apikey($_SERVER['HTTP_API_KEY'], $connection);

    if (insert_lahan($_POST['name'], $_POST['wilayah_id'], $_POST['luas_lahan'], $_POST['lokasi'], $user['no_ktp'], $connection)) {
        $response['message'] = 'Insert lahan berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Insert lahan gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::User);

router('GET', '/users/lahan', function() {
    global $connection;

    $user = get_user_by_apikey($_SERVER['HTTP_API_KEY'], $connection);

    if (isset($_GET['id'])) {
        $lahan = get_lahan($connection);
    } else if (isset($_GET['no_ktp'])){
        $lahan = get_lahan_by_user($_GET['no_ktp'], $connection);
    } else {
        $lahan = get_all_lahan($connection);
    }

    echo json_encode($lahan);
}, Permission::User);

router('POST', '/wilayah', function() {
    global $connection;

    if (submit_daerah($_POST['name'], $connection)) {
        $response['message'] = 'Insert wilayah berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Insert wilayah gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('GET','/wilayah', function() {
    global $connection;

    if (isset($_GET['id'])) {
        $wilayah = get_daerah_by_id($_GET['id'], $connection);
    } else {
        $wilayah = get_all_daerah($connection);
    }

    echo json_encode($wilayah);
}, Permission::Any);

router('POST', '/pangan', function() {
    global $connection;

    $response = [];

    if (submit_pangan($connection, $_POST['name'])) {
        $response['message'] = 'Insert pangan berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Insert pangan gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('GET', '/pangan', function() {
    global $connection;

    $pangan = null;

    if (isset($_GET['id'])) {
        $pangan = get_pangan($connection, $_GET['id']);
    } else {
        $pangan = get_pangan($connection);
    }

    echo json_encode($pangan);
}, Permission::Any);

router('POST', '/ruang-tani/artikel', function() {
    global $connection;

    $response = []; 

    $image = $_FILES['image'];

    $imgName = uploadImage($image, '../media/artikel/');

    if ($imgName['success'] === false) {
        echo json_encode($imgName);
        return;
    }

    if (submit_post($_POST['title'], $_POST['content'], $_POST['category'], $imgName['file_path'], $connection)) {
        $response['message'] = 'Insert artikel berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Insert artikel gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('GET', '/ruang-tani', function() {
    global $connection;

    if (isset($_GET['id'])) {
        $posts = get_post($connection, $_GET['id']);
    } else {
        $posts = get_post($connection);
    }

    echo json_encode($posts);
}, Permission::Any);

router('POST', '/info_tanah', function() {
    global $connection;

    $response = [];

    if (submit_soil_info($_POST['wilayah_id'], $_POST['content'], $connection)) {
        $response['message'] = 'Insert info tanah berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Insert info tanah gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('GET', '/info_tanah', function() {
    global $connection;

    if (isset($_GET['id'])) {
        $info = get_soil_info($connection, $_GET['id']);
    } else if (isset($_GET['wilayah_id'])) {
        $info = get_soil_by_wilayah($_GET['wilayah_id'], $connection);
    } else {
        $info = get_soil_info($connection);
    }

    echo json_encode($info);
}, Permission::Any);

router('POST', '/info_air', function() {
    global $connection;

    $response = [];

    if (submit_water_info($_POST['wilayah_id'], $_POST['content'], $connection)) {
        $response['message'] = 'Insert info air berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Insert info air gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('GET', '/info_air', function() {
    global $connection;

    if (isset($_GET['id'])) {
        $info = get_water_info($connection, $_GET['id']);
    } else if (isset($_GET['wilayah_id'])) {
        $info = get_water_by_wilayah($_GET['wilayah_id'], $connection);
    } else {
        $info = get_water_info($connection);
    }

    echo json_encode($info);
}, Permission::Any);

router('POST', '/info_suhu', function() {
    global $connection;

    $response = [];

    if (submit_temperature_info($_POST['wilayah_id'], $_POST['content'], $connection)) {
        $response['message'] = 'Insert info suhu berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Insert info suhu gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('GET', '/info_suhu', function() {
    global $connection;

    if (isset($_GET['id'])) {
        $info = get_temperature_info($connection, $_GET['id']);
    } else if (isset($_GET['wilayah_id'])) {
        $info = get_temperature_by_wilayah($_GET['wilayah_id'], $connection);
    } else {
        $info = get_temperature_info($connection);
    }

    echo json_encode($info);
}, Permission::Any);

router('DELETE', '/users/panen', function($params) {
    global $connection;

    $response = [];

    if (!isset($_GET['id'])) {
        $response['message'] = 'ID panen tidak ditemukan';
        $response['success'] = false;
        echo json_encode($response);
        return;
    }

    $id = $_GET['id'];

    if (delete_panen($connection, $id)) {
        $response['message'] = 'Delete panen berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Delete panen gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::User);

router('DELETE', '/users/lahan', function($params) {
    global $connection;

    $response = [];

    if (!isset($_GET['id'])) {
        $response['message'] = 'ID lahan tidak ditemukan';
        $response['success'] = false;
        echo json_encode($response);
        return;
    }

    $id = $_GET['id'];

    if (delete_lahan($connection, $id)) {
        $response['message'] = 'Delete lahan berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Delete lahan gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::User);

router('DELETE', '/wilayah', function($params) {
    global $connection;

    $response = [];

    if (!isset($_GET['id'])) {
        $response['message'] = 'ID wilayah tidak ditemukan';
        $response['success'] = false;
        echo json_encode($response);
        return;
    }

    $id = $_GET['id'];

    if (delete_daerah($connection, $id)) {
        $response['message'] = 'Delete wilayah berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Delete wilayah gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('DELETE', '/pangan', function($params) {
    global $connection;

    $response = [];

    if (!isset($_GET['id'])) {
        $response['message'] = 'ID pangan tidak ditemukan';
        $response['success'] = false;
        echo json_encode($response);
        return;
    }

    $id = $_GET['id'];

    if (delete_pangan($connection, $id)) {
        $response['message'] = 'Delete pangan berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Delete pangan gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('DELETE', '/ruang-tani', function($params) {
    global $connection;

    $response = [];

    if (!isset($_GET['id'])) {
        $response['message'] = 'ID artikel tidak ditemukan';
        $response['success'] = false;
        echo json_encode($response);
        return;
    }

    $id = $_GET['id'];

    if (delete_post($connection, $id)) {
        $response['message'] = 'Delete artikel berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Delete artikel gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('DELETE', '/info_tanah', function($params) {
    global $connection;

    $response = [];

    if (!isset($_GET['id'])) {
        $response['message'] = 'ID info tanah tidak ditemukan';
        $response['success'] = false;
        echo json_encode($response);
        return;
    }

    $id = $_GET['id'];

    if (delete_soil_info($connection, $id)) {
        $response['message'] = 'Delete info tanah berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Delete info tanah gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('DELETE', '/info_air', function($params) {
    global $connection;

    $response = [];

    if (!isset($_GET['id'])) {
        $response['message'] = 'ID info air tidak ditemukan';
        $response['success'] = false;
        echo json_encode($response);
        return;
    }

    $id = $_GET['id'];

    if (delete_water_info($connection, $id)) {
        $response['message'] = 'Delete info air berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Delete info air gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('DELETE', '/info_suhu', function($params) {
    global $connection;

    $response = [];

    if (!isset($_GET['id'])) {
        $response['message'] = 'ID info suhu tidak ditemukan';
        $response['success'] = false;
        echo json_encode($response);
        return;
    }

    $id = $_GET['id'];

    if (delete_temperature_info($connection, $id)) {
        $response['message'] = 'Delete info suhu berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Delete info suhu gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('PUT', '/users/data', function() {
    global $connection;

    $response = [];

    $user = get_user_by_apikey($_SERVER['HTTP_API_KEY'], $connection);

    $put = file_get_contents('php://input');

    $data = parse_form_data($put);

    $data = [
        'user_id' => $user['no_ktp'],
        'address' => $data['address'],
        'phone' => $data['phone'],
    ];

    if (update_user_data($data, $connection)) {
        $response['message'] = 'Update data user berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Update data user gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::User);

router('PUT', '/users/data/profile-photo', function() {
    global $connection;

    $response = [];

    $user = get_user_by_apikey($_SERVER['HTTP_API_KEY'], $connection);

    $file = $_FILES['profile_photo'];

    $imgName = uploadImage($file, '../media/profile_photo/');

    if ($imgName['success'] === false) {
        echo json_encode($imgName);
        return;
    }

    if (update_user_profile_photo($user['no_ktp'], $imgName['file_path'], $connection)) {
        $response['message'] = 'Update foto profil berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Update foto profil gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::User);

router('PUT', '/users/lahan', function() {
    global $connection;

    $response = [];

    $user = get_user_by_apikey($_SERVER['HTTP_API_KEY'], $connection);

    $put = file_get_contents('php://input');

    $data = parse_form_data($put);

    if (update_lahan([
        'id' => $_GET['id'],
        'name' => $data['name'],
        'luas_lahan' => $data['luas_lahan'],
        'wilayah_id' => $data['wilayah_id'],
        'lokasi' => $data['lokasi'],
    ], $connection)) {
        $response['message'] = 'Update lahan berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Update lahan gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::User);

router('PUT', '/wilayah', function() {
    global $connection;

    $response = [];

    $put = file_get_contents('php://input');

    $data = parse_form_data($put);

    if (update_daerah($connection, [
        'id' => $_GET['id'],
        'name' => $data['name'],
    ])) {
        $response['message'] = 'Update wilayah berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Update wilayah gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('PUT', '/pangan', function() {
    global $connection;

    $response = [];

    $put = file_get_contents('php://input');

    $data = parse_form_data($put);

    if (update_pangan($connection, [
        'id' => $_GET['id'],
        'name' => $data['name'],
    ])) {
        $response['message'] = 'Update pangan berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Update pangan gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('PUT', '/ruang-tani', function() {
    global $connection;

    $response = [];

    $put = file_get_contents('php://input');

    echo $put;

    $data = parse_form_data($put);

    if (update_post($connection, [
        'id' => $_GET['id'],
        'title' => $data['title'],
        'content' => $data['content'],
        'category' => $data['category'],
    ])) {
        $response['message'] = 'Update artikel berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Update artikel gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('PUT', '/ruang-tani/image', function() {
    global $connection;

    $response = [];

    $file = $_FILES['image'];

    $imgName = uploadImage($file, '../media/artikel/');

    if ($imgName['success'] === false) {
        echo json_encode($imgName);
        return;
    }

    if (update_post_image($connection, $_POST['id'], $imgName['file_path'])) {
        $response['message'] = 'Update artikel berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Update artikel gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('PUT', '/info_tanah', function() {
    global $connection;

    $response = [];

    $put = file_get_contents('php://input');

    $data = parse_form_data($put);

    if (update_soil_info($connection, [
        'id' => $_GET['id'],
        'content' => $data['content'],
    ])) {
        $response['message'] = 'Update info tanah berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Update info tanah gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('PUT', '/info_air', function() {
    global $connection;

    $response = [];

    $put = file_get_contents('php://input');

    $data = parse_form_data($put);

    if (update_water_info($connection, [
        'id' => $_GET['id'],
        'content' => $data['content'],
    ])) {
        $response['message'] = 'Update info air berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Update info air gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);

router('PUT', '/info_suhu', function() {
    global $connection;

    $response = [];

    $put = file_get_contents('php://input');

    $data = parse_form_data($put);

    if (update_temperature_info($connection, [
        'id' => $_GET['id'],
        'content' => $data['content'],
    ])) {
        $response['message'] = 'Update info suhu berhasil';
        $response['success'] = true;
    } else {
        $response['message'] = 'Update info suhu gagal';
        $response['success'] = false;
    }

    echo json_encode($response);
}, Permission::Admin);





function buildRouter($routes)
{
    return function () use ($routes) {
        foreach ($routes as $route) {
            router($route[0], $route[1], $route[2], $route[3], false);
        }
    };
}
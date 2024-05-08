<?php

// HelloController.php
// class HelloController {
//     public function index($params) {
//         echo json_encode(['message' => 'Hello, ' . $params['name'] . '!']);
//     }
// }

// // UserController.php
// class UserController {
//     public function register() {
//         global $connection;
//         $no_ktp = $_POST['no_ktp'];
//         $nama = $_POST['nama'];
//         $role_id = 2;
//         $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

//         if (register($no_ktp, $nama, $role_id, $password, $connection)) {
//             $response['message'] = 'Register berhasil';
//             $response['success'] = true;
//         } else {
//             $response['message'] = 'Register gagal';
//             $response['success'] = false;
//         }

//         echo json_encode($response);
//     }

//     public function login() {
//         global $connection;
//         $no_ktp = $_POST['no_ktp'];
//         $password = $_POST['password'];

//         $apiKey = login($no_ktp, $password, $connection);

//         if ($apiKey) {
//             $response['message'] = 'Login berhasil';
//             $response['success'] = true;
//             $response['api_key'] = $apiKey;
//         } else {
//             $response['message'] = 'Login gagal';
//             $response['success'] = false;
//         }

//         echo json_encode($response);
//     }

//     public static function get_user_by_apikey($api_key, $connection) {
//         $stmt = $connection->prepare("SELECT * FROM users WHERE token = :api_key");
//         $stmt->bindParam(':api_key', $api_key);
//         $stmt->execute();
//         $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
//         return $user ? $user : null;
//     }   
// }


?>
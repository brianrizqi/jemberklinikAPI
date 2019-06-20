<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../includes/Users.php';
require '../includes/Pemesanan.php';
require '../includes/Penyakit.php';
require '../includes/Analisa.php';

$app = new \Slim\App;
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Jember Klinik 2019");

    return $response;
});
$app->post('/regis', function (Request $request, Response $response) {
    if (!haveEmptyParameters(array('nama', 'email', 'password', 'alamat', 'no_telp', 'jenis_kelamin',
        'tanggal_lahir', 'level'), $request, $response)) {
        $request_data = $request->getParsedBody();

        $nama = $request_data['nama'];
        $email = $request_data['email'];
        $password = $request_data['password'];
        $alamat = $request_data['alamat'];
        $no_telp = $request_data['no_telp'];
        $jenis_kelamin = $request_data['jenis_kelamin'];
        $tanggal_lahir = $request_data['tanggal_lahir'];
        $level = $request_data['level'];


        $db = new Users;

        $result = $db->createUser($nama, $email, $password, $alamat, $no_telp, $jenis_kelamin, $tanggal_lahir, $level);

        if ($result == USER_CREATED) {
            $message = array();
            $message['error'] = false;
            $message['message'] = "User created successfully";

            $response->write(json_encode($message));

            return $response;
//                ->withHeader('Content-type', 'application/json')
//                ->withStatus(201);
        } else if ($result == USER_FAILURE) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Some error occurred";

            $response->write(json_encode($message));

            return $response;
//                ->withHeader('Content-type', 'application/json')
//                ->withStatus(422);
        } else if ($result == USER_EXISTS) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Email already exists";

            $response->write(json_encode($message));

            return $response;
//                ->withHeader('Content-type', 'application/json')
//                ->withStatus(422);
        }
    }
    return $response;
});
$app->post('/login', function (Request $request, Response $response) {
    if (!haveEmptyParameters(array('email', 'password'), $request, $response)) {
        $request_data = $request->getParsedBody();

        $email = $request_data['email'];
        $password = $request_data['password'];

        $db = new Users();

        $result = $db->login($email, $password);
        if ($result['error'] == false) {
            $message = array();
            $message['error'] = false;
            $message['message'] = "Login Successfull";
            $message['user'] = $result;

            $response->write(json_encode($message));
        } else {
            $message = array();
            $message['error'] = true;
            $message['message'] = "User Not Found";

            $response->write(json_encode($message));
        }
    }
    return $response;
});
$app->post('/getUserId', function (Request $request, Response $response) {
    if (!haveEmptyParameters(array('id_user'), $request, $response)) {
        $request_data = $request->getParsedBody();

        $id = $request_data['id_user'];

        $db = new Users();

        $result = $db->getUserId($id);
        if ($result['error'] == false) {
            $message = array();
            $message['error'] = false;
            $message['message'] = "Successfull";
            $message['user'] = $result;

            $response->write(json_encode($message));
        } else {
            $message = array();
            $message['error'] = true;
            $message['message'] = "User Not Found";

            $response->write(json_encode($message));
        }
    }
    return $response;
});
$app->post('/pemesanan', function (Request $request, Response $response) {
    if (!haveEmptyParameters(array('id_user', 'id_penyakit', 'nama', 'umur'), $request, $response)) {
        $request_data = $request->getParsedBody();

        $id_user = $request_data['id_user'];
        $id_penyakit = $request_data['id_penyakit'];
        $nama = $request_data['nama'];
        $umur = $request_data['umur'];


        $db = new Pemesanan();

        $result = $db->createPemesanan($id_user, $id_penyakit, $nama, $umur);

        if ($result == USER_CREATED) {
            $message = array();
            $message['error'] = false;
            $message['message'] = "Pemesanan Berhasil";

            $response->write(json_encode($message));

            return $response;
        } else if ($result == USER_FAILURE) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Pemesanan terjadi masalah";

            $response->write(json_encode($message));

            return $response;
        }
    }
    return $response;
});
$app->get('/getAllUsers', function (Request $request, Response $response) {
//    if (!haveEmptyParameters(array('id_user'), $request, $response)) {
    $request_data = $request->getParsedBody();

    $db = new Users();

    $result = $db->getAllUsers();
    $message = array();
    $message['error'] = false;
    $message['message'] = "Successfull";
    $message['user'] = $result;

    $response->write(json_encode($message));
//    }
    return $response;
});
$app->get('/getPemesanan', function (Request $request, Response $response) {
//    if (!haveEmptyParameters(array('id_user'), $request, $response)) {
    $request_data = $request->getParsedBody();

    $db = new Pemesanan();

    $result = $db->getPemesanan();
    $message = array();
    $message['error'] = false;
    $message['message'] = "Successfull";
    $message['pemesanan'] = $result;

    $response->write(json_encode($message));
//    }
    return $response;
});
$app->get('/getAntrian', function (Request $request, Response $response) {
    $request_data = $request->getParsedBody();

    $db = new Pemesanan();

    $result = $db->getAntrian();
    $message = array();
    $message['error'] = false;
    $message['message'] = "Successfull";
    $message['antrian'] = $result;

    $response->write(json_encode($message));
    return $response;
});
$app->post('/getPemesananId', function (Request $request, Response $response) {
    if (!haveEmptyParameters(array('id_user'), $request, $response)) {
        $request_data = $request->getParsedBody();

        $id_user = $request_data['id_user'];

        $db = new Pemesanan();

        $result = $db->getPemesananById($id_user);

        if ($result['error'] == false) {
            $message = array();
            $message['error'] = false;
            $message['message'] = "Ada Pemesanan";
            $message['pemesanan'] = $result;

            $response->write(json_encode($message));
        } else {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Tidak ada pemesanan";

            $response->write(json_encode($message));
        }
    }
    return $response;
});
$app->post('/riwayat', function (Request $request, Response $response) {
    if (!haveEmptyParameters(array('id_user'), $request, $response)) {
        $request_data = $request->getParsedBody();

        $id_user = $request_data['id_user'];

        $db = new Pemesanan();

        $result = $db->riwayat($id_user);
        $message = array();
        $message['error'] = false;
        $message['message'] = "Ada Pemesanan";
        $message['pemesanan'] = $result;
        $response->write(json_encode($message));
    }
    return $response;
});
$app->post('/verifPemesanan', function (Request $request, Response $response) {
    if (!haveEmptyParameters(array('verif', 'id_pemesanan'), $request, $response)) {
        $request_data = $request->getParsedBody();

        $id_pemesanan = $request_data['id_pemesanan'];
        $verif = $request_data['verif'];

        $db = new Pemesanan();

        $result = $db->verif($id_pemesanan, $verif);

        if ($result == USER_CREATED) {
            $message = array();
            $message['error'] = false;
            $message['message'] = "Verifikasi sukses";

            $response->write(json_encode($message));
        } else if (USER_EXISTS) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Masih Ada Pasien";

            $response->write(json_encode($message));
        } else if (USER_FAILURE) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Verifikasi Gagal";

            $response->write(json_encode($message));
        }
    }
    return $response;
});
$app->post('/kuota', function (Request $request, Response $response) {
    if (!haveEmptyParameters(array('kuota', 'jam_awal', 'jam_akhir'), $request, $response)) {
        $request_data = $request->getParsedBody();

        $kuota = $request_data['kuota'];
        $jam_awal = $request_data['jam_awal'];
        $jam_akhir = $request_data['jam_akhir'];

        $db = new Pemesanan();

        $result = $db->kuota($kuota, $jam_awal, $jam_akhir);

        if ($result == USER_CREATED) {
            $message = array();
            $message['error'] = false;
            $message['message'] = "Kuota Ditambahkan";

            $response->write(json_encode($message));
        } else if ($result == USER_FAILURE) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Kuota Tidak Berhasil Ditambahkan";

            $response->write(json_encode($message));
        } else if ($result == USER_EXISTS) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Hari ini Sudah Input Kuota";

            $response->write(json_encode($message));
        }
    }
    return $response;
});
$app->post('/editKuota', function (Request $request, Response $response) {
    if (!haveEmptyParameters(array('id_kuota', 'kuota', 'jam_awal', 'jam_akhir'), $request, $response)) {
        $request_data = $request->getParsedBody();

        $id_kuota = $request_data['id_kuota'];
        $kuota = $request_data['kuota'];
        $jam_awal = $request_data['jam_awal'];
        $jam_akhir = $request_data['jam_akhir'];

        $db = new Pemesanan();

        $result = $db->editKuota($id_kuota, $kuota, $jam_awal, $jam_akhir);

        if ($result == USER_CREATED) {
            $message = array();
            $message['error'] = false;
            $message['message'] = "Kuota Berhasil Diubah";

            $response->write(json_encode($message));
        } else if ($result == USER_FAILURE) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Kuota Tidak Berhasil Diubah";

            $response->write(json_encode($message));
        }
    }
    return $response;
});
$app->get('/getKuota', function (Request $request, Response $response) {
    $request_data = $request->getParsedBody();

    $db = new Pemesanan();

    $result = $db->getKuota();

    if ($result['error'] == false) {
        $message = array();
        $message['error'] = false;
        $message['message'] = "Ada Kuota";
        $message['kuota'] = $result;

        $response->write(json_encode($message));
    } else {
        $message = array();
        $message['error'] = true;
        $message['message'] = "Tidak Ada kuota";

        $response->write(json_encode($message));
    }
    return $response;
});
$app->get('/getPenyakit', function (Request $request, Response $response) {
    $request_data = $request->getParsedBody();

    $db = new Penyakit();

    $result = $db->getPenyakit();

    $message = array();
    $message['error'] = false;
    $message['message'] = "Ada Penyakit";
    $message['penyakit'] = $result;

    $response->write(json_encode($message));
    return $response;
});
$app->get('/analisa', function (Request $request, Response $response) {
    $request_data = $request->getParsedBody();

    $db = new Analisa();

    $result = $db->hitung();

    $message = array();
    $message['error'] = false;
    $message['message'] = $result;

    $response->write(json_encode($message));
    return $response;
});
$app->get('/cekNomor', function (Request $request, Response $response) {
    $request_data = $request->getParsedBody();

    $db = new Pemesanan();

    $result = $db->cekNomor();
    $message = array();
    if ($result['error'] == false) {
        $message['error'] = false;
        $message['nomor'] = $result['nomor'];
    } else {
        $message['error'] = true;
    }

    $response->write(json_encode($message));
    return $response->withHeader('Content-type', 'application/json')
        ->withStatus(200);
});
$app->post('/deleteUser', function (Request $request, Response $response) {
    if (!haveEmptyParameters(array('id_user'), $request, $response)) {
        $request_data = $request->getParsedBody();

        $id = $request_data['id_user'];

        $db = new Users();

        $result = $db->deleteUser($id);

        if ($result == USER_CREATED) {
            $message = array();
            $message['error'] = false;
            $message['message'] = "Data Berhasil Dihapus";

            $response->write(json_encode($message));
        } else if (USER_FAILURE) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Gagal";

            $response->write(json_encode($message));
        }
    }
    return $response;
});
$app->post('/updateUser', function (Request $request, Response $response) {
    if (!haveEmptyParameters(array('nama', 'email', 'password', 'alamat', 'no_telp', 'jenis_kelamin',
        'tanggal_lahir', 'id_user'), $request, $response)) {
        $request_data = $request->getParsedBody();

        $nama = $request_data['nama'];
        $email = $request_data['email'];
        $password = $request_data['password'];
        $alamat = $request_data['alamat'];
        $no_telp = $request_data['no_telp'];
        $jenis_kelamin = $request_data['jenis_kelamin'];
        $tanggal_lahir = $request_data['tanggal_lahir'];
        $id = $request_data['id_user'];


        $db = new Users();

        $result = $db->updateUser($nama, $email, $password, $alamat, $no_telp, $jenis_kelamin, $tanggal_lahir,$id);

        if ($result == USER_CREATED) {
            $message = array();
            $message['error'] = false;
            $message['message'] = "User updated successfully";

            $response->write(json_encode($message));

            return $response;
//                ->withHeader('Content-type', 'application/json')
//                ->withStatus(201);
        } else if ($result == USER_FAILURE) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Some error occurred";

            $response->write(json_encode($message));

            return $response;
//                ->withHeader('Content-type', 'application/json')
//                ->withStatus(422);
        } else if ($result == USER_EXISTS) {
            $message = array();
            $message['error'] = true;
            $message['message'] = "Email already exists";

            $response->write(json_encode($message));

            return $response;
//                ->withHeader('Content-type', 'application/json')
//                ->withStatus(422);
        }
    }
    return $response;
});

function haveEmptyParameters($required_params, $request, $response)
{
    $error = false;
    $error_params = '';
    $request_params = $request->getParsedBody();

    foreach ($required_params as $param) {
        if (!isset($request_params[$param]) || strlen($request_params[$param]) <= 0) {
            $error = true;
            $error_params .= $param . ', ';
        }
    }

    if ($error) {
        $error_detail = array();
        $error_detail['error'] = true;
        $error_detail['message'] = 'Required params ' . substr($error_params, 0, -2) . ' are missing or empty';
        $response->write(json_encode($error_detail));
    }
    return $error;
}

$app->run();

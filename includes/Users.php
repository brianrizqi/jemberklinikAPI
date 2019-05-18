<?php

class Users
{
    private $con;

    function __construct()
    {
        require_once dirname(__FILE__) . '/init.php';
        $db = new init();
        $this->con = $db->connect();
    }

    public function createUser($nama, $email, $password, $alamat, $no_telp, $jenis_kelamin, $tanggal_lahir, $bpjs, $level)
    {
        if (!$this->isEmailExists($email)) {
            $stmt = $this->con->prepare("INSERT INTO `users`(`nama`, `email`, `password`, `alamat`, `no_telp`,
 `jenis_kelamin`, `tanggal_lahir`, `bpjs`,`level`)
  VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssss",
                $nama, $email, $password, $alamat, $no_telp, $jenis_kelamin, $tanggal_lahir, $bpjs, $level);
            if ($stmt->execute()) {
                return USER_CREATED;
            } else {
                return USER_FAILURE;
            }
        }
        return USER_EXISTS;
    }

    public function login($email, $password)
    {
        $stmt = $this->con->prepare("select id_user,nama,email,password,alamat,no_telp,jenis_kelamin,tanggal_lahir,bpjs,level from users where email = ? and password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $stmt->bind_result($id_user, $nama, $email, $password, $alamat, $no_telp, $jenis_kelamin, $tanggal_lahir, $bpjs, $level);
        $user = array();
        if ($stmt->fetch() > 0) {
            $user['id_user'] = $id_user;
            $user['nama'] = $nama;
            $user['email'] = $email;
            $user['password'] = $password;
            $user['alamat'] = $alamat;
            $user['no_telp'] = $no_telp;
            $user['jenis_kelamin'] = $jenis_kelamin;
            $user['tanggal_lahir'] = $tanggal_lahir;
            $user['bpjs'] = $bpjs;
            $user['level'] = $level;
            $user['error'] = false;
        } else {
            $user['error'] = true;
        }
        return $user;
    }

    public function getUserId($id)
    {
        $stmt = $this->con->prepare("select id_user,nama,email,password,alamat,no_telp,jenis_kelamin,tanggal_lahir,bpjs,level from users where id_user = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($id_user, $nama, $email, $password, $alamat, $no_telp, $jenis_kelamin, $tanggal_lahir, $bpjs, $level);
        $user = array();
        if ($stmt->fetch() > 0) {
            $user['id_user'] = $id_user;
            $user['nama'] = $nama;
            $user['email'] = $email;
            $user['password'] = $password;
            $user['alamat'] = $alamat;
            $user['no_telp'] = $no_telp;
            $user['jenis_kelamin'] = $jenis_kelamin;
            $user['tanggal_lahir'] = $tanggal_lahir;
            $user['bpjs'] = $bpjs;
            $user['level'] = $level;
            $user['error'] = false;
        } else {
            $user['error'] = true;
        }
        return $user;
    }

    public function getAllUsers()
    {
        $level = "2";
        $stmt = $this->con->prepare("select id_user,nama,email,password,alamat,no_telp,jenis_kelamin,tanggal_lahir,bpjs from users where level = ?");
        $stmt->bind_param("s", $level);
        $stmt->execute();
        $stmt->bind_result($id_user, $nama, $email, $password, $alamat, $no_telp, $jenis_kelamin, $tanggal_lahir, $bpjs);
        $users = array();
        while ($stmt->fetch()) {
            $user = array();
            $user['id_user'] = $id_user;
            $user['nama'] = $nama;
            $user['email'] = $email;
            $user['password'] = $password;
            $user['alamat'] = $alamat;
            $user['no_telp'] = $no_telp;
            $user['jenis_kelamin'] = $jenis_kelamin;
            $user['tanggal_lahir'] = $tanggal_lahir;
            $user['bpjs'] = $bpjs;
            array_push($users, $user);
        }
        return $users;
    }

    private function isEmailExists($email)
    {
        $stmt = $this->con->prepare("select id_user from users where email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

}

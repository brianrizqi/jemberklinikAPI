<?php

class Pemesanan
{
    private $con;

    function __construct()
    {
        require_once dirname(__FILE__) . '/init.php';
        $db = new init();
        $this->con = $db->connect();
    }

    private function getAntrianSelesai()
    {
        $tanggal = date("Y-m-d");
        $statuss = "selesai";
        $selesai = $this->con->prepare("SELECT nomor FROM pemesanan where tanggal = ? and status = ? order by nomor desc limit 0,1");
        $selesai->bind_param("ss", $tanggal, $statuss);
        $selesai->execute();
        $selesai->bind_result($nomor);
        if ($selesai->fetch() > 0) {
            $nomorr = $nomor;
        } else {
            $nomorr = 0;
        }
        return $nomorr;
    }

    public function getAntrian()
    {
        $tanggal = date("Y-m-d");
        $status = "masuk";
        $selesai = $this->getAntrianSelesai();
        $antrian = $this->con->prepare("SELECT nomor FROM pemesanan where tanggal = ? and status = ?");
        $antrian->bind_param("ss", $tanggal, $status);
        $antrian->execute();
        $antrian->bind_result($nomor);

        $antri = array();
        if ($antrian->fetch() > 0) {
            $antri['antrian'] = $nomor;
            $antri['selesai'] = $selesai;
        } else {
            $antri['antrian'] = 0;
            $antri['selesai'] = $selesai;
        }
        return $antri;
    }

    public function cekNomor()
    {
        $tanggal = date("Y-m-d");
        $no = $this->con->prepare("SELECT DISTINCT `nomor` FROM `pemesanan` WHERE tanggal = ?");
        $no->bind_param('s', $tanggal);
        $no->execute();
        $no->bind_result($nomor);
        $item = array();
        if ($no->fetch() > 0) {
            $item['error'] = false;
            $item['nomor'] = $nomor;
        } else {
            $item['error'] = true;
        }
        return $item;
    }

    private function getNomor()
    {
        $tanggal = date("Y-m-d");
        $pemesanan = $this->con->prepare("SELECT `nomor` FROM `pemesanan` WHERE tanggal = ? ORDER BY 
created_at DESC LIMIT 0,1");
        $pemesanan->bind_param("s", $tanggal);
        $pemesanan->execute();
        $pemesanan->bind_result($nomor);
        if ($pemesanan->fetch() > 0) {
            $nomorr = $nomor + 1;
        } else {
            $nomorr = 1;
        }
        return $nomorr;
    }

    public function kuota($kuota, $jam_awal, $jam_akhir)
    {
        $tanggal = date("Y-m-d");
        if (!$this->isDateExists($tanggal)) {
            $stmt = $this->con->prepare("INSERT INTO `kuota`(`kuota`,`tanggal`,`jam_awal`,`jam_akhir`)
VALUES (?,?,?,?)");
            $stmt->bind_param("isss", $kuota, $tanggal, $jam_awal, $jam_akhir);
            if ($stmt->execute()) {
                return USER_CREATED;
            } else {
                return USER_FAILURE;
            }
        }
        return USER_EXISTS;
    }

    public function getKuota()
    {
        $tanggal = date("Y-m-d");
        $stmt = $this->con->prepare("SELECT id_kuota,kuota,jam_awal,jam_akhir FROM `kuota` 
WHERE tanggal = ?");
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        $stmt->bind_result($id_kuota, $kuota, $jam_awal, $jam_akhir);
        if ($stmt->fetch() > 0) {
            $kuotaa = array();
            $kuotaa['id_kuota'] = $id_kuota;
            $kuotaa['kuota'] = $kuota;
            $kuotaa['jam_awal'] = $jam_awal;
            $kuotaa['jam_akhir'] = $jam_akhir;
            $kuotaa['error'] = false;
        } else {
            $kuotaa['error'] = true;
        }
        return $kuotaa;
    }

    public function editKuota($id_kuota, $kuota, $jam_awal, $jam_akhir)
    {
        $stmt = $this->con->prepare("UPDATE `kuota` SET `kuota`=?,`jam_awal`=?,
`jam_akhir` = ? WHERE id_kuota = ?");
        $stmt->bind_param("sssi", $kuota, $jam_awal, $jam_akhir, $id_kuota);
        if ($stmt->execute()) {
            return USER_CREATED;
        } else {
            return USER_FAILURE;
        }
    }

    public function createPemesanan($id_user, $id_penyakit, $nama, $umur)
    {
        $status = "menunggu";
        $tanggal = date("Y-m-d");
        $nomorr = $this->getNomor();
        $stmt = $this->con->prepare("INSERT INTO `pemesanan`(`id_user`, `id_penyakit`,`nama`,`umur`, `tanggal`, `status`)
 VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ississ",
            $id_user, $id_penyakit, $nama, $umur, $tanggal, $status);
        if ($stmt->execute()) {
            return USER_CREATED;
        } else {
            return USER_FAILURE;
        }
    }

    public function getPemesanan()
    {
        $tanggal = date("Y-m-d");
        $stmt = $this->con->prepare("SELECT `id_pemesanan`, pemesanan.`id_user`, `keluhan`,
 `status`,pemesanan.`created_at` ,pemesanan.`nama`,users.`jenis_kelamin`,pemesanan.`nomor` FROM `pemesanan` 
 join users on users.id_user = pemesanan.id_user join penyakit p on pemesanan.id_penyakit = p.id_penyakit
 where pemesanan.tanggal = '$tanggal'");
        $stmt->execute();
        $stmt->bind_result($id_pemesanan, $id_user, $keluhan, $status, $created_at,
            $nama, $jenis_kelamin, $nomor);
        $pesan = array();
        while ($stmt->fetch()) {
            $pemesanan = array();
            $pemesanan['id_pemesanan'] = $id_pemesanan;
            $pemesanan['id_user'] = $id_user;
            $pemesanan['nama'] = $nama;
            $pemesanan['keluhan'] = $keluhan;
            $pemesanan['status'] = $status;
            $pemesanan['created_at'] = $created_at;
            $pemesanan['jenis_kelamin'] = $jenis_kelamin;
            $pemesanan['nomor'] = $nomor;
            array_push($pesan, $pemesanan);
        }
        usort($pesan, function($a, $b) {
            return $a['nomor'] <=> $b['nomor'];
        });
        return $pesan;
    }

    public function getPemesananById($id_user)
    {
        $tanggal = date("Y-m-d");
        $stmt = $this->con->prepare("SELECT `id_pemesanan`, `keluhan`,
 `status`,pemesanan.`created_at` ,users.`nama`,users.`jenis_kelamin`,`nomor` FROM `pemesanan` 
 join users on users.id_user = pemesanan.id_user join penyakit p on p.id_penyakit = pemesanan.id_penyakit
 where pemesanan.tanggal = ? and pemesanan.id_user = ?");
        $stmt->bind_param('si', $tanggal, $id_user);
        $stmt->execute();
        $stmt->bind_result($id_pemesanan, $keluhan, $status, $created_at, $nama, $jenis_kelamin, $nomor);
        $pemesanan = array();
        if ($stmt->fetch() > 0) {
            $pemesanan['id_pemesanan'] = $id_pemesanan;
            $pemesanan['nama'] = $nama;
            $pemesanan['keluhan'] = $keluhan;
            $pemesanan['status'] = $status;
            $pemesanan['created_at'] = $created_at;
            $pemesanan['jenis_kelamin'] = $jenis_kelamin;
            $pemesanan['nomor'] = $nomor;
            $pemesanan['error'] = false;
        } else {
            $pemesanan['error'] = true;
        }
        return $pemesanan;
    }

    public function verif($id_pemesanan, $verif)
    {
        $cek = $this->cekVerif();
        if ($cek == true) {
            $stmt = $this->con->prepare("UPDATE `pemesanan` SET `status`= ? WHERE id_pemesanan = ?");
            $stmt->bind_param('si', $verif, $id_pemesanan);
            if ($stmt->execute()) {
                return USER_CREATED;
            } else {
                return USER_FAILURE;
            }
        } else {
            if ($verif == "selesai") {
                $stmt = $this->con->prepare("UPDATE `pemesanan` SET `status`= ? WHERE id_pemesanan = ?");
                $stmt->bind_param('si', $verif, $id_pemesanan);
                if ($stmt->execute()) {
                    return USER_CREATED;
                } else {
                    return USER_FAILURE;
                }
            } else {
                return USER_EXISTS;
            }
        }
    }

    public function cekVerif()
    {
        $tanggal = date("Y-m-d");
        $status = "masuk";
        $stmt = $this->con->prepare("SELECT id_pemesanan FROM pemesanan where tanggal = ? and status = ?");
        $stmt->bind_param('ss', $tanggal, $status);
        $stmt->execute();
        $stmt->bind_result($id_pemesanan);
        if ($stmt->fetch() > 0) {
            $response = false;
        } else {
            $response = true;
        }
        return $response;
    }

    private function isDateExists($tanggal)
    {
        $stmt = $this->con->prepare("select id_kuota from kuota where tanggal = ?");
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}

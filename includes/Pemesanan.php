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
        $selesai = $this->con->prepare("SELECT nomor FROM antrean where tanggal = ? and status = ? order by nomor desc limit 0,1");
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
        $antrian = $this->con->prepare("SELECT nomor FROM antrean where tanggal = ? and status = ?");
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
        $no = $this->con->prepare("SELECT DISTINCT `nomor` FROM `antrean` WHERE tanggal = ?");
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
        $pemesanan = $this->con->prepare("SELECT `nomor` FROM `antrean` WHERE tanggal = ? ORDER BY 
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
        $kuota = $this->getKuota();
        $kuo = 0;
        if ($kuota['error'] == false) {
            $kuo = $kuota['id_kuota'];
        } else {
            $kuo = 0;
        }
        $status = "menunggu";
        $tanggal = date("Y-m-d");
        $jumlahKuota = $this->getJumlahKuota();
        $jumlahPesan = $this->getJumlahPesan();
        if ($jumlahPesan >= $jumlahKuota) {
            return USER_FAILURE;
        } else {
            $stmt = $this->con->prepare("INSERT INTO `antrean`(`id_user`, `id_penyakit`,`id_kuota`,`nama`,`umur`, `tanggal`, `status`)
 VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param("isisiss",
                $id_user, $id_penyakit, $kuo, $nama, $umur, $tanggal, $status);
            if ($stmt->execute()) {
                return USER_CREATED;
            } else {
                return USER_FAILURE;
            }
        }
    }

    private function getJumlahKuota()
    {
        $tanggal = date("Y-m-d");
        $stmt = $this->con->prepare("SELECT kuota FROM `kuota` 
WHERE tanggal = ?");
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        $stmt->bind_result($kuota);
        $stmt->fetch();
        return $kuota;
    }

    private function getJumlahPesan()
    {
        $tanggal = date("Y-m-d");
        $stmt = $this->con->prepare("SELECT COUNT(*) as jumlah FROM `antrean` 
WHERE tanggal = ?");
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        $stmt->bind_result($jumlah);
        $stmt->fetch();
        return $jumlah;
    }

    public function getPemesanan()
    {
        $tanggal = date("Y-m-d");
        $stmt = $this->con->prepare("SELECT `id_pemesanan`, antrean.`id_user`, `keluhan`,
 `status`,antrean.`created_at` ,antrean.`nama`,users.`jenis_kelamin`,antrean.`nomor` FROM `antrean` 
 join users on users.id_user = antrean.id_user join penyakit p on antrean.id_penyakit = p.id_penyakit
 where antrean.tanggal = '$tanggal'");
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
        usort($pesan, function ($a, $b) {
            return $a['nomor'] <=> $b['nomor'];
        });
        return $pesan;
    }

    public function getPemesananById($id_user)
    {
        $tanggal = date("Y-m-d");
        $stmt = $this->con->prepare("SELECT `id_pemesanan`, `keluhan`,
 `status`,antrean.`created_at` ,antrean.`nama`,users.`jenis_kelamin`,`nomor` FROM `antrean` 
 join users on users.id_user = antrean.id_user join penyakit p on p.id_penyakit = antrean.id_penyakit
 where antrean.tanggal = ? and antrean.id_user = ?");
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

    public function riwayat($id_user)
    {
        $stmt = $this->con->prepare("SELECT `id_pemesanan`, `keluhan`,antrean.`nama`,`tanggal`FROM `antrean` 
 join users on users.id_user = antrean.id_user join penyakit p on p.id_penyakit = antrean.id_penyakit
 where antrean.id_user = ?");
        $stmt->bind_param('i', $id_user);
        $stmt->execute();
        $stmt->bind_result($id_pemesanan, $keluhan, $nama, $tanggal);
        $pesan = array();
        while ($stmt->fetch()) {
            $pemesanan = array();
            $pemesanan['id_pemesanan'] = $id_pemesanan;
            $pemesanan['id_user'] = $id_user;
            $pemesanan['nama'] = $nama;
            $pemesanan['keluhan'] = $keluhan;
            $pemesanan['tanggal'] = $tanggal;
            array_push($pesan, $pemesanan);
        }
        return $pesan;
    }

    public function verif($id_pemesanan, $verif)
    {
        $cek = $this->cekVerif();
        if ($cek == true) {
            $stmt = $this->con->prepare("UPDATE `antrean` SET `status`= ? WHERE id_pemesanan = ?");
            $stmt->bind_param('si', $verif, $id_pemesanan);
            if ($stmt->execute()) {
                return USER_CREATED;
            } else {
                return USER_FAILURE;
            }
        } else {
            if ($verif == "selesai") {
                $stmt = $this->con->prepare("UPDATE `antrean` SET `status`= ? WHERE id_pemesanan = ?");
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
        $stmt = $this->con->prepare("SELECT id_pemesanan FROM antrean where tanggal = ? and status = ?");
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

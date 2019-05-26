<?php

class Analisa
{
    private $con;

    function __construct()
    {
        require_once dirname(__FILE__) . '/init.php';
        $db = new init();
        $this->con = $db->connect();
    }

    public function hitung()
    {
        $tanggal = date("Y-m-d");
        $stmt = $this->con->prepare("SELECT `id_pemesanan`, `id_user`, `id_penyakit`,`umur`,`created_at` FROM `pemesanan` 
 where pemesanan.tanggal = '$tanggal'");
        $stmt->execute();
        $stmt->bind_result($id_pemesanan, $id_user, $id_penyakit, $umur, $created_at);
        $pesan = array();
        while ($stmt->fetch()) {
            $pemesanan = array();
            $pemesanan['id_pemesanan'] = $id_pemesanan;
            $pemesanan['id_user'] = $id_user;
            $pemesanan['id_penyakit'] = $id_penyakit;
            $pemesanan['umur'] = $umur;
            array_push($pesan, $pemesanan);
        }
        $hasil = array();
        $stmt->close();
        foreach ($pesan as $item) {
            $analisa = array();
            $bobotUmur = 0;
            if ($item['umur'] > 0 && $item['umur'] <= 11) {
                $bobotUmur = 1;
            } else if ($item['umur'] > 11 && $item['umur'] <= 25) {
                $bobotUmur = 0.5;
            } else if ($item['umur'] > 25 && $item['umur'] <= 45) {
                $bobotUmur = 0.25;
            } else if ($item['umur'] > 45) {
                $bobotUmur = 0.75;
            }
            $sql = $this->con->query("select bobot from penyakit where id_penyakit = '" . $item['id_penyakit'] . "'");
            while ($row = $sql->fetch_assoc()) {
                $bobotPenyakit = $row;
            }
            $analisa["bobotPenyakit"] = round(round($bobotPenyakit['bobot'], 1) * 0.6, 2);
            $analisa['id_pemesanan'] = $item['id_pemesanan'];
            $analisa["bobotUmur"] = round(round($bobotUmur, 1) * 0.4, 1);
            $analisa['normalisasi'] = round((round($bobotUmur, 1) * 0.4) + (round(round($bobotPenyakit['bobot'], 1) * 0.6, 2)), 2);
            array_push($hasil, $analisa);
        }
        $i = 1;
        foreach ($hasil as $item) {
            $stmt = $this->con->prepare("UPDATE `pemesanan` SET `nomor`= ? WHERE id_pemesanan = ?");
            $stmt->bind_param('ii', $i, $item['id_pemesanan']);
            if ($stmt->execute()) {
                $i++;
            }

        }
        usort($hasil, function ($a, $b) {
            if ($a['normalisasi'] == $b['normalisasi']) return 0;
            return $a['normalisasi'] < $b['normalisasi'] ? 1 : -1;
        });
        return "Perhitungan Selesai";
    }
}

?>

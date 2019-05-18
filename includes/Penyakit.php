<?php

class Penyakit
{
    private $con;

    function __construct()
    {
        require_once dirname(__FILE__) . '/init.php';
        $db = new init();
        $this->con = $db->connect();
    }

    public function getPenyakit()
    {
        $stmt = $this->con->prepare("select id_penyakit,nama_penyakit,keluhan from penyakit");
        $stmt->execute();
        $stmt->bind_result($id_penyakit, $nama_penyakit, $keluhan);
        $penyakit = array();
        while ($stmt->fetch()) {
            $item = array();
            $item['id_penyakit'] = $id_penyakit;
            $item['nama_penyakit'] = $nama_penyakit;
            $item['keluhan'] = $keluhan;
            array_push($penyakit, $item);
        }
        return $penyakit;
    }

    public function getBobot()
    {
        $stmt = $this->con->prepare("select id_bobot,awal_umur,akhir_umur,bobot_persen,bobot_nilai from bobot");
        $stmt->execute();
        $stmt->bind_result($id_bobot, $awal_umur,$akhir_umur, $bobot_persen, $bobot_nilai);
        $bobot = array();
        while ($stmt->fetch()) {
            $item = array();
            $item['id_bobot'] = $id_bobot;
            $item['awal_umur'] = $awal_umur;
            $item['akhir_umur'] = $akhir_umur;
            $item['bobot_persen'] = $bobot_persen;
            $item['bobot_nilai'] = $bobot_nilai;
            array_push($bobot, $item);
        }
        return $bobot;
    }
}

?>

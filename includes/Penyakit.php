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
        $stmt = $this->con->prepare("select id_penyakit,keluhan from penyakit");
        $stmt->execute();
        $stmt->bind_result($id_penyakit,  $keluhan);
        $penyakit = array();
        while ($stmt->fetch()) {
            $item = array();
            $item['id_penyakit'] = $id_penyakit;
            $item['keluhan'] = $keluhan;
            array_push($penyakit, $item);
        }
        return $penyakit;
    }
}

?>

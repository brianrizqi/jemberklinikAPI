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

    public function hitung($keluhan, $kriteria)
    {
        if ($kriteria == 'C1') {
            $nilai_max = 1;
        } else if ($kriteria == 'C2') {
            $nilai_max = 0.75;
        } else if ($kriteria == 'C3') {
            $nilai_max = 1;
        } else {
            $nilai_max = 1;
        }
        if (($keluhan == 'A1' && $kriteria == 'C1') ||
            ($keluhan == 'A2' && $kriteria == 'C1') ||
            ($keluhan == 'A3' && $kriteria == 'C1') ||
            ($keluhan == 'A8' && $kriteria == 'C1') ||
            ($keluhan == 'A9' && $kriteria == 'C1') ||
            ($keluhan == 'A10' && $kriteria == 'C1') ||
            ($keluhan == 'A11' && $kriteria == 'C1') ||
            ($keluhan == 'A7' && $kriteria == 'C3') ||
            ($keluhan == 'A4' && $kriteria == 'C4') ||
            ($keluhan == 'A5' && $kriteria == 'C4') ||
            ($keluhan == 'A6' && $kriteria == 'C4')
        ) {
            $nilai = 1;
        } else if (
            ($keluhan == 'A4' && $kriteria == 'C1') ||
            ($keluhan == 'A6' && $kriteria == 'C1') ||
            ($keluhan == 'A7' && $kriteria == 'C1') ||
            ($keluhan == 'A10' && $kriteria == 'C3') ||
            ($keluhan == 'A7' && $kriteria == 'C4') ||
            ($keluhan == 'A9' && $kriteria == 'C4') ||
            ($keluhan == 'A11' && $kriteria == 'C4')
        ) {
            $nilai = 0;
        } else if (
            ($keluhan == 'A1' && $kriteria == 'C2') ||
            ($keluhan == 'A3' && $kriteria == 'C4') ||
            ($keluhan == 'A5' && $kriteria == 'C2') ||
            ($keluhan == 'A6' && $kriteria == 'C2') ||
            ($keluhan == 'A8' && $kriteria == 'C3') ||
            ($keluhan == 'A9' && $kriteria == 'C2') ||
            ($keluhan == 'A9' && $kriteria == 'C3') ||
            ($keluhan == 'A11' && $kriteria == 'C3')
        ) {
            $nilai = 0.5;
        } else if (
            ($keluhan == 'A1' && $kriteria == 'C4') ||
            ($keluhan == 'A2' && $kriteria == 'C4') ||
            ($keluhan == 'A4' && $kriteria == 'C3') ||
            ($keluhan == 'A5' && $kriteria == 'C3') ||
            ($keluhan == 'A6' && $kriteria == 'C3') ||
            ($keluhan == 'A7' && $kriteria == 'C2') ||
            ($keluhan == 'A8' && $kriteria == 'C4') ||
            ($keluhan == 'A10' && $kriteria == 'C4') ||
            ($keluhan == 'A11' && $kriteria == 'C2')
        ) {
            $nilai = 0.75;
        } else {
            $nilai = 0.25;
        }
        return $nilai / $nilai_max;
    }
}

?>

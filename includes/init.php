<?php

class init
{
    private $con;

    function connect()
    {
        include dirname(__FILE__) . '/Constant.php';

        $this->con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (mysqli_connect_errno()) {
            echo "Failed to connect" . mysqli_connect_error();
            return null;
        }

        return $this->con;
    }
}

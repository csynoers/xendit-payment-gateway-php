<?php
    function read_file($filename)
    {
        return json_decode( file_get_contents($filename) );
    }
    function overwrite_file($filename,$rows)
    {
        return file_put_contents($filename, json_encode($rows) );
    }
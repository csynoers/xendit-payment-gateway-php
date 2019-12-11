<?php
    require_once("helper_file.php");
    $data = NULL;
    switch ($_GET['data']) {
        case 'products':
            # code...
            $data = read_file("json/products.json");
            break;
        
        default:
            # code...
            break;
    }
    echo json_encode($data);
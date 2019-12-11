<?php
    require_once("helper_file.php");
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $data = file_get_contents("php://input");
        
        $orders     = read_file("json/result.json");
        $orders[]   = $data;
        overwrite_file("json/orders.json",$orders);

        print_r("\n\$data contains the updated invoice data \n\n");
        print_r($data);
        print_r("\n\nUpdate your database with the invoice status \n\n");
    } else {
        print_r("Cannot ".$_SERVER["REQUEST_METHOD"]." ".$_SERVER["SCRIPT_NAME"]);
    }
?>
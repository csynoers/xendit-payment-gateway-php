<?php
    require_once("helper_file.php");
    $data = NULL;
    switch ($_GET['data']) {
        case 'products':
            # code...
            $data = read_file("json/products.json");
            break;
        
        case 'carts':
            session_start();
            $data = empty( $_SESSION[session_id()] ) ? [] : $_SESSION[session_id()] ;
            break;
        
        case 'add-carts':
            session_start();
            $data = read_file("json/products.json");
            $data = $data[ $_GET['id'] ];
            if ( empty($_SESSION[session_id()]) ) {
                $data->qty = 1;
                $data->subtotal = $data->price;

                $_SESSION[ session_id() ][ $_GET['id'] ] = $data;
                
                $data = [
                    'status' => 1,
                    'msg'    => "Cart added"
                ];

            } else {
                if ( empty( $_SESSION[ session_id() ][ $_GET['id'] ] ) ) {
                    $data->qty = 1;
                    $data->subtotal = $data->price;
    
                    $_SESSION[ session_id() ][ $_GET['id'] ] = $data;
                    
                    $data = [
                        'status' => 1,
                        'msg'    => "Cart added"
                    ];

                } else {
                    $data = $_SESSION[ session_id() ][ $_GET['id'] ];
                    $data->qty      += 1;
                    $data->subtotal = ($data->price * $data->qty);
                    if ( $data->qty > $data->stock ) {
                        $data = [
                            'status' => 0,
                            'msg'    => "Sorry, Qty is limited"
                        ];
                    } else {
                        $_SESSION[ session_id() ][ $_GET['id'] ] = $data;
                        $data = [
                            'status' => 1,
                            'msg'    => "Cart added"
                        ];
                    }

                }
            }

            break;
        
        case 'create-invoice':
            session_start();
            require 'vendor/autoload.php';
            $options['secret_api_key'] = 'xnd_development_sSfLeSQ42NqJDsLb7P3vi5panydMvuVZgSp738cHrQFlwUv5rH4AGIApBVFBP';
            $xenditPHPClient = new XenditClient\XenditPHPClient($options);

            $external_id    = session_id();
            $amount         = $_GET['total'];
            $payer_email    = $_GET['email'];
            $description    = [];

            foreach ($_SESSION[ session_id() ] as $key => $value) {
                $description[] = "{$value->name} ({$value->qty}x)";
            }
            $description = 'Pembelian: ' .implode(',',$description);
            session_destroy();

            // create invoice
            $data = $xenditPHPClient->createInvoice($external_id, $amount, $payer_email, $description);
            $orders     = read_file("json/orders.json");
            $orders[]   = $data;
            overwrite_file("json/orders.json",$orders);

            $data = [
                'status'        => 'true',
                "invoice_url"   => $data['invoice_url'],
            ];
            break;

        case 'get-invoice':
            $data = null;
            foreach (read_file("json/orders.json") as $key => $value) {
                if ( $value->id==$_GET['id'] ) {
                    $data = $value;
                }
            }

            $data = [
                'status'        => 'true',
                "invoice_url"   => $data->invoice_url,
            ];
            break;
        
        case 'orders':
            $data     = read_file("json/orders.json");
            break;
        
        default:
            # code...
            break;
    }
    echo json_encode($data);
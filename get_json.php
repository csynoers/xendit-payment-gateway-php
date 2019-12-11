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
            foreach ($data as $key => $value) {
                if ( $value->product_id==$_GET['id'] ) {
                    $data = $value;
                }
            }
            if ( empty($_SESSION[session_id()]) ) {
                $data->qty = 1;
                $data->subtotal = $data->price;
                session_regenerate_id();
                $_SESSION[ session_id() ][] = $data;
                
                $data = [
                    'status' => 1,
                    'msg'    => "Cart added"
                ];

            } else {
                $rows = $_SESSION[ session_id() ];
                $rowsKey = NULL;
                foreach ($rows as $key => $value) {
                    if ( $value->product_id==$_GET['id'] ) {
                        $rowsKey = $key;
                    }
                }

                if ( empty( $rows[$rowsKey] ) ) {
                    $data->qty = 1;
                    $data->subtotal = $data->price;

                    $_SESSION[ session_id() ][] = $data;

                    $data = [
                        'status' => 1,
                        'msg'    => "Cart added"
                    ];

                } else {
                    $data = $_SESSION[ session_id() ][ $rowsKey ];
                    if ( $data->qty > $data->stock ) {
                        $data = [
                            'status' => 0,
                            'msg'    => "Sorry, Qty is limited"
                        ];
                    } else {
                        $data->qty      += 1;
                        $data->subtotal = ($data->price * $data->qty);

                        $_SESSION[ session_id() ][ $rowsKey ] = $data;
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

            // create invoice
            $data   = $xenditPHPClient->createInvoice($external_id, $amount, $payer_email, $description);
            $orders = read_file("json/orders.json");
            // $data           = $xenditPHPClient->createInvoice('5df13d3003a3cd3f45eb9317');
            // echo '<pre>';
            // // print_r([(object) $data]);
            // echo json_encode(array_merge($orders,[(object) $data]));
            // echo '</pre>';
            // die();
            overwrite_file("json/orders.json",array_merge($orders,[(object) $data]));

            $data = [
                'status'        => 'true',
                "invoice_url"   => $data['invoice_url'],
            ];
            session_destroy();
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
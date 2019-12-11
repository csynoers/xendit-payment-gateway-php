<?php
    require 'vendor/autoload.php';

    $options['secret_api_key'] = 'xnd_development_sSfLeSQ42NqJDsLb7P3vi5panydMvuVZgSp738cHrQFlwUv5rH4AGIApBVFBP';

    // $xenditPHPClient = new XenditClient\XenditPHPClient($options);

    // $external_id = 'demo_1475801962607';
    // $amount = 230000;
    // $payer_email = 'sample_email@xendit.co';
    // $description = 'Trip to Bali';

    // $response = $xenditPHPClient->createInvoice($external_id, $amount, $payer_email, $description);

    class Invoices extends XenditClient\XenditPHPClient
    {
        public function create($data)
        {
            /*
                $data = [
                    'external_id'    => 'demo_1475801962607',
                    'amount'         => 230000,
                    'payer_email'    => 'sample_email@xendit.co',
                    'description'    => 'Trip to Bali'
                ];
            */

            return $this->createInvoice($data['external_id'], $data['amount'], $data['payer_email'], $data['description']);
        }
        public function get($invoice_id)
        {
            return $this->getInvoice($invoice_id);
        }   
    }

    session_start();
    $invoices = new Invoices($options);
    if ( session_id() ) {
        echo '<pre>';
        print_r($_SESSION[session_id()][0]);
        echo '</pre>';

    } else {
        session_create_id();
        for ($i=0; $i < 5 ; $i++) { 
            $data_create_invoice = [
                'external_id'    => ($i+1) .'-' .session_id(),
                'amount'         => ($i+1) * 1000000,
                'payer_email'    => 'email_' .($i+1) .'@gmail.com',
                'description'    => 'Deskripsi pembelian ' .($i+1)
            ];
            $_SESSION[session_id()][] = $invoices->create($data_create_invoice);
        }
    }
    
?>
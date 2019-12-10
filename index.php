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
    $invoices = new Invoices($options);
    $data_create_invoice = [
        'external_id'    => 'demo_1475801962607',
        'amount'         => 230000,
        'payer_email'    => 'sample_email@xendit.co',
        'description'    => 'Trip to Bali'
    ];
    // $invoices->create($data_create_invoice);
    
?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

class Checkout extends MY_Controller
{

    function __construct()
    {
        $this->_module = trim(strtolower(__CLASS__));
        parent::__construct();

        if (!$this->_isLogin()) {
            if ($this->input->is_ajax_request()) {
                echo 'unlogin';
                die();
            }
            $currUrl = getCurrentUrl();
            dbClose();
            redirect(site_url('login/?url=' . urlencode($currUrl), $this->_langcode));
            die();
        }

        $this->load->model('order/Order_model');
        $this->load->model('payment/Payment_model');
    }

    function _paypal($id_order)
    {
        require_once("app/appv3.1.4/libraries/paypal/autoload.php");

        // integrate paypal 
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                'ASCWm_V76KLw3VAezYv8JJNyrJipJROjRpzzqLligNYw0PXD_gafFIU4bST7354x7bqbANmVZcIURloL',
                'EAtILCsdldsvTMUsvB6hHvslGJZjFBQIIRnjkJs7UPGPut1_BfnVoBtciSn7O_6KbvWG1LJChOxkOdJF'
            )
        );
        $apiContext->setConfig([
            'mode' => 'sandbox'
        ]);

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        //Itemized information (Optional) Lets you specify item wise information

        $item1 = new Item();
        $item1->setName('Ground Coffee 40 oz')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice(17);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        $details = new Details();
        $details->setShipping(1)
            ->setTax(2)
            ->setSubtotal(17);

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(20)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());

        $url_success = site_url('checkout/success');
        $url_cancle = site_url('checkout/cancle');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($url_success)
            ->setCancelUrl($url_cancle);

        $payment = new Payment();


        $payment->setIntent("order")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        //For Sample Purposes Only.

        // $request = clone $payment;

        $payment->create($apiContext);
        try {
            $payment->create($apiContext);
        } catch (Exception $e) {
            throw new Exception('Unable to create link for payment');
        }

        header('location:' . $payment->getApprovalLink());
        exit(1);
    }

    function paypal($id_order)
    {
        $cur_uid     = $this->_session_uid();
        $id_order = isIdNumber($id_order) ? $id_order : 0;

        $LOCAL_PAY = $this->input->get('l');

        $order = $this->Order_model->get_info_order($id_order);
        if (empty($order) || $order['id_user'] != $cur_uid) {
            resError('Bạn không có quyền truy cập đến đơn hàng này');
        }


        $list_payment = $this->Payment_model->get_list_payment_by_order($id_order);

        // tong tien can thanh toan cua don hang
        $amountPayable = 0;
        foreach ($list_payment as $pay) {
            if ($pay['is_payment'] == PAY_DANG_CHO) {
                $price = floatval($pay['price']);
                $price_vou = floatval($pay['price_voucher']);
                $price_pay = $price > $price_vou ? ($price - $price_vou) : 0;
                $amountPayable += $price_pay;
            }
        }

        if ($amountPayable > 0) {

            require_once("app/appv3.1.4/libraries/paypal/autoload.php");

            // integrate paypal 
            $apiContext = new ApiContext(
                new OAuthTokenCredential(
                    'ASCWm_V76KLw3VAezYv8JJNyrJipJROjRpzzqLligNYw0PXD_gafFIU4bST7354x7bqbANmVZcIURloL',
                    'EAtILCsdldsvTMUsvB6hHvslGJZjFBQIIRnjkJs7UPGPut1_BfnVoBtciSn7O_6KbvWG1LJChOxkOdJF'
                )
            );
            $apiContext->setConfig([
                'mode' => 'sandbox'
            ]);

            $payer = new Payer();
            $payer->setPaymentMethod("paypal");
            //Itemized information (Optional) Lets you specify item wise information

            $item1 = new Item();
            $item1->setName('Ground Coffee 40 oz')
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice(17);

            $itemList = new ItemList();
            $itemList->setItems(array($item1));

            $details = new Details();
            $details->setShipping(1)
                ->setTax(2)
                ->setSubtotal(17);

            $amount = new Amount();
            $amount->setCurrency("USD")
                ->setTotal(20)
                ->setDetails($details);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription("Payment description")
                ->setInvoiceNumber(uniqid());

            $url_success = site_url('checkout/success');
            $url_cancle = site_url('checkout/cancle');

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($url_success)
                ->setCancelUrl($url_cancle);

            $payment = new Payment();


            $payment->setIntent("order")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));
            //For Sample Purposes Only.

            // $request = clone $payment;

            $payment->create($apiContext);
            try {
                $payment->create($apiContext);
            } catch (Exception $e) {
                throw new Exception('Unable to create link for payment');
            }

            header('location:' . $payment->getApprovalLink());
            exit(1);

            // require_once("app/appv3.1.4/libraries/paypal/autoload.php");

            // $enableSandbox = true;
            // $paypalConfig = [
            //     'client_id'     => 'ASCWm_V76KLw3VAezYv8JJNyrJipJROjRpzzqLligNYw0PXD_gafFIU4bST7354x7bqbANmVZcIURloL',
            //     'client_secret' => 'EAtILCsdldsvTMUsvB6hHvslGJZjFBQIIRnjkJs7UPGPut1_BfnVoBtciSn7O_6KbvWG1LJChOxkOdJF',
            //     'return_url'    => site_url('checkout/success'),
            //     'cancel_url'    => site_url('checkout/cancle'),
            // ];

            // $payer = new Payer();
            // $payer->setPaymentMethod('paypal');

            // // Set some example data for the payment.
            // $currency      = 'USD';
            // $l             = 'xxxxxx';              // $_GET['l']
            // $description   = 'Paypal transaction';
            // $invoiceNumber = uniqid();
            // $my_items = array(
            //     array('id_order' => $id_order, 'l' => $l)
            // );

            // $amount = new Amount();
            // $amount->setCurrency($currency)
            //     ->setTotal($amountPayable);

            // $items = new ItemList();
            // $items->setItems($my_items);

            // $transaction = new Transaction();
            // $transaction->setAmount($amount)
            //     ->setDescription($description)
            //     ->setInvoiceNumber($invoiceNumber)
            //     ->setItemList($items);

            // $redirectUrls = new RedirectUrls();
            // $redirectUrls->setReturnUrl($paypalConfig['return_url'] . '?')
            //     ->setCancelUrl($paypalConfig['cancel_url']);

            // $apiContext = new ApiContext(
            //     new OAuthTokenCredential($paypalConfig['client_id'], $paypalConfig['client_secret'])
            // );

            // $apiContext->setConfig([
            //     'mode' => $enableSandbox ? 'sandbox' : 'live'
            // ]);

            // $payment = new Payment();
            // $payment->setIntent('sale')
            //     ->setPayer($payer)
            //     ->setTransactions([$transaction])
            //     ->setRedirectUrls($redirectUrls);


            // try {
            //     $payment->create($apiContext);
            // } catch (Exception $e) {
            //     throw new Exception('Unable to create link for payment');
            // }

            // header('location:' . $payment->getApprovalLink());
            // exit(1);
        } else {
            echo "<script>localStorage.setItem('local_storage_pay', " . PAY_HOAN_THANH . ");window.close()</script>";
            die();
        }
    }

    function success()
    {
        die('success');
        $LOCAL_PAY = $this->input->get('l');
        $local_storage_pay = PAY_HOAN_THANH;
        echo "<script>localStorage.setItem($LOCAL_PAY, $local_storage_pay);window.close()</script>";

        // $id_order = $this->input->get('id_order');
        // $status = $this->input->get('status');
        // $LOCAL_PAY = $this->input->get('l');


        // $trancsion = 'xxxxxx';
        // $type_pay = PAYPAL;
        // $update_time = date('Y-m-d H:i:s');
        // if ($status == PAY_HOAN_THANH) {
        //     $this->Payment_model->update_status_payment_by_id_order($id_order, PAY_HOAN_THANH, $type_pay, $trancsion, $update_time);
        //     $this->Order_model->update_status_order($id_order, ORDER_PENDING);

        //     $local_storage_pay = PAY_HOAN_THANH;
        // } else {
        //     $local_storage_pay = PAY_HUY;
        // }

        // echo "<script>localStorage.setItem($LOCAL_PAY, $local_storage_pay);window.close()</script>";
    }

    function cancle()
    {
        die('cancle');
        $LOCAL_PAY = $this->input->get('l');
        $local_storage_pay = PAY_HUY;
        echo "<script>localStorage.setItem($LOCAL_PAY, $local_storage_pay);window.close()</script>";
    }
}

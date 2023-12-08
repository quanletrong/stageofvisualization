<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once("app/appv3.1.4/libraries/paypal/autoload.php");

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

use PayPal\Api\PaymentExecution;

class Checkout extends MY_Controller
{

    private $_apiContext;

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

        $this->_apiContext = new ApiContext(
            new OAuthTokenCredential(
                'ASCWm_V76KLw3VAezYv8JJNyrJipJROjRpzzqLligNYw0PXD_gafFIU4bST7354x7bqbANmVZcIURloL',
                'EAtILCsdldsvTMUsvB6hHvslGJZjFBQIIRnjkJs7UPGPut1_BfnVoBtciSn7O_6KbvWG1LJChOxkOdJF'
            )
        );
        $this->_apiContext->setConfig([
            'mode' => 'sandbox'
        ]);
    }

    function _paypal($id_order)
    {
        // integrate paypal

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

        $payment->create($this->_apiContext);
        try {
            $payment->create($this->_apiContext);
        } catch (Exception $e) {
            throw new Exception('Unable to create link for payment');
        }

        header('location:' . $payment->getApprovalLink());
        exit(1);
    }

    function pay($id_order)
    {
        $cur_uid     = $this->_session_uid();
        $id_order = isIdNumber($id_order) ? $id_order : 0;

        $ID_POPUP = removeAllTags($this->input->get('ID_POPUP'));

        $order = $this->Order_model->get_info_order($id_order);
        if (empty($order) || $order['id_user'] != $cur_uid) {
            resError('Bạn không có quyền truy cập đến đơn hàng này'); //TODO: cho ra trang báo lỗi 
        }


        $list_payment = $this->Payment_model->get_list_payment_by_order($id_order);

        // tong tien can thanh toan cua don hang
        $amountPayable = 0;
        foreach ($list_payment as $item) {
            if ($item['is_payment'] == PAY_DANG_CHO) {
                $price = floatval($item['price']);
                $price_vou = floatval($item['price_voucher']);
                $price_pay = $price > $price_vou ? ($price - $price_vou) : 0;
                $amountPayable += $price_pay;
            }
        }

        if ($amountPayable > 0) {

            $pay['method']      = 'paypal';
            $pay['currency']    = 'USD';
            $pay['total_price'] = $amountPayable;
            $pay['desc']        = "Payment order";
            $pay['invoice']     = uniqid();
            $pay['return_url']  = site_url('checkout/success');
            $pay['cancel_url']  = site_url('checkout/cancle');

            $pay['item'] = array(
                [
                    'sku'      => $id_order,
                    'name'     => "PAYMENT ORDER",
                    'quantity' => 1,
                    'price'    => $amountPayable,
                    'currency' => $pay['currency']
                ]
            );

            $payer = new Payer();
            $payer->setPaymentMethod($pay['method']);

            $amount = new Amount();
            $amount->setCurrency($pay['currency'])
                ->setTotal($pay['total_price']);

            $items = new ItemList();
            $items->setItems($pay['item']);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setDescription($pay['desc'])
                ->setInvoiceNumber($pay['invoice'])
                ->setItemList($items);

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($pay['return_url'] . '?ID_POPUP=' . $ID_POPUP)
                ->setCancelUrl($pay['cancel_url'] . '?ID_POPUP=' . $ID_POPUP);

            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction])
                ->setRedirectUrls($redirectUrls);

            try {
                $payment->create($this->_apiContext);
            } catch (Exception $e) {
                throw new Exception('Unable to create link for payment');
            }

            header('location:' . $payment->getApprovalLink());
            exit(1);
        } else {
            echo "<script>localStorage.setItem('" . $ID_POPUP . "', " . PAY_HOAN_THANH . ");window.close()</script>";
            die();
        }
    }

    function success()
    {
        $success = PAY_HUY;

        $paymentId   = removeAllTags($this->input->get('paymentId'));
        $PayerID     = removeAllTags($this->input->get('PayerID'));
        $ID_POPUP = removeAllTags($this->input->get('ID_POPUP'));

        $payment = Payment::get($paymentId, $this->_apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($PayerID);
        try {
            // Take the payment
            $payment->execute($execution, $this->_apiContext);
            try {
                $payment = Payment::get($paymentId, $this->_apiContext);

                $id_order = $payment->transactions[0]->item_list->items[0]->sku;
                $trancsion_id   = $payment->getId();
                $payment_status = $payment->getState();

                $order = $this->Order_model->get_info_order($id_order);
                if (empty($order) || $order['id_user'] != $this->_session_uid()) {
                    resError('Bạn không có quyền truy cập đến đơn hàng này'); //TODO: cho ra trang báo lỗi 
                }

                $data = [
                    'transaction_id'  => $payment->getId(),
                    'payment_status'  => $payment->getState(), // "created", "approved", "failed", "partially_completed", "in_progress"
                    'id_order'        => $payment->transactions[0]->item_list->items[0]->sku,
                    'amount_total'    => $payment->transactions[0]->amount->total,
                    'amount_currency' => $payment->transactions[0]->amount->currency,
                    'invoice_id'      => $payment->transactions[0]->invoice_number
                ];

                if ($payment_status == 'approved') {
                    $update_time = date('Y-m-d H:i:s');
                    $this->Payment_model->update_status_payment_by_id_order($id_order, PAY_HOAN_THANH, PAYPAL, $trancsion_id, $update_time);
                    $this->Order_model->update_status_order($id_order, ORDER_PENDING);

                    $success = PAY_HOAN_THANH;
                } else {
                    $success = PAY_HUY;
                }
            } catch (Exception $e) {
                $success = PAY_HUY;
            }
        } catch (Exception $e) {
            $success = PAY_HUY;
        }

        echo "<script>localStorage.setItem($ID_POPUP, " . $success . ");window.close()</script>";
    }

    function cancle()
    {
        $ID_POPUP = removeAllTags($this->input->get('ID_POPUP'));
        echo "<script>localStorage.setItem($ID_POPUP, " . PAY_HUY . ");window.close()</script>";
    }
}

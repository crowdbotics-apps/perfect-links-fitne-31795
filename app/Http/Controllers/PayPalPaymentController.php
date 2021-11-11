<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Validator;
use DB;
use Session;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class PayPalPaymentController extends Controller
{
    public function paymentForm()
    {
		return view('paypal-pay');
	}  
	public function cancelPayment(){
		$txn_id = 'TRASACTIONDECLINED';
		return view('paypal/success', ['txn_id'=>$txn_id]);
	}
	public function paymentSubscriptionForm()
    {
		return view('paypal-subscription-pay');
	}
	
	function getOrderInfoFromTempTable($orderId,$userId){
		$productInfo  = DB::table('temp_payments')->select('*')->where(['user_id'=>$userId,'order_id'=>$orderId])->first();
		return $productInfo;
	}	
	function getProductInfo($productType,$productId){
		if($productType='PRODUCT'){
			$productInfo  = DB::table('products')->select('*')->where(['product_id'=>$productId])->first();
		}
		return $productInfo;
	}
	function getShippingInfo($shippingId,$userId){
		$shippingAddressInfo = DB::table('shipping_address')->select('*')->where(['id'=>$shippingId,'user_id'=>$userId])->first();
		return $shippingAddressInfo;
	}
	function getUserInfo($userId){
		$userInfo  = DB::table('users')->select('*')->where(['user_id'=>$userId])->first();
		return $userInfo;
	}
	public function creatPayment(Request $request)
    {
		$order_id     	= Str::random(12);
		$user_id      	= $request->user_id;
		$product_type 	= $request->product_type;
		$product_id   	= $request->product_id;
		
		$quantity     	= $request->quantity;
		$shipping_id  	= $request->shipping_address_id;
		
		$shippingAmount = 0;
		$taxAmount      = 0;
		
		$productInfo    = $this->getProductInfo($product_type,$product_id);
		$productTitle   = $productInfo->product_title;
		$productAmount  = $productInfo->amount;
		$subtotalAmount = $productAmount*$quantity;
		
		$totalAmount    = $shippingAmount + $taxAmount + $subtotalAmount;
		$tempTableData 	= array(
			"user_id"=>$user_id,
			"order_id"=>$order_id,
			"product_type"=>$product_type,
			"product_id"=>$product_id,
			"amount"=>$totalAmount,
			"quantity"=>$quantity,
			"shipping_address_id"=>$shipping_id,
			"created_at"=>date('Y-m-d h:i:s')
		);
		
		$tempId = DB::table('temp_payments')->insertGetId($tempTableData);
		/* If data inserted into temporary table then proceed for paypal payment else return error */
		if($tempId){
			$shippingInfo = $this->getShippingInfo($shipping_id,$user_id);		
			$clientId       = config('constants.PAYPAL_CLIENT_ID');
			$clientSecretId = config('constants.PAYPAL_CLIENT_SECRET');
			$apiContext = new \PayPal\Rest\ApiContext(
				new \PayPal\Auth\OAuthTokenCredential(
					$clientId,$clientSecretId
				)
			);
		
			$payer = new Payer();
			$payer->setPaymentMethod("paypal");
			/* Shipping address */
		/*  $shippingAddress = new ShippingAddress();
			$shippingAddress->setState($shippingInfo->state);
			$shippingAddress->setCity($shippingInfo->city);
			$shippingAddress->setPostalCode($shippingInfo->postal_code);
			$shippingAddress->setCountryCode($shippingInfo->country_code);
			$shippingAddress->setPhone($shippingInfo->phone_number);
			$shippingAddress->setDefaultAddress(true);
			$shippingAddress->setPreferredAddress(true);
			$shippingAddress->setLine1($shippingInfo->line1);
			
			$payerInfo = new PayerInfo();
			$payerInfo->setShippingAddress($shippingAddress);
			$payer->setPayerInfo($payerInfo); 
		*/
			/* End Shipping address */
			$item1 = new Item();
			$item1->setName($productTitle)
					->setCurrency('USD')
					->setQuantity($quantity)
					->setSku($order_id) // Similar to `item_number` in Classic API
					->setPrice($productAmount);
			/* $item2 = new Item();
			$item2->setName('Granola bars')
					->setCurrency('USD')
					->setQuantity(5)
					->setSku("321321") // Similar to `item_number` in Classic API
					->setPrice(2); */

			$itemList = new ItemList();
			/* $itemList->setItems(array($item1, $item2)); */
			$itemList->setItems(array($item1));
			
			/*  */
			$details = new Details();
			$details->setShipping($shippingAmount)
				->setTax($taxAmount)
				->setSubtotal($subtotalAmount);
			
			$amount = new Amount();
			$amount->setCurrency("USD")
					->setTotal($totalAmount)
					->setDetails($details);
				
			$transaction = new Transaction();
			$transaction->setAmount($amount)
						->setItemList($itemList)
						->setDescription("Payment description")
						->setInvoiceNumber(uniqid());
		
		
			$redirectUrls = new RedirectUrls();
			$redirectUrls->setReturnUrl("http://perfectlinkfitness.com/api/execute-payment?subtotalAmount=".$subtotalAmount."&orderId=".$order_id."&shippingId=".$shipping_id."&userId=".$user_id)
				->setCancelUrl("http://perfectlinkfitness.com/api/cancel-payment");
		
			$payment = new Payment();
			$payment->setIntent("sale")
					->setPayer($payer)
					->setRedirectUrls($redirectUrls)
					->setTransactions(array($transaction));
			
			$payment->create($apiContext);
			return redirect($payment->getApprovalLink());
		} else {
			$txn_id = 'TRASACTIONDECLINED';
			return view('paypal/success', ['txn_id'=>$txn_id]);
		}
    }
    public function executePayment(){
		$clientId       = config('constants.PAYPAL_CLIENT_ID');
		$clientSecretId = config('constants.PAYPAL_CLIENT_SECRET');
		$apiContext = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				$clientId,$clientSecretId
			)
		);
		$userId     = request('userId');
		$orderId    = request('orderId');
		$shippingId = request('shippingId');
		$PayerID    = request('PayerID');
		$token      = request('token');
		
		$subtotalAmount = request('subtotalAmount');
		$shippingAmount = 0;
		$taxAmount      = 0;
		$totalAmount    = $shippingAmount + $taxAmount + $subtotalAmount;
		if (empty($PayerID) || empty($token)) {
            $txn_id = 'TRASACTIONERROR';
			return view('paypal/success', ['txn_id'=>$txn_id]);
        } else {
			$paymentId = request('paymentId');
			$payment = Payment::get($paymentId, $apiContext);
			
			$execution = new PaymentExecution();
			$execution->setPayerId(request('PayerID'));
			
			$transaction = new Transaction();
			$amount = new Amount();
			$details = new Details();

			$details->setShipping($shippingAmount)
					->setTax($taxAmount)
					->setSubtotal($subtotalAmount);

			$amount->setCurrency('USD');
			$amount->setTotal($totalAmount);
			$amount->setDetails($details);
			$transaction->setAmount($amount);
			
			$execution->addTransaction($transaction);
			$result = $payment->execute($execution, $apiContext);

			if($result->getState() == 'approved') {
				$shippingInfo  = $this->getShippingInfo($shippingId,$userId);
				$tempTableData = $this->getOrderInfoFromTempTable($orderId,$userId);
				$trans         = $result->getTransactions();
				// item info
				$Subtotal         = $trans[0]->getAmount()->getDetails()->getSubtotal();
				$Tax              = $trans[0]->getAmount()->getDetails()->getTax();
				$payer            = $result->getPayer();
				// payer info
				$PaymentMethod    = $payer->getPaymentMethod();
				$PayerStatus      = $payer->getStatus();
				$PayerMail        = $payer->getPayerInfo()->getEmail();
				$relatedResources = $trans[0]->getRelatedResources();
				$sale             = $relatedResources[0]->getSale();
				// sale info
				$saleId           = $sale->getId();
			
				$CreateTime       = $sale->getCreateTime();
				$UpdateTime       = $sale->getUpdateTime();
				$State            = $sale->getState();
				$Total            = $sale->getAmount()->getTotal();
			
				$paymentTable = array(
					"user_id"               => $userId,
					"order_id"              => $orderId,
					"transaction_id"        => $saleId,
					"product_type"          => $tempTableData->product_type,
					"product_id"            => $tempTableData->product_id,
					"quantity"              => $tempTableData->quantity,
					"tax"                   => $Tax,
					"shipping"              => $shippingAmount,
					"sub_total"             => $Subtotal,
					"total"                 => $Total,
					"payer_status"          => $PayerStatus,
					"payment_state"         => $State,
					"payment_method"        => $PaymentMethod,
					"payer_email"           => $PayerMail,
					"shipping_address_id"   => $shippingId,
					"purchase_status"       => "1",
					"payment_created_at"    => $CreateTime,
					"payment_update_time"   => $UpdateTime,
					"created_at"            => date('Y-m-d h:i:s')
				);
				$paymentFinal = DB::table('payments')->insertGetId($paymentTable);
			/* 	echo '<pre>';
				print_r($paymentTable); 
				print_r($shippingInfo); */
				return view('paypal/success', ['txn_id'=>$saleId]);
			}
		
		}
	}
    public function cancel()
    {
        dd('Your payment is canceled. You can create cancel page here.');
		return view('paypal/cancel', ['txn_id'=>'CANCELED']);
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function success(Request $request)
    {
		 dd('Your payment is success.');
    }
}
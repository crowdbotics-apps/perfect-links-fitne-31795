<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Perfectlinkfitness PayPal Integration - perfectlinkfitness.com</title>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }
            .content {
                margin-top: 100px;
                text-align: center;
            }
        </style>
    </head>
    <body>
	<!-- PRODUCT  ACCESSORIES -->
        <div class="flex-center position-ref full-height">
            <div class="content">
                <h1>PRODUCT/ACCESSOIES PURCHASE WITHOUT COUPON</h1>
				<!--<form method="post" action="{{ route('creat-payment') }}">-->
				<form method="post" action="http://perfectlinkfitness.com/api/creat-payment">
					@csrf
					<input type="text" name="user_id" value="AO2FUI2QLERM" placeholder="User Id">
					<input type="text" name="product_type" value="PRODUCT"  placeholder="PRODUCT/ACCESSORIES">
					<input type="text" name="product_id" value=""  placeholder="Product Id">
					<!--<input type="text" name="amount" value=""  placeholder="Amount">-->
					<input type="text" name="quantity" value="" placeholder="Quantity">
					<input type="text" name="shipping_address_id" value="1" placeholder="Shipping Address">
					<input type="submit" name="paynow" value="Pay Now">
				</form>
            </div>
        </div>
		</br>
		</br>
		</br>
		        <div class="flex-center position-ref full-height">
            <div class="content">
                <h1>PRODUCT/ACCESSOIES PURCHASE WITH COUPON</h1>
				<!--<form method="post" action="{{ route('creat-payment') }}">-->
				<form method="post" action="http://perfectlinkfitness.com/api/creat-payment">
					@csrf
					<input type="text" name="user_id" value="AO2FUI2QLERM" placeholder="User Id">
					<input type="text" name="product_type" value="PRODUCT"  placeholder="PRODUCT/ACCESSORIES">
					<input type="text" name="product_id" value=""  placeholder="Product Id">
					<!--<input type="text" name="amount" value=""  placeholder="Amount">-->
					<input type="text" name="quantity" value="" placeholder="Quantity">
					<input type="text" name="shipping_address_id" value="1" placeholder="Shipping Address">
					<input type="text" name="discount_id" value="10" placeholder="Discount Id">
					<input type="submit" name="paynow" value="Pay Now">
				</form>
            </div>
        </div>
    </body>
</html>
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
<!--
user_id:RNJI6LZKSH7H
subscription_for:TRAINER
gym_id:4S4UZM5E01SV
member_count:3
membership_type:GROUP
trainer_id:1Q3MAZCC4DXB
months:3
-->
	<!-- GYM  TRAINER -->
        <div class="flex-center position-ref full-height">
            <div class="content">
                <h1>SUBSCRIPTION Laravel 6 PayPal Integration Tutorial - octatrades.com</h1>
				<!--<form method="post" action="{{ route('creat-payment') }}">-->
				<form method="post" action="http://perfectlinkfitness.com/api/subscription-payment">
					@csrf
					<input type="text" name="user_id" value="AO2FUI2QLERM" placeholder="User Id">
					<input type="text" name="subscription_for" value="TRAINER"  placeholder="GYM/TRAINER">
					<input type="text" name="gym_id" value="4S4UZM5E01SV"  placeholder="Gym Id">
					<input type="text" name="trainer_id" value="1Q3MAZCC4DXB"  placeholder="Trainer Id">
					<input type="text" name="member_count" value="1" placeholder="Member Count">
					<input type="text" name="membership_type" value="SINGLE" placeholder="SINGLE/GROUP">
					<input type="text" name="months" value=""  placeholder="Months">
					<input type="submit" name="paynow" value="Pay Now">
				</form>
            </div>
        </div>
    </body>
</html>
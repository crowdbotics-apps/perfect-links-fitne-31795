<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Perfectlinkfitness PayPal Payment - perfectlinkfitness.com</title>
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
        <div class="flex-center position-ref full-height">
            <div class="content">
				<img class="main-logo" src="{{ URL::asset('assets/admin/images/logo.png') }}" style="height: 120px;" alt="Logo" />
				<h1 style="text-align: center;">Payment Information</h1>
				<div class="contact-form">
					<h2 style="font-family: 'quicksandbold'; font-size:16px; color:#313131; padding-bottom:8px;text-align: center;">Welcome to Perfectlinkfitness</h2>
					@if($txn_id=='TRASACTIONERROR')
					<span style="color: #646464; width: 100px; padding: 5px;">Payment Error. Please wait while we redirecting to application.</span><br/>
					@elseif($txn_id=='TRASACTIONDECLINED')
					<span style="color: #646464; width: 100px; padding: 5px;">Payment Declined. Please wait while we redirecting to application.</span><br/>
					@else
					<span style="color: #646464; width: 100px; padding: 5px;">Payment has been done successfully. Please wait while we redirecting to application.</span><br/>
					@endif
				</div>
				<input type="hidden" id="octatradesFitness" value="{{$txn_id}}">
           </div>
        </div>
    </body>
</html>

<script type="text/javascript">
   function finalStatus(t) {
       OctatradesFitness.status(t);      
   }
    var stat = "<?php echo $txn_id; ?>";
    window.OctatradesFitness.status(stat); 
</script>

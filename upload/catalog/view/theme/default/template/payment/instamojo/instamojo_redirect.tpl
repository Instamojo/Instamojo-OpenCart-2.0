  <?php echo $header; ?>
  <style>
	#container{background:#FFF !important}
	.instamojo{width:300px;margin:0 auto; padding:  44px 0;}
	.instamojo form{margin: 20px 0;}
	.btn.btn-primary{margin-top:10px;}
	.instamojo img{display: block;
margin-left: auto;
margin-right: auto }
  </style>
 <div id="container" class="container j-container"  >
 <div class="instamojo">
 <?php if(isset($errors)): ?>
	<?php foreach($errors as $error): ?>
	<?php if(stristr($error,"phone")) {$showPhoneInput = true;} ?>
	<div class="alert alert-danger">
		<?php echo $error ?>
	</div>
	
	<?php if(isset($showPhoneInput)): ?>
	    <form method='POST'>
		<div class='form-group'>
			<label class='label' for="telephone">Phone</label>
			<input class='form-controls' type="telephone" name='telephone' value='<?= $telephone ?>' />
			<input class='btn btn-primary' type='submit' value="Update Phone">
		</div>
		</form>
	<?php endif; ?>
	<?php endforeach; ?>
  <?php else: ?>
<form id='paymentGateway' name='paymentGateway' action="<?php echo $action; ?>" method="GET">
  
  <input type="hidden" name="embed" value="form" >	
 
</form>
	<img src='image/spinner.gif'  style="" />
	<p style="text-align:center">Redirecting To Payment Gateway...</p>
  <?php endif; ?>
  </div>
</div>
    <?php echo $footer; ?>
 <script> document.paymentGateway.submit(); </script>

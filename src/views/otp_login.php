<?php include_once('../src/views/elements/header.php');?>
<form method="POST" action="/login-width-otp" class="form-validation form-signin">
	<div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">Token Authentification</h1>
    </div>

  	<div class="form-label-group">
        <input type="text" name="token" id="inputToken" class="form-control" placeholder="Sent token" value="<?php echo (!empty($_SESSION['failed_token']))?$_SESSION['failed_token']:''; ?>" required autofocus />
        <label class="forPlaceholder" for="inputToken">Sent token</label>
  	</div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Send</button>
</form>
<?php 
$showRequestTokenForm = true;
if($loginAttempt['parseResult']['otp_code']
	&& !is_null($loginAttempt['parseResult']['valid_seconds'])){
		if($loginAttempt['parseResult']['valid_seconds'] > 100){
			$showRequestTokenForm = false;
		} ?>
	<div class="text-center mb-4">
		<p> The sent token will be valid for <span id="countdowntimer"><?= ($loginAttempt['parseResult']['valid_seconds'] > 0)?$loginAttempt['parseResult']['valid_seconds']:0;?></span> Seconds</p>
	</div>
	<script type="text/javascript">
	    var timeleft = parseInt(<?php echo $loginAttempt['parseResult']['valid_seconds'];?>);
	    var downloadTimer = setInterval(function(){
	    timeleft--;
	    document.getElementById("countdowntimer").textContent = timeleft;
	    if(timeleft <= 0)
	        clearInterval(downloadTimer);
	    },1000);
	</script>
<?php } ?>
<form method="POST" action="/generate-otp-token" class="form-validation form-signin <?php if(!$showRequestTokenForm) echo 'hidden';?>" id="requestTokenForm">
	<div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal"><?= ($showRequestTokenForm)?'Request token':'No token received?';?></h1>
    </div>
	<div class="form-label-group">
	  	<div class="form-check">
		  	<input class="form-check-input" type="radio" name="token-option" id="sendToEmail" value="email" required />
		  	<label class="form-check-label" for="sendToEmail">To your email account</label>
		</div>
		<div class="form-check">
		  	<input class="form-check-input" type="radio" name="token-option" id="sendToPhone" value="phone" required />
		  	<label class="form-check-label" for="sendToPhone">To your phone account</label>
		</div>
	</div>
    <button class="btn btn-lg btn-primary btn-block" type="submit"><?= ($showRequestTokenForm)?'Request now':'Resend token';?></button>
</form>
<?php 
if(isset($_SESSION['failed_token'])){
	unset($_SESSION['failed_token']);
}
include_once('../src/views/elements/footer.php');
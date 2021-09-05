<?php include_once('../src/views/elements/header.php');?>
<form method="POST" action="/login" class="form-validation form-signin">
  	<div class="text-center mb-4">
        <h1 class="h3 mb-3 font-weight-normal">Account Login</h1>
    </div>

  	<div class="form-label-group">
        <input type="email" name="login[email]" id="inputEmail" class="form-control" placeholder="Email address" value="<?php echo (!empty($_SESSION['failed_login']['email']))?$_SESSION['failed_login']['email']:''; ?>" required autofocus />
        <label class="forPlaceholder" for="inputEmail">Email address</label>
  	</div>

  	<div class="form-label-group">
    	<input type="password" name="login[password]"  id="inputPassword" class="form-control" placeholder="Password" value="<?php echo (!empty($_SESSION['failed_login']['password']))?$_SESSION['failed_login']['password']:''; ?>" required/>
    	<label class="forPlaceholder" for="inputPassword">Password</label>
  	</div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>
<?php 
if(isset($_SESSION['failed_login'])){
	unset($_SESSION['failed_login']);
}
include_once('../src/views/elements/footer.php');

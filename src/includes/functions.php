<?php
function setErrorMessage($error) {
	$_SESSION['error'] = $error;
}

function setSuccesMessage($succ) {
	$_SESSION['success'] = $succ;
}

function showMessages()
{
	if(!empty($_SESSION['error'])){
		echo '<div class="alert alert-danger" role="alert">'.$_SESSION['error'].'</div>';
		unset($_SESSION['error']);
	}
	if(!empty($_SESSION['success'])){
		echo '<div class="alert alert-success" role="alert">'.$_SESSION['success'].'</div>';
		unset($_SESSION['success']);
	}	
}
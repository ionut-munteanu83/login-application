<?php include_once('../src/views/elements/header.php');?>
<main role="main" class="container">
    <div class="starter-template">
	    <h1>Welcome <?= $_SESSION['user']['name'].' '.$_SESSION['user']['surname'];?>,</h1>
	    <p class="lead">You reached the application homepage!</p>
	</div>
	<a href="/logout" title="Logout" class="btn btn-primary btn-sm">Logout</a>
</main><!-- /.container -->
<?php include_once('../src/views/elements/footer.php');?>

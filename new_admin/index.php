<!DOCTYPE html>
<html lang="en">

<head>

  <?php include_once('./head.php'); ?>

</head>

<body id="page-top">


  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

<form action="./login.php" method="post">
	<div class="row mt-5">
		<div class="offset-md-4 col-4 offset-md-4">
			<p>어드민 로그인</p>
			<input type="text" class="form-control" placeholder="ID" name="id"/>
		</div>
	</div>
	<div class="row mt-3">
		<div class="offset-md-4 col-4 offset-md-4">
			<input type="password" class="form-control" placeholder="PW"  name="pw"/>
		</div>
	</div>
	
	<div class="row mt-3">
		<div class="offset-md-4 col-4 offset-md-4">
			<button type="submit" class="btn btn-block btn-primary">로그인</button>
		</div>
	</div>
</form>

</body>

</html>

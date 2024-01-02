
<?php
	$companyInfo = $this->db->query("select * from tbl_company c order by c.Company_SlNo desc limit 1")->row();
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?php echo $companyInfo->Company_Name;?><?php echo "<br>"; ?><?php echo $companyInfo->Repot_Heading;?>">
	<meta name="author" content="<?php echo $companyInfo->Company_Name;?>">

	<title><?php echo $companyInfo->Company_Name;?> || Login Page</title>
	<style>
		body {
			opacity: 0;
		}
		.typed-cursor{
			color: #fff;
		}
		.background {
			background-image: url('/assets/extra/img/photos/unsplash-2.jpg');
			background-size: cover;
			background-attachment: fixed;
			background-color: rgb(0 0 0 / 50%);
			background-blend-mode: color;
		}
		.lead {
			color: #fff;
		}
		#typed {
			color: #fff;
		}
	</style>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/extra/css/modern.css">
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-120946860-7"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-120946860-7');
	</script>
</head>

<body class="theme-blue">
	<div class="splash active">
		<div class="splash-icon"></div>
	</div>

	<main class="main h-100 w-100 background">
		<div class="container h-100">
			<div class="row h-100">
				<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center mt-4">
							<h1 class="h2"><span id="typed"></span></h1>
							<p class="lead">
								Sign in to your account to continue
							</p>
						</div>

						<div class="card">
							<div class="card-body">
								<div class="m-sm-4">
									<div class="text-center pt-2 pb-4">
										<?php if($companyInfo->Company_Logo_thum == ""){?>
											<img src="/assets/login/img/images.jpg" class="img-fluid rounded-circle" width="132" height="132">
										<?php } else {?>
											<img src="/uploads/company_profile_org/<?php echo $companyInfo->Company_Logo_thum;?>" class="img-fluid rounded-circle" width="132" height="132">
										<?php } ?>
									</div>
									<p style="color:red;"><?php if(isset($message)){ echo $message; } ?></p>
									<form method="post" action="<?php echo base_url();?>Login/procedureCustomer">
										<div class="mb-3">
											<?php echo form_error('user_name'); ?>
											<input class="form-control" type="text"  name="user_name" placeholder="User Name"/>
										</div>
										<div class="mb-3">
											<?php echo form_error('password'); ?>
											<input class="form-control" type="password" name="password" placeholder="Password" />
											<small>
												<a href='/pages-reset-password'>Forgot password?</a>
											</small>
										</div>
										<div>
											<div class="form-check align-items-center">
												<input id="customControlInline" type="checkbox" class="form-check-input" value="remember-me" name="remember-me"
													checked>
												<label class="form-check-label text-small" for="customControlInline">Remember me next time</label>
											</div>
										</div>
										<div class="text-center mt-3">
											<!-- <a class='btn btn-primary w-100' href='/dashboard-default'>Sign in</a> -->
											<button type="submit" class="btn w-100 btn-primary">Sign in</button>
										</div>
									</form>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</main>

	<svg width="0" height="0" style="position:absolute">
		<defs>
			<symbol viewBox="0 0 512 512" id="ion-ios-pulse-strong">
				<path
					d="M448 273.001c-21.27 0-39.296 13.999-45.596 32.999h-38.857l-28.361-85.417a15.999 15.999 0 0 0-15.183-10.956c-.112 0-.224 0-.335.004a15.997 15.997 0 0 0-15.049 11.588l-44.484 155.262-52.353-314.108C206.535 54.893 200.333 48 192 48s-13.693 5.776-15.525 13.135L115.496 306H16v31.999h112c7.348 0 13.75-5.003 15.525-12.134l45.368-182.177 51.324 307.94c1.229 7.377 7.397 11.92 14.864 12.344.308.018.614.028.919.028 7.097 0 13.406-3.701 15.381-10.594l49.744-173.617 15.689 47.252A16.001 16.001 0 0 0 352 337.999h51.108C409.973 355.999 427.477 369 448 369c26.511 0 48-22.492 48-49 0-26.509-21.489-46.999-48-46.999z">
				</path>
			</symbol>
		</defs>
	</svg>
	<script src="<?php echo base_url(); ?>assets/extra/js/app.js"></script>
	<script src="/assets/login/js/jquery.min.js"></script>
	<script src="/assets/js/typed.js"></script>
	<script>
		$(function(){
			var typed = new Typed('#typed', {
				strings: ['Welcome to Hazrat Shah Ali Business Association'],
				typeSpeed: 100,
				backSpeed: 100,
				loop: true
			});
		});
	</script>										
</body>

</html>
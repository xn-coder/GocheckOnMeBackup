@include('layouts/header')
   <meta name="csrf-token" content="{{ csrf_token() }}">
<section class="login_section">
	
	<form class="login_form" id="forget_password" enctype="multipart/form-data">
		<div class="result" style="color:green;"></div>
	    <div class="row">
			<div class="title col-md-12">
				<h2>Forgot Password</h2>
			</div>
			<div class="form_box col-md-12">
				<label for="email" class="form-label">Email:</label>
				<input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
			</div>
			<div class="form_box col-md-12">
				<button type="submit" class="btn btn-primary">Submit</button>
				<p class="singupaccount">Don't have an account? <a href="{{url('signup')}}">Sign up</a></p>
			</div>
		</div>
	</form>
	
	<div>
		<div class="gradient-circle"></div>
		<div class="gradient-circle two"></div>
	</div>
</section>

@include('layouts/footer')
<script>
	jQuery(document).on('click', '.password_toggle', function() {
		jQuery(this).toggleClass("fa-eye fa-eye-slash");
		var input = $(".password_show");
		input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
	});
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script>

	$("#forget_password").validate({
	rules: {
		email: {required: true,email: true,},  
		},
	
	messages: {
	    email: {required: "Please enter valid email",email: "Please enter valid email",},   
	},
		submitHandler: function(form) {
		   var formData= new FormData(jQuery('#forget_password')[0]);
		   formData.append("_token",$('meta[name="csrf-token"]').attr('content'));
		   u ="development/happiest_team/";
		  
		jQuery.ajax({
				url: "forgetpassword_sendotp",
				type: "post",
				cache: false,
				data: formData,
				processData: false,
				contentType: false,
				
				success:function(data) { 
				var obj = JSON.parse(data);
				
				if(obj.status==true){
					$(".result").text(obj.message);
					 $(".result").css("color", "green");
					setTimeout(function(){
						 $("#b_trainer").modal('show');
						 window.location.href = "verifyotp_view";
					}, 1000);
				}
				else{
					if(obj.status==false){
						//statuscheck 1/2/3
						$(".result").css("color", "red");
						if(obj.check==1){
						$("#email_error").show();		
						jQuery('#email_error').text('');
						jQuery('#email_error').text(obj.message);
					}
					else if(obj.check==2){
							$("#password_error1").show();					

						jQuery('#password_error1').text('');
						jQuery('#password_error1').text(obj.message);
				
						setTimeout(function(){
						jQuery('#password_error1').text('');	
						jQuery('#password_error1').hide();	
					}, 1000);

					}
					else{
						jQuery('#mobile_number_error').html('');
						jQuery('#email_error').html('');
					}
					}
				}
				}
			});
		}
	});

</script>
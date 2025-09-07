@include('layouts/header')
   <meta name="csrf-token" content="{{ csrf_token() }}">
<section class="login_section">
	
	<form class="login_form" id="change_password" enctype="multipart/form-data">
		<div class="result" style="color:green;"></div>
		  <input type="hidden" name="id" value="{{$id}}"/>
	    <div class="row">
			<div class="title col-md-12">
				<h2>Reset Password</h2>
			</div>
			<div class="form_box col-md-12">
				<label for="password" class="form-label">Password:</label>
				<input type="text" class="form-control" id="password" placeholder="Enter Password" name="password">
			</div>

			<div class="form_box col-md-12">
				<label for="email" class="form-label">Confirm Password:</label>
				<input type="text" class="form-control" id="cpassword" placeholder="Enter confirm password" name="confirm_password">
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

	$("#change_password").validate({
		
	rules: {
		password: {required: true,},
		confirm_password: {required: true, minlength : 8,equalTo: "#password",},  
    },
	messages: {
		password: {required: "Please enter password",},
      confirm_password: {required: "Please enter confirm password",equalTo:"Password and confirm password must be same.",},
	},
		submitHandler: function(form) {
		   var formData= new FormData(jQuery('#change_password')[0]);
		   formData.append("_token",$('meta[name="csrf-token"]').attr('content'));
		   u ="development/happiest_team/";
		  
		jQuery.ajax({
				url: "{{route('change_password')}}",
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
						 window.location.href = "{{route('login')}}";
					}, 1000);
				}
				else{
					if(obj.status==false){
						//statuscheck 1/2/3
						 $(".result").css("color", "red");
						// $(".result").text(obj.message);
						// jQuery('#mobile_number_error').html(obj.message);
						// jQuery('#mobile_number_error').css("display", "block");
						
						if(obj.check=='cpassword'){
						$("#cpassword").show();		
						jQuery('#cpassword').html('');
						jQuery('#cpassword').html(obj.message);
						
					}
					else if(obj.check=='password'){
							$("#password").show();					

						jQuery('#password').text('');
						jQuery('#password').text(obj.message);
				
					// 	setTimeout(function(){
					// 	jQuery('#password_error1').text('');	
					// 	jQuery('#password_error1').hide();	
					// }, 1000);

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
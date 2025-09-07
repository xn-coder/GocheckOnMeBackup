@include('layouts.header')
<meta name="csrf-token" content="{{ csrf_token() }}">


<section class="login_section">
	
	<form class="login_form" id="Verify_otp"  enctype="multipart/form-data" method="post">
		<span style="color:red" id="wotp"></span>
					<div class="result" style="color: green;" id="result"></div>
					<span style="color:red" id="wotp"></span>
	    <div class="row">
			<div class="title col-md-12">
				<h2>OTP Verification</h2>
			</div>
			<div class="form_box col-md-12">
				<input type="text" required class="form-control" id="digit" placeholder="Enter your OTP" name="otp">
			</div>
			<div class="form_box col-md-12">
				<button type="submit" class="btn btn-primary">Verify</button>
				<p class="singupaccount">Don't have an account? <a href="{{url('sign_type')}}">Sign up</a></p>
			</div>
		</div>
	</form>
	
	<div>
		<div class="gradient-circle"></div>
		<div class="gradient-circle two"></div>
	</div>
</section>

@include('layouts.footer')
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
	$(function(){
	
	$("#Verify_otp").validate({
		rules: {
			otp:{required:true,},	
		},
		
		messages: {
			otp: {required: "Please enter otp",},
		},
		submitHandler: function(form) {
		   var formData= new FormData(jQuery('#Verify_otp')[0]);
		   formData.append("_token",$('meta[name="csrf-token"]').attr('content'));
		   
		   u ="development/happiest_team/";
		  
		jQuery.ajax({
				url: "verifyOtp_forgotpassword",
				type: "post",
				cache: false,
				data: formData,
				processData: false,
				contentType: false,
				
				success:function(data) { 
				var obj = JSON.parse(data);
				var role = obj.role;
				if(obj.status==true){
					$(".result").text(obj.message);
					 $(".result").css("color", "green");
					setTimeout(function(){
						 $("#b_trainer").modal('show');
							window.location.href = "reset_password/"+obj.user_id;			 
					}, 1000);
				}
				else{
					if(obj.status==false){
						//statuscheck 1/2/3
						 $(".result").css("color", "red");
						// $(".result").text(obj.message);
						// jQuery('#mobile_number_error').html(obj.message);
						// jQuery('#mobile_number_error').css("display", "block");
						
						if(obj.check=='wotp'){
						$("#wotp").show();		
						jQuery('#wotp').html('');
						jQuery('#wotp').html(obj.message);
						
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
});

</script>



<!-- <script>
	let timerOn = true;

function timer(remaining) {
  var m = Math.floor(remaining / 60);
  var s = remaining % 60;
  
  m = m < 10 ? '0' + m : m;
  s = s < 10 ? '0' + s : s;
  document.getElementById('timer').innerHTML = "Your OTP Expire In "+ m + ':' + s;
  remaining -= 1;
  
  if(remaining >= 0 && timerOn) {
    setTimeout(function() {
        timer(remaining);
    }, 1000);
    return;
  }
  else{
  	 $("#dis").hide();
     $('#resotp').show();
  }
  if(!timerOn) {
    // Do validate stuff here
    return;
  }
  
  // Do timeout stuff here
  // alert('Timeout for otp');
}
timer(30);
 $('#resotp').hide();
</script> -->
	
	<script>
	$(function(){
		$("#digit").on("keyup",()=>{
			$("#wotp").text(' ');
		});
		$(".digitvalidation").on("keyup",()=>{
			$("#result").text(' ');
		});
	});


</script>
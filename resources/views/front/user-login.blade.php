<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gocheckonme</title>
    <link rel="stylesheet" href="{{asset('front-assets/style.css')}}">
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>

</head>

<body style="background-image: url('front-assets/image/parchment.gif')">
    <div class="headng_align">
        <h4> Good Afternoon, Welcome to</h4>
        <img src="{{asset('front-assets/image/gocheck.gif')}}" alt="" width="381" height="52"><span>®</span>
        <h5>Go Check On Me, LLC</h5>
    </div>
    <div class="login_input">
        <form action="" id="userlogin">
            @csrf
        <h4>Login</h4>
        <p>E-mail address: <input type="email" id="email" name="email" size="40">&nbsp; </p>
        <p>Password: <input type="password" id="password" name="password" size="40">&nbsp; </p>
        <h6><a href="{{url('/user-send-link')}}">Forgot Password?</a></h6>
       <div class="submitBtn"> <button type="submit">Submit</button></div>
       </form>
    </div>
</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>



$(function(){

	$("#userlogin").validate({
		rules: {
        email: { required: true },
        password: { required: true },
       
		},

		messages: {
			email: {required: "Please enter email",},
			password: {required: "Please enter password",},
			

		},

        submitHandler: function(form) {
		   var formData= new FormData(jQuery('#userlogin')[0]);

                    jQuery.ajax({
                            url: "{{url('/user')}}",
                            type: "post",
                            cache: false,
                            data: formData,
                            processData: false,
                            contentType: false,

                            success:function(response) {

                            if(response.status == true){
                            Swal.fire({
                                    title: response.status,
                                    text: response.message,
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                            window.location.href = "{{url('/')}}"; 
                                        });
                            }else{
                                Swal.fire({
                                    title: response.status,
                                    text: response.message,
                                    icon: "error",
                                    timer: 3000,
                                    showConfirmButton: false
                                }); 
                            }

                            if(response.errors){
                            $.each(response.errors, function(key, value) {
                                $.each(value, function(index, message) {
                                    var err = '#'+key+'err';
                                    $(err).text(message);
                                });
                            });

                        }
                     
				}
			});
		}
    });
});

</script>
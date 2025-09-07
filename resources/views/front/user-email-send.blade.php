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
    <div class="login_input email">
        <form action="" id="checkemail">
            @csrf
        <h4>Forgot Password</h4>
        <p>Enter Your E-mail: <input type="email" id="email" name="email" size="40">&nbsp; </p>
        <div class="wrapper">
            <button type="submit" class="modal-Btn">Submit</button>
        </div>
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

	$("#checkemail").validate({
		rules: {
        email: { required: true },
       
		},

		messages: {
			email: {required: "Please enter email",},
			
		},

        submitHandler: function(form) {
		   var formData= new FormData(jQuery('#checkemail')[0]);

                    jQuery.ajax({
                            url: "{{url('/user-check')}}",
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
                                    timer: 5000,
                                    showConfirmButton: false
                                }).then(() => {
                                            window.location.href = "{{url('/')}}"; 
                                        });
                            }else{
                                // console.log("object");
                                Swal.fire({
                                    title: response.status,
                                    text: response.message,
                                    icon: "error",
                                    timer: 5000,
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
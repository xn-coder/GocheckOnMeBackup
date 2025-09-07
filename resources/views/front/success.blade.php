

<html>

<head>
    <meta http-equiv="Content-Language" content="en-us">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title>Payment Success.</title>
    <script>
        // Redirect to home after 2 seconds
        setTimeout(function() {
            window.location.href = "{{ url('/') }}";
        }, 2000); // 2000 milliseconds = 2 seconds
    </script>
</head>

<style>
	.sccessPage{
		padding: 160px 0px;
	}
	.sccessPage .box {
	    width: 320px;
	    padding: 20px;
	    background: #fff;
	    border-radius: 10px;
	    margin: 0 auto;
	    text-align: center;
	}
	.sccessPage .box img {
	    width: 120px;
	}
	.sccessPage .box h3 {
	    font-size: 25px;
	    margin-bottom: 10px;
	    font-weight: 600;
	}

	@media (max-width: 767px) {
		.sccessPage{
			padding: 40px 0px;
		}
	}
</style>

<body background="{{url('front-assets/parchment.gif')}}" onload="FP_preloadImgs(/*url*/'button31.jpg', /*url*/'button32.jpg', /*url*/'button58.jpg', /*url*/'button57.jpg', /*url*/'button69.jpg', /*url*/'button68.jpg', /*url*/'button2A.jpg', /*url*/'button29.jpg', /*url*/'button3E.jpg', /*url*/'button3D.jpg', /*url*/'button3.jpg', /*url*/'button2.jpg', /*url*/'button12.jpg', /*url*/'button11.jpg', /*url*/'button17.jpg', /*url*/'button16.jpg', /*url*/'button26.jpg', /*url*/'button27.jpg')">

    <font color="#800000" face="Arial">
        <h5 align="center">
            <img border="0" src="{{url('front-assets/gocheck.gif')}}" width="381" height="52">
            <!-- <font color="#800000" face="Times New Roman"> </font>
			<font color="#800000" face="Arial Unicode MS">®</font> -->
        </h5>

        <div class="sccessPage">
        	<div class="container">
        		<div class="row">
        			<div class="col-lg-5 m-auto">
        				<div class="box">
            				<img border="0" src="{{url('front-assets/sccessfullyIcon.svg')}}">
        					<h3>Payment Successful</h3>
        					<p>Thank you for your payment. You will be redirected to the homepage shortly.</p>
        				</div>
        			</div>
        		</div>
        	</div>
        </div>
    </font>
</body>

</html>
<footer class="main_footer">
		<div class="container">
			<div class="row">
				<div class="col-md-12 right_menu">
					<ul>
						<li><a href="{{('blog')}}">Blog</a></li>
						<li><a href="{{('reviews')}}">Reviews</a></li><!--
						<li><a href="javascript:void(0);">Contact Us</a></li>-->
					</ul>
					Copyright © 2021 Happiest Team All Rights Reserved.
				</div>
			</div>
		</div>
	</footer>
		
<div class="vertical-lines-wrapper">
    <div class="vertical-lines">
		<div class="vertical-lines-wrapper">
			<div class="vertical-effect"></div>
			<div class="vertical-effect"></div>
			<div class="vertical-effect"></div>
		</div>
	</div>
    <div class="vertical-effect"></div>
	<div class="vertical-effect"></div>
	<div class="vertical-effect"></div>
</div>

<a href="javascript:void(0);" id="scroll" title="Top"><span></span></a><!--back to top-->
@include('layouts.modals')



<script src="{{asset('assets/website/vendors/owl.carousel/js/owl.carousel.js')}}"></script>

<!--*** This is Bootstrap 5.0.2 Beta Js ***-->
<script src="{{asset('assets/website/vendors/bootstrap-5.0.2-beta3-dist/js/popper.min.js')}}"></script>
<script src="{{asset('assets/website/vendors/bootstrap-5.0.2-beta3-dist/js/bootstrap.min.js')}}"></script>


<!-- Option 2: Separate Popper and Bootstrap JS 
<script src="assets/website/vendors/bootstrap-5.0.0-beta3-dist/css/bootstrap.bundle.min.js"></script> -->

<script src="{{asset('assets/website/vendors/animate/js/wow.js')}}"></script>
<script src="{{asset('assets/website/js/style.js')}}"></script>
<!--
<div class="fix_menu">
	<ul>
		<li><a href="mailto:join@happiest.team"><span><i class="far fa-envelope"></i></span> <strong>Mail</strong></a></li> 
		<li><a href="tel:8233801424"><span><i class="fas fa-phone-alt"></i></span> <strong>Call</strong></a></li> 
		<li><a href="skype:live:e458ac3d3f8c0fa2?chat"><span><i class="fab fa-skype"></i></span> <strong>Skype</strong></a></li> 
		<li><a href="https://api.whatsapp.com/send?phone=+918233801424"><span><i class="fab fa-whatsapp"></i></span> <strong>Whatsapp</strong></a></li> 
	</ul>
</div>-->

<style>
    .fix_menu ul {
    list-style: none;
    margin: 0px;
    padding: 0px;
    position: fixed;
    right: 0px;
    top: 50%;
	z-index: 99;
	transform: translate(-0%, -50%);
}
.fix_menu ul li a span {
    width: 40px;
    display: inline-block;
    text-align: center;
    height: 40px;
    line-height: 40px;
    border-radius: 50px;
    background: #fff;
    margin: 3px 0px;
	font-size: 18px;
}
.fix_menu ul li a {
    display: block;
    background: #fff;
    margin: 6px 0px;
    border-radius:50px 0px 0px 50px;
    padding: 0px 10px;
    box-shadow: 0 0px 1px 1px #cfcfcf;
	transition:0.5s;
	right: -90px;
	position:relative;
}
.fix_menu ul li a strong{
    
}

.fix_menu ul li a:hover{
	right:0px;
}
</style>

</body>
</html>

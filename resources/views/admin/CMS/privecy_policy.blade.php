@extends('layouts.admin')
@section('content')

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 col-12 align-self-center">
			<h4 class="text-themecolor mb-0">Privacy Policy</h4>
		</div>
		<div class="col-md-7 col-12 align-self-center d-none d-md-block">
			<ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
				<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
				<li class="breadcrumb-item active">Privacy Policy</li>
			</ol>
		</div>
	</div>
	<!-- ============================================================== -->
	<!-- Container fluid  -->
	<!-- ============================================================== -->
	<div class="container-fluid">

		<div class="col-12">
			<div class="card">
				<div class="border-bottom title-part-padding">
					<h4 class="card-title mb-0">Privacy Policy</h4>
				</div>
				<div class="card-body min_height">

					<div class="">

						<!-- Alert Append Box -->
						<!-- <div class="alert alert-success alert-dismissible text-white border-0 fade show" role="alert">
							<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
							<strong>Success - </strong> A simple Success alert
						</div>  -->

					</div>
					<form id="aboutform">
						@csrf
						@if(!empty($cms_data))
						@foreach($cms_data as $val)
						<textarea cols="80" id="testedit1" name="testedit1" rows="10" data-sample="2">
						{{$val->privacy_policy}}
						</textarea>
						<input type="hidden" name="aboutid" id="aboutid" value="{{$val->id}}">
						<button type="submit" id="registerCust" class="btn btn-success btn_submit fa-pull-right mt-3">Update</button>
						@endforeach
						@endif
					</form>
				</div>
			</div>
		</div>

	</div>
	<!-- ============================================================== -->
	<!-- End Container fluid  -->
	<!-- ============================================================== -->

	<!-- This Page JS -->
	<script src="{{ asset('/public/assets/admin/libs/ckeditor/ckeditor.js') }}"></script>
	<script src="{{ asset('/public/assets/admin/libs/ckeditor/samples/js/sample.js') }}"></script>
	<script>
		//default
		initSample();

		//inline editor
		// We need to turn off the automatic editor creation first.
		CKEDITOR.disableAutoInline = true;

		CKEDITOR.inline('editor2', {
			extraPlugins: 'sourcedialog',
			removePlugins: 'sourcearea'
		});

		var editor1 = CKEDITOR.replace('editor1', {
			extraAllowedContent: 'div',
			height: 460
		});
		editor1.on('instanceReady', function() {
			// Output self-closing tags the HTML4 way, like <br>.
			this.dataProcessor.writer.selfClosingEnd = '>';

			// Use line breaks for block elements, tables, and lists.
			var dtd = CKEDITOR.dtd;
			for (var e in CKEDITOR.tools.extend({}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent)) {
				this.dataProcessor.writer.setRules(e, {
					indent: true,
					breakBeforeOpen: true,
					breakAfterOpen: true,
					breakBeforeClose: true,
					breakAfterClose: true
				});
			}
			// Start in source mode.
			this.setMode('source');
		});
	</script>
	<script data-sample="1">
		CKEDITOR.replace('testedit', {
			height: 150
		});
	</script>
	<script data-sample="2">
		CKEDITOR.replace('testedit1', {
			height: 400
		});
	</script>
	<script data-sample="3">
		CKEDITOR.replace('testedit2', {
			height: 400
		});
	</script>
	<script data-sample="4">
		CKEDITOR.replace('tool-location', {
			toolbarLocation: 'bottom',
			// Remove some plugins that would conflict with the bottom
			// toolbar position.
			removePlugins: 'elementspath,resize'
		});
	</script>
	<script data-sample="4">
		CKEDITOR.replace('tool-location', {
			toolbarLocation: 'bottom',
			// Remove some plugins that would conflict with the bottom
			// toolbar position.
			removePlugins: 'elementspath,resize'
		});
	</script>
	<script>
		jQuery("#aboutform").validate({
			rules: {
				testedit1: {
					required: true,

				},
			},
			messages: {
				testedit1: {
					required: "This field is required.",

				},
			},

			submitHandler: function(form) {
				var formData = new FormData(jQuery('#aboutform')[0]);
				desc = CKEDITOR.instances['testedit1'].getData();
				formData.append("testedit1", desc);
				jQuery.ajax({
					type: 'post',
					url: "{{url('privacy-update')}}",
					cache: false,
					data: formData,
					processData: false,
					contentType: false,
					beforeSend: function() {
						jQuery('#registerCust').text('Submitting...');
						jQuery('#registerCust').attr('disabled', 'disabled');
					},
					success: function(data) {
						//console.log(data)
						var obj = JSON.parse(data);
						if (obj.status == true) {
							var id = obj.id;
							$("#showSuccessMsg").html(obj.message);
							$('#successModal').modal('show');
							jQuery('#registerCust').text('Submit');
							setTimeout(function() {
								jQuery('#registerCust').removeAttr('disabled');
								window.location.reload();
							}, 2000);
							jQuery('#resetRegi').trigger('click');
						} else {

							$("#showMsg").html(obj.message);
							$('#myRegModal').modal('show');
							jQuery('#registerCust').text('Submit');
							jQuery('#registerCust').removeAttr('disabled');
							setTimeout(function() {
								jQuery('#registerCust').removeAttr('disabled');
								window.location.reload();
							}, 2000);
						}
					}
				});

			}
		});
	</script>
	<!-- The Modal to show message-->
	<div class="modal fade" id="myRegModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal body -->
				<div class="modal-body" id="showMsg">

				</div>

			</div>
		</div>
	</div>


	<!-- The Modal to show message-->
	<div class="modal fade" id="successModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal body -->
				<div class="modal-body" id="showSuccessMsg">

				</div>
			</div>
		</div>
	</div>

	@stop
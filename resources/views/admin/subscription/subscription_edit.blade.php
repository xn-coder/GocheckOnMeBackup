@extends('layouts.admin')
@section('content')
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Edit Subscription</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active"> Edit Subscription</li>
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
					<h4 class="card-title mb-0">Edit Subscription</h4>
				</div>
				<div class="result"></div>


				<div class="card-body min_height">
					<form name="subscription_edit" id="subscription_edit" method="post" action="javascript:void(0)" enctype="multipart/form-data">
						@csrf
					    <div class="row">
							<!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
							<input type="hidden" name="subscription_id" id="subscription_id" value="{{ $subscription->id }}">

							<div class="mb-3 col-md-6">
		                     <label for="Phone" class="control-label">Name:</label>
		                     <input type="text" id="name" name="name" value="{{$subscription->name}}" class="form-control">
		                     {{-- allready exit error --}}
		                     <label id="employer_tin_error" class="error"></label>
		                  </div>

                            <div class="mb-3 col-md-6">
                                <label for="timezone" class="control-label">Amount:</label>
                                <input type="text" id="amount" name="amount" value="{{$subscription->amount}}" class="form-control" required>
                            {{-- allready exit error --}}
                            <label id="name_error" class="error"></label>
                            </div>
							
						</div>
						<a type="button" href="{{ url('admin/subscription_list') }}" class="btn btn-dark fa-pull-left mt-3">Back</a>
						<button class="btn btn-primary" style="float: right"  id="laodingbtn" type="button" disabled>
							<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Loading..</button>

						<input type="submit" id="submitimport" value="Update" class="btn btn-success btn_submit fa-pull-right mt-3">
					</form>
				</div>
			</div>
		</div>
		
	</div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->

<script>
   $("#laodingbtn").hide();
   	
</script>
<script>
    $("#subscription_edit").validate({
        rules: {
            name: {
                required: true,
            },
            timezone: {
                required: true
            },
        },
        messages: {
            amount: {
                required: "Please enter phone_no",
            },
            timezone: {
                required: "Please enter timezone",
            },
        },
        submitHandler: function(form) {
            var formData = new FormData(jQuery('#subscription_edit')[0]);
            jQuery.ajax({
                url: "{{ url('admin/update_subscription') }}",
                type: "post",
                cache: false,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(msg) {
                    $("#laodingbtn").show();
                    $("#submitimport").hide();
                },
                success: function(data) { 
                    if(data.status == true) {
                        Swal.fire({
                            text: data.message,
                            icon: "success",
                        });
                        setTimeout(function() {
                            window.location.href = "{{ route('subscription_list') }}";
                        }, 2000);
                    } else {
                        $.each(data.errors, function (key, value) {
                            var err = '#' + key + '_err';
                            $(err).text(value);
                        });
                    }
                }
            });
        }
    });
</script>
@stop



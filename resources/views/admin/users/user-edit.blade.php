@extends('layouts.admin')
@section('content')
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Edit User</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active"> Edit User </li>
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
					<h4 class="card-title mb-0">Edit User </h4>
				</div>
				<div class="result"></div>


				<div class="card-body min_height">
					<form name="user_edit" id="user_edit" method="post" action="javascript:void(0)" enctype="multipart/form-data">
						@csrf
					    <div class="row">
							<!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
							<input type="hidden" name="user_id" id="user_id" value="{{ $edit_user->id }}">

							<div class="mb-3 col-md-6">
								<label for="Name" class="control-label" >Name: <span style="color:Red">*</span></label>
								<input type="text" id="name" value="{{$edit_user->name}}" name="name" class="form-control" required>
							</div>
							<div class="mb-3 col-md-6">
		                     <label for="Phone" class="control-label">Phone Number(Land Line):</label>
		                     <input type="number" id="phone" name="phone" min='0' value="{{$edit_user->phone}}" class="form-control">
		                     {{-- allready exit error --}}
		                     <label id="employer_tin_error" class="error"></label>
		                  </div>

                          <div class="mb-3 col-md-6">
                             <label for="Phone" class="control-label">Phone Number(Cell Phone No):</label>
                             <input type="number" id="cell_phone_no" name="cell_phone_no" min='0' value="{{$edit_user->cell_phone_no}}" class="form-control">
                             {{-- allready exit error --}}
                             <label id="employer_tin_error" class="error"></label>
                          </div>

                          <div class="mb-3 col-md-6">
                             <label for="AlternatePhone" class="control-label">Phone Number(Alternate Phone No):</label>
                             <input type="number" id="alternate_phone_no" name="alternate_phone_no" min='0' value="{{$edit_user->alternate_phone_no}}" class="form-control">
                             {{-- allready exit error --}}
                             <label id="employer_tin_error" class="error"></label>
                          </div>

							<div class="mb-3 col-md-6">
								<label for="username" class="control-label">Email: <span style="color:Red">*</span></label>
								<input type="email" id="agency" name="email" value="{{$edit_user->email}}" class="form-control" required>
							{{-- allready exit error --}}
							<label id="name_error" class="error"></label>
							</div>

                            <div class="mb-3 col-md-6">
                                <label for="username" class="control-label">Email (Alternate Email):</label>
                                <input type="email" id="alernate_email" name="alernate_email" value="{{$edit_user->alernate_email}}" class="form-control" required>
                            {{-- allready exit error --}}
                            <label id="name_error" class="error"></label>
                            </div>

							<div class="mb-3 col-md-6">
		                     <label for="Email" class="control-label">Passowrd: <span style="color:Red">*</span></label>
		                     <input type="text" id="password" name="password" value="{{$edit_user->password}}"  required class="form-control">
		                     {{-- allready exit error --}}
		                     <label id="password_error" class="error"></label>
		                  </div>
							
						</div>
						<a type="button" href="{{ url('admin/users-list') }}" class="btn btn-dark fa-pull-left mt-3">Back</a>
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
    $("#user_edit").validate({
        rules: {
            name: {
                required: true,
            },
            phone: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter names",
            },
            phone: {
                required: "Please enter phone number",
            },
            email: {
                required: "Please enter valid email",
                email: "Please enter valid email",
            },
            password: {
                required: "Please enter password",
            },
        },
        submitHandler: function(form) {
            var formData = new FormData(jQuery('#user_edit')[0]);
            jQuery.ajax({
                url: "{{ url('admin/update_user') }}",
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
                            window.location.href = "{{ route('user_list') }}";
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



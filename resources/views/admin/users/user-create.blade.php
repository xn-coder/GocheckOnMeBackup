@extends('layouts.admin')
@section('content')
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
<div class="row page-titles">
   <div class="col-md-5 col-12 align-self-center">
      <h4 class="text-themecolor mb-0">Add New User</h4>
   </div>
   <div class="col-md-7 col-12 align-self-center d-none d-md-block">
      <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
         <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
         <li class="breadcrumb-item active">Add New User</li>
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
            <h4 class="card-title mb-0">Add User</h4>
         </div>
         <div class="card-body min_height">
            <form name="user_add" id="user_add"  enctype="multipart/form-data">
               @csrf
               <div class="row">
                  <div class="mb-3 col-md-6">
                     <label for="Name" class="control-label" >Name: <span style="color:Red">*</span> </label>
                     <input type="text" required id="name" autocomplete="off" name="name" class="form-control">
                  </div>
                  <div class="mb-3 col-md-6 d-none">
                     <label for="Phone" class="control-label">Phone Number: <span style="color:Red">*</span></label>
                     <input type="number" id="phone" name="phone" min='0' required class="form-control">
                     {{-- allready exit error --}}
                     <label id="employer_tin_error" class="error"></label>
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="Email" class="control-label">Email: <span style="color:Red">*</span></label>
                     <input type="text" id="email" name="email" autocomplete="off" required class="form-control">
                     {{-- allready exit error --}}
                     <label id="email_error" class="error"></label>
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="Email" class="control-label">Passowrd: <span style="color:Red">*</span></label>
                     <input type="text" id="password" name="password" required class="form-control">
                     {{-- allready exit error --}}
                     <label id="password_error" class="error"></label>
                  </div>
               </div>
               <div>
                  <a type="button" href="{{ route('user_list') }}" class="btn btn-dark fa-pull-left mt-3">Back</a>
                  <button class="btn btn-success"  id="laodingbtn" style="float: right" type="button" disabled>
                  <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                  Loading..
                  </button>
                  <input type="submit" value="Add" id="submitimport" class="btn btn-success btn_submit fa-pull-right mt-3">
               </div>
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
    $("#user_add").validate({
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
            var formData = new FormData(jQuery('#user_add')[0]);
            jQuery.ajax({
                url: "{{ url('admin/add_user') }}",
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
@extends('layouts.admin')
@section('content')


<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">View User</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">View User</li>
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
					<h4 class="card-title mb-0">View User</h4>
				</div>
				<div class="card-body min_height">
					<form name="user_view" id="user_view" method="post" action="javascript:void(0)" enctype="multipart/form-data">
						@csrf
					    <div class="row">
				
							<div class="mb-3 col-md-6">
								<label for="Name" class="control-label" >Name:</label>
                                <input type="text" required id="employer_id" name="employer_id" value="{{$user_view->name}}" readonly autocomplete="off" class="form-control">

							</div>
							<div class="mb-3 col-md-6">
		                     <label for="Phone" class="control-label">Phone Number(Land Line):</label>
		                     <input type="number" id="phone" readonly name="phone" min='0' value="{{$user_view->phone}}" class="form-control">
		                     {{-- allready exit error --}}
		                     <label id="employer_tin_error" class="error"></label>
		                  </div>

                          <div class="mb-3 col-md-6">
                             <label for="Phone" class="control-label">Phone Number(Cell Phone No):</label>
                             <input type="number" id="cell_phone_no" readonly name="cell_phone_no" min='0' value="{{$user_view->cell_phone_no}}" class="form-control">
                             {{-- allready exit error --}}
                             <label id="employer_tin_error" class="error"></label>
                          </div>

                          <div class="mb-3 col-md-6">
                             <label for="Phone" class="control-label">Phone Number(Alternate Phone No):</label>
                             <input type="number" readonly id="alternate_phone_no" name="alternate_phone_no" min='0' value="{{$user_view->alternate_phone_no}}" class="form-control">
                             {{-- allready exit error --}}
                             <label id="employer_tin_error" class="error"></label>
                          </div>

                           <div class="mb-3 col-md-6">
                                <label for="username" class="control-label">Email (Alternate Email):</label>
                                <input type="email" id="alernate_email" readonly name="alernate_email" value="{{$user_view->alernate_email}}" class="form-control" required>
                            {{-- allready exit error --}}
                            <label id="name_error" class="error"></label>
                            </div>
					
                            <div class="mb-3 col-md-6">
								<label for="Name" class="control-label" > Email:</label>
                                <input type="text" readonly id="email" name="email" value="{{$user_view->email}}" readonly autocomplete="off" class="form-control">
							</div>
                            
                           
							

                       </div>
						<a type="button" href="{{ url('admin/users-list') }}" class="btn btn-dark fa-pull-left mt-3">Back</a>
					</form>
				</div>
		
		</div>
			</div>
		</div>
        <meta name="csrf-token" content="{{ csrf_token() }}" />		
	</div>

    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->

@stop



@extends('layouts.admin')
@section('content')

<style>
    .btn_submit:disabled {
    background: #d66821;
    border-color: #d66821;
    opacity: 1;
}
</style>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Employer</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Employer</li>
			</ol>
		</div>
	</div>
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
		<!-- basic table -->
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="border-bottom title-part-padding d-flex justify-content-between">
					    <h4 class="card-title mb-0">Employer List</h4> 
						<a  href="{{url('add_employer')}}" class="btn btn-info btn-sm">
							Add Employer
						</a>               
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<div class="">
                                <!-- Alert Append Box -->
                                <div class="result"></div>


							<button style="display:none"  type="button" class="btn btn-primary"  data-toggle="modal" data-target="#exampleModal">
  								Send Notification
							</button>

                            </div>
							<table id="zero_config" class="table table-striped table-bordered">
								<thead>
									<tr>
									<!-- <th style=""><input type="checkbox" id="user_master"></th> -->
										<th style="width:100px;">Sr No.</th>
										<th style="width:800px;">Name</th>
										<th style="width:800px;">Employer TIN</th>
										<th style="width:500px;">Email</th>
										<Th style="width:800px;">Created Date</th>
										<th style="width:200px;">Action</th>
									</tr>
								</thead>
								<tbody>
								@foreach ($users as $sr=>  $user) 
								<tr>
									<!-- <td style=""><input type="checkbox" class="sub_chk" data-id="{{$user->id}}"></td> -->
										<td>{{ $sr+1 }}</td>
										<td style="display: table-cell;">
											<!-- <a href="javascript:void(0)" class="link"> -->
												<!-- <img src="assets/admin/images/theme/user.png" alt="user" width="30" height="30" class="rounded-circle">  -->
												<span class="ml-2">{{$user->name }}</span>
											<!-- </a> -->
										</td>
										<td>{{ $user->employer_tin }}</td>
									
										
										<td>{{$user->email}}</td>
										<td>{{$user->created_at}}</td>
										<td>
											<div class="table_action" >
												<a style="display: none;" href="{{url('/user-view')}}/{{$user->id}}" class="btn btn-success btn-sm list_view infoU"  data-id='"{{ $user->id }}"' data-bs-whatever="@mdo">
													<i class="mdi mdi-eye"></i>
												</a>  

												<a href="{{ url('employer_edit',$user->id) }}" class="btn btn-info btn-sm list_edit">
													<i class="mdi mdi-lead-pencil"></i>
												</a> 

												<a style="display:" href="{{ route('user_delete',$user->id) }}" class="btn btn-danger btn-sm  " onclick="return confirm('Are you sure delete this employer ?')">
													<i class="mdi mdi-delete"></i>
												</a> 
												
												<span class="status">
													<label class="switch">
														@if($user->status==1)
															<input data-id="{{$user->id}}" class="  switch-input" onchange="useractivedeactive({{$user->id}},'0');" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $user->status ? 'checked' : '' }}>
															<span class="switch-label" data-on="Active" data-off="Deactive"></span> 
															<span class="switch-handle"></span> 
														@else
															<input data-id="{{$user->id}}" class="  switch-input" onchange="useractivedeactive({{$user->id}},'1');" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Deactive" data-off="InActive">
															<span class="switch-label" data-on="Active" data-off="Deactive"></span> 
															<span class="switch-handle"></span> 
														@endif
													</label>
												</span>
											</div>
											  
										</td>
									</tr>
									
									@endforeach
									<meta name="csrf-token" content="{{ csrf_token() }}">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->


	<!-- model open  notification send-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send Notification</h5>
      </div>
      <div class="modal-body">
         <form class=""  method="post" action="javascript:void(0)" id="send_message">
                            @csrf
                            <div class="mb-3 col-md-12" style="position:relative;">
                                <label for="Name" class="control-label">Message:</label>
                               <textarea required="true" id="message" name="message" class="form-control" id="recipient-name1"></textarea>
					        </div> 
                    </div>
      <div class="modal-footer">
        <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Send</button>
      </div>
         </form>
    </div>
  </div>
</div>
<!--notification modal end-->
<!-- This page plugin CSS -->

<!-- Blog Details -->
<div class="modal fade" id="customer_details_modal" tabindex="-1" aria-labelledby="exampleModalLabel1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center">
				<h4 class="modal-title" id="exampleModalLabel1">User Details</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<div id="user-data">
					{{-- modal data here --}}
				</div>
			</div>

			<div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-weight-medium" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
</div>
 </div>
		<script>

	$('#user_master').on('click', function(e) {
		if($(this).is(':checked',true))  
		{
		$(".sub_chk").prop('checked', true);  
		} 
		else {  
		$(".sub_chk").prop('checked',false);  
		}  
	});

</script>
<script>

	$("#send_message").validate({
		
	rules: {
		messages: {required: true,}
	
		},
	
	messages: {
		messages: {required: "Please enter messages",}  

	},
		submitHandler: function(form) {
		   var formData= new FormData(jQuery('#send_message')[0]);
		   // formData.append("_token",$('meta[name="csrf-token"]').attr('content'));
		   u ="development/meyfintech/";
		   var allVals = [];  
		   $(".sub_chk:checked").each(function() {  
			allVals.push($(this).attr('data-id'));
			
		}); 

		   var join_selected_values = allVals.join(","); 
		    formData.append("ids",join_selected_values );
		jQuery.ajax({
				url: host_url+"notification",
				type: "post",				
				data: formData,
				 headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				processData: false,
				contentType: false,
				
				success:function(data) { 
				var obj = JSON.parse(data);
				
				if(obj.status==true){
					$("#result").html(obj.message);
					setTimeout(function(){
						 $("#b_trainer").modal('show');
					//	 window.location.href = "user_list";
					}, 1000);
				}
				else{
					if(obj.statusmobile_number==false){
						jQuery('#mobile_number_error').html(obj.message);
						jQuery('#mobile_number_error').css("display", "block");
					}
					else if(obj.statusemail==false){
						jQuery('#email_error').html('');
						jQuery('#email_error').html(obj.message);
						jQuery('#email_error').css("display", "block");
					}
					else{
						$("#result").html(obj.message);
						jQuery('#mobile_number_error').html('');
						jQuery('#email_error').html('');
					}
				}
				}
			});
		}
	});

</script>

    <script type="text/javascript">

  

    function useractivedeactive($id,$status){

		//host_url = "/development/payslip/";
		var status =$status; //$(this).prop('checked') == true ? 1 : 0; 
		var token = $("meta[name='csrf-token']").attr("content");
		var user_id =$id; //$(this).data('id'); 
		//	alert(user_id+status);

	//	host_url = $("#base_url").val();
			$.ajax({
				type: "POST",
				dataType: "json",
				url: 'userchangestatus',
				data: {'_token':  token,'status': status, 'user_id': user_id},
				success: function(data){
					if(data.status == true)
					{
						setTimeout(function(){
							jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> "+data.message+"</div>");
					  window.location.reload();
				  }, 1000);
					}
				}
			});
		
		
	}


$("#user_deactiveactive").validate({
	// rules: {
	// 	reason: {required: true,},
	// 	},
	// 	messages: {
	// 	reason: {required: "Please enter reason",},
	// },
		submitHandler: function(form) {
		   var formData= new FormData(jQuery('#user_deactiveactive')[0]);
	//alert("user_id"+$("#user_id").val());
		 //  formData.append("user_id",$("#user_id").val());
		// host_url = $("#base_url").val();
		jQuery.ajax({
			url: host_url+"userchangestatusactive",
			type: "post",
			cache: false,
			data: formData,
			processData: false,
			contentType: false,
			beforeSend: function(msg){
				$(".loadingbtnap").css("display","block");
				$(".btnokform").hide();
			},
		success:function(data) { 
			var obj = JSON.parse(data);
			if(obj.status==true)
			{
				$("#u_activemodal").modal('hide');
				jQuery('#name_error').html('');
				jQuery('#email_error').html('');
				jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> "+obj.message+"</div>");
	
				setTimeout(function(){
					
					window.location ="/user_list";
				}, 3000);
			}	
		}
		});
		}
	});

$("#user_deactive").validate({
	rules: {
		
		reason: {required: true,},
		},
		messages: {
		reason: {required: "Please enter reason",},
	},
		submitHandler: function(form) {
		   var formData= new FormData(jQuery('#user_deactive')[0]);
			//alert("user_id"+$("#user_id").val());
		   formData.append("user_id",$("#user_id").val());
		//   host_url = $("#base_url").val();
		jQuery.ajax({
				url: host_url+"userchangestatus",
				type: "post",
				cache: false,
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function(msg){
       				$(".loadingbtnap").css("display","block");
        			$(".btnokform").hide();
      			},
				success:function(data) { 
				var obj = JSON.parse(data);
				if(data.status==true){
					$("#u_reject").modal('hide');
					jQuery('#name_error').html('');
					jQuery('#email_error').html('');
					jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> "+data.message+"</div>");
					//	jQuery('.result').html('');
					setTimeout(function(){
						
						window.location = "/user_list";
					}, 3000);
				}else{
					if(obj.status==false){
						jQuery('#name_error').html(obj.message);
						jQuery('#name_error').css("display", "block");
					}
				}
				}
			});
		}
	});
	</script>

@stop
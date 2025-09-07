@extends('layouts.admin')
@section('content')
<style>
   .btn_submit:disabled {
   background: #d66821;
   border-color: #d66821;
   opacity: 1;
   }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
   <div class="row page-titles">
      <div class="col-md-5 col-12 align-self-center">
         <h4 class="text-themecolor mb-0">Users</h4>
      </div>
      <div class="col-md-7 col-12 align-self-center d-none d-md-block">
         <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
            <li class="breadcrumb-item active">Users</li>
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
                  <h4 class="card-title mb-0">Users List</h4>
                  <div class="">
                     <a href="{{url('admin/user-create')}}" class="btn btn-info btn-sm">Add Users</a> 
                  </div>
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
                              <th>Sr No</th>
                              <th>Name</th>
                              <th>Phone</th>
                              <th>Email</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($users as $sr=> $user) 
                           <tr>
                              <td>{{ $sr+1 }}</td>
                              <!-- <td style=""><input type="checkbox" class="sub_chk" data-id="{{$user->id}}"></td> -->
                              <td style="display: table-cell;">
                                 <span class="ml-2">{{$user->name }}</span>
                              </td>
                              <td>{{ $user->phone }}</td>
                              <td>{{ $user->email }}</td>
                                 <!-- <td>{{ $user->country }}</td> -->
                              <td>
                                 <div class="table_action" >
                                    <a href="{{url('/admin/user-view')}}/{{$user->id}}" class="btn btn-success btn-sm list_view infoU"  data-id='"{{ $user->id }}"' data-bs-whatever="@mdo">
                                    <i class="mdi mdi-eye"></i>
                                    </a>  
                                    <a href="{{ url('admin/user_edit',$user->id) }}" class="btn btn-info btn-sm list_edit">
                                    <i class="mdi mdi-lead-pencil"></i>
                                    </a> 
                                   <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-link" data-userid="{{ $user->id }}"
   												onclick="showDeleteConfirmation('{{ url('/admin/delete_user/'.$user->id) }}')">
   												<i class="mdi mdi-delete"></i>
												</a> 
                                    <span class="status">
                                    <label class="switch">
                                    <input data-id="{{$user->id}}" class="toggle-class1 switch-input statusChange" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $user->status ? 'checked' : '' }}>
                                    <span class="switch-label" data-on="Active" data-off="Deactive"></span> 
                                    <span class="switch-handle"></span> 
                                    </label>
                                    </span>
                                 </div>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- ============================================================== -->

</div>

<script>
   $('.statusChange').change(function() {
   	  var status = $(this).prop('checked') == true ? 1 : 0;
   	  var token = $("meta[name='csrf-token']").attr("content");
   	  var id = $(this).data('id'); 
   	  $.ajax({
   		  type: "POST",		
   		  cache: false,
   		  dataType: "json",
   		  url: "{{ url('admin/user_Status') }}",
   		  data: {'_token':  token,'status': status, 'id': id},
   		  success: function(data){
   		  	//location.reload();
   			setTimeout(function(){
   				jQuery('.result').html('');
   				window.location.reload();
   			}, 1000);
   		  }
   	  });
     });
   
</script>
@stop
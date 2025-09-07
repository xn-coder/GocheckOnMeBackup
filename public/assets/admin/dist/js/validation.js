$(function() {
//		host_url = "/development/payslip/"
	
		// console.log("host_url-->"+host_url);	
		
localStorage.setItem("baseurl",$("#base_url").val());
		// Add user form
$("#user_add").validate({
rules: {
	name: {required: true,},
	employer_tin: {required: true},
	email: {required: true,email:true},
	password:{ required:true, },
	logo:{ required:true, },
	profile:{ required:true, },
},
messages: {
		name: {required: "Please enter  name",},
		employer_tin: {required: "Please enter employer tin",},
		email: {required: "Please enter valid email",email: "Please enter valid email",},
		password:{required: "Please enter password",},
		logo:{required: "Please select logo",},
		profile:{required: "Please select profile",},
		
},
	submitHandler: function(form) {
	   var formData= new FormData(jQuery('#user_add')[0]);
	//    host_url = $("#base_url").val();
	//    console.log("host_url-->"+host_url);

//	   console.log( $("#base_url").val());

	jQuery.ajax({
		//	url: "{{route('store-data')}}",
			url: localStorage.getItem("baseurl")+"store-data",
			type: "post",
			cache: false,
			data: formData,
			processData: false,
			contentType: false,
			beforeSend: function(msg){
				$("#laodingbtn").show();
				$("#submitimport").hide();
			},
			success:function(data) { 
			var obj = JSON.parse(data);
			if(obj.status==true){
				jQuery('#payslip_id_error').html('');
				
				jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> "+obj.message+"</div>");
				setTimeout(function(){
					jQuery('.result').html('');
					$("#laodingbtn").hide();
				$("#submitimport").show();
				//	window.location = "employer_list";
					window.location = localStorage.getItem("baseurl")+"employer_list";
				}, 2000);
			}
			else{
				if(obj.employer_tin==false){
					$("#laodingbtn").hide();
				$("#submitimport").show();
					jQuery('#employer_tin_error').html(obj.message);
					jQuery('#employer_tin_error').css("display", "block");
				}
				else if(obj.email==false){
				$("#laodingbtn").hide();
				$("#submitimport").show();

					jQuery('#email_error').html(obj.message);
					jQuery('#email_error').css("display", "block");
				}
				else{
				$("#laodingbtn").hide();
				$("#submitimport").show();

					jQuery('#employer_tin_error').html('');
					jQuery('#email_error').html('');
					jQuery('.result').html('');
				}
			}
			}
		});
	}
});




// Status active deactive
	
	$('.toggle-class').change(function() {
		
//		host_url = "/development/payslip/";
		var status = $(this).prop('checked') == true ? 1 : 0; 
		var token = $("meta[name='csrf-token']").attr("content");
		var user_id = $(this).data('id'); 
		
		$.ajax({
			type: "POST",
			dataType: "json",
			//url: host_url+'changeStatus/'+user_id,
			url: '/changeStatus/'+user_id,
			data: {'_token':  token,'status': status, 'user_id': user_id},
			success: function(data){
			  setTimeout(function(){
				  jQuery('.result').html('');
				  window.location = "/user_list";
			  }, 1000);
			}
		});
	});
	$('.useractivedeactive').change(function() {
		
	//	host_url = "/development/payslip/";
		var status = $(this).prop('checked') == true ? 1 : 0; 
		var token = $("meta[name='csrf-token']").attr("content");
		var user_id = $(this).data('id'); 
		
		if(status==0)
		{
			$("#u_reject").modal('show');
			$("#user_id").val(user_id);
			$("#status").val(status);

		}
		else
		{
		//	host_url = $("#base_url").val();
		
			$.ajax({
				type: "POST",
				dataType: "json",
				url: 'userchangestatus',
				//url: '/userchangestatus',
				data: {'_token':  token,'status': status, 'user_id': user_id},
				success: function(data){
				  setTimeout(function(){
					  jQuery('.result').html('');
					  window.location.reload();
				  }, 1000);
				}
			});
		}
	
	});
	
	



//------------------set_password_form_fitness_trainer-------------------------


patten ="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$";


      jQuery.validator.addMethod("passwordcheck", function(value, element, param) {
    return value.match(patten);
},'Please enter valid password');

$("#user_change_password").validate({
	rules: {
		old_password: {required: true,passwordcheck:true,},
		
		new_password: {required: true,passwordcheck:true,},
		
		confirm_password : {
			required: true,
			equalTo : "#new_password"
		}
	},
		
	messages: {

		old_password: {required: "Please enter old password",},
		new_password:{required:"please enter new password",},

	
	confirm_password:{required:"Please enter confirm password", equalTo:"Password and confirm password must be same"},
	},
		submitHandler: function(form) {
		   var formData= new FormData(jQuery('#user_change_password')[0]);
		jQuery.ajax({
			//	url: host_url+"user_change_password",
				url: localStorage.getItem("baseurl")+"user_change_password",
				type: "post",
				cache: false,
				data: formData,
				processData: false,
				contentType: false,
				
				success:function(data) { 
				
				var obj = JSON.parse(data);
				
				if(obj.status==true){
					jQuery('#name_error').html('');
					jQuery('#email_error').html('');
					jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Change Password Successfully.</strong> </div>");
					setTimeout(function(){
						jQuery('.result').html('');
						//window.location = localStorage.getItem("baseurl")+"employees_list";
						window.location.reload();
					}, 2000);
				}
				else{
					if(obj.status==false){
						jQuery('.result').html(obj.message);
						jQuery('#name_error').css("display", "block");
					}
					
				}
				}
			});
		}
	});

$("#update_admin_profile").validate({
	rules: {
		name:{required:true}
	
	},
		
	messages: {
	
		name: {required: "Please enter name",},
	},
		submitHandler: function(form) {
		   var formData= new FormData(jQuery('#update_admin_profile')[0]);
		host_url = $("#base_url").val();
		jQuery.ajax({
				//url: host_url+"update_admin_profile",
				url: localStorage.getItem("baseurl")+"update_admin_profile",
				type: "post",
				cache: false,
				data: formData,
				processData: false,
				contentType: false,
				
				success:function(data) { 
				
				var obj = JSON.parse(data);
				
				if(obj.status==true){
					jQuery('#name_error').html('');
					jQuery('#email_error').html('');
					jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>"+obj.message+"</strong></div>");
				setTimeout(function(){
						jQuery('.result').html('');
							location.reload();
						window.location = localStorage.getItem("baseurl")+"my_profile";
					}, 2000);
				}
				else{
					if(obj.status==false){
						jQuery('.result').html(obj.message);
						jQuery('#name_error').css("display", "block");
					}
					
				}
				}
			});
		}
	});
$("#update_admin_profile").validate({
	rules: {
		name:{required:true},
		phone:{ 
	
		required:true,
		minlength:10,
		maxlength:10}
		
	
	},
		
	messages: {
	
		name: {required: "Please enter name",},
		phone:{required:"please enter Mobile Number"}
		
	},
		submitHandler: function(form) {
		   var formData= new FormData(jQuery('#update_admin_profile')[0]);
		   host_url = $("#base_url").val();
	console.log(localStorage.getItem("baseurl"));
		jQuery.ajax({
				//url: host_url+"update_admin_profile",
				url: localStorage.getItem("baseurl")+"update_admin_profile",
				type: "post",
				cache: false,
				data: formData,
				processData: false,
				contentType: false,
				
				success:function(data) { 
				
				var obj = JSON.parse(data);
				
				if(obj.status==true){
					jQuery('#name_error').html('');
					jQuery('#email_error').html('');
					jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Profile Update Successfull </strong> </div>");
					setTimeout(function(){
						jQuery('.result').html('');
						location.reload();
						window.location = host_url+"employees_list";
					}, 2000);
				}
				else{
					if(obj.status==false){
						jQuery('.result').html(obj.message);
						jQuery('#name_error').css("display", "block");
					}
					
				}
				}
			});
		}
	});
// single user view data in modal 

$(".infoU").click(function (e) {
	$currID = $(this).attr("data-id");
	$test = $currID.replace(/\"/g, "");
	$a ='/user_view/'+$test;
	$.get($a, function (data) {
		$('#user-data').html(data);
	});
});


   //host_url = "/development/payslip/";
   $("#user_edit").validate({
	rules: {
		name: {required: true,},
		email: {required: true,email: true,},  
		username: {required: true},
		password: {required: true,}, 
			
	},
	messages: {
		name: {required: "Please enter name",},
		email: {required: "Please enter valid email",email: "Please enter valid email",},   
		username: {required: "Please enter username",},
		password: {required: "Please enter password",},

	},
		submitHandler: function(form) {
		   var formData= new FormData(jQuery('#user_edit')[0]);
		   var url = '\nhref = ' + $(location).attr('href');
		   var id = url.substring(url.lastIndexOf('/') + 1);
		jQuery.ajax({
				//url: host_url+"update_data",
				url: host_url+"update_data",
				type: "post",
				cache: false,
				data: formData,
				processData: false,
				contentType: false,
				
				success:function(data) { 
				var obj = JSON.parse(data);
				if(obj.status==true){
					jQuery('#name_error').html('');
					jQuery('#email_error').html('');
					jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> User update success</div>");
					setTimeout(function(){
						jQuery('.result').html('');
					//	window.location = "/"+host+"/user_list";
window.location = "/user_list";
					}, 2000);
				}
				else{
					if(obj.statusName==true){
						alert(obj.message);
						jQuery('#name_error').html(obj.message);
						jQuery('#name_error').css("display", "block");
					}
					else if(obj.statusEmail==true){
						jQuery('#name_error').html('');
						jQuery('#email_error').html(obj.message);
						jQuery('#email_error').css("display", "block");
					}
					else{
						jQuery('#email_error').html('');
						jQuery('.result').html('');
					}
				}
				}
			});
		}
	});

	function confrimDelete() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
        })
    }

	$('.deleteConf').click(function() {
		return confirm("Are you sure?");
	   });
  
   
	

	

//    multiple category delete
    
    
	$('#master').on('click', function(e) {
		if($(this).is(':checked',true))  
		{
		$(".sub_chk").prop('checked', true);  
		} else {  
		$(".sub_chk").prop('checked',false);  
		}  
	});
    
    
	$('.delete_all').on('click', function(e) {
		var allVals = [];  
		
		var token  =  $('meta[name="_token"]').attr('content');
		$(".sub_chk:checked").each(function() {  
			allVals.push($(this).attr('data-id'));
			
		});  
    
		if(allVals.length <=0)  
		{  
			alert("Please select row.");  
		}  else {  

			var check = confirm("Are you sure you want to delete this row?");  
			if(check == true){  

				var join_selected_values = allVals.join(","); 

				$.ajax({
					url: host_url+$(this).data('url'),
					type: 'POST',
					headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
					data: 'ids='+join_selected_values,
					success: function (data) {
						if (data['success']) {
							$(".sub_chk:checked").each(function() {  
								$(this).parents("tr").remove();
							});
							alert(data['success']);
							location.reload();
						} else if (data['error']) {
							alert(data['error']);
						} else {
							alert('Whoops Something went wrong!!');
						}
					},
					error: function (data) {
						alert(data.responseText);
					}
				});
				$.each(allVals, function( index, value ) {
					$('table tr').filter("[data-row-id='" + value + "']").remove();
				});
			}  
		}  
	});

    

	$(document).on('confirm', function (e) {
		var ele = e.target;
		e.preventDefault();
		$.ajax({
			url: ele.href,
			type: 'POST',
			headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
			success: function (data) {
				if (data['success']) {
					$("#" + data['tr']).slideUp("slow");
					alert(data['success']);
				} else if (data['error']) {
					alert(data['error']);
				} else {
					alert('Whoops Something went wrong!!');
				}
			},
			error: function (data) {
				alert(data.responseText);
			}
		});
		return false;
	});

	$('body').on('click', '.editBtn', function () {
        var id = $(this).data('id');
        var token = $('meta[name="_token"]').attr('content')
        $.ajax({
        type:"GET",
        url: "/category_edit/"+id,
        data: { id: id, _token: token  },
        dataType: 'json',
        success: function(data){
            alert(data.data.id);
           }
        });
      });

	  $("#admin_change_password").validate({
		rules: {
				old_password: {required: true,},
				new_password: {required: true},  
				confirm_password: {
				equalTo: "#password"
				}
			},
		messages: {
			old_password: {required: "Please enter Old Password"},
			new_password: {required: "Please enter new_password"},
			confirm_password: " Enter Confirm Password Same as Password",
		},
			submitHandler: function(form) {
			   var formData= new FormData(jQuery('#admin_change_password')[0]);
			//    var url = '\nhref = ' + $(location).attr('href');
			//    var id = url.substring(url.lastIndexOf('/') + 1);
			jQuery.ajax({
					url: "/admin_change_password",
					type: "post",
					cache: false,
					data: formData,
					processData: false,
					contentType: false,
					
					success:function(data) { 
					var obj = JSON.parse(data);
					if(obj.status==true){
						jQuery('#name_error').html('');
						jQuery('#email_error').html('');
						jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> User update success</div>");
						setTimeout(function(){
							jQuery('.result').html('');
							window.location = "/user_list";
						}, 2000);
					}
					else{
						if(obj.statusName==true){
							alert(obj.message);
							jQuery('#name_error').html(obj.message);
							jQuery('#name_error').css("display", "block");
						}
						else if(obj.statusEmail==true){
							jQuery('#name_error').html('');
							jQuery('#email_error').html(obj.message);
							jQuery('#email_error').css("display", "block");
						}
						else{
							jQuery('#email_error').html('');
							jQuery('.result').html('');
						}
					}
					}
				});
			}
		});


//--------------------------admin add
$("#admin_add").validate({
	rules: {
		name: {required: true,},
		email: {required: true,email: true,},  
		password: {required: true,},
		image:{ required:true, },
	},
	messages: {
		name: {required: "Please enter name",},
		email: {required: "Please enter valid email",email: "Please enter valid email",},
		password: {required: "Please enter password",},
		image:{required: "Please enter profile",},
	},
		submitHandler: function(form) {
		   var formData= new FormData(jQuery('#admin_add')[0]);
		jQuery.ajax({
				//url: host_url+"admin-data",
				url: "/admin-data",
				type: "post",
				cache: false,
				data: formData,
				processData: false,
				contentType: false,
				
				success:function(data) { 
				var obj = JSON.parse(data);
				if(obj.status==true){
					jQuery('#payslip_id_error').html('');
					
					jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> "+obj.message+"</div>");
					setTimeout(function(){
						jQuery('.result').html('');
						window.location = "/admin_list";
					}, 2000);
				}
				else{
					if(obj.statusemail==false){
						jQuery('#email_error').html(obj.message);
						jQuery('#email_error').css("display", "block");
					}
					else{
						jQuery('#email_error').html('');
						jQuery('.result').html('');
					}
				}
				}
			});
		}
	});

});



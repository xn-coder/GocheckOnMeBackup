<script src="{{ asset('assets/admin/dist/js/validation.js') }}"></script>
<!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <footer class="footer text-center">
 All Rights Reserved. TAT Payroll System	</footer>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== --> </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->

   <div class="chat-windows"></div>
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
   
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/admin/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!-- apps -->
   
    <script src="{{ asset('assets/admin/dist/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/admin/dist/js/app.init.js') }}"></script>
    <script src="{{ asset('assets/admin/dist/js/app-style-switcher.js') }}"></script>
	
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('assets/admin/extra-libs/sparkline/sparkline.js') }}"></script>
	<script src="{{ asset('assets/admin/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('assets/admin/dist/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('assets/admin/dist/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('assets/admin/dist/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/admin/dist/js/custom.min.js') }}"></script>
    
</body>

</html>
{{-- validation form  --}}

<!-- This page plugin CSS -->
<link href="{{ asset('assets/admin/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<!--This page plugins -->
<script src="{{ asset('assets/admin/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/admin/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>



<link href="{{ asset('assets/admin/libs/apexcharts/dist/apexcharts.css') }}" rel="">
<link href="{{ asset('assets/admin/libs/jvectormap/jquery-jvectormap.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/libs/fullcalendar/dist/fullcalendar.min.css') }}" rel="stylesheet">
<!-- chartist chart -->

<script src="{{ asset('assets/admin/libs/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('assets/admin/dist/js/pages/calendar/cal-init.js') }}"></script>
<script src="{{ asset('assets/admin/libs/apexcharts/dist/apexcharts.min.js') }}"></script>

<!-- Vector map JavaScript -->
<script src="{{ asset('assets/admin/libs/jvectormap/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('assets/admin/extra-libs/jvector/jquery-jvectormap-world-mill-en.js') }}"></script>

<!-- Chart JS -->
<script src="{{ asset('assets/admin/dist/js/pages/dashboards/dashboard4.js') }}"></script>

<script type="text/javascript">
    function showDeleteConfirmation(url) {  
        Swal.fire({
            title: "Delete Confirmation",
            text: "Are you sure you want to delete this itme?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                type: "get",              
                url: url,              
                success: function(data) {
                    if (data.message) {
                        setTimeout(function() {
                        jQuery('.result').html('');
                        location.reload();
                    }, 2000);
                        Swal.fire({
                            text: data.message,
                            icon: "success",
                        }).then(function() {
                     
                    });
                  }
                }
             });
            }
            
        });
    }

      function changeStatus(url,id,status) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: url,
            data: {'_token': '{{ csrf_token() }}', 'id': id},
            success: function(data) {
                if (data.message) {
                    Swal.fire({
                        text: data.message,
                        icon: "success",
                    });
                    setTimeout(function() {
                    jQuery('.result').html('');
                    window.location.reload();
                }, 2000);
                }
            }
        });
    } 
</script>
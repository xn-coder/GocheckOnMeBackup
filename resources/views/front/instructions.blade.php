<html>

<head>
    <meta http-equiv="Content-Language" content="en-us">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title>Step by step instructions.</title>
    <link rel="stylesheet" href="{{asset('front-assets/style.css')}}">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">


    <style>
        .chosepic {
            padding-left: 20px;
        }
        .sendCall_btn{
        	border: none;
        	background: none;
        	cursor: pointer;
        }
    </style>
    <script language="JavaScript">
        <!--
        function FP_preloadImgs() { //.0
            var d = document,
                a = arguments;
            if (!d.FP_imgs) d.FP_imgs = new Array();
            for (var i = 0; i < a.length; i++) {
                d.FP_imgs[i] = new Image;
                d.FP_imgs[i].src = a[i];
            }
        }

        function FP_swapImg() { //v1.0
            var doc = document,
                args = arguments,
                elm, n;
            doc.$imgSwaps = new Array();
            for (n = 2; n < args.length; n += 2) {
                elm = FP_getObjectByID(args[n]);
                if (elm) {
                    doc.$imgSwaps[doc.$imgSwaps.length] = elm;
                    elm.$src = elm.src;
                    elm.src = args[n + 1];
                }
            }
        }

        function FP_getObjectByID(id, o) { //v1.0
            var c, el, els, f, m, n;
            if (!o) o = document;
            if (o.getElementById) el = o.getElementById(id);
            else if (o.layers) c = o.layers;
            else if (o.all) el = o.all[id];
            if (el) return el;
            if (o.id == id || o.name == id) return o;
            if (o.childNodes) c = o.childNodes;
            if (c)
                for (n = 0; n < c.length; n++) {
                    el = FP_getObjectByID(id, c[n]);
                    if (el) return el;
                }
            f = o.forms;
            if (f)
                for (n = 0; n < f.length; n++) {
                    els = f[n].elements;
                    for (m = 0; m < els.length; m++) {
                        el = FP_getObjectByID(id, els[n]);
                        if (el) return el;
                    }
                }
            return null;
        }
        //
        -->
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <script type="text/javascript">
      // const itis = {};
    
document.addEventListener('DOMContentLoaded', function() {
    // Store all instances
    const itiInstances = {};


    document.querySelectorAll('.numbers').forEach(function(input) {
        input.addEventListener('keypress', function(e) {
            // Allow only numbers (0-9)
            if (e.key < '0' || e.key > '9') {
                e.preventDefault();
            }
        });

        input.addEventListener('input', function(e) {
            // Remove any non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Prevent paste of non-numeric characters
        input.addEventListener('paste', function(e) {
            // Get pasted data
            let pastedData = (e.clipboardData || window.clipboardData).getData('text');
            if (!/^\d+$/.test(pastedData)) {
                e.preventDefault();
            }
        });
    });


    
    
    // Function to remove spaces from input
    function removeSpaces(input) {
        input.value = input.value.replace(/\s/g, '');
    }
    
    // Initialize each phone input separately
    function initializePhoneInput(inputId) {
        const input = document.getElementById(inputId);
        
        if (input) {
            // Add event listeners to prevent and remove spaces
            input.addEventListener('input', function(e) {
                removeSpaces(this);
            });
            
            input.addEventListener('keypress', function(e) {
                if (e.key === ' ') {
                    e.preventDefault();
                }
            });
            
            // Destroy existing instance if any
            if (itiInstances[inputId]) {
                itiInstances[inputId].destroy();
            }
            
            // Initialize new instance
            itiInstances[inputId] = window.intlTelInput(input, {
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                separateDialCode: true,
                initialCountry: "in",
                preferredCountries: ['in'],
                formatOnDisplay: false // Prevent automatic formatting
            });
            
            // Set initial country code
            const countryData = itiInstances[inputId].getSelectedCountryData();
            const hiddenInput = document.getElementById(inputId + '_country_code');
            if (hiddenInput && countryData) {
                hiddenInput.value = countryData.dialCode;
            }
            
            // Add change event listener
            input.addEventListener('countrychange', function() {
                const countryData = itiInstances[inputId].getSelectedCountryData();
                const hiddenInput = document.getElementById(inputId + '_country_code');
                if (hiddenInput && countryData) {
                    hiddenInput.value = countryData.dialCode;
                    removeSpaces(input); // Remove any spaces after country change
                }
            });
        }
    }
    
    // Initialize each input
    ['phone', 'cell_phone_no', 'phone_no'].forEach(inputId => {
        setTimeout(() => {
            initializePhoneInput(inputId);
        }, 100);
    });
});



        $(document).ready(function() {
            $('#notification_email_and_phone').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    method: 'POST',
                    url: "{{url('notification_number_and_email')}}",
                    data: formData,
                    processData: false, // Important!
                    contentType: false, // Important!
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.status == true) {
                            Swal.fire({
                                title: data.status,
                                text: data.message,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // window.location.href = "{{url('/')}}";
                            });
                        } else {
                            $(".inputerror").text('');
                            $.each(data.error, function(key, val) {
                                $("#" + key + "_error").text(val[0]);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('form#step-nstruction').submit(function(e) {
                e.preventDefault();

                   // Validate call limits before submitting
                var validationErrors = validateDailyCallLimits();
                if (validationErrors.length > 0) {
                    Swal.fire({
                        title: "Too Many Calls!",
                        html: validationErrors.join("<br>"),
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                    return;
                }

                var formData = new FormData(this);
                $.ajax({
                    method: 'POST',
                    url: "{{url('fill_data_instructions')}}",
                    data: formData,
                    processData: false, // Important!
                    contentType: false, // Important!
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.status == true) {
                            Swal.fire({
                                title: data.status,
                                text: data.message,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // window.location.href = "{{url('/')}}";
                            });
                        } else {
                            $(".inputerror").text('');
                            $.each(data.error, function(key, val) {
                                $("#" + key + "_error").text(val[0]);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 400) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.errors) {
                                Swal.fire({
                                    title: "Call Limit Exceeded!",
                                    html: response.errors.join("<br>"),
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            }
                        } else {
                            console.error(xhr.responseText);
                        }
                    }
                });
            });
         // Real-time validation as user types
         $("input[name*="_time"]").on("input", function() {
                validateDailyCallLimits();
            });
        });

        function validateDailyCallLimits() {
            var days = ["mon", "tue", "wed", "thu", "fri", "sat", "sun"];
            var errors = [];
            
            days.forEach(function(day) {
                var timeCount = 0;
                
                // Count non-empty time inputs for this day
                if ($("input[name="" + day + "_time1"]").val().trim() !== "") timeCount++;
                if ($("input[name="" + day + "_time2"]").val().trim() !== "") timeCount++;
                if ($("input[name="" + day + "_time3"]").val().trim() !== "") timeCount++;
                
                if (timeCount > 3) {
                    errors.push("Maximum 3 calls allowed per day. You entered " + timeCount + " times for " + day.charAt(0).toUpperCase() + day.slice(1) + ".");
                }
                
                // Visual feedback - highlight the day column if over limit
                var dayColumn = $("input[name="" + day + "_time1"]").closest("td");
                if (timeCount > 3) {
                    dayColumn.css("background-color", "#ffcccc");
                } else {
                    dayColumn.css("background-color", "");
                }
            });
            
            return errors;
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.active_service').on('click', function(){
                var type = $('#send_call').val();
                sendAjaxRequest(type);
            });

            $('.stop_call_btn').on('click', function(){
                var type = $('#stop_call').val();
                sendAjaxRequest(type);
            });

            function sendAjaxRequest(type) {
                $.ajax({
                    method: 'POST',
                    url: "{{ url('send_call_and_stop') }}",
                    data: { type: type },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.status == true) {
                            Swal.fire({
                                title: data.status,
                                text: data.message,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // window.location.href = "{{ url('/') }}";
                            });
                        } else {
                            $(".inputerror").text('');
                            $.each(data.error, function(key, val) {
                                $("#" + key + "_error").text(val[0]);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    </script>


</head>


<body background="{{url('front-assets/parchment.gif')}}" onload="FP_preloadImgs(/*url*/'button31.jpg', /*url*/'button32.jpg', /*url*/'button58.jpg', /*url*/'button57.jpg', /*url*/'button69.jpg', /*url*/'button68.jpg', /*url*/'button2A.jpg', /*url*/'button29.jpg', /*url*/'button3E.jpg', /*url*/'button3D.jpg', /*url*/'button3.jpg', /*url*/'button2.jpg', /*url*/'button12.jpg', /*url*/'button11.jpg', /*url*/'button17.jpg', /*url*/'button16.jpg', /*url*/'button26.jpg', /*url*/'button27.jpg')">

    <font color="#800000" face="Arial">
        <h5 align="center">
            <img border="0" src="{{url('front-assets/gocheck.gif')}}" width="381" height="52">
            <!-- <font color="#800000" face="Times New Roman"> </font>
			<font color="#800000" face="Arial Unicode MS">®</font> -->
        </h5>
        <h4 align="center">
            <!-- <img border="0" src="{{url('front-assets/littlerobot.jpg')}}" width="50" height="110"> -->
            <h2 style="font-size: 40px; text-align: center;">Step by Step Instructions</h2>
            <div align="center">
                <div align="center">
                    <table border="0" width="800" cellspacing="1" cellpadding="5">
                        <tr>
                            <td>

                                <font color="#800000" face="Arial">
                                    <p align="center" style="margin-bottom: 0px;">
                                        This is the sample form that you, the client (caretaker) will fill out for
                                        contact with your
                                        recipient (loved one) information.
                                        For safety the clients and recipients name is not required, just their phone
                                        numbers.</p>
                                    <p align="center" style="margin-top: 0;">
                                        After registration and payment is complete, sign on with your user name and
                                        password. Fill out the &quot;contact form&quot; with the times of the day or
                                        night you
                                        wish for checking on your <i>loved one .</i></p>
                                </font>
                            </td>
                        </tr>
                    </table>
                </div>
                <h2 style="font-size: 40px; text-align: center;">
                    Sample &quot;Contact Times Form&quot; entry.</h2>
                <p align="center" style="max-width: 800px;">
                    This is the sample form that you, the client (care taker), will fill out for
                    contact with your recipient's information.For safety the clients' and recipients'
                    names is not required, just their phone numbers.</p>
                <div align="center">
                    <form id="step-nstruction" enctype="multipart/form-data">
                        @csrf
                        <table border="0" width="900" cellpadding="5" style="margin-top: 50px;" class="contact_time_form_table">
                            <tr>
                                <td width="200">
                                    <font color="#800000">Your user name: {{isset($user->name) ? $user->name:''}}</font>
                                </td>
                                <td width="200">
                                    <font color="#800000">Use your e-mail address:
                                        {{isset($user->email) ? $user->email:''}}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td>

                                    <font color="#800000">Your<font face="Arial">
                                            password: {{ $user ? ($user->password ? $user->password : '') : '' }}</font>
                                    </font>
                                </td>

                            </tr>
                            <tr>
                            <td class="inputBox">
                                <input type="tel" id="phone" class="numbers" required="required" 
                                       value="{{isset($user->phone) ? $user->phone:''}}" name="phone" 
                                       placeholder="Your Land Line Number, if Any">
                                <input type="hidden" name="phone_country_code" id="phone_country_code">
                            </td>
                        </tr>
                        <tr>
                            <td class="inputBox">
                                <input type="tel" id="cell_phone_no" class="numbers" required="required" 
                                       value="{{isset($user->cell_phone_no) ? $user->cell_phone_no :''}}" 
                                       name="cell_phone_no" placeholder="Your Cell Phone Number, if Any">
                                <input type="hidden" name="cell_phone_country_code" id="cell_phone_no_country_code">
                            </td>
                            <td class="inputBox">
                                <input type="tel" id="phone_no" class="numbers" required="required" 
                                       value="{{isset($loved->phone_no) ? $loved->phone_no:''}}" 
                                       name="phone_no" placeholder="Loved Ones Phone Number">
                                <input type="hidden" name="phone_no_country_code" id="phone_no_country_code">
                            </td>
                        </tr>
                            <tr>
                                <td style="display: flex; gap: 28px;">

                                    <font color="#800000" face="Arial">

                                        Loved Ones time zone:
                                    </font>

                                    <font color="#800000" face="Arial">
                                        <!-- <form style="margin: 0;" method="POST" action="https://gocheckonme.com/--WEBBOT-SELF--"> -->
                                        <!--webbot bot="SaveResults" U-File="C:\clients\waldo\_private\form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" -->
                                        <p align="center" style="margin: 0;">
                                            <input type="radio" value="Pacific/Honolulu" name="timezone" @if(optional($loved)->timezone == 'Pacific/Honolulu') checked @endif>
                                            <font color="#800000">HI</font> &nbsp;
                                            <input type="radio" value="America/Phoenix" name="timezone" @if(optional($loved)->timezone == 'America/Phoenix') checked @endif>AZ
                                            &nbsp;
                                            <input type="radio" value="America/Anchorage" name="timezone" @if(optional($loved)->timezone == 'America/Anchorage') checked @endif>AK
                                            &nbsp;
                                            <input type="radio" value="America/Los_Angeles" name="timezone" @if(optional($loved)->timezone == 'America/Los_Angeles') checked
                                            @endif>PT &nbsp;
                                            <input type="radio" value="America/Denver" name="timezone" @if(optional($loved)->timezone == 'America/Denver') checked @endif>MT
                                            &nbsp;
                                            <input type="radio" value="America/Chicago" name="timezone" @if(optional($loved)->timezone == 'America/Chicago') checked @endif>CT
                                            &nbsp;
                                            <input type="radio" value="America/New_York" name="timezone" @if(optional($loved)->timezone == 'America/New_York') checked @endif>ET
                                            &nbsp;
                                            <input type="radio" value="Asia/Kolkata" name="timezone" @if(optional($loved)->timezone == 'Asia/Kolkata') checked @endif >IN
                                            &nbsp;
                                        </p>
                                        <!-- </form> -->
                                    </font>
                                </td>

                            </tr>
                            <tr>
                                <td class="inputBox">
                                    <input type="file" name="voice_message" style="padding-left: 20%;">
                                    <span>Loved Ones Voice</span>
                                </td>
                            </tr>

                        </table>

                        <p>Arizona stays on mountain standard time. Select Arizona time for Arizona residents.</p>

                        <h2 style="font-size: 40px; text-align: center; margin-bottom: 0px; margin-top: 48px;">$7.47 per
                            month for <br> three (3) calls per day. </h2>

                        <div style="background-color: #ffffcc; border: 2px solid #ffcc00; padding: 15px; margin: 20px 0; border-radius: 5px; text-align: center;">
                            <h3 style="color: #cc6600; margin-top: 0;">⚠️ DAILY CALL LIMIT ⚠️</h3>
                            <p><strong>Maximum 3 calls allowed per calendar day (in your loved one\'s timezone)</strong></p>
                            <p>• The system will prevent you from entering more than 3 times per day<br>
                            • If you try to exceed this limit, you\'ll see a warning message<br>
                            • Use the <a href='{{url("/practice")}}'>Practice Page</a> for unlimited practice entries</p>
                        </div>

                        <p>Up to three calls per day, please use this practice
                            form.</p>
                        <table border="1" width="800" cellpadding="2" style="border-collapse: collapse;" class="call_three_perday_table">
                            <tr style="background-color: #D4CBAF;">
                                <td>
                                    <p align="center">
                                        <font color="#fff">Mon</font>
                                </td>
                                <td>
                                    <p align="center">
                                        <font color="#fff">Tue</font>
                                </td>
                                <td>
                                    <p align="center">
                                        <font color="#fff">Wed</font>
                                </td>
                                <td>
                                    <p align="center">
                                        <font color="#fff">Thu</font>
                                </td>
                                <td>
                                    <p align="center">
                                        <font color="#fff">Fri</font>
                                </td>
                                <td>
                                    <p align="center">
                                        <font color="#fff">Sat</font>
                                </td>
                                <td>
                                    <p align="center">
                                        <font color="#fff">Sun</font>
                                </td>
                            </tr>
                            @if(auth()->check() && $timecheck == true)
                            <tr>

                                @foreach(['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day)
                                <td valign="top" width="100">
                                    <div>
                                        @php
                                        $time = $times->where('day', $day)->first();
                                        $time1 = $time ? $time->time1 : '';
                                        $time2 = $time ? $time->time2 : '';
                                        $time3 = $time ? $time->time3 : '';
                                        $am_pm1 = $time ? $time->am_pm1 : 'am';
                                        $am_pm2 = $time ? $time->am_pm2 : 'am';
                                        $am_pm3 = $time ? $time->am_pm3 : 'am';
                                        @endphp
                                        <font color="#800000" face="Arial">
                                            <input type="text" value="{{ $time1 }}" name="{{ $day }}_time1">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" name="{{ $day }}_am_pm1" {{ $am_pm1 == 'am' ? 'checked' : '' }}>am
                                                <input type="radio" value="pm" name="{{ $day }}_am_pm1" {{ $am_pm1 == 'pm' ? 'checked' : '' }}>pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" value="{{ $time2 }}" name="{{ $day }}_time2">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" name="{{ $day }}_am_pm2" {{ $am_pm2 == 'am' ? 'checked' : '' }}>am
                                                <input type="radio" value="pm" name="{{ $day }}_am_pm2" {{ $am_pm2 == 'pm' ? 'checked' : '' }}>pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" value="{{ $time3 }}" name="{{ $day }}_time3">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" name="{{ $day }}_am_pm3" {{ $am_pm3 == 'am' ? 'checked' : '' }}>am
                                                <input type="radio" value="pm" name="{{ $day }}_am_pm3" {{ $am_pm3 == 'pm' ? 'checked' : '' }}>pm
                                            </div>
                                        </font>
                                    </div>
                                </td>
                                @endforeach

                            </tr>
                            @else

                            <tr>
                                <td valign="top" width="100">
                                    <div>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="mon_time1">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="mon_am_pm1">am
                                                <input type="radio" value="pm" name="mon_am_pm1">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="mon_time2">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="mon_am_pm2">am
                                                <input type="radio" value="pm" name="mon_am_pm2">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="mon_time3">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="mon_am_pm3">am
                                                <input type="radio" value="pm" name="mon_am_pm3">pm
                                            </div>
                                        </font>
                                    </div>
                                </td>
                                <td valign="top" width="100">
                                    <div>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="tue_time1">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="tue_am_pm1">am
                                                <input type="radio" value="pm" name="tue_am_pm1">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="tue_time2">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="tue_am_pm2">am
                                                <input type="radio" value="pm" name="tue_am_pm2">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="tue_time3">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="tue_am_pm3">am
                                                <input type="radio" value="pm" name="tue_am_pm3">pm
                                            </div>
                                        </font>
                                    </div>
                                </td>
                                <td valign="top" width="100">
                                    <div>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="wed_time1">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="wed_am_pm1">am
                                                <input type="radio" value="pm" name="wed_am_pm1">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="wed_time2">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="wed_am_pm2">am
                                                <input type="radio" value="pm" name="wed_am_pm2">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="wed_time3">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="wed_am_pm3">am
                                                <input type="radio" value="pm" name="wed_am_pm3">pm
                                            </div>
                                        </font>
                                    </div>
                                </td>
                                <td valign="top" width="100">
                                    <div>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="thu_time1">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="thu_am_pm1">am
                                                <input type="radio" value="pm" name="thu_am_pm1">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="thu_time2">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="thu_am_pm2">am
                                                <input type="radio" value="pm" name="thu_am_pm2">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="thu_time3">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="thu_am_pm3">am
                                                <input type="radio" value="pm" name="thu_am_pm3">pm
                                            </div>
                                        </font>
                                    </div>
                                </td>
                                <td valign="top" width="100">
                                    <div>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="fri_time1">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="fri_am_pm1">am
                                                <input type="radio" value="pm" name="fri_am_pm1">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="fri_time2">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="fri_am_pm2">am
                                                <input type="radio" value="pm" name="fri_am_pm2">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="fri_time3">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="fri_am_pm3">am
                                                <input type="radio" value="pm" name="fri_am_pm3">pm
                                            </div>
                                        </font>
                                    </div>
                                </td>
                                <td valign="top" width="100">
                                    <div>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="sat_time1">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="sat_am_pm1">am
                                                <input type="radio" value="pm" name="sat_am_pm1">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="sat_time2">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="sat_am_pm2">am
                                                <input type="radio" value="pm" name="sat_am_pm2">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="sat_time3">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="sat_am_pm3">am
                                                <input type="radio" value="pm" name="sat_am_pm3">pm
                                            </div>
                                        </font>
                                    </div>
                                </td>
                                <td valign="top" width="100">
                                    <div>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="sun_time1">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="sun_am_pm1">am
                                                <input type="radio" value="pm" name="sun_am_pm1">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="sun_time2">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="sun_am_pm2">am
                                                <input type="radio" value="pm" name="sun_am_pm2">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="sun_time3">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="sun_am_pm3">am
                                                <input type="radio" value="pm" name="sun_am_pm3">pm
                                            </div>
                                        </font>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </table>

                        <!-- </form> -->
                        </td>

                        </table>


                        <div class="termsofservice">
                            <div class="submitBtn" style="padding-top: 20px; ">
                                <button type="submit">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>


                    <h3 style="margin-top: 45px;"><b style="font-weight: 800;">Sample:</b> If a loved one is called at 9
                        AM on Monday, <br> Tuesday, and Friday,
                        and at 10:30 AM and 5:20 PM on <br> Wednesday and Thursday, and at 11 AM
                        on the weekend, <br> then this present form is ideal for complex call times.</h3>
                    <p style="line-height: 24px; font-size: 17px;">Type the hour, followed by the minute you wish the
                        check call to be
                        made.<br>
                        Please use the (:) colon between the hour and the minute
                        selected.</p>
                    <div style="display: flex; justify-content: center; align-items: center; gap: 13px; margin: 28px 0px;">
                        <p style="margin: 0; font-size: 21px; font-weight: 600;">Example:</p>
                        <p style="margin: 0;     border: 2px solid #a19696; background: #fff; padding: 10px 16px;">9:15
                            &nbsp;
                            <input type="radio" value="" checked name="">am &nbsp;
                            <input disabled type="radio" value=checked name="">pm
                        </p>
                    </div>
                    <p>When you signup you can select the number of times to call, up to three
                        (3) <br> per day. &nbsp;<font color="#FF0000" face="Arial">&#9660;</font> If you need to change
                        the
                        times for the standard three calls per <br> day, you may do so by refreshing your browser and
                        select the
                        revised <br> times. If the three times have already been sent for that day, <br>
                        the data base will automatically update for the next day. </p>

                    <h2 style="text-align: center; font-size: 35px;">List your&nbsp; e-mail address and/or text phone
                        <br>
                        number you request any notification to be
                        sent.
                    </h2>
                    <form id="notification_email_and_phone">
                        <!--webbot bot="SaveResults" U-File="C:\clients\waldo\_private\form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" -->
                        <div class="email_number_inputBox">


                            <div class="inputBox" style=" width: 30%;">
                                <input type="number" name="notification_number" required="required">
                                <span>Text Phone Number</span>
                            </div>
                            <div class="inputBox" style=" width: 30%;">
                                <input type="e-mail" name="email_notification" required="required">
                                <span>Use Your E-mail Address</span>
                            </div>
                        </div>
                   
                    <div class="termsofservice">
                        <div class="submitBtn" style="margin-top: 10px;">
                            <button type="submit">
                                Submit
                            </button>
                        </div>
                        <p align="center">
                            <font color="#800000">By clicking 'submit', you provide your consent and
                             agree to receiving notifications from us for whenever the auto-initiated
                              call goes unanswered or is rejected by the loved one.</font>
                        </p>
                    </div>
                 </form>
                    <p>Depending on the volume of requests, the calling time will be closest to the hour and minutes
                        selected.</p>
                    <div align="center">
                        
                            <button class="active_service sendCall_btn">
                            	<input type="hidden" id="send_call" name="send_call" value="send_call">
                            	<img border="0"  id="img18" src="{{url('front-assets/button56.jpg')}}" height="20" width="120" alt="Send Times" fp-title="Send Times">
                            </button>&nbsp;&nbsp;&nbsp;

                           <button class="sendCall_btn stop_call_btn">
                           	<input type="hidden" id="stop_call" name="stop_call" value="stop_call">
                           	 <img border="0" id="img33" src="{{url('front-assets/button33.jpg')}}" height="20" width="116" alt="Stop Calls" fp-style="fp-btn: Embossed Capsule 4; fp-font: Arial; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0" fp-title="Stop Calls">
                           </button>
                        
                        <p>After you send the times requested, a confirmation email&nbsp;
                            and/or <br>
                            text message will be sent to you confirming the times requested.<br>
                            <font color="#FF0000" face="Arial">&#9660;</font> If your Loved One is
                            not going to be home for a period of time,<br>
                            &nbsp;you may not want the phone check call to alert you. When the service <br>
                            is needed to resume, click on the
                            <img border="0" id="img34" src="{{url('front-assets/button56.jpg')}}" height="20" width="120" alt="Send Times" fp-title="Send Times">
                            button again.<br>
                            &nbsp;
                        </p>
                        </p>
                    </div>
                    <p align="center">
                        <a href='{{url("/practice")}}'>
                            <img border="0" id="img_practice" src='{{url("front-assets/button28.jpg")}}' height="20" width="160" alt="Practice Page" onmouseover="FP_swapImg(1,0,/*id*/\'img_practice\',/*url*/url(\'front-assets/button29.jpg\'))" onmouseout="FP_swapImg(0,0,/*id*/\'img_practice\',/*url*/url(\'front-assets/button28.jpg\'))" onmousedown="FP_swapImg(1,0,/*id*/\'img_practice\',/*url*/url(\'front-assets/button2A.jpg\'))" onmouseup="FP_swapImg(0,0,/*id*/\'img_practice\',/*url*/url(\'front-assets/button29.jpg\'))" fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0; fp-orig: 0" fp-title="Practice Page"></a>&nbsp;
                        <a href="{{url('/howitworks')}}">
                            <img border="0" id="img23" src='{{url("front-assets/button28.jpg")}}' height="20" width="160" alt="How it Works" onmouseover="FP_swapImg(1,0,/*id*/\'img23\',/*url*/url(\'front-assets/button29.jpg\'))" onmouseout="FP_swapImg(0,0,/*id*/\'img23\',/*url*/url(\'front-assets/button28.jpg\'))" onmousedown="FP_swapImg(1,0,/*id*/\'img23\',/*url*/url(\'front-assets/button2A.jpg\'))" onmouseup="FP_swapImg(0,0,/*id*/\'img23\',/*url*/url(\'front-assets/button29.jpg\'))" fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0; fp-orig: 0" fp-title="How it Works"></a>
                    </p>
                    <p align="center"><a href="{{url('/instructions')}}">
                            <img border="0" id="img31" src="{{url('front-assets/button3C.jpg')}}" height="20" width="240" alt="Step by Step Instructions" onmouseover="FP_swapImg(1,0,/*id*/'img31',/*url*/url('front-assets/button3D.jpg'))" onmouseout="FP_swapImg(0,0,/*id*/'img31',/*url*/url('front-assets/button3C.jpg'))" onmousedown="FP_swapImg(1,0,/*id*/'img31',/*url*/url('front-assets/button3E.jpg'))" onmouseup="FP_swapImg(0,0,/*id*/'img31',/*url*/url('front-assets/button3D.jpg'))" fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0; fp-orig: 0" fp-title="Step by Step Instructions"></a>
                    </p>
                    <p align="center">
                        &nbsp; &nbsp; <a href="{{url('/termsofservice')}}">
                            <img border="0" id="img32" src="{{url('front-assets/button25.jpg')}}" height="20" width="160" alt="Terms of Service" fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0" fp-title="Terms of Service" onmouseover="FP_swapImg(1,0,/*id*/'img32',/*url*/url('front-assets/button16.jpg'))" onmouseout="FP_swapImg(0,0,/*id*/'img32',/*url*/url('front-assets/button25.jpg'))" onmousedown="FP_swapImg(1,0,/*id*/'img32',/*url*/url('front-assets/button17.jpg'))" onmouseup="FP_swapImg(0,0,/*id*/'img32',/*url*/url('front-assets/button16.jpg'))"></a>&nbsp;
                        <a href="{{url('/competitors')}}">
                            <img border="0" id="img29" src="{{url('front-assets/button1.jpg')}}" height="20" width="170" alt="Competitors" fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0" fp-title="Competitors" onmouseover="FP_swapImg(1,0,/*id*/'img29',/*url*/url('front-assets/button2.jpg'))" onmouseout="FP_swapImg(0,0,/*id*/'img29',/*url*/url('front-assets/button1.jpg'))" onmousedown="FP_swapImg(1,0,/*id*/'img29',/*url*/url('front-assets/button3.jpg'))" onmouseup="FP_swapImg(0,0,/*id*/'img29',/*url*/url('front-assets/button2.jpg'))"></a>
                    </p>
                    <p align="center">
                        <a href="{{url('/contactus')}}">
                            <img border="0" id="img26" src="{{url('front-assets/button7A.jpg')}}" height="20" width="105" alt="Contact Us" onmouseover="FP_swapImg(1,0,/*id*/'img26',/*url*/url('front-assets/button7B.jpg'))" onmouseout="FP_swapImg(0,0,/*id*/'img26',/*url*/url('front-assets/utton7A.jpg'))" onmousedown="FP_swapImg(1,0,/*id*/'img26',/*url*/url('front-assets/button7C.jpg'))" onmouseup="FP_swapImg(0,0,/*id*/'img26',/*url*/url('front-assets/button7B.jpg'))" fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0; fp-orig: 0" fp-title="Contact Us"></a>
                    </p>
                    <p align="center">
                        <a href="{{url('user-signup')}}"><img border="0" id="img30" src="{{url('front-assets/button10.jpg')}}" height="25" width="165" alt="Open Account" onmouseover="FP_swapImg(1,0,/*id*/'img30',/*url*/url('front-assets/button11.jpg'))" onmouseout="FP_swapImg(0,0,/*id*/'img30',/*url*/url('front-assets/button10.jpg'))" onmousedown="FP_swapImg(1,0,/*id*/'img30',/*url*/url('front-assets/button12.jpg'))" onmouseup="FP_swapImg(0,0,/*id*/img30',/*url*/url('front-assets/button11.jpg'))" fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0" fp-title="Open Account"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Already a client?
                        <a href="{{url('user-login')}}"><img border="0" id="img33" src="{{url('front-assets/button36.jpg')}}" height="25" width="140" alt="Log In" onmouseover="FP_swapImg(1,0,/*id*/'img33',/*url*/url('front-assets/button34.jpg'))" onmouseout="FP_swapImg(0,0,/*id*/'img33',/*url*/url('front-assets/button36.jpg'))" onmousedown="FP_swapImg(1,0,/*id*/'img33',/*url*/url('front-assets/button35.jpg'))" onmouseup="FP_swapImg(0,0,/*id*/'img33',/*url*/url('front-assets/button34.jpg'))" fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0" fp-title="Log In">
                            <h5 align="center"></h5>
                        </a><br>
                        &nbsp;
                    <p align="center">
                        <a href="{{url('/')}}">
                            <img border="0" id="img1" src="{{url('front-assets/button30.jpg')}}" height="20" width="100" alt="Back" onmouseover="FP_swapImg(1,0,/*id*/'img1',/*url*/url('front-assets/button31.jpg'))" onmouseout="FP_swapImg(0,0,/*id*/'img1',/*url*/url('front-assets/button30.jpg'))" onmousedown="FP_swapImg(1,0,/*id*/'img1',/*url*/url('front-assets/button32.jpg'))" onmouseup="FP_swapImg(0,0,/*id*/'img1',/*url*/url('front-assets/button31.jpg'))" fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0" fp-title="Back"></a>
                    </p>
                    <p align="center">&nbsp;
                </div>
            </div>
            <p align="center">&nbsp;</p>
            <p align="center">&nbsp;</p>
    </font>
</body>


<!-- Mirrored from gocheckonme.com/instructions.htm by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 10 Apr 2024 04:35:01 GMT -->

</html>
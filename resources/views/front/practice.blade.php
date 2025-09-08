<html>

<head>
    <meta http-equiv="Content-Language" content="en-us">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title>Practice Call Setup - Go Check On Me</title>
    <link rel="stylesheet" href="{{asset('front-assets/style.css')}}">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">

    <style>
        .chosepic {
            padding-left: 20px;
        }
        .practice-notice {
            background-color: #ffffcc;
            border: 2px solid #ffcc00;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
        .practice-notice h3 {
            color: #cc6600;
            margin-top: 0;
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
                if (!/^\\d+$/.test(pastedData)) {
                    e.preventDefault();
                }
            });
        });
        
        // Function to remove spaces from input
        function removeSpaces(input) {,input.value = input.value.replace(/\\s/g, '');
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
        $('form#practice-form').submit(function(e) {
            e.preventDefault();
            
            // Show success message without actually saving
            Swal.fire({
                title: "Practice Complete!",
                text: "This is practice mode. No data has been saved and no calls will be made. You can practice entering times as many times as you like!",
                icon: "success",
                timer: 3000,
                showConfirmButton: true
            });
        });

        // Clear form button
        $('#clear-form').click(function() {
            document.getElementById('practice-form').reset();
            Swal.fire({
                title: "Form Cleared!",
                text: "All fields have been reset for new practice.",
                icon: "info",
                timer: 1500,
                showConfirmButton: false
            });
        });
    });
    </script>
</head>

<body background="{{url('front-assets/parchment.gif')}}" onload="FP_preloadImgs(/*url*/'button31.jpg', /*url*/'button32.jpg', /*url*/'button58.jpg', /*url*/'button57.jpg', /*url*/'button69.jpg', /*url*/'button68.jpg', /*url*/'button2A.jpg', /*url*/'button29.jpg', /*url*/'button3E.jpg', /*url*/'button3D.jpg', /*url*/'button3.jpg', /*url*/'button2.jpg', /*url*/'button12.jpg', /*url*/'button11.jpg', /*url*/'button17.jpg', /*url*/'button16.jpg', /*url*/'button26.jpg', /*url*/'button27.jpg')">

    <font color="#800000" face="Arial">
        <h5 align="center">
            <img border="0" src="{{url('front-assets/gocheck.gif')}}" width="381" height="52">
        </h5>
        <h4 align="center">
            <h2 style="font-size: 40px; text-align: center;">Practice Call Setup</h2>
            
            <div class="practice-notice">
                <h3>🏃\u200d♂️ PRACTICE MODE 🏃\u200d♂️</h3>
                <p><strong>This is a practice page where you can practice entering call times without any limits!</strong></p>
                <p>• No data will be saved<br>
                • No actual calls will be made<br>
                • You can enter as many times as you want<br>
                • Practice as much as you need!</p>
            </div>

            <div align="center">
                <div align="center">
                    <table border="0" width="800" cellspacing="1" cellpadding="5">
                        <tr>
                            <td>
                                <font color="#800000" face="Arial">
                                    <p align="center" style="margin-bottom: 0px;">
                                        This is the practice form for you to get familiar with entering
                                        contact information and call times for your loved one.
                                    </p>
                                    <p align="center" style="margin-top: 0;">
                                        Practice entering different times and phone numbers. When you're ready,
                                        you can go to the actual setup page to save your real information.
                                    </p>
                                </font>
                            </td>
                        </tr>
                    </table>
                </div>
                <h2 style="font-size: 40px; text-align: center;">Practice Contact Form</h2>
                <p align="center" style="max-width: 800px;">
                    Use this form to practice entering your information. Remember, this is just for practice!
                </p>
                <div align="center">
                    <form id="practice-form" enctype="multipart/form-data">
                        @csrf
                        <table border="0" width="900" cellpadding="5" style="margin-top: 50px;" class="contact_time_form_table">
                            <tr>
                                <td width="200">
                                    <font color="#800000">Practice Name:</font>
                                    <input type="text" name="practice_name" placeholder="Enter any name for practice">
                                </td>
                                <td width="200">
                                    <font color="#800000">Practice Email:</font>
                                    <input type="email" name="practice_email" placeholder="Enter any email for practice">
                                </td>
                            </tr>
                            <tr>
                                <td class="inputBox">
                                    <input type="tel" id="phone" class="numbers" name="phone" placeholder="Practice Land Line Number">
                                    <input type="hidden" name="phone_country_code" id="phone_country_code">
                                </td>
                            </tr>
                            <tr>
                                <td class="inputBox">
                                    <input type="tel" id="cell_phone_no" class="numbers" name="cell_phone_no" placeholder="Practice Cell Phone Number">
                                    <input type="hidden" name="cell_phone_country_code" id="cell_phone_no_country_code">
                                </td>
                                <td class="inputBox">
                                    <input type="tel" id="phone_no" class="numbers" name="phone_no" placeholder="Practice Loved One's Phone Number">
                                    <input type="hidden" name="phone_no_country_code" id="phone_no_country_code">
                                </td>
                            </tr>
                            <tr>
                                <td style="display: flex; gap: 28px;">
                                    <font color="#800000" face="Arial">Practice Timezone:</font>
                                    <font color="#800000" face="Arial">
                                        <p align="center" style="margin: 0;">
                                            <input type="radio" value="Pacific/Honolulu" name="timezone">
                                            <font color="#800000">HI</font> &nbsp;
                                            <input type="radio" value="America/Phoenix" name="timezone">AZ &nbsp;
                                            <input type="radio" value="America/Anchorage" name="timezone">AK &nbsp;
                                            <input type="radio" value="America/Los_Angeles" name="timezone">PT &nbsp;
                                            <input type="radio" value="America/Denver" name="timezone">MT &nbsp;
                                            <input type="radio" value="America/Chicago" name="timezone" checked>CT &nbsp;
                                            <input type="radio" value="America/New_York" name="timezone">ET &nbsp;
                                            <input type="radio" value="Asia/Kolkata" name="timezone">IN &nbsp;
                                        </p>
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td class="inputBox">
                                    <input type="file" name="voice_message" style="padding-left: 20%;">
                                    <span>Practice Voice File (Optional)</span>
                                </td>
                            </tr>
                        </table>

                        <p>Practice with different timezone selections above.</p>

                        <h2 style="font-size: 40px; text-align: center; margin-bottom: 0px; margin-top: 48px;">Practice Call Times<br>
                        <small style="font-size: 24px;">(Enter as many as you want!)</small></h2>
                        <p>Practice entering call times - you can fill in every slot if you want!</p>
                        
                        <table border="1" width="800" cellpadding="2" style="border-collapse: collapse;" class="call_three_perday_table">
                            <tr style="background-color: #D4CBAF;">
                                <td><p align="center"><font color="#fff">Mon</font></td>
                                <td><p align="center"><font color="#fff">Tue</font></td>
                                <td><p align="center"><font color="#fff">Wed</font></td>
                                <td><p align="center"><font color="#fff">Thu</font></td>
                                <td><p align="center"><font color="#fff">Fri</font></td>
                                <td><p align="center"><font color="#fff">Sat</font></td>
                                <td><p align="center"><font color="#fff">Sun</font></td>
                            </tr>
                            <tr>
                                @foreach(['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day)
                                <td valign="top" width="100">
                                    <div>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="{{ $day }}_time1" placeholder="Time 1">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="{{ $day }}_am_pm1">am
                                                <input type="radio" value="pm" name="{{ $day }}_am_pm1">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="{{ $day }}_time2" placeholder="Time 2">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="{{ $day }}_am_pm2">am
                                                <input type="radio" value="pm" name="{{ $day }}_am_pm2">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="{{ $day }}_time3" placeholder="Time 3">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="{{ $day }}_am_pm3">am
                                                <input type="radio" value="pm" name="{{ $day }}_am_pm3">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="{{ $day }}_time4" placeholder="Time 4">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="{{ $day }}_am_pm4">am
                                                <input type="radio" value="pm" name="{{ $day }}_am_pm4">pm
                                            </div>
                                        </font>
                                        <font color="#800000" face="Arial">
                                            <input type="text" name="{{ $day }}_time5" placeholder="Time 5">
                                            <div style="margin-top: 4px;">
                                                <input type="radio" value="am" checked name="{{ $day }}_am_pm5">am
                                                <input type="radio" value="pm" name="{{ $day }}_am_pm5">pm
                                            </div>
                                        </font>
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                        </table>

                        <div class="termsofservice">
                            <div class="submitBtn" style="padding-top: 20px; display: flex; gap: 20px; justify-content: center;">
                                <button type="submit">Practice Submit</button>
                                <button type="button" id="clear-form">Clear Form</button>
                            </div>
                        </div>
                    </form>

                    <h3 style="margin-top: 45px;"><b style="font-weight: 800;">Practice Example:</b> Try entering different times like 9:00 AM, 2:30 PM, 6:15 AM, etc. <br>
                    You can enter as many times as you want in each day - this is just for practice!</h3>
                    <p style="line-height: 24px; font-size: 17px;">Type the hour, followed by the minute you wish to practice.<br>
                    Please use the (:) colon between the hour and the minute selected.</p>
                    
                    <div style="display: flex; justify-content: center; align-items: center; gap: 13px; margin: 28px 0px;">
                        <p style="margin: 0; font-size: 21px; font-weight: 600;">Example:</p>
                        <p style="margin: 0; border: 2px solid #a19696; background: #fff; padding: 10px 16px;">9:15 &nbsp;
                            <input type="radio" value="" checked name="">am &nbsp;
                            <input disabled type="radio" value="" name="">pm
                        </p>
                    </div>
                    
                    <p>This practice page allows unlimited entries per day so you can get comfortable with the interface. <br>
                    When you're ready to set up actual calls, use the regular setup page which limits you to three calls per day.</p>

                    <p align="center">
                        <a href="{{url('/instructions')}}">
                            <img border="0" id="img31" src="{{url('front-assets/button3C.jpg')}}" height="20" width="240" alt="Go to Real Setup Page" onmouseover="FP_swapImg(1,0,/*id*/'img31',/*url*/url('front-assets/button3D.jpg'))" onmouseout="FP_swapImg(0,0,/*id*/'img31',/*url*/url('front-assets/button3C.jpg'))" onmousedown="FP_swapImg(1,0,/*id*/'img31',/*url*/url('front-assets/button3E.jpg'))" onmouseup="FP_swapImg(0,0,/*id*/'img31',/*url*/url('front-assets/button3D.jpg'))" fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0; fp-orig: 0" fp-title="Go to Real Setup Page"></a>
                    </p>
                    <p align="center">
                        <a href="{{url('/')}}">
                            <img border="0" id="img1" src="{{url('front-assets/button30.jpg')}}" height="20" width="100" alt="Back" onmouseover="FP_swapImg(1,0,/*id*/'img1',/*url*/url('front-assets/button31.jpg'))" onmouseout="FP_swapImg(0,0,/*id*/'img1',/*url*/url('front-assets/button30.jpg'))" onmousedown="FP_swapImg(1,0,/*id*/'img1',/*url*/url('front-assets/button32.jpg'))" onmouseup="FP_swapImg(0,0,/*id*/'img1',/*url*/url('front-assets/button31.jpg'))" fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0" fp-title="Back"></a>
                    </p>
                </div>
            </div>
        </div>
    </font>
</body>

</html>
<html>

<head>
    <meta http-equiv="Content-Language" content="en-us">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <meta name="viewport" content="width=device-width">



    <meta name="description" content="check on loved ones">
    <meta name="keywords" content="wellfair check, home care, senior care, check on loved ones, caretaker, elder care, phone check on elderly, phone check for sick loved ones, automatic checkin for seniors, reassurance services, senior and disability services, telephone reassurance program, telephone checkin calls for elderly, caregiver, elderly monitoring, older adult services, adult ageing, senior care resources, daily checkin
<meta name=" robots content="index, follow ">
    <meta name="revisit-after" content="7 days">

    <link rel="stylesheet" href="{{asset('front-assets/style.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">



    <title>Welcome to Go Check on Me</title>

    <style>
    .dropbtnn {
        background-color: #04AA6D;
        color: white;
        padding: 10px 0px;
        text-align: center;
        font-size: 16px;
        border: none;
        cursor: pointer;
        width: 100%;
    }

    .dropdownn {
        position: relative;
        display: inline-block;

    }

    .dropdownn-content {
        display: none;
        position: absolute;
        min-width: 160px;
        z-index: 1;
        padding-top: 8px;

    }

    .dropdownn-content a {
        color: black;
        padding: 12px 35px !important;
        text-decoration: none;
        display: block;
    }

    .dropdownn-content a:hover {
        background-color: #ddd;
    }

    .dropdownn:hover .dropdownn-content {
        display: flex;
    }

    .dropdownn:hover .dropbtnn {
        background-color: #3e8e41;
    }

    .login_list {
        justify-content: end !important;
    }

    @media (max-width: 767px) {
       .login_list {
            justify-content: center !important;
        }
    }
    @media (max-width: 991px) {
      body {
        width: max-content;
      }
    }
    </style>
  <script>
    function style_swapImg() { 
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

    function FP_preloadImgs() { //v1.0
        var d = document,
            a = arguments;
        if (!d.FP_imgs) d.FP_imgs = new Array();
        for (var i = 0; i < a.length; i++) {
            d.FP_imgs[i] = new Image;
            d.FP_imgs[i].src = a[i];
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
        if (c) {
            for (n = 0; n < c.length; n++) {
                el = FP_getObjectByID(id, c[n]);
                if (el) return el;
            }
        }
        f = o.forms;
        if (f) {
            for (n = 0; n < f.length; n++) {
                els = f[n].elements;
                for (m = 0; m < els.length; m++) {
                    el = FP_getObjectByID(id, els[n]);
                    if (el) return el;
                }
            }
        }
        return null;
    }
</script>

</head>


<body background="{{url('front-assets/parchment.gif')}}"
    onload="FP_preloadImgs(/*url*/'button2A.jpg', /*url*/'button29.jpg', /*url*/'button3E.jpg', /*url*/'button3D.jpg', /*url*/'button2.jpg', /*url*/'button3.jpg', /*url*/'button11.jpg', /*url*/'button12.jpg', /*url*/'button16.jpg', /*url*/'button17.jpg', /*url*/'button34.jpg', /*url*/'button35.jpg')">

    <nav id="myHeader">
        <!-- <meta name="viewport" content="width=device-width"> -->
        <input type="checkbox" id="nav-toggle">
        <div class="logo">
            <img src="{{url('front-assets/gocheck.gif')}}" width="180" height="35">
        </div>



        <ul class="links login_list ">
            <!-- <li><a href="#home">Home</a></li>
			<li><a href="#about">Services</a></li>
			<li><a href="#work">About Us</a></li>
			<li><a href="#projects">Resources</a></li>
			<li><a href="#contact">Contact</a></li>
			@if(auth()->check())
			<li><a href="{{url('logout')}}">Logout</a></li>
			@else
			<li><a href="{{url('user-signup')}}">Open Account</a></li> -->



            <!-- @endif -->
            @if(auth()->check())
            <li class="dropdownn">
                <a href="#" class="dropbtnn">
                    <i class="bi bi-person-fill"></i>
                    <i class="bi bi-caret-down-fill"></i>
                </a>
                <div class="dropdownn-content">
                    <a href="{{url('logout')}}">Logout</a>

                </div>
            </li>
            @else
            <li class="dropdownn">
                <a href="#" class="dropbtnn">
                    <i class="bi bi-person-fill"></i> <!-- Person fill icon -->
                    <i class="bi bi-caret-down-fill"></i> <!-- Caret down fill icon -->
                </a>
                <div class="dropdownn-content">
                    <a href="{{url('user-login')}}">Log In</a>
                    <!-- Additional dropdown links can be added here -->
                </div>
            </li>
            @endif
        </ul>
        <label for="nav-toggle" class="icon-burger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </label>
    </nav>
    <div class="header_height"></div>
    <h4 align="center">
        <font color="#800000" face="Arial" style="background: linear-gradient(#0B7DCF,#CF0B5E, #92D63B); -webkit-background-clip: text;
                  background-clip: text; -webkit-text-fill-color: transparent; font-size: 24px;
           ">&nbsp;&nbsp;
            <script language="JavaScript">
            <!-- Begin
            datetoday = new Date();
            timenow = datetoday.getTime();
            datetoday.setTime(timenow);
            thehour = datetoday.getHours();
            if (thehour > 18) display = "Evening";
            else if (thehour > 12) display = "Afternoon";
            else display = "Morning";
            var greeting = (" Good " + display + ", Welcome to");
            document.write(greeting);
            //  End 
            -->
            </script>
            </p>

        </font>
        <font color="#800000" face="Arial Unicode MS">®<br>
            Go Check On Me, LLC</font>
        </h5>
        <h4 align="center">
            <font color="#800000" face="Arial">Your one stop check on your <i>Loved Ones</i>. </font>
            <font color="#800000" face="Times New Roman">
                ™</font>
            <div align="center">
                <table border="0" width="700" cellspacing="15">
                    <tr>
                        <td>
                            <font color="#800000" face="Arial">
                                <img border="0" src="{{url('front-assets/waldo.gif')}}" width="176" height="272"><img
                                    border="0" src="{{url('front-assets/robotheart.jpg')}}" width="238"
                                    height="135"><br>
                                Stay in
                                touch with your <i>Loved Ones</i>
                            </font>
                            <font color="#800000" face="Times New Roman">
                                ™</font>
                            <font color="#800000" face="Arial">
                                with Waldo, the automatic Robot with a
                                heart!</font>
                        </td>
                        <td>
                            <p align="center">
                                <font color="#800000" face="Arial">
                                    <br>
                                    <img border="0" src="{{url('front-assets/video/gifvid1.gif')}}" width="261"
                                        height="156"><br>
                                    The next best thing <br>
                                    to being there.
                                </font>
                            </p>
                            <p align="center">
                                <font color="#800000" face="Arial">
                                    <br>
                                </font>
                                <img border="0" src="{{url('front-assets/video/gifvid2.gif')}}" width="261"
                                    height="156">
                                <font color="#800000" face="Arial"><br>
                                    For peace of mind.</font>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
            <div align="center">
                <font color="#800000" face="Arial">


                    <div align="center">
                        &nbsp;<table border="0" width="750" cellpadding="5">
                            <tr>
                                <td>
                                    <font color="#800000" face="Arial">
                                        <p align="left">
                                            Now your
                                            <i>Loved</i>
                                            <img border="0" src="{{url('front-assets/heartr.gif')}}" width="28"
                                                height="23"><i>One
                                            </i>
                                    </font>
                </font>
                <i>
                    <font color="#800000" face="Times New Roman">
                        ™ </font>
                </i>
                <font color="#800000" face="Arial">
                    doesn't have to worry about leaving their home where they have
                    lived for years. They will be checked on, up to three times daily, for
                    their welfare
                    and safety. Just fill out the 'Contact Times Form' after registration, for the times you want
                    your <i>Loved One </i>called. This is an ideal service if your Loved One does
                    not live near you.</td>
                    <td valign="top">
                        &nbsp;</td>
                    </tr>
                    </table>
                    <p align="center">
                        <font color="#800000">Change call times anytime and as often as needed.
                        </font>
                    <div align="center">
                        <table border="0" width="790" bordercolorlight="#800000" cellspacing="7" cellpadding="7">
                            <tr>
                                <td>
                                    <font color="#800000" face="Arial">
                                        <img border="0" src="{{url('front-assets/PricingIcon.jpg')}}" width="44"
                                            height="44">
                                    </font>
                                </td>
                                <td>
                                    <font color="#800000" face="Arial">


                                        &nbsp;<p>$7.47
                                            low cost per month for up to three (3) phone checks per day. <i>Loved
                                                One</i>
                                            <font color="#800000" face="Times New Roman">
                                                <i>™ </i>
                                            </font>must have easy to reach land line or cell phone.
                                        </p>
                                    </font>
                                </td>
                                <td>
                                    <font color="#800000" face="Arial">


                                        <img border="0" src="{{url('front-assets/CallIcon.jpg')}}" width="44"
                                            height="44">
                                    </font>
                                </td>
                                <td>
                                    <font color="#800000" face="Arial">
                                        Your <i>Loved
                                            One</i>
                                        <font color="#800000" face="Times New Roman">
                                            <i>™ </i>
                                        </font>only has to press <u>any</u> button on the
                                        phone pad to acknowledge the check up phone call. No further
                                        action or conversation is required.
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img border="0" src="{{url('front-assets/Messageicon.jpg')}}" width="44"
                                        height="44">
                                </td>
                                <td>
                                    <font color="#800000" face="Arial">


                                        If NO contact is made with your
                                        <i>Loved One</i>
                                        <font color="#800000" face="Times New Roman">
                                            ™</font>, you will be sent an
                                        e-mail and/or a text message immediately stating that there was no contact.
                                    </font>
                                </td>
                                <td>
                                    <img border="0" src="{{url('front-assets/Deviceicon.jpg')}}" width="44" height="44">
                                </td>
                                <td>
                                    <font color="#800000" face="Arial">


                                        <font color="#800000">There are many electronic devices that
                                            your <i>Loved One</i> </font>
                                        <font color="#800000" face="Times New Roman">
                                            <i>™ </i>
                                        </font>
                                        <font color="#800000">can call for help,
                                            assuming they are able to make contact. If they are incapacitated
                                            and cannot call for help, the electronic devices are of little help.
                                            Waldo is a great inexpensive backup for peace of mind.</font>
                                    </font>
                                </td>
                            </tr>
                        </table>
                    </div>
            </div>
            <p align="center">
                <font color="#800000">Cancel anytime for a refund.&nbsp;<br>Contact within 30 days and a full refund
                    will be made.&nbsp;
                </font>
            </p>
            <p align="center"><a href="{{url('/howitworks')}}">
                    <img border="0" id="img23" src="{{url('front-assets/button28.jpg')}}" height="20" width="160"
                        alt="How it Works"
                        onmouseover="FP_swapImg(1,0,/*id*/'img23',/*url*/url('front-assets/button29.jpg'))"
                        onmouseout="FP_swapImg(0,0,/*id*/'img23',/*url*/url('front-assets/button28.jpg'))"
                        onmousedown="FP_swapImg(1,0,/*id*/'img23',/*url*/url('front-assets/button2A.jpg'))"
                        onmouseup="FP_swapImg(0,0,/*id*/'img23',/*url*/url('front-assets/button29.jpg'))"
                        fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0; fp-orig: 0"
                        fp-title="How it Works"></a>&nbsp;
                <a href="{{url('/instructions')}}">
                    <img border="0" id="img31" src="{{url('front-assets/button3C.jpg')}}" height="20" width="240"
                        alt="Step by Step Instructions"
                        onmouseover="FP_swapImg(1,0,/*id*/'img31',/*url*/url('front-assets/button3D.jpg'))"
                        onmouseout="FP_swapImg(0,0,/*id*/'img31',/*url*/url('front-assets/button3C.jpg'))"
                        onmousedown="FP_swapImg(1,0,/*id*/'img31',/*url*/url('front-assets/button3E.jpg'))"
                        onmouseup="FP_swapImg(0,0,/*id*/'img31',/*url*/url('front-assets/button3D.jpg'))"
                        fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0; fp-orig: 0"
                        fp-title="Step by Step Instructions"></a>
            </p>
            <p align="center">&nbsp; <a href="{{url('/termsofservice')}}">
                    <img border="0" id="img32" src="{{url('front-assets/button25.jpg')}}" height="20" width="160"
                        alt="Terms of Service"
                        fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0"
                        fp-title="Terms of Service"
                        onmouseover="FP_swapImg(1,0,/*id*/'img32',/*url*/url('front-assets/button16.jpg'))"
                        onmouseout="FP_swapImg(0,0,/*id*/'img32',/*url*/url('front-assets/button25.jpg'))"
                        onmousedown="FP_swapImg(1,0,/*id*/'img32',/*url*/url('front-assets/button17.jpg'))"
                        onmouseup="FP_swapImg(0,0,/*id*/'img32',/*url*/url('front-assets/button16.jpg'))"></a>&nbsp;
                <a href="{{url('/competitors')}}">
                    <img border="0" id="img29" src="{{url('front-assets/button1.jpg')}}" height="20" width="170"
                        alt="Competitors"
                        fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0"
                        fp-title="Competitors"
                        onmouseover="FP_swapImg(1,0,/*id*/'img29',/*url*/url('front-assets/button2.jpg'))"
                        onmouseout="FP_swapImg(0,0,/*id*/'img29',/*url*/url('front-assets/button1.jpg'))"
                        onmousedown="FP_swapImg(1,0,/*id*/'img29',/*url*/url('front-assets/button3.jpg'))"
                        onmouseup="FP_swapImg(0,0,/*id*/'img29',/*url*/url('front-assets/button2.jpg'))"></a>
            </p>
            <p align="center">&nbsp;</p>
            <p align="center">
                <a href="{{url('user-signup')}}"><img border="0" id="img30" src="{{url('front-assets/button10.jpg')}}"
                        height="25" width="165" alt="Open Account"
                        onmouseover="FP_swapImg(1,0,/*id*/'img30',/*url*/url('front-assets/button11.jpg'))"
                        onmouseout="FP_swapImg(0,0,/*id*/'img30',/*url*/url('front-assets/button10.jpg'))"
                        onmousedown="FP_swapImg(1,0,/*id*/'img30',/*url*/url('front-assets/button12.jpg'))"
                        onmouseup="FP_swapImg(0,0,/*id*/'img30',/*url*/url('front-assets/button11.jpg'))"
                        fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0"
                        fp-title="Open Account"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Already a client?
                <a href="{{url('user-login')}}"><img border="0" id="img33" src="{{url('front-assets/button36.jpg')}}"
                        height="25" width="140" alt="Log In"
                        onmouseover="FP_swapImg(1,0,/*id*/'img33',/*url*/url('front-assets/button34.jpg'))"
                        onmouseout="FP_swapImg(0,0,/*id*/'img33',/*url*/url('front-assets/button36.jpg'))"
                        onmousedown="FP_swapImg(1,0,/*id*/'img33',/*url*/url('front-assets/button35.jpg'))"
                        onmouseup="FP_swapImg(0,0,/*id*/'img33',/*url*/url('front-assets/button34.jpg'))"
                        fp-style="fp-btn: Embossed Capsule 4; fp-font-style: Bold Italic; fp-font-color-hover: #800000; fp-proportional: 0"
                        fp-title="Log In">
                    <h5 align="center"></h5>
                </a><br>&nbsp;
                <img border="0" src="{{url('front-assets/waldosstaff.jpg')}}" width="468" height="258"><br>Waldo's
                staff, hard at work using the
                <br>latest Cloud encrypted technology.</h5>
            <p align="center">Powered by SanguineIT technologies.<br>
                <a href="#">Login</a>
            </p>
            <p align="center">&nbsp;</p>
            <p align="center">&nbsp;</p>
            <p align="center">&nbsp;</div>
            <p align="center">&nbsp;</p>
            <p align="center">&nbsp;</p>
            </font>
            <p align="left">*
                <script type="text/javascript" src="http://counter.websiteout.net/js/19/6/1155/0"></script>
            </p>


            <script>
            window.onscroll = function() {
                scrollFunction()
            };

            function scrollFunction() {
                var header = document.getElementById("myHeader");
                if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                    header.classList.add("addSticky");
                } else {
                    header.classList.remove("addSticky");
                }
            }
            </script>

</body>

</html>
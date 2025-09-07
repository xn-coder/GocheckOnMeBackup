<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->

<aside class="left-sidebar">
<input type="hidden" id ="base_url" value="{{env('BASE_URL')}}">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
        <div class="user-profile position-relative" style="background: url({{ asset('assets/admin/images/background/user-info.jpg') }}) no-repeat;">
            <!-- User profile image -->
            <div class="profile-img">

               @if (session()->has('image'))
                <img src="{{ \App\Models\User::where('id', session()->get('id'))->pluck('image')[0] }}" alt="1" class="w-100" />
            @else
                <img src="{{ asset('assets/admin/images/users/1.jpg') }}" alt="2" class="w-100" />
            @endif

            </div>

            <!-- User profile text-->
            <div class="profile-text pt-1 dropdown">
                <a href="#" class="dropdown-toggle u-dropdown w-100 text-white d-block position-relative" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">{{\App\Models\User::where('id',session()->get('id'))->pluck('name')[0]}}</a>
                <div class="dropdown-menu animated flipInY" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="{{ url('/my_profile') }}"><i data-feather="user" class="feather-sm text-info me-1 ms-1"></i> My Profile</a>
                    <div class="dropdown-divider"></div>
                    <a style="display:none" class="dropdown-item" href="{{ url('admin/signout') }}"><i data-feather="log-out" class="feather-sm text-danger me-1 ms-1"></i> Logout</a>
                    <div class="dropdown-divider"></div>
                    <div class="pl-4 p-2"><a href="{{ url('/admin/signout') }}" class="btn d-block w-100 btn-info rounded-pill">Logout</a></div>
                </div>
            </div>
        </div>
        <!-- End User profile text-->
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link @if (Request()->segment(1)=='dashboard') active @endif" href="{{ url('/admin/dashboard') }}" aria-expanded="false">
                        <i class="mdi mdi-gauge"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                
                <li class="sidebar-item" style="">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link @if (Request()->segment(1)=='user_list') active  @elseif (Request()->segment(1)=='user_list')  aclass   @endif" href="{{ url('/admin/users-list') }}" aria-expanded="false">
                        <i class="mdi mdi-account"></i>
                        <span class="hide-menu">User Management</span>
                    </a>
                </li>

                <li class="sidebar-item" style="">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link @if (Request()->segment(1)=='lovedones_list') active  @elseif (Request()->segment(1)=='lovedones_list')  aclass   @endif" href="{{ url('/admin/lovedones_list') }}" aria-expanded="false">
                        <i class="mdi mdi-account"></i>
                        <span class="hide-menu">Loved ones contacts</span>
                    </a>
                </li>

                <li class="sidebar-item" style="">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link @if (Request()->segment(1)=='subscription_list') active  @elseif (Request()->segment(1)=='subscription_list')  aclass   @endif" href="{{ url('/admin/subscription_list') }}" aria-expanded="false">
                        <i class="mdi mdi-account"></i>
                        <span class="hide-menu">Subscription management</span>
                    </a>
                </li>

              

                <li class="sidebar-item" style="display:none;">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-format-list-bulleted"></i>
                        <span class="hide-menu">CMS Pages</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ url('/about_us') }}" class="sidebar-link">
                                <i class="mdi mdi-account-group "></i>
                                <i class="fa fa-solid fa-users d-block"></i>
                                <span class="hide-menu">About Us</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('/faq') }}" class="sidebar-link">
                                <i class="fa fa-question d-block"></i>
                                <span class="hide-menu">FAQ</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('/privacy_policy') }}" class="sidebar-link">
                                <i class="fa fa fa-lock d-block"></i>
                                <span class="hide-menu">Privacy Policy</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ url('/terms') }}" class="sidebar-link">
                                <i class="mdi mdi-adjust d-block"></i>
                                <span class="hide-menu">Terms & Conditions</span>
                            </a>
                        </li>

                    </ul>
                </li>


                <!-- ========================================================================== -->
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
    <!-- Bottom points-->
    <div class="sidebar-footer">
        <!-- item--><!--
        <a href="" class="link" data-bs-toggle="tooltip" data-bs-placement="top" title="Settings"><i class="ti-settings"></i></a>
        <a href="" class="link" data-bs-toggle="tooltip" data-bs-placement="top" title="Email"><i class="mdi mdi-gmail"></i></a>-->
        <a href="{{ url('/admin/signout') }}" class="link" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout"><i class="mdi mdi-power"></i></a>
    </div>
    <!-- End Bottom points-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<!-- ============================================================== -->

<style>
.sidebar-nav ul .sidebar-item .sidebar-link.active {
    color: #607d8b !important;
    opacity: 1;
    font-weight: normal;
}
.sidebar-nav ul .sidebar-item .sidebar-link.active.aclass {
    color: #000 !important;
    font-weight: 500 !important;
}
</style>
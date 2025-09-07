<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Register</title>
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/admin/images/favicon.png">
    <link href="{{ asset('/assets/admin/dist/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/admin/dist/css/custom.css') }}" rel="stylesheet">
    <script src="{{ asset('/assets/admin/libs/jquery/dist/jquery.min.js') }}"></script>
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="spinner-border text-muted"></div>
        </div>

        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center"
             style="background:url(/assets/admin/images/background/login-register.jpg) no-repeat center center; background-size: cover;">

            <div class="auth-box p-4 bg-white rounded">
                <div class="logo_box_login mb-4" style="text-align: center;">
                    <img style="width: 100%;object-fit:contain" src="{{asset('assets/admin/images/gocheck.gif')}}" alt="homepage" class="light-logo"/>
                </div>

                <div id="registerform" style="display:block;">
                    <div class="logo">
                        <h3 class="box-title mb-3" style="text-align: center;">Admin Register</h3>
                    </div>

                    @if (session()->has('msg'))
                        <div class="alert alert-success">
                            <ul><li>{!! session('msg') !!}</li></ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <form method="POST" class="form-horizontal mt-3 form-material"
                                  action="{{ route('admin.register') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <input type="text" placeholder="Full Name" class="form-control" name="name" required>
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <input type="email" placeholder="Email" class="form-control" name="email" required>
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="password" placeholder="Password" id="password" class="form-control" name="password" required>
                                        <span class="icon-right fa input_icon fa-eye-slash" id="togglePassword" data-name="password"></span>
                                    </div>
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="password" placeholder="Confirm Password" id="password_confirmation" class="form-control" name="password_confirmation" required>
                                        <span class="icon-right fa input_icon fa-eye-slash" id="togglePasswordConfirm" data-name="password_confirmation"></span>
                                    </div>
                                </div>

                                <div class="form-group text-center mt-4 mb-3">
                                    <button class="btn btn-info d-block w-100 waves-effect waves-light" type="submit">Register</button>
                                </div>

                                <div class="form-group mb-0 mt-4">
                                    <div class="col-sm-12 justify-content-center d-flex">
                                        <p>Already have an account?
                                            <a href="{{ url('admin/login') }}" class="text-info font-weight-medium ms-1">Login</a>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/admin/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(".preloader").fadeOut();

        $(".input_icon").on("click", function () {
            let input = $("input[name='" + $(this).data("name") + "']");
            let type = input.attr("type");
            if (type === 'password') {
                input.attr("type", 'text');
                $(this).removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                input.attr("type", 'password');
                $(this).addClass('fa-eye-slash').removeClass('fa-eye');
            }
        });
    </script>
</body>

<style>
    .input_icon {
        right: 0px;
        top: 10px;
        position: absolute;
        cursor: pointer;
        transform: translate(-50%, -0%);
        z-index: 999;
    }
</style>

</html>

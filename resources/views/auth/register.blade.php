<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/title-meta', ['title' => 'Log In'])
    @include('layouts.shared/head-css')
    @vite(['resources/scss/icons.scss'])
</head>

<body class="loading auth-fluid-pages pb-0">

    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
        <div class="auth-fluid-form-box" style="max-width: 420px; width: 100%;">
            <div class="card w-100 shadow-lg">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="auth-brand text-center text-lg-start mb-4">
                        <a href="" class="logo logo-dark text-center">
                            <span class="logo-lg">
                                <img src="{{ asset('uploads/logo/' . gs()->logo) }}" alt="" height="22">
                            </span>
                        </a>

                        <a href="" class="logo logo-light text-center">
                            <span class="logo-lg">
                                <img src="{{ asset('uploads/logo/' . gs()->logo) }}" alt="" height="22">
                            </span>
                        </a>
                    </div>

                    <!-- title-->
                    <h4 class="mt-0">Sign Up</h4>
                    <p class="text-muted mb-4">Please provide your valid information.We will send your login credentials to your registered mobile number.</p>

                    <!-- form -->
                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                       <div class="mb-3">
                            <label for="firstNameInput" class="form-label">First name</label>
                            <input class="form-control" type="text" id="firstNameInput" value="{{ old('name') }}"
                                name="name" required placeholder="@lang('First name')">
                        </div> 
                        
                       <div class="mb-3">
                            <label for="lastNmaeInput" class="form-label">Last Name</label>
                            <input class="form-control" type="text" id="lastNmaeInput" value="{{ old('lastNmae') }}"
                                name="lastNmae" required placeholder="@lang('Last Name')">
                        </div> 

                        <div class="mb-3">
                            <label for="emailInput" class="form-label">Email</label>
                            <input class="form-control" type="email" id="emailInput" value="{{ old('email') }}"
                                name="email" required placeholder="@lang('Email')">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mobile</label>
                            <input class="form-control" type="text" id="mobileInput" value="{{ old('mobile') }}"
                                name="mobile" required placeholder="@lang('Mobile')">
                        </div>  

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input class="form-control" type="text" id="address" value="{{ old('address') }}"
                                name="address" required placeholder="@lang('Address')"> 
                        </div>

                        <input type="hidden" name="roles" value="Demo Admin">
                        <input type="hidden" name="password" value="123456">


                        {{--<div class="mb-3">
                            <a href="#" class="text-muted float-end"><small>Forgot your password?</small></a>
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" required placeholder="Password">
                                <div class="input-group-text" onclick="togglePassword()">
                                    <i class="fa fa-eye" id="toggleIcon"></i>
                                </div>
                            </div>
                        </div>--}}


                        <div class="text-center d-grid">
                            <button class="btn btn-primary" type="submit">Sign Up</button>
                        </div>

                    </form>

                    <!-- Footer-->
                     <br>
                    <div>
                        <p class="text-muted">Already have an account? <a href="{{ route('login.form') }}" class="text-muted ms-1"><b>Sign
                                    In</b></a></p>
                    </div>
                </div> <!-- end .card-body -->
            </div> <!-- end .card -->
        </div> <!-- end auth-fluid-form-box -->

        <!-- Auth fluid right content -->
        {{--<div class="auth-fluid-right text-center">
            <div class="auth-user-testimonial">
                <h2 class="mb-3 text-white">I love the color!</h2>
                <p class="lead"><i class="mdi mdi-format-quote-open"></i> I've been using your theme from the
                    previous
                    developer for our web app, once I knew new version is out, I immediately bought with no hesitation.
                    Great themes, good documentation with lots of customization available and sample app that really fit
                    our need. <i class="mdi mdi-format-quote-close"></i>
                </p>
                <h5 class="text-white">
                    - Bangladesh Software Development (bsd)
                </h5>
            </div> <!-- end auth-user-testimonial-->
        </div>--}}

    </div> <!-- end page wrapper -->

    @vite('resources/js/pages/auth.js')

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const toggleIcon = document.getElementById("toggleIcon");
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            toggleIcon.classList.toggle("fa-eye");
            toggleIcon.classList.toggle("fa-eye-slash");
        }
    </script>

</body>
</html>

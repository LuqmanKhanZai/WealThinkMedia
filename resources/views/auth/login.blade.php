<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="{{ asset('admin_file/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin_file/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{ asset('admin_file/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('admin_file/css/style.css') }}" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="{{ asset('admin_file/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <!-- Toastr style -->
    <link href="{{ asset('admin_file/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">

    <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-messaging.js"></script>


    <style>
        .table-condensed td {
            padding: 1px !important;
        }

        .table-condensed td,
        tr,
        th {
            border: none !important;
        }

        .table-condensed th {
            font-size: 12px !important;
            vertical-align: middle !important;
        }

        .table-condensed span {
            font-size: 13px !important;
        }

        .for-text {
            font-size: 12px !important;
        }

        .table-condensed-bordered td {
            padding: 1px !important;
        }

        .panel-heading .text {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            padding: 5px;
        }
    </style>

</head>

<body class="gray-bg">
    @include('sweetalert::alert')
    <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                {{-- @if ($systemIp) --}}
                    {{-- Login Form --}}
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="text">Login To Your Account</div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="profile-image text-right">
                                        <img src="{{ asset('admin_file/img/login_logo.png') }}" class="img-circle circle-border m-b-md" alt="profile">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <small>Walthink</small>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-8">
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf

                                        <table class="table table-condensed">
                                            <tr>
                                                <th>Username</th>
                                                <td>
                                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-Mail Address">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Password</th>
                                                <td>
                                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="*********" required autocomplete="current-password">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <br>

                                        </table>
                                        <div class="row">
                                            <div class="col-md-4 col-md-offset-8">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary full-width m-b">{{ __('Login') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <small>© Walthink</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Login Form --}}
                {{-- @else --}}
                    {{-- Request Form --}}
                    {{-- <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="text">Financial & Quality Management System</div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="profile-image text-right">
                                        <img src="{{ asset('admin_file/img/login_logo.png') }}" class="img-circle circle-border m-b-md" alt="profile">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <small>ERP (Software) Solution Providers</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <table class="table table-condensed">
                                        <tr>
                                            <th>Terminal Code : </th>
                                            <td>{{ $code ? $code->code : '0709709' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Your Name : </th>
                                            <td><input type="text" class="form-control name" name="name"></td>
                                        </tr>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <td><button type="submit" class="btn btn-primary btn-block" onclick="SendRequest()">{{ __('Send Request') }}</button></td>
                                        </tr>
                                        <tr>
                                            <td>Verification Code: </td>
                                            <td><input type="text" class="form-control code" name="code"></td>
                                        </tr>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <td><button type="submit" class="btn btn-primary btn-block" onclick="Verify()">{{ __('Verify') }}</button></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <small>© Soflixx (Software) Solution Provider</small>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    {{-- Request Form --}}
                {{-- @endif --}}
            </div>
        </div>
        <hr />
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('admin_file/js/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('admin_file/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('admin_file/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

<script>
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyBcDuhrSqlNPMx9brWwk4deOlbnkN33chQ",
        authDomain: "pitzza-express.firebaseapp.com",
        projectId: "pitzza-express",
        storageBucket: "pitzza-express.appspot.com",
        messagingSenderId: "734072995072",
        appId: "1:734072995072:web:7c9daeecfa0732e90107e5",
        measurementId: "G-T9VN2K62YL"
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);

    // Retrieve Firebase Messaging object
    const messaging = firebase.messaging();

    // Request permission to receive notifications
    messaging.requestPermission()
        .then(function() {
            console.log('Notification permission granted.');
            // Get the token
            return messaging.getToken();
        })
        .then(function(token) {
            console.log('FCM Token:', token);
        })
        .catch(function(err) {
            console.log('Unable to get permission to notify.', err);
        });

    function SendRequest() {
        var name = $('.name').val();
        $.ajax({
            url: '/login_request_send',
            method: 'POST',
            data: {
                name: name,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status == 200) {
                    toastr.success(response.msg);
                }
                if (response.status == 201) {
                    toastr.error(response.msgErr);
                }
            },
            error: function(xhr) {
                // If the response has a status of 422, it's a validation error
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    // Display each validation error using Toastr
                    Object.values(errors).forEach(function(error) {
                        toastr.error(error[0]);
                    });
                } else {
                    toastr.error("An error occurred during the request.");
                }
            }
        })
    }

    function Verify() {
        var code = $('.code').val();
        $.ajax({
            url: '/login_request_verify',
            method: 'POST',
            data: {
                code: code,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status == 200) {
                    toastr.success(response.msg);
                    window.location.href = '/login';
                }
                if (response.status == 201) {
                    toastr.error(response.msgErr);
                }
            },
            error: function(xhr) {
                // If the response has a status of 422, it's a validation error
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    // Display each validation error using Toastr
                    Object.values(errors).forEach(function(error) {
                        toastr.error(error[0]);
                    });
                } else {
                    toastr.error("An error occurred during the request.");
                }
            }
        })
    }
</script>

@if (Session::has('success'))
    <script>
        $(document).ready(function() {
            toastr.success("{!! Session::get('success') !!}");
        });
    </script>
@endif
@if (Session::has('error'))
    <script>
        $(document).ready(function() {
            toastr.error("{!! Session::get('error') !!}")
        });
    </script>
@endif

</html>

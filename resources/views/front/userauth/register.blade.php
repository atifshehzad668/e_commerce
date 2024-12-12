@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item">Register</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">

        <div class="container">
            <div class="login-form">
                <form action="" method="post" name="registrationForm" id="registrationForm">
                    <h4 class="modal-title">Register Now</h4>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                        <p class="error"></p>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email" id="email" name="email">
                        <p class="error"></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone">
                        <p class="error"></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Password" id="password" name="password">
                        <p class="error"></p>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Confirm Password"
                            id="password_confirmation" name="password_confirmation">
                        <p class="error"></p>
                    </div>
                    <div class="form-group small">
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
                </form>
                <div class="text-center small">Already have an account? <a href="{{ route('account.login') }}">Login Now</a>
                </div>
            </div>
        </div>
    </section>
@endsection



@section('customJs')
    <script type="text/javascript">
        $("#registrationForm").submit(function() {
            event.preventDefault();
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: '{{ route('account.process.register') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type='submit']").prop('disabled', false);
                    if (response['status'] == true) {
                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'], input[type='email'], input[type='password'], select")
                            .removeClass('is-invalid');

                        window.location.href = "{{ route('account.login') }}";
                    } else {
                        var errors = response['errors'];
                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'], input[type='email'], input[type='password'], select")
                            .removeClass('is-invalid');

                        $.each(errors, function(key, value) {
                            $('#' + key).addClass('is-invalid').siblings('p').html(value);
                        });
                    }

                },
                error: function(jQXHR, exception) {
                    console.log("Something Went Wrong");
                }
            });
        });
    </script>
@endsection

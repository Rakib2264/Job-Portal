@extends('frontend.layouts.master')

@section('content')
<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Register</h1>
                    <form action="" name="registationForm" id="registationForm">
                        <div class="mb-3">
                            <label for="" class="mb-2">Name*</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                            <p></p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="mb-2">Email*</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email">
                            <p></p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="mb-2">Password*</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                            <p></p>
                        </div>
                        <div class="mb-3">
                            <label for="" class="mb-2">Confirm Password*</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Enter Confirm Password">
                            <p></p>
                        </div>
                        <button class="btn btn-primary mt-2">Register</button>
                    </form>
                </div>
                <div class="mt-4 text-center">
                    <p>Have an account? <a  href="{{route('processLogin')}}">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')

    <script>
    $("#registationForm").submit(function(e){
    e.preventDefault(); // Prevent the default form submission

    $.ajax({
        url: '{{ route("processRegistation") }}', // URL for the AJAX request
        type: 'post', // HTTP method (POST)
        data: $('#registationForm').serializeArray(), // Form data to be sent to the server
        dataType: 'json', // Expected data type of the response
        success: function(res){
            if (res.status == false) {
            var errors = res.errors;
            if (errors.name) {
                $("#name").addClass('is_invalid').siblings('p').addClass('invalid-feedback').html(errors.name).show();
            } else {
                $("#name").removeClass('is_invalid').siblings('p').removeClass('invalid-feedback').html('').show();
            }

            if (errors.email) {
                $("#email").addClass('is_invalid').siblings('p').addClass('invalid-feedback').html(errors.email).show();
            } else {
                $("#email").removeClass('is_invalid').siblings('p').removeClass('invalid-feedback').html('').show();
            }


            if (errors.password) {
                $("#password").addClass('is_invalid').siblings('p').addClass('invalid-feedback').html(errors.password).show();
            } else {
                $("#password").removeClass('is_invalid').siblings('p').removeClass('invalid-feedback').html('').show();
            }

            if (errors.confirm_password) {
                $("#confirm_password").addClass('is_invalid').siblings('p').addClass('invalid-feedback').html(errors.confirm_password).show();
            } else {
                $("#confirm_password").removeClass('is_invalid').siblings('p').removeClass('invalid-feedback').html('').show();
            }
    }else{

                $("#name").addClass('is_invalid').siblings('p').addClass('invalid-feedback').html('').show();
                $("#email").addClass('is_invalid').siblings('p').addClass('invalid-feedback').html('').show();
                $("#password").addClass('is_invalid').siblings('p').addClass('invalid-feedback').html('').show();
                $("#confirm_password").addClass('is_invalid').siblings('p').addClass('invalid-feedback').html('').show();

                window.location.href='{{ route('processLogin') }}'



    }

        }
    });
});

    </script>

@endsection

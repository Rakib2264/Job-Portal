@extends('frontend.layouts.master')
@section('content')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Account Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('frontend.account.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="card border-0 shadow mb-4">
                        <form action="" method="post" id="userForm" name="userForm">
                            <div class="card-body  p-4">
                                <h3 class="fs-4 mb-1">My Profile</h3>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Name*</label>
                                    <input type="text" name="name" id="name" value="{{ $user->name }}"
                                        placeholder="Enter Name" class="form-control">
                                    <span class="text-danger spn-name"></span>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Email*</label>
                                    <input type="text" name="email" id="email" value="{{ $user->email }}"
                                        placeholder="Enter Email" class="form-control">
                                    <span class="text-danger spn-email"></span>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Designation</label>
                                    <input type="text" name="designation" value="{{ $user->designation }}"
                                        placeholder="Designation" class="form-control">
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Mobile</label>
                                    <input type="text" name="mobile" value="{{ $user->mobile }}" placeholder="Mobile"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>

                    <div class="card border-0 shadow mb-4">
                        <form action="" method="post" id="changePasswordForm">
                            <div class="card-body p-4">
                                <h3 class="fs-4 mb-1">Change Password</h3>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Old Password*</label>
                                    <input type="password" name="old_password" id="old_password" placeholder="Old Password"
                                        class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">New Password*</label>
                                    <input type="password" name="new_password" id="new_password" placeholder="New Password"
                                        class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Confirm Password*</label>
                                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password"
                                        class="form-control">
                                    <p></p>
                                </div>
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script type="text/javascript">
        $("#userForm").submit(function(e) {
            e.preventDefault()

            $.ajax({
                url: '{{ route('updateProfile') }}',
                type: 'put', //user informaction update that why put
                dataType: 'json',
                data: $('#userForm').serializeArray(),
                success: function(res) {

                    if (res.status == 'faild') {
                        $('.spn-name').text(res.errors.name);
                        $('.spn-email').text(res.errors.email);

                    } else {
                        window.location.href = '{{ route('profile') }}'
                    }
                }
            });

        });


        $("#changePasswordForm").submit(function(e) {
            e.preventDefault()

            $.ajax({
                url: '{{ route('updatePassword') }}',
                type: 'post', //user informaction update that why put
                dataType: 'json',
                data: $('#changePasswordForm').serializeArray(),
                success: function(res) {
                    if (res.status == false) {
                        var errors = res.errors;
                        if (errors.old_password) {
                            $("#old_password").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.old_password);
                        } else {
                            $("#old_password").removeClass('is-invalid').siblings('p')
                                .removeClass(
                                    'invalid-feedback').html('');
                        }

                        if (errors.new_password) {
                            $("#new_password").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.new_password);
                        } else {
                            $("#new_password").removeClass('is-invalid').siblings('p')
                                .removeClass(
                                    'invalid-feedback').html('');
                        }

                        if (errors.confirm_password) {
                            $("#confirm_password").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.confirm_password);
                        } else {
                            $("#confirm_password").removeClass('is-invalid').siblings('p')
                                .removeClass(
                                    'invalid-feedback').html('');
                        }

                    } else {
                        window.location.href = '{{ route('profile') }}'
                    }
                }
            });

        });
    </script>
@endsection

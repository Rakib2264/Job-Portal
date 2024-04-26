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
                    @include('admin.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body card-form">
                            <form action="" method="post" id="userEditForm" name="userEditForm">
                                <div class="card-body  p-4">
                                    <h3 class="fs-4 mb-1">User Edit</h3>
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
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
@section('customJs')
  <script>
     $("#userEditForm").submit(function(e) {
            e.preventDefault()
            $.ajax({
                url: '{{ route('update',$user->id) }}',
                type: 'post',
                dataType: 'json',
                data: $('#userEditForm').serializeArray(),
                success: function(res) {

                    if (res.status == 'faild') {
                        $('.spn-name').text(res.errors.name);
                        $('.spn-email').text(res.errors.email);

                    } else {
                        window.location.href = '{{ route('users') }}'
                    }
                }
            });

        });
  </script>
@endsection

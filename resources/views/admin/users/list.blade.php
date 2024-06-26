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
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fs-4 mb-1">Users</h3>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Mobile</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        @forelse ($users as $user)
                                            <tr class="active">


                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->mobile }}</td>
                                                <td>
                                                    <div class="action-dots float-end">
                                                        <button href="#" class="" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">

                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('edit', $user->id) }}"><i
                                                                        class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    onclick="deleteUser({{ $user->id }})"><i
                                                                        class="fa fa-trash" aria-hidden="true"></i>
                                                                    Remove</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">No users found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script>
        function deleteUser(id) {
            if (confirm('Are You Sure You Want To Delete This User ?')) {
                $.ajax({
                    url: '{{route('delete')}}',
                    type: 'delete',
                    dataType: 'json',
                    data: {id:id},
                    success: function(res) {
                        window.location.href = "{{ route('users') }}";

                    },

                });

            }
        }
    </script>
@endsection

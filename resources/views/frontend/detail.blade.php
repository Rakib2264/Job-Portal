@extends('frontend.layouts.master')
@section('content')
    <section class="section-4 bg-2">
        <div class="container pt-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left"
                                        aria-hidden="true"></i> &nbsp;Back to Jobs</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container job_details_area">
            <div class="row pb-5">
                <div class="col-md-8">
                    <div class="card shadow border-0">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">

                                    <div class="jobs_conetent">
                                        <a href="#">
                                            <h4>{{ $job->title }}</h4>
                                        </a>
                                        <div class="links_locat d-flex align-items-center">
                                            <div class="location">
                                                <p> <i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                            </div>
                                            <div class="location">
                                                <p> <i class="fa fa-clock-o"></i> {{ $job->jobType->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jobs_right">

                                    <div class="apply_now {{($count==1)?'save-job':''}}">
                                        <a class="heart_mark" href="javascript:void(0)" onclick="saveJobwish({{ $job->id }})" > <i class="fa fa-heart-o"
                                                aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            <div class="single_wrap">
                                <h4>Job description</h4>
                                <p>{!! nl2br($job->description) !!}</p>
                            </div>
                            <div class="single_wrap">
                                @if (!empty($job->responsibility))
                                    <h4>Responsibility</h4>
                                    <ul>
                                        <li>{!! nl2br($job->responsibility) !!}</li>

                                    </ul>
                                @endif

                            </div>
                            <div class="single_wrap">
                                @if (!empty($job->responsibility))
                                    <h4>Qualifications</h4>
                                    <ul>
                                        <li>{!! nl2br($job->qualifications) !!}</li>

                                    </ul>
                                @endif

                            </div>
                            <div class="single_wrap">
                                @if (!empty($job->benefits))
                                    <h4>Benefits</h4>
                                    <p>{!! nl2br($job->benefits) !!}</p>
                                @endif
                            </div>
                            <div class="border-bottom"></div>
                            <div class="pt-3 text-end">

                                @if (Auth::check())
                                    <a href="#" onclick="saveJobwish({{ $job->id }})"
                                        class="btn btn-secondary">Save</a>
                                @else
                                    <a href="#" class="btn btn-secondary disabled">Login To Save</a>
                                @endif

                                @if (Auth::check())
                                    <a href="#" onclick="applyJob({{ $job->id }})"
                                        class="btn btn-success">Apply</a>
                                @else
                                    <a href="#" class="btn btn-success disabled">Login To Apply</a>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Job Summery</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Published on:
                                        <span>{{ \Carbon\Carbon::parse($job->created_at)->format('d, M Y') }}</span>
                                    </li>
                                    <li>Vacancy: <span>{{ $job->vacancy }}</span></li>
                                    <li>Salary: <span>{{ $job->salary }}</span></li>
                                    <li>Location: <span>{{ $job->location }}</span></li>
                                    <li>Job Nature: <span> {{ $job->jobType->name }} </span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow border-0 my-4">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Company Details</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Name: <span>{{ $job->company_name }}</span></li>
                                    <li>Locaion: <span>{{ $job->company_location }}</span></li>
                                    <li>Webite: <span><a
                                                href="{{ $job->company_website }}">{{ $job->company_website }}</a></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script type="text/javascript">
        // apply jobs
        function applyJob(id) {

            if (confirm("Are You Sure You Want Apply This Job?")) {
                $.ajax({
                    url: '{{ route('applyJob') }}',
                    type: 'post',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function() {
                        window.location.href = "{{ url()->current() }}";
                    }
                });
            }

        }
        // end

        //saveJobwish
        function saveJobwish(id) {
            // Send an AJAX request to the server to save the job
            $.ajax({
                url: '{{ route('saveJobwish') }}',
                type: 'POST',
                data: {
                    id: id,
                },
                dataType: 'json',
                success: function(response) {
                    // If the request is successful, redirect to the current page
                    if (response.status == true) {
                        window.location.href = "{{ url()->current() }}";
                    }
                    if (response.status == false) {
                        window.location.href = "{{ url()->current() }}";
                    }
                },

            });
        }

        // end
    </script>
@endsection

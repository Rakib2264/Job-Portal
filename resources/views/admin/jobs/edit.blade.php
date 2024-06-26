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
                                    <h3 class="fs-4 mb-1">Edit Job</h3>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Created By</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                            <form action="" method="post" id="updateJobFormm">
                                <div class="card border-0 shadow mb-4 ">
                                    <div class="card-body card-form p-4">
                                        <h3 class="fs-4 mb-1">Edit Job Details</h3>
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <label for="" class="mb-2">Title<span class="req">*</span></label>
                                                <input type="text" value="{{ $job->title }}" placeholder="Job Title"
                                                    id="title" name="title" class="form-control">
                                                <small class=" text-danger spn-title"></small>
                                            </div>
                                            <div class="col-md-6  mb-4">
                                                <label for="" class="mb-2">Category<span class="req">*</span></label>
                                                <select name="category" id="category" class="form-select">
                                                    <option selected disabled>Select a Category</option>
                                                    @if ($category->isNotEmpty())
                                                        @foreach ($category as $category)
                                                            <option {{ $job->category_id == $category->id ? 'selected' : '' }}
                                                                value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <small class=" text-danger spn-category"></small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <label for="" class="mb-2">Job Type<span class="req">*</span></label>
                                                <select name="jobType" id="jobType" class="form-select">
                                                    <option selected disabled>Select Job Nature</option>

                                                    @if ($jobtype->isNotEmpty())
                                                        @foreach ($jobtype as $jobtype)
                                                            <option {{ $job->job_type_id == $jobtype->id ? 'selected' : '' }}
                                                                value="{{ $jobtype->id }}">{{ $jobtype->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <small class=" text-danger spn-jobType"></small>
                                            </div>
                                            <div class="col-md-6  mb-4">
                                                <label for="" class="mb-2">Vacancy<span class="req">*</span></label>
                                                <input value="{{ $job->vacancy }}" type="number" min="1"
                                                    placeholder="Vacancy" id="vacancy" name="vacancy" class="form-control">
                                                <small class=" text-danger spn-vacancy"></small>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="mb-4 col-md-6">
                                                <label for="" class="mb-2">Salary</label>
                                                <input type="text" value="{{ $job->salary }}" placeholder="Salary"
                                                    id="salary" name="salary" class="form-control">

                                            </div>

                                            <div class="mb-4 col-md-6">
                                                <label for="" class="mb-2">Location<span class="req">*</span></label>
                                                <input type="text" value="{{ $job->location }}" placeholder="location"
                                                    id="location" name="Location" class="form-control">
                                                <small class=" text-danger spn-Location"></small>

                                            </div>

                                        </div>

                                        <div class="mb-4">
                                            <label for="" class="mb-2">Description<span class="req">*</span></label>
                                            <textarea class="trumbowyg-demo" name="description" id="description" cols="5" rows="5"
                                                placeholder="Description">{{ $job->description }}</textarea>
                                        </div>
                                        <small class=" text-danger spn-description"></small>

                                        <div class="mb-4">
                                            <label for="" class="mb-2">Benefits</label>
                                            <textarea class="trumbowyg-demo" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits">{{ $job->benefits }}</textarea>
                                        </div>
                                        <div class="mb-4">
                                            <label for="" class="mb-2">Responsibility</label>
                                            <textarea class="trumbowyg-demo" name="responsibility" id="responsibility" cols="5" rows="5"
                                                placeholder="Responsibility">{{ $job->responsibility }}</textarea>
                                        </div>
                                        <div class="mb-4">
                                            <label for="" class="mb-2">Qualifications</label>
                                            <textarea class="trumbowyg-demo" name="qualifications" id="qualifications" cols="5" rows="5"
                                                placeholder="Qualifications">{{ $job->qualifications }}</textarea>
                                        </div>

                                        <div class="mb-4">
                                            <label for="" class="mb-2">Expreience</label>
                                            <select name="experience" id="experience" class="form-select">
                                                <option selected disabled>Select Expreiences</option>
                                                <option value="0" {{ $job->experience == 0 ? 'selected' : '' }}>O Year
                                                </option>
                                                <option value="1" {{ $job->experience == 1 ? 'selected' : '' }}>1 Year
                                                </option>
                                                <option value="2" {{ $job->experience == 2 ? 'selected' : '' }}>2 Years
                                                </option>
                                                <option value="3" {{ $job->experience == 3 ? 'selected' : '' }}>3 Years
                                                </option>
                                                <option value="4" {{ $job->experience == 4 ? 'selected' : '' }}>4 Years
                                                </option>
                                                <option value="5" {{ $job->experience == 5 ? 'selected' : '' }}>5 Years
                                                </option>
                                                <option value="6" {{ $job->experience == 6 ? 'selected' : '' }}>6 Years
                                                </option>
                                                <option value="7" {{ $job->experience == 7 ? 'selected' : '' }}>7 Years
                                                </option>
                                                <option value="8" {{ $job->experience == 8 ? 'selected' : '' }}>8 Years
                                                </option>
                                                <option value="9" {{ $job->experience == 9 ? 'selected' : '' }}>9 Years
                                                </option>
                                                <option value="10_plus" {{ $job->experience == '10_plus' ? 'selected' : '' }}>10+
                                                    Years</option>

                                            </select>
                                        </div>

                                        <div class="mb-4">
                                            <label for="" class="mb-2">Keywords<span class="req">*</span></label>
                                            <input type="text" value="{{ $job->Keywords }}" placeholder="keywords"
                                                id="keywords" name="keywords" class="form-control">
                                        </div>

                                        <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                                        <div class="row">
                                            <div class="mb-4 col-md-6">
                                                <label for="" class="mb-2">Name<span class="req">*</span></label>
                                                <input type="text" value="{{ $job->company_name }}"
                                                    placeholder="Company Name" id="company_name" name="company_name"
                                                    class="form-control">
                                            </div>
                                            <small class=" text-danger spn-company_name"></small>


                                            <div class="mb-4 col-md-6">
                                                <label for="" class="mb-2">Location</label>
                                                <input type="text" value="{{ $job->company_location }}"
                                                    placeholder="Location" id="company_location" name="company_location"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-4 col-md-4">
                                                <div class="form-check">
                                                    <input {{($job->isfeatured == 1) ? 'checked':''}} class="form-check-input" type="checkbox" value="1" id="is_Featured" name="is_Featured">
                                                    <label class="form-check-label" for="is_Featured">
                                                        Featured
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="mb-4 col-md-8">
                                                <div class="form-check-inline">
                                                    <input {{($job->status == 1) ? 'checked':''}} class="form-check-input" type="checkbox" value="1" id="status-active" name="status">
                                                    <label class="form-check-label" for="status-active">
                                                        Active
                                                    </label>
                                                </div>
                                                <div class="form-check-inline">
                                                    <input {{($job->status == 0) ? 'checked':''}} class="form-check-input" type="checkbox" value="0" id="status-inactive" name="status">
                                                    <label class="form-check-label" for="status-inactive">
                                                        Blocked
                                                    </label>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="mb-4">
                                            <label for="" class="mb-2">Website</label>
                                            <input type="text" value="{{ $job->company_website }}"
                                                placeholder="company website" id="company_website" name="company_website"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="card-footer  p-4">
                                        <button type="submit" class="btn btn-primary">Update Job</button>
                                    </div>
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
   $("#updateJobFormm").submit(function(e) {
            e.preventDefault()
            // console.log($('#createJobForm').serializeArray());
            // return false;
            $.ajax({
                url: '{{ route('job.update', $job->id) }}',
                type: 'put', //user informaction update that why put
                dataType: 'json',
                data: $('#updateJobFormm').serializeArray(),
                success: function(res) {

                    if (res.status == 'faild') {
                        $('.spn-title').text(res.errors.title);
                        $('.spn-category').text(res.errors.category);
                        $('.spn-jobType').text(res.errors.jobType);
                        $('.spn-vacancy').text(res.errors.vacancy);
                        $('.spn-Location').text(res.errors.Location);
                        $('.spn-description').text(res.errors.description);
                        $('.spn-company_name').text(res.errors.company_name);
                    } else {
                        window.location.href = "{{ route('job.list') }}"
                    }


                }
            });

        });



    </script>
@endsection

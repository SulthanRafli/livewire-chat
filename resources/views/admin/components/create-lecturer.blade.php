@extends('admin.main')

@section('container')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Form Lecturer</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('lecturer') }}">List Lecturer</a></li>
                            <li class="breadcrumb-item active">Add Lecturer</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <form action="{{ route('lecturer.save') }}" method="POST">
                                <div class="card-body">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputName">Name</label>
                                        <input type="text" name="name" class="form-control" id="inputName"
                                            placeholder="Enter Name">
                                        @error('name')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDepartment">Department</label>
                                        <select class="form-control select2bs4" name="departments_id" id="inputDepartment"
                                            data-placeholder="Choose Department" style="width: 100%;">
                                            <option value="">Choose Department</option>
                                            @foreach ($department as $d)
                                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('department')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail">Email</label>
                                        <input type="email" name="email" class="form-control" id="inputEmail"
                                            placeholder="Enter Email">
                                        @error('email')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword">Password</label>
                                        <input type="password" name="password" class="form-control" id="inputPassword"
                                            placeholder="Enter Password">
                                        @error('password')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <a type="button" class="btn btn-danger" href="{{ route('lecturer') }}">Back</a>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

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
                            <li class="breadcrumb-item active">Edit Lecturer</li>
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
                            <form action="{{ route('lecturer.update', ['id' => $lecturer->id]) }}" method="POST">
                                <div class="card-body">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputName">Name</label>
                                        <input type="text" name="name" class="form-control" id="inputName"
                                            placeholder="Enter Name" value="{{ $lecturer->name }}">
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
                                                <option value="{{ $d->id }}"
                                                    {{ old('departments_id', $lecturer->departments_id) == $d->id ? 'selected' : '' }}>
                                                    {{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('department')
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

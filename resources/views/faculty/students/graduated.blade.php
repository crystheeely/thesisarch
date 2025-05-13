@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Graduated Students</h1>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Students</h5>
                    <h3>{{ $totalStudents }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Graduated Students</h5>
                    <h3>{{ $graduates }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Completed Theses</h5>
                    <h3>{{ $completedTheses }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5>Pending Theses</h5>
                    <h3>{{ $pendingTheses }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">List of Graduated Students</div>
        <div class="card-body">
            @if($students->count())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Graduation Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ ucfirst($student->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No graduated students found.</p>
            @endif
        </div>
    </div>
</div>
@endsection

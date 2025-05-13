@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Theses Dashboard</h1>
        
        <!-- Display a list of theses -->
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($theses as $thesis)
                    <tr>
                        <td>{{ $thesis->title }}</td>
                        <td>{{ $thesis->full_name }}</td>
                        <td>{{ $thesis->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination links -->
        {{ $theses->links() }}
    </div>
@endsection
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Roles
                        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">+</a>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Full access</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>

                            @foreach($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->full_access }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>
                                        <a href="{{ route('roles.edit', ['role' => $item->id]) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="{{ route('roles.show', ['role' => $item->id]) }}" class="btn btn-sm btn-success">Show</a>
                                        <form action="{{ route('roles.destroy', ['role' => $item->id]) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" onclick="return confirm('Do you really want to delete a record?');" class="btn btn-sm btn-danger" value="Delete">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>

                    <div class="card-footer">
                        {{ $list->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
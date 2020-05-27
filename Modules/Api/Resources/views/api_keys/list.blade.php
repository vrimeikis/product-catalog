@extends('administration::layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        App keys
                        @if (canAccess('api_keys.create'))
                            <a href="{{ route('api_keys.create') }}" class="btn btn-sm btn-primary">+</a>
                        @endif
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                            @foreach($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td class="@if ($item->active) text-success @else text-danger @endif">
                                        {{ $item->active ? 'Yes' : 'No'   }}
                                    </td>
                                    <td>
                                        @if(canAccess('api_keys.edit'))
                                            <a href="{{ route('api_keys.edit', ['api_key' => $item->id]) }}"
                                               class="btn btn-sm btn-primary">Edit</a>
                                        @endif
                                        @if (canAccess('api_keys.show'))
                                            <a href="{{ route('api_keys.show', ['api_key' => $item->id]) }}"
                                               class="btn btn-sm btn-success">Show</a>
                                        @endif
                                        @if (canAccess('api_keys.destroy'))
                                            <form action="{{ route('api_keys.destroy', ['api_key' => $item->id]) }}"
                                                  method="post">
                                                @csrf
                                                @method('delete')
                                                <input type="submit"
                                                       onclick="return confirm('Do you really want to delete a record?');"
                                                       class="btn btn-sm btn-danger" value="Delete">
                                            </form>
                                        @endif
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
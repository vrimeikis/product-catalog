@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        View role
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Key') }}</th>
                                <th>{{ __('Value') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ __('Name') }}</td>
                                <td>{{ $item->name }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Full access') }}</td>
                                <td>{{ $item->full_access }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Accessible routes') }}</td>
                                <td>
                                    @foreach($item->accessible_routes as $value)
                                        {{ $value }} <br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Description') }}</td>
                                <td>{{ $item->description }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Created') }}</td>
                                <td> {{ $item->created_at }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Last update') }}</td>
                                <td>{{ $item->updated_at }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('roles.edit', ['role' => $item->id]) }}" class="btn btn-sm btn-primary">Edit</a>
                        <a href="{{ route('roles.index') }}" class="btn btn-sm btn-dark">Back to list</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
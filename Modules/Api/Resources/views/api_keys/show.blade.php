@extends('administration::layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        View app key
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
                                <td>{{ __('Title') }}</td>
                                <td>{{ $item->title }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('App key') }}</td>
                                <td>{{ $item->app_key }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Active') }}</td>
                                <td> {{ $item->active ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Last update') }}</td>
                                <td>{{ $item->updated_at }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        @if (canAccess('api_keys.edit'))
                            <a href="{{ route('api_keys.edit', ['api_key' => $item->id]) }}"
                               class="btn btn-sm btn-primary">Edit</a>
                        @endif
                        @if (canAccess('api_keys.index'))
                            <a href="{{ route('api_keys.index') }}" class="btn btn-sm btn-dark">Back to list</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
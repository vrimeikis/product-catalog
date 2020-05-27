@extends('administration::layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @isset($item->id)
                            Edit
                        @else
                            New
                        @endisset
                        app key
                    </div>
                    <form action="{{ route('api_keys.'.(isset($item->id) ? 'update' : 'store'), (isset($item->id) ? ['api_key' => $item->id] : [])) }}" method="post">
                        @csrf
                        @isset($item->id)
                            @method('put')
                        @endisset

                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input class="form-control @error('title') is-invalid @enderror" type="text" name="title"
                                       id="title"
                                       value="{{ old('title', $item->title ?? '') }}">
                                @error('title')
                                <div class="alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="app_key">App key</label>
                                <input class="form-control @error('app_key') is-invalid @enderror" type="text"
                                       name="app_key" id="app_key" disabled
                                       value="{{ old('email', $item->app_key ?? '') }}">
                                @error('app_key')
                                <div class="alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="active">Active</label>
                                <input class="@error('active') is-invalid @enderror" type="checkbox" name="active" id="active"
                                       value="1" @if (old('active', $item->active ?? false)) checked @endif>
                                @error('active')
                                <div class="alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer">
                            <input type="submit" class="btn btn-success" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
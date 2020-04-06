@extends('layouts.app')

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
                        role
                    </div>

                    <form action="{{ route('roles.' . (isset($item->id) ? 'update' : 'store'), isset($item->id) ? ['role' => $item->id] : []) }}" method="post">
                        @csrf
                        @isset($item->id)
                            @method('put')
                        @endisset

                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name"
                                       value="{{ old('name', $item->name ?? '') }}">
                                @error('name')
                                <div class="alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="full_access">Full access</label>
                                <input class="@error('full_access') is-invalid @enderror" type="checkbox" name="full_access" id="full_access"
                                       value="1" @if (old('full_access', $item->full_access ?? false)) checked @endif>
                                @error('full_access')
                                <div class="alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          name="description" id="description"
                                >{{ old('description', $item->description ?? '') }}</textarea>
                                @error('description')
                                <div class="alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

{{--                            <div class="form-group">--}}
{{--                                <label for="categories">Categories</label>--}}
{{--                                @foreach($categories as $category)--}}
{{--                                    <input type="checkbox" id="categories" name="categories[]" value="{{ $category->id }}"--}}
{{--                                           @if(in_array($category->id, old('categories', $categoryIds ?? []))) checked @endif--}}
{{--                                    > {{ $category->title }}--}}
{{--                                @endforeach--}}
{{--                            </div>--}}


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

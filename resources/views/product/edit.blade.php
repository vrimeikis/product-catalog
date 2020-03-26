@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit Product</div>
                    <form action="{{ route('products.update', ['product' => $product->id]) }}" method="post">
                        @csrf
                        @method('put')

                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" id="title"
                                       value="{{ old('title', $product->title) }}">
                                @error('title')
                                <div class="alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                                          id="description">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                <div class="alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input class="form-control @error('price') is-invalid @enderror" type="number" name="price" id="price"
                                       value="{{ old('price', $product->price) }}" min="0.01" step="0.01">
                                @error('price')
                                <div class="alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="categories">Categories</label>
                                @foreach($categories as $category)
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                           @if(in_array($category->id, old('categories', $categoryIds))) checked @endif
                                    > {{ $category->title }}
                                @endforeach
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

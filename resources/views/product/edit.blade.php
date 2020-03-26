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
                                <input class="form-control" type="text" name="title" id="title"
                                       value="{{ $product->title }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description"
                                          id="description">{{ $product->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input class="form-control" type="number" name="price" id="price"
                                       value="{{ $product->price }}" min="0.01" step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="categories">Categories</label>
                                @foreach($categories as $category)
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                           @if(in_array($category->id, $categoryIds)) checked @endif
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

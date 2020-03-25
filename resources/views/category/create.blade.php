@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">New category</div>
                    <form action="{{ route('categories.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input class="form-control" type="text" name="title" id="title" value="">
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

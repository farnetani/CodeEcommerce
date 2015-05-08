@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Categorias</div>

                    <div class="panel-body">
                        <a href="{{ route('categories.create') }}" class="btn btn-default">New Category</a>
                        <br>
                        <br>
                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{$category->id}}</td>
                                <td>{{$category->name}}</td>
                                <td>{{$category->description}}</td>
                                <td>
                                    <a href="{{ route('categories.edit', ['id'=>$category->id]) }}">Edit</a> |
                                    <a href="{{ route('categories.destroy', ['id'=>$category->id]) }}">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>
        {!! $categories->render() !!}
    </div>
@endsection


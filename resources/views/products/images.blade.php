@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Imagens do Produto: {{ $product->name }}</div>

                    <div class="panel-body">
                        <a href="{{ route('products.images.create', ['id'=>$product->id])}}" class="btn btn-default">New Image</a>
                        <br>
                        <br>
                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Extension</th>
                                <th>Action</th>
                            </tr>
                            @foreach($product->images as $image)
                            <tr>
                                <td>{{$image->id}}</td>
                                <td>
                                    <img src="{{ url('uploads/'.$image->id.'.'.$image->extension) }}" width="40">
                                </td>
                                <td>{{$image->extension}}</td>
                                <td>
                                    <a href="{{ route('products.images.destroy', ['id'=>$image->id]) }}">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        <a href="{{ route('products') }}" class="btn btn-default">Voltar</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
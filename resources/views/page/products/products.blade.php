@extends('layout')
@section('title', 'Список товаров')
@section('caption', 'Список товаров')
@section('content')
    <div class="list-group mt-4">
        @foreach($products as $product)
            <div class="list-group-item mb-2">
                <h2 class="fs-4">{{$product->name}}</h2>
                <span class="mt-2">Цена: {{$product->price}} руб.</span>
                <div class="mt-2">
                    <a href="{{route('products_story', ['id' => $product->id])}}" class="link">История движения</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@extends('layout')
@section('title', "Склад: $storage->name")
@section('caption', "Склад: $storage->name")
@section('content')
    @if($storage->products->isNotEmpty())
        <div class="list-group mt-4">
            @foreach($storage->products as $product)
                <div class="list-group-item mb-2">
                    <h2 class="fs-4">{{$product->name}}</h2>
                    <div class="mt-2">
                        <span>Кол-во на складе: {{$product->pivot->count}}</span>
                        <a href="{{route('products_story', ['id' => $product->id])}}" class="link ms-2">История движения</a>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <p>Товаров на складе нет...</p>
    @endif
@endsection

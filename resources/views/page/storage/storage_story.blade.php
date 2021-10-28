@extends('layout')
@section('title', "История для склада: $storage->name")
@section('caption', "История для склада: $storage->name")
@section('content')
    @forelse($stories as $story)
        <div class="list-group mt-4">
            <div class="list-group-item">
                @if($story['is_from'])
                    <h2 class="fs-4">
                        Убыло из &laquo;{{$storage->name}}&raquo; в &laquo;{{$story['to']}}&raquo; <span class="text-danger">(-)</span>
                    </h2>
                @else
                    <h2 class="fs-4">
                        Прибыло из &laquo;{{$story['to']}}&raquo; в &laquo;{{$storage->name}}&raquo; <span class="text-success">(+)</span>
                    </h2>
                @endif
                <div class="mt-2">
                    <span class="d-block">Дата: {{$story['date']}}</span>
                </div>
                @foreach($story['products'] as $product)
                    <div class="mt-2">
                        <div class="fs-5 fw-bold">{{$product->name}}</div>
                        @if(!$product->pivot->is_deleted && !$product->pivot->is_created)
                            @if($product->pivot->is_decreased)
                                <span class="d-block text-danger">Кол-во уменьшилось на {{$product->pivot->count}}.</span>
                                <span class="d-block">Осталось на складе: {{$product->pivot->remainder}}.</span>
                            @endif
                            @if($product->pivot->is_increased)
                                <span class="d-block text-success">Кол-во увеличилось на {{$product->pivot->count}}.</span>
                                <span class="d-block">Кол-во на складе: {{$product->pivot->remainder}}.</span>
                            @endif
                            @elseif($product->pivot->is_deleted && !$product->pivot->is_created)
                                <span class="d-block">Полностью перемещён.</span>
                            @elseif(!$product->pivot->is_deleted && $product->pivot->is_created)
                                <span class="d-block">Новый товар добавлен на склад.</span>
                                <span class="d-block">Кол-во: {{$product->pivot->remainder}}.</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @empty
        <p>История не найдена</p>
    @endforelse
@endsection

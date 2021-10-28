@extends('layout')
@section('title', "История: $product->name")
@section('caption', "История: $product->name")
@section('content')
    @forelse($stories as $story)
        <div class="list-group mt-4">
            <div class="list-group-item">
                @if($story['is_from'] && $story['to'])
                    <h2 class="fs-4">Убыл из &laquo;{{$story['storage']}}&raquo; в &laquo;{{$story['to']}}&raquo;</h2>
                @elseif(!$story['is_from'] && $story['to'])
                    <h2 class="fs-4">Прибыл из &laquo;{{$story['to']}}&raquo; в &laquo;{{$story['storage']}}&raquo;</h2>
                @else
                    @if(!$story['is_from'])
                        <h2 class="fs-4">Зачисление</h2>
                        @else
                        <h2 class="fs-4">Списание</h2>
                    @endif
                @endif
                <div class="mt-2">
                    <span class="d-block">Дата: {{$story['date']}}</span>
                </div>
                <div class="mt-2">
                    @if(!$story['is_deleted'] && !$story['is_created'])
                        @if($story['is_decreased'])
                            @if($story['to'] !== null)
                                <span class="d-block text-danger">Кол-во уменьшилось на {{$story['count']}}.</span>
                                <span class="d-block">Осталось на складе: {{$story['remainder']}}.</span>
                                @else
                                <span class="d-block text-danger">Кол-во списано: {{$story['count']}}.</span>
                                <span class="d-block">Осталось на складе: {{$story['remainder']}}.</span>
                            @endif
                        @endif
                        @if($story['is_increased'])
                            <span class="d-block text-success">Кол-во увеличилось на {{$story['count']}}.</span>
                            <span class="d-block">Кол-во на складе: {{$story['remainder']}}.</span>
                        @endif
                        @elseif($story['is_deleted'] && !$story['is_created'])
                            @if($story['to'] !== null)
                                <span class="d-block">Полностью перемещён.</span>
                                <span class="d-block">Кол-во перемещено: {{$story['count']}}.</span>
                                @else
                                <span class="d-block">Полностью списан.</span>
                                <span class="d-block">Кол-во списано: {{$story['remainder']}}.</span>
                            @endif
                        @elseif(!$story['is_deleted'] && $story['is_created'])
                            <span class="d-block">Добавлен на склад.</span>
                            <span class="d-block">Кол-во: {{$story['remainder']}}.</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <p>История не найдена</p>
    @endforelse
@endsection

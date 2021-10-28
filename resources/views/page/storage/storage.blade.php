@extends('layout')
@section('title', 'Список складов')
@section('caption', 'Список складов')
@section('content')
    <div class="list-group mt-4">
        @foreach ($storages as $storage)
            <div class="list-group-item mb-2">
                <h2 class="fs-4">{{$storage->name}}</h2>
                <div class="mt-2">
                    <a href="{{route('storage_product', ['id' => $storage->id])}}" class="link">Товары склада</a>
                    <a href="{{route('storage_story', ['id' => $storage->id])}}" class="link ms-3">История движения</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection

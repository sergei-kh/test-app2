@extends('layout')
@section('title', 'Управление наличием')
@section('caption', 'Управление наличием')
@section('content')
    <div class="btn-group mt-4">
        <button type="button" class="btn btn-outline-primary" id="add_to_storage">Добавить/списать со склада</button>
        <button type="button" class="btn btn-outline-primary">Перенести между складами</button>
    </div>
    <vue-modal id="modal_add_to_storage" trigger-id="add_to_storage">
        <template slot-scope="{signal}">
            <vue-form-add-storage :signal="signal"></vue-form-add-storage>
        </template>
    </vue-modal>
@endsection

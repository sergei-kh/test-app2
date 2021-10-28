<nav class="list-group">
    <a href="{{route('products')}}"
       class="list-group-item list-group-item-action @if(Route::is('products')) active @endif">
        Товары
    </a>
    <a href="{{route('storage')}}"
       class="list-group-item list-group-item-action @if(Route::is('storage')) active @endif">
        Склады
    </a>
    <a href="{{route('stock_manager')}}"
       class="list-group-item list-group-item-action @if(Route::is('stock_manager')) active @endif">
        Управление наличием
    </a>
</nav>

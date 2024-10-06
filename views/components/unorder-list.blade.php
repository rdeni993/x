{{-- 

    Unorder List
    ============

    This component will create a simple
    unorder list that will be related to
    passed model

--}}
<ul class="list-group">
    @isset($items)
        @if (sizeof($items))
            @foreach ($items as $item)
                <li class="list-group-item">
                    {{ $item->$key }}
                </li>
            @endforeach
        @else
            <p>
                <small>{{ __("Nema rezultata!") }}</small>
            </p>
        @endif
    @endisset
</ul>
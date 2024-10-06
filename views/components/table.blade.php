{{-- 

    Table
    =====

    Table is a class that will create full control
    table using livewire

--}}
<div>
    <div class="table-responsive">
        <table class="{{ $classes }}">
            <thead>
                <tr>
                    @isset($keys)
                        @foreach ($keys as $key)
                            <th>{{ ucwords($key) }}</th>            
                        @endforeach
                    @endisset
                </tr>
            </thead>
            <tbody>
                @isset($items)
                    @if (sizeof($items))
                        @foreach ($items as $item)
                            <tr>
                                @isset($keys)
                                    @foreach ($keys as $key)
                                        <td>{{ $item->$key }}</td>          
                                    @endforeach
                                @endisset
                            </tr>
                        @endforeach
                    @endif
                @endisset
            </tbody>
        </table>
    </div>
    <div>
        {{ $items->links() }}
    </div>
</div>
<div class="x-form-input">
    @switch($type)
        @case("select")
                <select name="{{ $name }}"
                    class="form-select"
                    name="{{ $name }}" 
                    livewire="{{ $livewireModel }}"
                >
                @isset($options)
                    @if (sizeof($options))
                        @foreach ($options as $option)
                            <option value="{{ $option['value'] }}" @selected($option['selected'] ?? false)>{{ $option['key'] }}</option>
                        @endforeach
                    @endif
                @endisset
            </select>
            @break
        @case("checkbox")
            <div class="form-check form-switch">
                <input type="checkbox" 
                    name="{{ $name }}" 
                    role="switch" 
                    class="form-check-input" 
                    value="{{ $value }}" 
                    livewire="{{ $livewireModel }}"
                />
                <label class="form-check-label">{{ $label ?? 'Check' }}</label>
            </div>
            @break
        @case ("radio")
            <div class="form-check">
                <input type="radio" 
                    name="{{ $name }}" 
                    class="form-check-input" 
                    value="{{ $value }}" 
                    livewire="{{ $livewireModel }}"
                />
                <label class="form-check-label">{{ $label ?? 'Check' }}</label>
            </div>
            @break
        @case("textarea")
            <textarea
                class="form-control"
                placeholder="{{ $placeholder }}" 
                name="{{ $name }}" 
                livewire="{{ $livewireModel }}"
                rows="{{ $rows }}"
                cols="{{ $cols }}"
            >{{ $value ?? null }}</textarea>
            @break
        @default
            <input type="{{ $type }}" 
                class="form-control"
                placeholder="{{ $placeholder }}" 
                name="{{ $name }}" 
                livewire="{{ $livewireModel }}"
                value="{{ $value ?? null }}"
            />
    @endswitch
    @error($name)    
        <div class="text-danger">
            <small>{{ $message }}</small>
        </div>
    @enderror
</div>
<?php 

namespace X\X\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public function __construct(
        public string $type = "text",
        public string $placeholder = "",
        public string $name = "",
        public string $livewireModel = "",
        public string $value = "",
        public int $rows = 10,
        public int $cols = 10,
        public string $label = "",
        public array $options = []
    )
    {
        
    }

    public function render() : View|Closure|string
    {
        return view("x::components.input");
    }
}
<?php 

namespace X\X\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use X\X\Traits\LoadModel;

class UnorderList extends Component
{
    use LoadModel;

    /**
     * @var Model
     */
    private Model $model;

    /**
     * @var Collection
     */
    public Collection $items;

    /**
     * @var string
     */
    public string $key;

    public function __construct(string $target, string $key)
    {
        // Create model instance
        $this->model = new ($this->modelName($target));

        // Load items
        $this->items = $this->model::all();

        // Set Key
        $this->key = $key;
    }

    public function render() : View|Closure|string
    {
        return view("x::components.unorder-list");
    }
}
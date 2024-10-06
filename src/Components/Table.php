<?php 

namespace X\X\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;
use X\X\Traits\LoadModel;

class Table extends Component
{
    use LoadModel;

    /**
     * @var Model
     */
    private Model $model;

    /**
     * @var Collection
     */
    public LengthAwarePaginator $items;

    /**
     * @var string
     */
    public string $classes;

    /**
     * @var array
     */
    public array $keys;

    public function __construct(string $target, array $keys, int $paginate = 10, string $classes = "table table-striped")
    {
        // Instantiate models
        $this->model = new ($this->modelName($target));

        // Set items
        $this->items = $this->model::paginate($paginate);

        // Set keys
        $this->keys = $keys;

        // Set classes
        $this->classes = $classes;
    }

    public function render() : View|Closure|string
    {
        return("x::components.table");
    }
}
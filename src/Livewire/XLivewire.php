<?php

/**
 * 
 * Let Create Livewire template for
 * handling basic crud operation with
 * livewire object. This must handle a
 * 
 * save
 * update
 * fetch
 * delete
 * 
 * So basically every time we extend this
 * component we have this operation done.
 * 
 */

namespace X\X\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use X\X\Exceptions\ModelNotFoundException;
use X\X\Traits\LoadEvent;
use X\X\Traits\LoadModel;
use X\X\Traits\LoadPolicy;

class XLivewire extends Component
{
    use LoadModel;
    use LoadPolicy;
    use WithPagination;
    use LoadEvent;

    /**
     * @var Model
     */
    public Model $targetModel;

    /**
     * @var string
     */
    public string $targetModelName;

    /**
     * @var Collection
     */
    protected Collection $items;

    /**
     * @var LengthAwarePaginator
     */
    protected LengthAwarePaginator $itemsPagination;

    /**
     * @var string
     */
    protected $paginationTheme = 'bootstrap';

    /**
     * Most important is proper way to handle
     * mounting. By mounting user pass target 
     * model that will be thing about. 
     * Everything else is just a simple task of
     * manipulating data.
     * 
     * @param string $targetModel
     * 
     * @return void
     */
    public function mount(string $model) : void
    {
        // First save string 
        $this->targetModelName = $model;

        // Instantiate model
        try
        {
            if(! class_exists($this->modelName($model)))
                throw new ModelNotFoundException();

            $this->targetModel = new ($this->modelName($model));
        }
        catch(ModelNotFoundException $e)
        {
            Log::channel('xLog')->error("Model {$model} is not created! User artisan to create.");
            abort(404); 
        }
    }

    /**
     * Get items that are adjusted by search term
     * we user. That items will be stored in usual
     * items variable
     * 
     * @param mixed $itemValue
     * @param string $itemKey
     * 
     * @return void
     */
    protected function search(mixed $itemValue, string $itemKey) : void
    {
        $this->items = $this->targetModel::where($itemKey, 'like', "%{$itemValue}%")->get();
    }

    /**
     * Search for items with pagination included.
     * 
     * @param mixed $itemValue
     * @param string $itemKey
     * @param int $limit
     * 
     * @return void
     */
    protected function searchPagination(mixed $itemValue, string $itemKey, int $limit) : void 
    {
        $this->itemsPagination = $this->targetModel::where($itemKey, 'like', "%{$itemValue}%")->paginate($limit);
    }

    /**
     * Use list to fill items variable
     * and get all records
     * 
     * @return void
     */
    protected function list() : void
    {
        $this->items = $this->targetModel::all();
    }

    /**
     * Use to fill itemsPaginate variable that
     * are used to fill with pagination
     * 
     * @param int $limit
     * 
     * @return void
     */
    protected function listPagination(int $limit = 5) : void
    {
        $this->itemsPagination = $this->targetModel::paginate($limit);
    }

    /**
     * Save is first implemented operation within
     * component.
     * 
     * @return void
     */
    public function save() : void
    {
        // Before anything check did all data
        // passed validation
        $validatedData = $this->validate();

        // Pass authorization if Exists
        if(class_exists(
            $this->loadPolicy($this->targetModelName)
        ))
        {
            $this->authorize('create', $this->targetModel::class);
        }

        // If data is validated and authorizied we can
        // save it to the database.
        $newRecord = $this->targetModel::create($validatedData);

        // if we have new record create simple 
        // event
        $eventName = strtolower($this->targetModelName);

        if($newRecord)
        {
            $this->dispatch("{$eventName}-created", $newRecord);
        }
        else
        {
            $this->dispatch("{$eventName}-not-created");
        }
    }

    /**
     * @param mixed $itemId
     * @param string $itemKey
     * 
     * @return void
     */
    public function delete(mixed $itemId, string $itemKey = 'id') : void 
    {
        // Removing item is simple 
        // process. Get item, authorize operation
        // and at the end remove it
        $gatherModel = $this->targetModel::where([
            $itemKey => $itemId
        ])->first();

        // Create event for further use
        $eventName = strtolower($this->targetModelName);
        
        if($gatherModel)
        {
            if(class_exists($this->loadPolicy($this->targetModelName)))
                $this->authorize('delete', $gatherModel);

            // If user can remove 
            // item we can do it
            if($gatherModel->delete())
                $this->dispatch("{$eventName}-deleted", $gatherModel);
        }
        else 
        {
            $this->dispatch("{$eventName}-deleted");
        }
    }

    /**
     * Lets do update selected model. Usually update
     * will be in different component then create so we
     * can use already built in structure..
     * 
     * @param mixed $itemId
     * @param string $itemKey
     * 
     * @return void
     */
    public function update(mixed $itemId, string $itemKey = 'id') : void 
    {
        // Validate data
        $updateRecordData = $this->validate();

        // Grab the record from database
        $gatherModel = $this->targetModel::where([
            $itemKey => $itemId
        ])->first();

        // Event create
        $eventName = strtolower($this->targetModelName);

        if($gatherModel)
        {
            // Now authorize operation
            if(class_exists($this->loadPolicy($this->targetModelName)))
                $this->authorize('update', $gatherModel);

            if($gatherModel->update($updateRecordData))
            {
                $this->dispatch("{$eventName}-updated", $gatherModel);
            }
        }
        else
        {
            $this->dispatch("{$eventName}-not-updated");
        }
    }
}

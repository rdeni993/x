<?php 

namespace X\X\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use X\X\Traits\LoadEvent;
use X\X\Traits\LoadModel;
use X\X\Traits\LoadPolicy;
use X\X\Traits\LoadRedirector;

use function PHPSTORM_META\type;

class Delete 
{
    use LoadModel;
    use LoadPolicy;
    use LoadEvent;
    use LoadRedirector;

    /**
     * @param Request $request
     * @param string $model
     * @param mixed $value
     * @param string $key
     * 
     * @return RedirectResponse
     */
    public function index(Request $request, string $model, mixed $value, string $key = 'id') : RedirectResponse
    {
        // Delete method will handle
        // link resolving for removing
        // item.... Insted of request
        //
        // Format /x/remove/user/24/id
        $selectedModel = 
            ($this->modelName($model))::where([
                $key => $value
            ])->first();
        
        // Check model existance
        if(empty($selectedModel))
        {
            // Log about model is not find
            Log::channel('xLog')->error("Model {$selectedModel} is not found!");

            // about
            abort(404);
        }

        // If model is found we should proceed to authorization
        // if autgorization exists. By default every user can remove any
        // item so if you need specific you should create policy for model
        if(class_exists($this->loadPolicy($model)))
        {
            if(Auth::check())
            {
                if($request->user()->cannot('delete', $selectedModel))
                {
                    abort(403);
                }
            }
        }

        // Now we can remove element
        if($selectedModel->delete())
        {
            // Return event about removing 
            // item
            
            $event = $this->eventCompleted(
                $model,
                true,
                'Deleted'
            );

            // Check for event
            if(class_exists($event)) 
            {
                // emit event
                event(new $event($selectedModel));
            }

            // After event is emited
            // we should do redirection 
            $redirector = $this->redirector(
                $this->redirectorName(
                    $model,
                    'Delete',
                    true
                )
            );

            if(class_exists($redirector))
            {
                return $redirector::go($selectedModel);
            }
        }
        else
        {
            $event = $this->eventCompleted(
                $model,
                false,
                'Deleted'
            );

            // Check for event
            if(class_exists($event)) 
            {
                // emit event
                event(new $event($selectedModel));
            }
            
            // After event is emited
            // we should do redirection 
            $redirector = $this->redirector(
                $this->redirectorName(
                    $model,
                    'Delete',
                    false
                )
            );

            if(class_exists($redirector))
            {
                return $redirector::go($selectedModel);
            }
        }

        return redirect()->back();
    }
}
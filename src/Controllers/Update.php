<?php 

namespace X\X\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use X\X\Traits\LoadEvent;
use X\X\Traits\LoadModel;
use X\X\Traits\LoadPolicy;
use X\X\Traits\LoadRedirector;

class Update extends Controller
{
    use LoadModel;
    use LoadPolicy;
    use LoadEvent;
    use LoadRedirector;

    public function index(Request $request, mixed $target, mixed $reference = 'id') : RedirectResponse
    {
        // Usually if we are here validation process
        // is passed. Now lets check did user have 
        // authorization to update
        // Lets make instance of target model
        try
        {
            $currentModel = ($this->modelName($request->xTargetModel))::where([
                $reference => $target
            ])->first();

            if(empty($currentModel))
                throw new Exception("Model {$request->xTargetModel} with {$reference} {$target} not exists");
        }
        catch(Exception $e)
        {
            // Log error
            Log::channel('xLog')->error($e->getMessage());

            // Abort 404
            abort(404);
        }

        if (class_exists(
            $this->loadPolicy(
                $request->xTargetModel
            )
        )) 
        {
            if(Auth::check())
            {
                if($request->user()->cannot('update', $currentModel))
                {
                    // Do some log
                    Log::channel('xLog')->error('You need to define policy for this action');

                    // Forbiden
                    abort(403);
                }
            }
        }

        // Update model
        $updatedModel = $currentModel->update($request->xValidated);

        if($updatedModel)
        {
            // Model is updated
            // Now check did developer create
            // event that will handle after 
            // update
            // Create successfull event
            $event = $this->eventCompleted(
                $request->xTargetModel,
                true,
                'Updated'
            );

            // Check for event
            if(class_exists($event)) 
            {
                // emit event
                event(new $event($currentModel));
            }

            // After event is emited
            // we should do redirection 
            $redirector = $this->redirector(
                $this->redirectorName(
                    $request->xTargetModel,
                    'Update',
                    true
                )
            );

            if(class_exists($redirector))
            {
                return $redirector::go($currentModel);
            }

            // Or just print
            dd($currentModel);
        }
        else
        {
            // Model is not updated
            // Create unsuccessfull event
            $event = $this->eventCompleted(
                $request->xTargetModel,
                false,
                'Updated'
            );

            // Check for event
            if(class_exists($event)) 
            {
                // emit event
                event(new $event($currentModel));
            }

            // Handle redirection if update fails
            $redirector = $this->redirector(
                $this->redirectorName(
                    $request->xTargetModel,
                    'Update',
                    false
                )
            );

            if(class_exists($redirector))
            {
                return $redirector::go($currentModel);
            }
        }

        // Usually if everything fails 
        // go back
        return redirect()->back()->withErrors([
            'status' => "Error happend! Check log for more details"
        ]);
    }
}
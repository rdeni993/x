<?php 

namespace X\X\Controllers;

use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use X\X\Traits\LoadEvent;
use X\X\Traits\LoadModel;
use X\X\Traits\LoadPolicy;
use X\X\Traits\LoadRedirector;
use X\X\Traits\LockForGuests;

/**
 * This controller is responsible
 * for creating new user
 */

class Create extends Controller
{
    use LoadModel;
    use LoadPolicy;
    use LoadEvent;
    use LoadRedirector;

    public function index(Request $request) : RedirectResponse
    {
        // Valid requests will now proceed to 
        // final creation.. 
        // Let first authorize request
        if (class_exists(
            $this->loadPolicy(
                $request->xTargetModel
            )
        )) 
        {
            if(Auth::check())
            {
                if($request->user()->cannot('create', $this->modelName($request->xTargetModel)))
                {
                    abort(403);
                }
            }
        }

        // If request is authorized it means
        // we passed all what need to create
        // we can proceed to create class.
        $createModel = 
            ($this->modelName(
                $request->xTargetModel
            ))::create(
                $request->xValidated
            );
        
        // If model successfully created
        // we should provide events
        // Convetion is that event comes in 
        // format 
        // model + prefix? + action + Event
        // success : Model Created Event
        // failed : Model Not Created Event
        if(isset($createModel))
        {
            // Create successfull event
            $event = $this->eventCompleted(
                $request->xTargetModel,
                true,
                'Created'
            );

            // Check for event
            if(class_exists($event)) 
            {
                // emit event
                event(new $event($createModel));
            }

            // This is the end of the script
            // Lets search a redirector or go
            // back
            $redirector = $this->redirector(
                $this->redirectorName(
                    $request->xTargetModel,
                    'Create',
                    true
                )
            );

            if(class_exists($redirector))
            {
                return $redirector::go($createModel);
            }

            // If redirector is not declared
            // we will use this simple method
            dd($createModel);
        }
        else 
        {
            // Create unsuccessfull event
            $event = $this->eventCompleted(
                $request->xTargetModel,
                false,
                'Created'
            );

            // Check for event
            if(class_exists($event)) 
            {
                // emit event
                event(new $event());
            }

            // At this point model did not
            // create instance so default is
            // to go back
            $redirector = $this->redirector(
                $this->redirectorName(
                    $request->xTargetModel,
                    'Create',
                    false
                )
            );

            if(class_exists($redirector))
            {
                return $redirector::go();
            }

            return redirect()->back()->withErrors([
                'status' => "Object not created. Check log for more"
            ]);
        }

        // This is default redirector
        // where user will be redirectered
        // if everything successfull.
        return redirect()->back();
    } 
}
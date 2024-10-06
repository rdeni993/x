<?php 

namespace X\X\Middlewares;

use Closure;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use X\X\Exceptions\FormRequestNotDefinedException;
use X\X\Traits\RequestName;

class AdjustFormRequest 
{
    use RequestName;

    /**
     * @param Request $request
     * @param Closure $next
     * 
     * @return Response
     */
    public function handle(Request $request, Closure $next) : Response
    {
        // We will listen for model route param
        // that must be parsed if user want to 
        // be here. Model name will be parsed
        // trough TargetModel directive.
        try
        {
            // This middleware have task to grab proper
            // request and inject inside controller
            $requestClass = $this->requestPath(
                $this->requestName(
                    $request->xTargetModel,
                    $request->xAction
                )
            );

            // Now lets try to find a class, 
            // if class is not defined it will throw 
            // exception related to class 
            if(! class_exists($requestClass))
                throw new FormRequestNotDefinedException();

            // Lets instantiate class
            $formRequest = app($requestClass);

            // Merge with current request
            if ($formData = $formRequest->validated()) {
                // Add parsed to our
                // request
                $request->merge([
                    'xValidated' => $formData
                ]);

                // Continue
                return $next($request);
            }
        }
        catch(FormRequestNotDefinedException $e)
        {
            // File exists but class not
            Log::channel('xLog')->error("{$requestClass} is not defined inside file!");

            // Abort page
            abort(404);
        }
        catch(ErrorException $e)
        {
            // File is not created
            Log::channel('xLog')->error("Please create {$requestClass}");

            // Abort page
            abort(404);
        }
    }
}
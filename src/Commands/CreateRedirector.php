<?php 

namespace X\X\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CreateRedirector extends Command
{
    /**
     * @var string
     */
    protected $signature = 'make:redirector {name}';

    /**
     * @var string
     */
    protected $description = "Create redirector class";

    /**
     * @return void
     */
    public function handle() : void
    {
        // User create redirector for
        // redirection in success case or in
        // failed..
        $redirectorDirectory = app_path('Redirectors');
        $redirectorName = implode('\\', [
            $redirectorDirectory,
            $this->argument('name') . '.php'
        ]);

        // Now we can check for directory
        if(! File::exists($redirectorDirectory))
        {
            File::makeDirectory(app_path('Redirectors'));
        }

        // Now create file 
        if(File::exists($redirectorName))
        {
            $this->error("{$this->argument('name')} already exists");
        }
        else 
        {
            File::put(
                $redirectorName,
                $this->logic($this->argument('name'))
            );
        }
    }

    public function logic($name) : string
    {
        return <<<EOFREDIRECTOR
<?php 

namespace App\Redirectors;

use Illuminate\Database\Eloquent\Model;
use X\X\Redirector\Redirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class $name 
{
    /*
    *   Update this with your url
    */
    protected static string \$url = '';

    /**
     * * Redirector willl go where you want
     * *
     * */
    public static function go(?Model \$model) : RedirectResponse
    {
        return redirect()->to(
            url(self::\$url)
        );
    }
    
    /**
     * @param string \$route
     * @param Model|null \$model
     * 
     * @return RedirectResponse
     */
    public static function goToRoute(string \$route, ?Model \$model) : RedirectResponse
    {
        \$modelPath = explode(
                '\\\\',
                strtolower(\$model::class)
        );
        \$modelSlug = array_pop(
          \$modelPath  
        );

        \$modelSlug = strtolower(\$modelSlug);

        if(Route::has(\$route))
            return redirect()->to(
                route(\$route, [\$modelSlug => \$model->id ?? null])
            );
        else 
        {
            // Log
            Log::channel('xLog')->error("Defined route {\$route} is not exists!");
            
            // Route is not found
            abort(404);
        }
    }
}
EOFREDIRECTOR;
    }
}
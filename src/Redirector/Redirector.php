<?php 

namespace X\X\Redirector;

use Illuminate\Http\RedirectResponse;

class Redirector 
{
    protected static string $url = '';
    
    /**
     * @return RedirectResponse
     */
    public static function go() : RedirectResponse
    {
        dd(self::$url);
        if(! isset(self::$url))
            return redirect()->back();
        
        return redirect()->to(
            url(self::$url)
        );
    }
}
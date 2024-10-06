<?php 

namespace X\X\Traits;

trait LoadRedirector
{
    /**
     * @param string $model
     * @param string $action
     * @param string $status
     * 
     * @return string
     */
    public function redirectorName(string $model, string $action = 'Create', bool $status = true) : string
    {
        return implode(
            '',
            [
                $action,
                $model,
                "Redirect",
                ($status) ? "Forward" : "Backward"
            ]
        );
    }

    /**
     * @param string $redirectorName
     * 
     * @return string
     */
    public function redirector(string $redirectorName) : string
    {
        return implode(
            '\\',
            [
                'App',
                'Redirectors',
                $redirectorName
            ]
            );
    }
}
<?php 

namespace X\X\Traits;

trait RequestName
{
    /**
     * @param string $model
     * @param string $command
     * 
     * @return string
     */
    public function requestName(string $model, string $command = 'create') : string
    {
        if(!$model)
            return '';

        return implode(
            '',
            [
                ucfirst($command),
                $model,
                "Request"
            ]
        );
    }

    /**
     * @return string
     */
    public function requestPath(string $requestName) : string
    {
        return implode(
            '\\',
            [
                'App',
                'Http',
                'Requests',
                $requestName
            ]
        );
    }
}
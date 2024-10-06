<?php 

namespace X\X\Traits;

trait LoadModel
{
    public function modelName(string $model) : string
    {
        return implode('\\', [
            'App',
            'Models',
            $model
        ]);
    }
}
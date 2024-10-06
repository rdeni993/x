<?php

namespace X\X\Traits;

trait LoadPolicy
{
    // Policy is related to models
    // So convetion is that policy is
    // in format
    // ModelPolicy
    public function loadPolicy(string $model) : string
    {
        return implode('\\', [
            'App',
            'Policies',
            $model . "Policy"
        ]);
    }

}
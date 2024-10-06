<?php 

namespace X\X\Traits;

trait LoadEvent
{
    public function eventCompleted(string $model, bool $status = true, string $eventAction = 'Created') : string
    {
        return implode(
            '\\',
            [
                'App',
                'Events',
                $model .
                ($status ? 
                    ($eventAction)
                    :
                    ('Not' . $eventAction)) .
                'Event'
            ]
        );
    }
}
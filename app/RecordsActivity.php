<?php

namespace App;

trait RecordsActivity
{

    protected static function bootRecordsActivity() //we can call the boot function here, following the notation
    //boot+nameOfTrait
    {
        if (auth()->guest()) return;
        
        foreach (static::getAcctivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
        
    }

    protected static function getAcctivitiesToRecord()
    {
        return ['created'];
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);

        // Activity::create([
        //     'user_id' => auth()->id(),
        //     //the next line is to long, we replace it with a function
        //     //'type' => 'created_' . strtolower ((new \ReflectionClass($this))->getShortName()), //Give us Thread instead of Forum\App\Thread
        //     'type' => $this->getActivityType($event),
        //     'subject_id' => $this->id,
        //     'subject_type' => get_class($this)
        // ]);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivityType($event)
    {
        $type = strtolower ((new \ReflectionClass($this))->getShortName());
        //return $event . '_' . $type;
        return "{$event}_{$type}";
    }  

}
<?php
namespace App\Filters;

use Illuminate\Http\Request;

//Abstract class you never instance this class directly, always instanciate a subclass
abstract class Filters
{
    protected $request, $builder;
    protected $filters = [];
    /**
     * ThreadFilters constructor
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        /*
        $this->getFilters()
             ->filter(function ($filter) {
                 return method_exists($this, $filter);
             })
             ->each(function ($filter, $value) {
                $this->$filter($value);
             });
        */
        

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) { 
                $this->$filter($value);
            }
        }
        
        //if ($this->request->has('by')){
        //    $this->by($this->request->by);
        //}
        //We apply our filters to the builder
        //if(! $username = $this->request->by) return $builder;

        return $this->builder;

    }

    public function getFilters()
    {
        //return $this->request->only($this->filters);
        //change only for intersect because only retunr a null value when the url dont put anything to search
        //with intersect return a [] and this dont make any error
        //intersect dont exit, we have to handle the error 
        if ($this->request->only($this->filters) != null){
            return $this->request->only($this->filters);
            //return collect($this->request->only($this->filters))->flip();
        }

        
        return $this->request = [];
    }
    
}
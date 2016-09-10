<?php

namespace BestServedCold\LaravelZendSearch\Laravel\Model;

final class Saved extends AbstractModel
{

    public function update()
    {
        if ($this->eloquent->find($this->model)) {
            // delete all the fucking records.
        }

        $this->eloquent->add($this->model);
        // no insert all the fucking records.
        
    }
}

<?php

namespace SiranixSociety\HMLikes\Traits;

trait Likeable {
    /*
     * Relationships
     */
    public function Likes(){
        return $this->morphMany(
            config('HelperModels.Structure.Models.Like'),
            'Likeable'
        );
    }

}
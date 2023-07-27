<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ArticleFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function section($id)
    {
        return $this->where('section_id', $id);
    }

    public function headline($headline)
    {
        return $this->where('headline', 'LIKE', '%' . $headline . '%');
    }
}

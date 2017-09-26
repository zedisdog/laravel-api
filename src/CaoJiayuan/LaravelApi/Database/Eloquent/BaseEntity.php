<?php
/**
 * Created by PhpStorm.
 * User: 0x01301c74
 * Date: 2017/8/27
 * Time: 20:11
 */

namespace CaoJiayuan\LaravelApi\Database\Eloquent;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseEntity extends Model
{

    /**
     * @return Builder
     */
    public function newQuery()
    {
        $builder = parent::newQuery();

        $this->beforeQuery($builder);

        return $builder;
    }

    /**
     * @param Builder $builder
     */
    public function beforeQuery($builder)
    {

    }
}
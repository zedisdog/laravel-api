<?php
/**
 * Created by PhpStorm.
 * User: caojiayuan
 * Date: 17-8-28
 * Time: 上午11:40
 */

namespace CaoJiayuan\LaravelApi\Database\Eloquent\Relations;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class RelationEmpty extends Relation
{

    public function get($columns = ['*'])
    {
        return new Collection();
    }

    public function __construct(Model $model)
    {
        parent::__construct($model->newQuery(), $model);
    }


    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {
        // TODO: Implement addConstraints() method.
    }

    /**
     * Set the constraints for an eager load of the relation.
     *
     * @param  array $models
     * @return void
     */
    public function addEagerConstraints(array $models)
    {
        // TODO: Implement addEagerConstraints() method.
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param  array $models
     * @param  string $relation
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * @param  array $models
     * @param  \Illuminate\Database\Eloquent\Collection $results
     * @param  string $relation
     * @return array
     */
    public function match(array $models, Collection $results, $relation)
    {
        foreach ($models as $model) {
            $model->$relation = [];
        }

        return $models;
    }

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        return [];
    }
}
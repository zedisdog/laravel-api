<?php
/**
 * Created by PhpStorm.
 * User: caojiayuan
 * Date: 17-11-2
 * Time: 下午3:04
 */

namespace CaoJiayuan\LaravelApi\Pagination;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait PageHelper
{
    /**
     * @param Builder|Model $builder
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param null $page
     */
    public function applyPaginate($builder, $perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $url = url()->current();
        $query = \Request::query();
        $query = http_build_query(array_except($query, $pageName));
        $query && $query = '?' . $query;
        $path = $url . $query;
        return $builder->paginate($perPage)->setPath($path);
    }

    /**
     * @param Builder $builder
     * @param int $perPage
     * @param string $minKeyName
     * @param string $maxKeyName
     * @return mixed
     */
    public function pageByKey($builder, $perPage = 15, $minKeyName = 'since_id', $maxKeyName = 'to_id')
    {
        $maxId = \Request::get($maxKeyName, 0);
        $sinceId = \Request::get($minKeyName);
        $table = $builder->getModel()->getTable();
        $key = $builder->getModel()->getKeyName();
        if ($maxId) {
            $builder->where($table . '.' . $key, '<', $maxId);
        } else {
            if ($sinceId !== null) {
                $builder->where($table . '.' . $key, '>', $sinceId);
            }
        }

        $builder->take($perPage);
        return $builder;
    }
}
<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-5-5
 * Time: 下午3:37
 */

namespace CaoJiayuan\LaravelApi\Database\Eloquent;


class KeyValue extends BaseEntity
{
    public $timestamps = false;

    public static $items = [];

    public static function getItem($key, $default = null)
    {
        if (!static::$items) {
            static::$items = self::getConvertedData();
        }

        return array_get(static::$items, $key, $default);
    }

    public static function getConvertedData()
    {
        $all = static::all();

        $data = [];
        foreach ($all as $item) {
            $data[$item->key] = $item->value;
        }

        return $data;
    }

    public static function store($key, $value = null)
    {
        $insert = [];
        if (is_array($key)) {
            foreach ($key as $k => $item) {
                if (!is_numeric($k)) {
                    $insert[] = [
                        'key'   => $k,
                        'value' => $item
                    ];
                }
            }
        } else if (!is_numeric($key) && $value !== null) {
            $insert[] = [
                'key'   => $key,
                'value' => $value
            ];
        }

        return static::insert($insert);
    }
}
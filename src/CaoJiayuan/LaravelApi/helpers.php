<?php

if (!function_exists('is_local')) {
    function is_local()
    {
        return env('APP_ENV') == 'local';
    }
}

if (!function_exists('array_remove_empty')) {
    function array_remove_empty($array, $remove = ['', null])
    {
        $removed = [];
        foreach ((array)$array as $key => $item) {
            if (!in_array($item, $remove)) {
                $removed[$key] = $item;
            }
        }
        return $removed;
    }
}


if (!function_exists('object_to_array')) {
    function object_to_array($object, &$result)
    {
        $data = $object;
        if (is_object($data)) {
            $data = get_object_vars($data);
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $res = null;
                object_to_array($value, $res);
                if (($key == '@attributes') && ($key)) {
                    $result = $res;
                } else {
                    $result[$key] = $res;
                }
            }
        } else {
            $result = $data;
        }
    }
}

if (!function_exists('file_map')) {
    /**
     * @param string|array $file
     * @param callable $closure
     * @param bool $recursive
     */
    function file_map($file, callable $closure, $recursive = true)
    {
        foreach ((array)$file as $fe) {
            if (is_dir($fe)) {
                $items = new FilesystemIterator($fe);
                /** @var SplFileInfo $item */
                foreach ($items as $item) {
                    if ($item->isDir() && !$item->isLink() && $recursive) {
                        $closure($item->getPathname(), $item, $item->isDir());
                        file_map($item->getPathname(), $closure);
                    } else {
                        $closure($item->getPathname(), $item, $item->isDir());
                    }
                }
            } else {
                $f = new SplFileInfo($fe);
                $closure($fe, $f);
            }
        }
    }
}

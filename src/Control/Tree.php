<?php

namespace Ares\Rabc\Control;

/**
 * 整理权限树
 * Class Tree
 * @package Ares\Rabc\Control
 */
class Tree
{
    public function tree(&$array, $field_title, $field_key = 'id', $field_parent = 'pid')
    {
        if (empty($array) || !is_array($array)) return array();

        $result = $this->get_tree_position($array, 0, $field_key, $field_parent);

        foreach ($result as $k => $item) {
            $front = "";
            if ($item['_level'] > 2) {
                for ($i = 1; $i < $item['_level'] - 1; $i++) {
                    $front .= "&emsp;│";
                }
            }
            if ($item['_level'] != 1) {
                $title = $field_title ? $item[$field_title] : "";
                if (isset($result[$k + 1]) && $result[$k + 1]['_level'] >= $result[$k]['_level']) {
                    $result[$k]['_name'] = $front . "&emsp;├─ " . $title;
                } else {
                    $result[$k]['_name'] = $front . "&emsp;└─ " . $title;
                }
            } else {
                $result[$k]['_name'] = $item[$field_title];
            }
        }
        return $result;
    }

    public function table(&$array)
    {
        if (empty($array) || !is_array($array))
        {
            return array('rows' => 0, 'list' => array());
        }

        $result = array();
        foreach ($array as $row)
        {
            $result[$row['id']] = $row;
        }

        foreach ($result as $id => $item)
        {
            $result[$id]['column'] = $this->get_level($result, $id, 1); // Y轴位置
            $result[$id]['children'] = $this->get_children($result, $id, ''); // 所有子节点
            $result[$id]['parents'] = $this->get_parents($result, $id, ''); // 所有父节点
            $result[$id]['bottom'] = $this->get_child_count($result, $id); // 所有底层元素节点
        }

        $columns = $this->get_columns($result); // 总行数
        $rows = $this->get_rows($result); // 总列数

        // 按照parents、ord和id号进行排序
        $this->sort_array($result, $columns);

        foreach ($result as $id => $item)
        {
            $result[$id]['row'] = $this->get_row_location($result, $id);    // X轴位置
            $result[$id]['rowspan'] = $item['bottom']; // 行合并数
            $result[$id]['colspan'] = $item['bottom'] == 0 ? $columns - $item['column'] + 1 : 0; //列合并数
        }

        return array('rows' => $rows, 'list' => $result);
    }

    private function get_tree_position(&$array, $pid = 0, $field_key = 'id', $field_parent = 'pid', $level = 1)
    {
        $result = $this->get_tree_level($array, $pid, $field_key, $field_parent, $level);
        foreach ($result as $i => $item) {
            if ($item['_level'] == 1) continue;
            $result[$i]['_first'] = false;
            $result[$i]['_end'] = false;
            if (!isset($result[$i - 1]) || $result[$i - 1]['_level'] != $item['_level']) {
                $result[$i]['_first'] = true;
            }
            if (isset($result[$i + 1]) && $result[$i]['_level'] > $result[$i + 1]['_level']) {
                $result[$i]['_end'] = true;
            }
        }
        return $result;
    }

    private function get_tree_level(&$array, $pid = 0, $field_key = 'id', $field_parent = 'pid', $level = 1)
    {
        $result = array();
        foreach ($array as $item) {
            if ($item[$field_parent] == $pid) {
                $id = $item[$field_key];
                $item['_level'] = $level;
                array_push($result, $item);
                $tmp = $this->get_tree_level($array, $id, $field_key, $field_parent, $level + 1);
                $result = array_merge($result, $tmp);
            }
        }
        return $result;
    }

    private function get_level(&$result, $id, $level = 1)
    {
        if ($result[$id]['pid'])
        {
            $level = $this->get_level($result, $result[$id]['pid'], $level + 1);
        }
        return $level;
    }

    private function get_children(&$result, $id, $children = '')
    {
        foreach ($result as $item)
        {
            if ($item['pid'] == $id)
            {
                $children .= ($children ? ',' : '') . $item['id'];
                $children = $this->get_children($result, $item['id'], $children);
            }
        }
        return $children;
    }

    private function get_parents(&$result, $id, $parents = '')
    {
        $pid = $result[$id]['pid'];
        if ($pid > 0) $parents = $pid . ($parents ? ',' : '') . $parents;
        if ($pid) $parents = $this->get_parents($result, $pid, $parents);
        return $parents;
    }

    private function get_child_count(&$result, $id, $init = true)
    {
        static $count = 0;
        if ($init) $count = 0;
        $child_ids = $this->get_child($result, $id);
        if (empty($child_ids) && $init) return 0;
        if ($child_ids)
        {
            foreach ($child_ids as $child_id)
            {
                $this->get_child_count($result, $child_id, false);
            }
        }
        else
        {
            $count++;
        }
        return $count;
    }

    private function get_child(&$result, $pid)
    {
        $child_ids = array();
        foreach ($result as $id => $item)
        {
            if ($item['pid'] == $pid) $child_ids[] = $id;
        }
        return $child_ids;
    }

    private function get_columns(&$result)
    {
        $columns = 0;
        foreach ($result as $id => $item)
        {
            if ($item['column'] > $columns)
            {
                $columns = $item['column']; // 总列数
            }
        }
        return $columns;
    }

    private function get_rows(&$result)
    {
        $rows = 0;
        foreach ($result as $id => $item)
        {
            $rows += $item['bottom'] == 0 ? 1 : 0; // 总行数
        }
        return $rows;
    }

    private function sort_array(&$result, $columns)
    {
        $sort_pid = $sort_id = array();
        // 要进行排序的字段
        foreach ($result as $id => $item)
        {
            $parents = explode(',', $item['parents']);
            foreach ($parents as $i => $pid)
            {
                $parents[$i] = sprintf("%04d", $pid);
            }
            $sort_pid[] = implode(',', $parents); //$item['parents'];
            $sort_id[] = $item['id'];
        }

        // 先根据parents排序，再根据ord排序，id号排序
        array_multisort(
            $sort_pid, SORT_ASC, SORT_STRING,
            $sort_id, SORT_ASC, SORT_NUMERIC,
            $result);

        // 获取每一个节点层次
        for ($column = 1; $column <= $columns; $column++)
        {
            $row_level = 0;
            foreach ($result as $i => $item)
            {
                if ($item['column'] == $column)
                {
                    $row_level++;
                    $result[$i]['column_level'] = $row_level;
                }
            }
        }

        $array = array();
        // 重新计算以ID作为键名
        foreach ($result as $item)
        {
            $array[$item['id']] = $item;
        }

        $result = $array;
    }

    private function get_row_location(&$result, $id)
    {
        $row = $result[$id];
        $count = 0;
        $parents = $row['parents'] ? explode(',', $row['parents']) : array();
        // 所有父节点小于当前节点层次的底层节点等于0的元素
        foreach ($parents as $pid)
        {
            if (empty($pid)) continue;
            foreach ($result as $item)
            {
                if ($item['column'] == $result[$pid]['column'] && $result[$pid]['column_level'] > $item['column_level'] && $item['bottom'] == 0)
                {
                    $count++;
                }
            }
        }
        // 所有当前节点并且节点层次（column_level）小于当前节点层次的个数
        foreach ($result as $item)
        {
            if ($row['column'] == $item['column'] && $item['column_level'] < $row['column_level'])
            {
                $count += $item['bottom'] ? $item['bottom'] : 1;
            }
        }
        $count++;
        return $count;
    }
}
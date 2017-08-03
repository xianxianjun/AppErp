<?php
namespace Common\Common\PublicCode;
class ExpandTp
{
    function batch_update($table_name = '', $data = array(), $field = '',$isComm = false)
    {
        if (!$table_name || !$data || !$field) {
            return false;
        } else {
            $tnamePriefix = C('DB_PREFIX');
            $sql = 'UPDATE ' . (empty($tnamePriefix)?$table_name:$tnamePriefix.$table_name);
        }
        $con = array();
        $con_sql = array();
        $fields = array();
        foreach ($data as $key => $value) {
            $x = 0;
            foreach ($value as $k => $v) {
                if ($k != $field && !$con[$x] && $x == 0) {
                    $con[$x] = " set {$k} = (CASE {$field} ";
                } elseif ($k != $field && !$con[$x] && $x > 0) {
                    $con[$x] = "  {$k} = (CASE {$field} ";
                }
                if ($k != $field) {
                    $temp = str_replace("'","''",$value[$field]);
                    $vr = str_replace("'","''",$v);
                    $con_sql[$x] .= " WHEN '{$temp}' THEN '{$vr}' ";
                    $x++;
                }
            }
            $temp = $value[$field];
            if (!in_array($temp, $fields)) {
                if(!$isComm) {
                    $fields[] = $temp;
                }
                else
                {
                    $fields[] = "'".$temp."'";
                }
            }
        }
        $num = count($con) - 1;
        foreach ($con as $key => $value) {
            foreach ($con_sql as $k => $v) {
                if ($k == $key && $key < $num) {
                    $sql .= $value . $v . ' end),';
                } elseif ($k == $key && $key == $num) {
                    $sql .= $value . $v . ' end)';
                }
            }
        }
        $str = implode(',', $fields);
        $sql .= " where {$field} in({$str})";
        $res = M($table_name)->execute($sql);
        return $res;
    }
}
<?php

namespace App\Libraries;

use Config\Database;
use DateTime;

class DataTable
{
    private $table;
    private $fields;
    private $whereFixed;
    private $joinTable;
    private $groupBy;
    private $db;
    private $limitString;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->limitString = "";
    }

    public function getListDataTable($request, $table, $fields, $joinTable = '', $whereFixed = '', $groupBy = '', $excel = FALSE)
    {
        if (!is_array($fields)) {
            return array();
        }

        $limit = (int) $excel ? 0 : ($request->getGet('limit') <= 0 ? 10 : $request->getGet('limit'));
        $page = (int) $request->getGet('page') <= 0 ? 1 : $request->getGet('page');
        $filter = (array) $request->getGet('filter');
        $sort = (string) $request->getGet('sort');


        $dir = strtoupper($request->getGet('dir'));

        $this->table = $table;
        $this->fields = $fields;
        $this->whereFixed = $whereFixed;
        $this->joinTable = $joinTable;
        $this->groupBy = $groupBy;

        $start = ($page - 1) * $limit;
        if ($limit != '0') {
            $this->limitString = "LIMIT $start, $limit";
        }
        $results = $this->getListDataDataTable($sort, $dir, $filter);
        $total = (int) $results['count'];
        $pagination = $this->pageGenerate($total, $page, $limit, $start);

        $data = [
            'results' => $results['data'],
            'pagination' => $pagination,
        ];

        return $data;
    }

    private function getListDataDataTable($sort, $dir, $filter = null)
    {
        $querySearch = [
            "sqlSearch" => "",
            "sqlSearchValue" => []
        ];

        $results = array();

        if (is_array($filter)) {
            $querySearch = $this->searchInput($filter, $this->fields);
        }
        if (in_array($sort, $this->fields)) {
            $sort = array_search($sort, $this->fields);
            if (gettype($sort) == 'integer') {
                $sort = $this->fields[$sort];
            }
        } else {
            $sort = array_keys($this->fields)[0];
            if (gettype($sort) == 'integer') {
                $sort = $this->fields[$sort];
            }
        }
        if (array_key_exists("sqlSearch", $querySearch) && trim($querySearch['sqlSearch']) != '') {
            $querySearch['sqlSearch'] = substr($querySearch['sqlSearch'], 4);
            $querySearch['sqlSearch'] = 'AND ' . $querySearch['sqlSearch'];
        }
        if (trim($this->whereFixed) !== '') {
            $this->whereFixed = 'AND ' . $this->whereFixed;
        }
        $fieldSearch = empty($this->fields) ? '*' : '';
        foreach ($this->fields as $key => $value) {
            if (gettype($key) == 'integer') {
                $fieldSearch .= $value;
            } else {
                $fieldSearch .= $key . ' AS ' . $value;
            }

            if ($key !== array_keys($this->fields)[count($this->fields) - 1]) {
                $fieldSearch .= ', ';
            }
        }
        $sqlNumRows = "SELECT COUNT(*) as total FROM (SELECT " . $sort . " FROM " . $this->table . " " . $this->joinTable . " WHERE 1 " . $querySearch['sqlSearch'] . " " . $this->whereFixed . " " . $this->groupBy . ") sqlCount";

        $results['count'] = $this->db->query($sqlNumRows, $querySearch['sqlSearchValue'])->getRow('total');

        $sqlGetData = "SELECT $fieldSearch FROM " . $this->table . " " . $this->joinTable . " WHERE 1 " . $querySearch['sqlSearch'] . " " . $this->whereFixed . " " . $this->groupBy . " ORDER BY $sort $dir {$this->limitString}";

        $results['data'] = $this->db->query($sqlGetData, $querySearch['sqlSearchValue'])->getResultArray();

        foreach ($results['data'] as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if (is_null($value2)) {
                    $results['data'][$key][$key2] = '';
                }
            }
        }
        return $results;
    }

    public function searchInput($whereFilter = array(), $fieldAllowed = array())
    {
        $sqlSearch = '';
        $sqlSearchValue = array();
        if ($whereFilter != null) {
            $bindingNum = 1;
            $bindingField = 'field_' . $bindingNum;
            foreach ($whereFilter as $row) {
                $type = isset($row['type']) ? $row['type'] : '';
                $field = isset($row['field']) ? $row['field'] : '';
                $value = isset($row['value']) ? $row['value'] : '';
                $comparison = isset($row['comparison']) ? $row['comparison'] : '';

                if (in_array($field, array_values($fieldAllowed))) {
                    $field = array_search($field, $fieldAllowed);
                    if (gettype($field) == 'integer') {
                        $field = $fieldAllowed[$field];
                    }
                } else {
                    $field = '';
                }

                if ($field == '' || $value == '') {
                    $type = '';
                }

                switch ($type) {
                    case 'string':
                        $arrAllowed = array('=', '<', '>', '<>', 'like');
                        if (!in_array($comparison, $arrAllowed)) {
                            $comparison = 'like';
                        }
                        switch ($comparison) {
                            case '=':
                                $sqlSearch .= " AND " . $field . " = :" . $bindingField . ":";
                                $sqlSearchValue[$bindingField] = $value;
                                $bindingNum++;
                                $bindingField = 'field_' . $bindingNum;
                                break;
                            case '<':
                                $sqlSearch .= " AND " . $field . " LIKE :" . $bindingField . ":";
                                $sqlSearchValue[$bindingField] = $value . "%";
                                $bindingNum++;
                                $bindingField = 'field_' . $bindingNum;
                                break;
                            case '>':
                                $sqlSearch .= " AND " . $field . " LIKE :" . $bindingField . ":";
                                $sqlSearchValue[$bindingField] = "%" . $value;
                                $bindingNum++;
                                $bindingField = 'field_' . $bindingNum;
                                break;
                            case '<>':
                                $sqlSearch .= " AND " . $field . " LIKE :" . $bindingField . ":";
                                $sqlSearchValue[$bindingField] = "%" . $value . "%";
                                $bindingNum++;
                                $bindingField = 'field_' . $bindingNum;
                                break;
                            case 'like':
                                $sqlSearch .= " AND " . $field . " LIKE :" . $bindingField . ":";
                                $sqlSearchValue[$bindingField] = "%" . $value . "%";
                                $bindingNum++;
                                $bindingField = 'field_' . $bindingNum;
                                break;
                        }
                        break;
                    case 'numeric':
                        if (is_numeric($value)) {
                            $arrAllowed = array('=', '<', '>', '<=', '>=', '<>');
                            if (!in_array($comparison, $arrAllowed)) {
                                $comparison = '=';
                            }
                            $sqlSearch .= " AND " . $field . " " . $comparison . " :" . $bindingField . ":";
                            $sqlSearchValue[$bindingField] = $value;
                            $bindingNum++;
                            $bindingField = 'field_' . $bindingNum;
                        }
                        break;
                    case 'list':
                        if (strstr($value, '::')) {
                            $arrAllowed = array('yes', 'no', 'bet');
                            if (!in_array($comparison, $arrAllowed)) {
                                $comparison = 'yes';
                            }
                            $fi = explode('::', $value);
                            for ($q = 0; $q < count($fi); $q++) {
                                $fi[$q] = $fi[$q];
                            }
                            $value = $fi;
                            if ($comparison == 'yes') {
                                $sqlSearch .= " AND " . $field . " IN :" . $bindingField . ":";
                                $sqlSearchValue[$bindingField] = $value;
                                $bindingNum++;
                                $bindingField = 'field_' . $bindingNum;
                            }
                            if ($comparison == 'no') {
                                $sqlSearch .= " AND " . $field . " NOT IN :" . $bindingField . ":";
                                $sqlSearchValue[$bindingField] = $value;
                                $bindingNum++;
                                $bindingField = 'field_' . $bindingNum;
                            }
                            if ($comparison == 'bet') {
                                $sqlSearch .= " AND " . $field . " BETWEEN :" . $bindingField . "1: AND :" . $bindingField . "2:";
                                $sqlSearchValue[$bindingField . "1"] = $fi[0];
                                $bindingNum++;
                                $bindingField = 'field_' . $bindingNum;
                                $sqlSearchValue[$bindingField . "2"] = $fi[1];
                                $bindingNum++;
                                $bindingField = 'field_' . $bindingNum;
                            }
                        } else {
                            $sqlSearch .= " AND " . $field . " = :" . $bindingField . ":";
                            $sqlSearchValue[$bindingField] = $value;
                            $bindingNum++;
                            $bindingField = 'field_' . $bindingNum;
                        }
                        break;
                    case 'date':
                        if (strpos($field, 'date') !== false || strpos($field, 'datetime') !== false) {
                            // if ($this->endsWith($field, 'date') || $this->endsWith($field, 'datetime')) {
                            $value1 = '';
                            $value2 = '';
                            if (strstr($value, '::')) {
                                $dateValue = explode('::', $value);
                                $value1 = $dateValue[0];
                                $value2 = $dateValue[1];
                            } else {
                                $value1 = $value;
                            }

                            if ($this->endsWith($field, 'datetime')) {
                                $field = 'date(' . $field . ')';
                            }

                            $arrAllowed = array('=', '<', '>', '<=', '>=', '<>', 'bet');
                            if (!in_array($comparison, $arrAllowed)) {
                                $comparison = 'bet';
                            }

                            if ($comparison == 'bet') {
                                if ($this->validateDate($value1) && $this->validateDate($value2)) {
                                    $sqlSearch .= " AND " . $field . " BETWEEN :" . $bindingField . "1: AND :" . $bindingField . "2:";
                                    $sqlSearchValue[$bindingField . "1"] = $value1;
                                    $bindingField = 'field_' . $bindingNum;
                                    $sqlSearchValue[$bindingField . "2"] = $value2;
                                    $bindingNum++;
                                    $bindingField = 'field_' . $bindingNum;
                                }
                            } else {
                                if ($this->validateDate($value1)) {
                                    $sqlSearch .= " AND " . $field . " " . $comparison . " :" . $bindingField . ":";
                                    $sqlSearchValue[$bindingField] = $value1;
                                    $bindingNum++;
                                    $bindingField = 'field_' . $bindingNum;
                                }
                            }
                        }
                        break;
                }
            }
        }

        return [
            'sqlSearch' => $sqlSearch,
            'sqlSearchValue' => $sqlSearchValue,
        ];
    }

    public function pageGenerate($total, $pagenum, $limit, $start)
    {
        $totalPage = $limit == 0 ? 1 : ceil($total / $limit);
        $start += 1;
        $end = $pagenum * $limit;
        if ($end > $total) {
            $end = $total;
        }

        if ($total == 0) {
            $start = $total;
        }

        //------------- Prev page
        $prev = $pagenum - 1;
        if ($prev < 1) {
            $prev = 0;
        }
        //------------------------

        //------------- Next page
        $next = $pagenum + 1;
        if ($next > $totalPage) {
            $next = 0;
        }
        //----------------------

        $from = 1;
        $to = $totalPage;

        $toPage = $pagenum - 2;
        if ($toPage > 0) {
            $from = $toPage;
        }

        if ($totalPage >= 5) {
            if ($totalPage > 0) {
                $to = 5 + $toPage;
                if ($to > $totalPage) {
                    $to = $totalPage;
                }
            } else {
                $to = 5;
            }
        }

        #looping kotak pagination
        $firstPageIsTrue = false;
        $lastPageIsTrue = false;
        $detail = [];
        if ($totalPage <= 1) {
            $detail = [1];
        } else {
            for ($i = $from; $i <= $to; $i++) {
                $detail[] = $i;
            }
            if ($from != 1) {
                $firstPageIsTrue = true;
            }
            if ($to != $totalPage) {
                $lastPageIsTrue = true;
            }
        }

        $totalDisplay = $limit;
        if ($next == 0) {
            $totalDisplay = $limit == 0 ? $total : $total % $limit;
        }

        $pagination = array(
            'total_data' => $total,
            'total_page' => $totalPage,
            'total_display' => $totalDisplay,
            'first_page' => $firstPageIsTrue,
            'last_page' => $lastPageIsTrue,
            'prev' => $prev,
            'current' => $pagenum,
            'next' => $next,
            'detail' => $detail,
            'start' => $start,
            'end' => $end,
        );

        return $pagination;
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    private function endsWith($haystack, $needle)
    {
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }


    public function getListDataExcelCustom($request, $table, $joinTable = '', $whereFixed, $limit = 1000, $groupBy = '')
    {
        $filter = (array) $request['filter'];
        $dir = json_decode(strtoupper($request['dir'])) ?? "ASC";
        $sort = json_decode($request['sort']);
        $start = 0;
        $this->table = $table;
        $this->fields = $request['columns'];
        $this->whereFixed = $whereFixed;
        $this->joinTable = $joinTable;
        $this->groupBy = $groupBy;
        $results = $this->getListDataDataTable($sort, $dir, $filter);

        return $results['data'];
    }
}

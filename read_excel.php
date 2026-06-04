<?php
require_once 'app/Libraries/SimpleXLSX.php';

$xlsx = \Shuchkin\SimpleXLSX::parse('SESAR_Q2_Sample.xlsx');
if ($xlsx) {
    $rows = $xlsx->rows();
    foreach(array_slice($rows, 0, 5) as $i => $row) {
        echo "Row $i:\n";
        print_r($row);
    }
} else {
    echo \Shuchkin\SimpleXLSX::parseError();
}

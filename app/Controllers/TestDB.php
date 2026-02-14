<?php

namespace App\Controllers;

class TestDB extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $query = $db->query('SELECT * FROM tags');
        $results = $query->getResult();

        foreach ($results as $row) {
            echo $row->name . '<br>';
        }
    }
}
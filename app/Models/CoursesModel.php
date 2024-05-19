<?php

namespace App\Models;

use CodeIgniter\Model;

class CoursesModel extends Model
{
    protected $table            = 'courses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $protectFields  = false;
}

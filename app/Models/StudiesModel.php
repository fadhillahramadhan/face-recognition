<?php

namespace App\Models;

use CodeIgniter\Model;

class StudiesModel extends Model
{
    protected $table            = 'studies';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $protectFields  = false;
}

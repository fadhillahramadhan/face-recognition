<?php

namespace App\Models;

use CodeIgniter\Model;

class CoursesUsersModel extends Model
{
    protected $table            = 'courses_users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $protectFields  = false;

    // relationship
    public function courses()
    {
        return $this->belongsTo(CoursesModel::class, 'course_id');
    }

    public function users()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}

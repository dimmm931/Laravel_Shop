<?php 
//not needed for Entrust RBAC, I have just created to make my custom queries to DB Role_User

namespace App\models\EntrustRbac\My_rbac;

use Illuminate\Database\Eloquent\Model;


class Role_User extends Model
{
	  /**
   * Connected DB table name.
   *
   * @var string
   */
   protected $table = 'role_user';
} 
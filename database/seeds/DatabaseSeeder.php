<?php
//This is the MAIN SEEDER!!!!

use Illuminate\Database\Seeder;
//use App\database\seeds\SeedersFiles\ShopSimpleSeeder;
//use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//specify whta data to run
		$this->call('Users_Seeder');  //fill DB table {users} with data
		$this->call('Roles_Seeder');  //fill DB table {roles} with data {4 roles}
		$this->call('RoleUsers_Seeder');  //fill DB table {role_user} with data {assign admin to Dima}
        $this->call('ShopCategories_Seeder');  //fill DB table {shop_categories} with data. MUST BE BEFORE {ShopSimpleSeeder} as contains Forein Keys for {ShopSimpleSeeder}
        //Seeder in separated file
    	$this->call('ShopSimpleSeeder');      //fill DB table {shopsimple} with data. SEEDER IS IN SUBFOLDER /SeedersFiles.
		$this->call('Shop_Quantity_Seeder');  //fill DB table {shop_quantity} with data. 
		 
		$this->command->info('Seedering action was successful!');
    }  
}


//------------------- ALL SEEDERS CLASS -----------------------------------

//fill DB table {users} with data 
class Users_Seeder extends Seeder {
    public function run()
    {
        DB::table('users')->delete();  //whether to Delete old materials
        DB::table('users')->insert(['id' => 1, 'name' => 'Admin', 'email' => 'admin@ukr.net',      'password' => bcrypt('adminadmin') ]);
	    DB::table('users')->insert(['id' => 2, 'name' => 'Dima',  'email' => 'dimmm931@gmail.com', 'password' => bcrypt('dimadima') ]);
	    DB::table('users')->insert(['id' => 3, 'name' => 'Olya',  'email' => 'olya@gmail.com',     'password' => bcrypt('olyaolya') ]);
        DB::table('users')->insert(['id' => 4, 'name' => 'Test',  'email' => 'test@gmail.com',     'password' => bcrypt('testtest') ]);
        
        //create regular users
        $NUMBER_OF_REGULAR_USERS = 10;		
        $faker = Faker::create();
        $gender = $faker->randomElement(['male', 'female']);

    	foreach (range(1, $NUMBER_OF_REGULAR_USERS) as $index) {
            DB::table('users')->insert([
                'name'        => $faker->name($gender),
                'email'       => $faker->email,
                'password'    => bcrypt('password')
			]);
        }
        //
    }
}


//fill DB table {roles} with data (create 4 roles)
class Roles_Seeder extends Seeder {
    public function run()
   {
        DB::table('roles')->delete();  //whether to Delete old materials
        //User::create(['email' => 'foo@bar.com']);
        DB::table('roles')->insert(['id' => 12, 'name' => 'owner',     'display_name' => 'Project Owner',      'description' => 'User is the owner of a given project',           'created_at' => date('Y-m-d H:i:s') ]);
	    DB::table('roles')->insert(['id' => 13, 'name' => 'admin',     'display_name' => 'User Administrator', 'description' => 'User is allowed to manage and edit other users', 'created_at' =>  date('Y-m-d H:i:s') ]);
        DB::table('roles')->insert(['id' => 14, 'name' => 'manager',   'display_name' => 'Company Manager',    'description' => 'User is a manager of a Department',              'created_at' =>  date('Y-m-d H:i:s') ]);
        DB::table('roles')->insert(['id' => 15, 'name' => 'commander', 'display_name' => 'custom role',        'description' => 'Wing Commander',                                 'created_at' => date('Y-m-d H:i:s') ]);
    }
}



//fill DB table {role_user} with data 
class RoleUsers_Seeder extends Seeder {
    public function run()
    {
        DB::table('role_user')->delete();  //whether to Delete old materials
        DB::table('role_user')->insert(['user_id' => 2, 'role_id' => 13 ]);
        DB::table('role_user')->insert(['user_id' => 4, 'role_id' => 13 ]);
    }
}



//fill DB table {shop_categories} with data.
class ShopCategories_Seeder extends Seeder {
    public function run()
    {
        DB::table('shop_categories')->delete();  //whether to Delete old materials

        DB::table('shop_categories')->insert(['categ_id' => 1, 'categ_name' => 'Desktop' ]);
	    DB::table('shop_categories')->insert(['categ_id' => 2, 'categ_name' => 'Mobile' ]);
	    DB::table('shop_categories')->insert(['categ_id' => 3, 'categ_name' => 'Tablet' ]);
	    DB::table('shop_categories')->insert(['categ_id' => 4, 'categ_name' => 'Audio Pro' ]);
    }
}





//fill DB table {shop_quantity} with data.
class Shop_Quantity_Seeder extends Seeder {
    public function run()
    {
        DB::table('shop_quantity')->delete();  //whether to Delete old materials

        DB::table('shop_quantity')->insert(['id' => 1, 'product_id' => 1, 'all_quantity' => 200, 'left_quantity' => 200 ]);
	    DB::table('shop_quantity')->insert(['id' => 2, 'product_id' => 2, 'all_quantity' => 20,  'left_quantity' => 20 ]);
	    DB::table('shop_quantity')->insert(['id' => 3, 'product_id' => 3, 'all_quantity' => 10,  'left_quantity' => 10 ]);
	    DB::table('shop_quantity')->insert(['id' => 4, 'product_id' => 4, 'all_quantity' => 10,  'left_quantity' => 10 ]);
	    DB::table('shop_quantity')->insert(['id' => 5, 'product_id' => 5, 'all_quantity' => 10,  'left_quantity' => 10 ]);
	    DB::table('shop_quantity')->insert(['id' => 6, 'product_id' => 6, 'all_quantity' => 10,  'left_quantity' => 10 ]);
	    DB::table('shop_quantity')->insert(['id' => 7, 'product_id' => 7, 'all_quantity' => 10,  'left_quantity' => 10 ]);
	    DB::table('shop_quantity')->insert(['id' => 8, 'product_id' => 8, 'all_quantity' => 10,  'left_quantity' => 10 ]);
	    DB::table('shop_quantity')->insert(['id' => 9, 'product_id' => 9, 'all_quantity' => 10,  'left_quantity' => 10 ]);
	    DB::table('shop_quantity')->insert(['id' => 10, 'product_id' => 10, 'all_quantity' => 3,   'left_quantity' => 3 ]);
    }
}


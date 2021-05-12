<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;


use App\User;
use Laravel\Dusk\Chrome;


class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            //$browser->visit('/')
			$browser->visit('Laravel+Yii2_comment_widget/blog_Laravel/public')
                    ->assertSee('Laravel');
        });
    }
	
	
	 use DatabaseMigrations;
	
	/**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExampleDusk()
    {
        /*
		$user = factory(User::class)->create([
            'email' => 'taylor@laravel.com',
        ]);
		*/
		

        
        $this->browse(function (Browser $browser)  {
            //$browser->visit('/login') 
            $browser->visit('Laravel+Yii2_comment_widget/blog_Laravel/public/login')			
                    ->type('email', 'dimmmm931@gmail.com')
                    ->type('password', 'dimadima')
                    ->press('Login')
                    ->assertPathIs('Laravel+Yii2_comment_widget/blog_Laravel/public/home');
        });
		
		
		
		//works!!!!!
		$this->browse(function ($first, $second) {
             $first->loginAs(User::find(1))
              ->visit('/')
			  ->assertSee('Laravel');
        });
    }
	
	
	
}

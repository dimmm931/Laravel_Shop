<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\models\ShopSimple\ShopSimple;     //model to test

class MyTest extends TestCase
{
	
	
    /**WORKING!!!!!
     * A basic test example to test creating instance of class
     *
     * @return void
     */
    public function testBoxContents()
    {
        $box = new ShopSimple();
        //$this->assertTrue($box->has('table'));
        //$this->assertFalse($box->has('ball'));
		$this->assertClassHasAttribute('table', ShopSimple::class); // test if ShopSimple::class has attribute 'table'
		//$this->assertTrue(true);
    }
	
	
	
	
	 /**WORKING!!!!!
     * A test for function truncateTextProcessor($text, $maxLength), that crops a string by length and adds '...'
     * make sure it crops a string as designed
     * @return boolean
     */
    public function testTruncateTextProcessor()
    {
        $m = new ShopSimple();
		
		$source = "Shop title"; //initial string 
		$cropLength = 3;
		
		//checking that function crops text as expected
		$one = "Sho...";  //manually cropped string $source 
		$two = $m->truncateTextProcessor($source, $cropLength); //string $source cropped by function
		$this->assertEquals($one, $two); //Assertion, must be equal to return True (i.e 'Sho...' ==='Sho...' )
		
		//checking that cropped string length equals $cropLength + 3 , i.e + '...' 
		$x = str_split($two); // $two to array, i.e 'Sho...' to array('s', 'h', 'o', '.', '.', '.');
		//var_dump(count($x));
		$this->assertCount($cropLength +3, $x);  //not count($x), Php Counts by itself
		
    }
	
	
	
	
	
	
}

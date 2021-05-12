<?php
//Model for DB table {shop_quantity} that contains all products quantity
namespace App\models\ShopSimple;

use Illuminate\Database\Eloquent\Model;

class ShopQuantity extends Model
{
 

  /**
   * Connected DB table name.
   *
   * @var string
   */
  protected $table = 'shop_quantity';
  
  
  //protected $fillable = ['wpBlog_author', 'title', 'description', 'category_sel'];  //????? protected $fillable = ['wpBlog_author', 'wpBlog_text', 'wpBlog_author', 'wpBlog_category'];
  public $timestamps = false; //to override Error "Unknown Column 'updated_at'" that fires when saving new entry
  
  
  
  
}

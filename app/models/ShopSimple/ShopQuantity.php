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
  
  
    /**
     * Method to save new product quantity to DB (used in admin panel)
     * @param array $data
     * @param int $id
     * @return boolean
     *
     * 
     */
    function saveNewQuantity($data, $id)
    {
        $this->product_id    = $id; //$shop->shop_id;
		$this->all_quantity  = $data['product-quant'];
	    $this->left_quantity = $data['product-quant']; // it is new, so qunatity is the same not ++
		$this->all_updated   = date('Y-m-d H:i:s');
		if($this->save()){
            return true;
        } else {
            return false;
        }
    }
  
}

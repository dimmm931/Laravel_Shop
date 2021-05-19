<?php
//Used to validate when saving a new product to table {shop_simple}
//used in ShopPayPalSimple_AdminPanel /public function storeProduct(SaveNewProductRequest $request)
//Used for validation via Request Class, not via controller
namespace App\Http\Requests\ShopPaypalSimple_AdminPanel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule; //for in: validation
use App\models\ShopSimple\ShopCategories; //model for DB table {} for Range in: validation

class SaveNewProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     * 
     */
    public function authorize()
    {
        //return false; //return False will stop everything
		return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     * 
     */
    public function rules()
    {
		
		//getting all existing categories from DB {shop_categories}, get from DB only column "id". Used for validation in range {Rule::in(['admin', 'owner']) ]}, ['13', '17']
		$existingRoles = ShopCategories::select('categ_id')->get(); 
		$rolesList     = array(); // array to contain all roles id  from DB in format ['13', '17']
		foreach($existingRoles as $n){
			array_push($rolesList, $n->categ_id);	
		}
				
        return [
		    'product-name'     => ['required', 'string', 'min:3', 'unique:shop_simple,shop_title'],  //unique:tableName, columnName
            'product-desr'     => ['required', 'string', 'min:3'],   
			'product-price'    => ['required', 'string', 'min:3'],
			'product-price'    => ['required', 'numeric'], //numeric to accept float
			'product-type'     => ['required', 'string', 'min:3'], 
			'product-quant'    => ['required', 'integer', 'min:1' ],
            'product-category' => ['required', 'string', Rule::in($rolesList) ] , //integer];
			'image'            => ['required', /*'image',*/ 'mimes:jpeg,png,jpg,gif,svg', 'max:2048' ], // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',,
		];	
    }
	
	
	
	/**
     * Get the validation messages that apply to the request.
     * @return array
     * 
     */
    public function messages()
    {
        return [
	        'product-desr.required' => 'We need u to specify the name',
		    'product-desr.min'      => 'We kindly require more than 3 letters',
		    'product-category.in'   => 'Category has invalid value',
		    'image.image'           => 'Make sure it is an image',
		    'image.mimes'           => 'Must be .jpeg, .png, .jpg, .gif, .svg file. Max size is 2048',
		    'image.max'             => 'Sorry! Maximum allowed size for an image is 2MB',
		];
	}
	 
	 
	 
    /**
     * Return validation errors 
     * @param Validator $validator
     * 
     */
    public function withValidator(Validator $validator)
    {
	    if ($validator->fails()) {
            return redirect('/admin-add-product')->withInput()->with('flashMessageFailX', 'Validation Failed!!!' )->withErrors($validator);
        }
	}	 
}

<?php
//Used in ShopPayPalSimple_AdminPanel /public function updateStatusField(OrderStatusChangeRequest $request)
//Used for validation via Request Class, not via controller
namespace App\Http\Requests\ShopPaypalSimple_AdminPanel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class OrderStatusChangeRequest extends FormRequest
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
        return [
            'u_status'  => ['required', 'string', 'min:3'],  
			'u_orderID' => ['required', 'integer'],
        ];
		
    }
	
	
	/**
     * Get the validation messages that apply to the request.
     * @return array
     * 
     */
    public function messages()
    {
        // use trans instead on Lang 
        return [
            //'username.required' => Lang::get('userpasschange.usernamerequired'),
	        'u_status.required'  => 'We need u to specify the status',
		    'u_orderID.required' => 'We need u to specify the OrderID',
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
            return redirect('/admin-orders')->withInput()->with('flashMessageFailX', 'Validation Failed!!!' )->withErrors($validator);
        }
	} 	 
}

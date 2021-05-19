<?php
//Used in ShopPayPalSimpleController/public function pay1(ShopShippingRequest $request) for validation via Request Class, not via controller
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ShopShippingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return false; //return False will stop everything
		return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$RegExp_Phone = '/^[+]380[\d]{1,4}[0-9]+$/';
		
        return [
            'u_name'    => ['required', 'string', 'min:3'], 
			'u_address' => ['required', 'string', 'min:8'],
            'u_email'   => ['required', 'email' ],
            'u_phone'   => ['required', "regex: $RegExp_Phone" ],	
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
            //'username.required' => Lang::get('userpasschange.usernamerequired'),
	        'u_name.required' => 'We need u to specify your name',
	        'u_email.email'   => 'Give us real email',
	        'u_phone.regex'   => 'Phone must be in format +380....',
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
            return redirect('/checkOut2')->withInput()->with('flashMessageFailX', 'Validation Failed!!!' )->withErrors($validator);
        }
	}	 
}

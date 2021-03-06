<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UpdateCams extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255|min:2|regex:~^[a-zA-Z0-9_.-]*$~i',
            'cam_id'=>'int',
            'login' => 'required',
            'realpath' => ' required|max:255|min:2|regex:~^[a-zA-Z0-9_.-]*$~i',
            'password' => 'required',
            'alarmServerUrl' => 'required',
        ];
    }
}
<?php


namespace App\Http\Requests\Adverts;


use App\Entity\Adverts\Category;
use App\Entity\Region;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

/**
 * @property Category $category
 * @property Region $region
 */

class CreateRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $items = [];

        foreach ($this->category->allAttributes() as $attribute) {
            $rules = [
                $attribute->required ? 'required' : 'nullable',
            ];
            if ($attribute->isInteger()) {
                $rules[] = 'integer';
            } elseif ($attribute->isFloat()) {
                $rules[] = 'numeric';
            } else {
                $rules[] = 'string';
                $rules[] = 'max:255';
            }
            if ($attribute->isSelect()){
                $rules[] = Rule::in($attribute->variants);
            }
            $items['attribute.' . $attribute->id] = $rules;
        }

        /*$validator = Validator::make($this->category->allAttributes(),$items);
        if($validator->errors()) {
//            die(var_dump(Request::post('attributes')));
            die(var_dump($validator->messages()));
        }*/

        return array_merge([
            'title' => 'required|string',
            'content' => 'required|string',
            'price' => 'required|integer',
            'address' => 'required|string',
        ], $items);
    }
}

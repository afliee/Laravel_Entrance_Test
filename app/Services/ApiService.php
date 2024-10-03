<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class ApiService
{
    abstract public function show(Request $request);
    abstract public function store(Request $request, array $options = []);
    abstract public function update(Request $request, array $options = []);
    abstract public function delete(Request $request);
//    abstract public function validate(Request $request, array $rules);
    public function validate(Request $request, array $rules) {
        $params = $request->all();
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => $validator->errors()
            ];
        }
    }
}

<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class UserService extends ApiService
{
    public function show(Request $request)
    {
        $data = $request->all();
        $rules = [
            'id' => 'required|integer',
        ];

        $validate = $this->validate($request, $rules);

        if (isset($validate)) {
            return $validate;
        }

        $user = User::where('id', $data['id'])->first();

        if (empty($user)) {
            return [
                'status' => false,
                'message' => 'User not found'
            ];
        }

        return [
            'status' => true,
            'user' => $user
        ];
    }

    public function store(Request $request, array $options = [])
    {
//         get data from form
        if (empty($options)) {
            $data = $request->all();
            $validate = $this->validate($request, [
                'name' => 'required|string',
                'email' => 'required|email',
            ]);

            if (isset($validate)) {
                return $validate;
            }
        }
        // save data to database
//        check in options if have user key then update else create
        if (isset($options['user'])) {
            $userOptions = $options['user'];

            $user = User::updateOrCreate([
                'email' => $userOptions->email
            ], [
                'name' => $userOptions->name,
                'avatar' => $userOptions->avatar,
                'email_verified_at' => now(),
                'google_id' => $userOptions->id,
            ]);
        } else {
            $user = User::create($data);
        }

        return [
            'status' => true,
            'user' => $user
        ];
    }

    public function update(Request $request, array $options = []) : array
    {
        $data = $request->all();
        $validate = $this->validate($request, $options['rules']);

        if (isset($validate)) {
            return $validate;
        }

        try {
            $user = User::where('id', $data['id'])->first();
            if (empty($user)) {
                return [
                    'status' => false,
                    'message' => 'User not found'
                ];
            }

            $user->update($data);

            return [
                'status' => true,
                'user' => $user
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        // TODO: Implement delete() method.
    }

}

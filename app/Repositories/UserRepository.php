<?php

namespace App\Repositories;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Storage;


class UserRepository
{
    use ApiResponse;


    public function findByEmail($email)
    {
        return User::where('email', $email)
            ->firstOrFail();
    }


    public function update(User $user, $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Array Updates
        |--------------------------------------------------------------------------
        |
        | مستخدمة من أماكن أخرى مثل تفعيل البريد وتغيير كلمة المرور.
        |
        */

        if (is_array($request)) {

            $user->update($request);

            return $user->fresh();
        }


        /*
        |--------------------------------------------------------------------------
        | Profile Information
        |--------------------------------------------------------------------------
        */

        $data = $request->only([
            'name',
            'email',
            'phone',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Email changed
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['email']) &&
            $data['email'] !== $user->email
        ) {
            $data['email_verified_at'] = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Profile Image
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile('image') &&
            $request->file('image')->isValid()
        ) {

            $oldImage = $user->getRawOriginal('image');


            $filename =
                uniqid('user_', true)
                . '.'
                . $request->file('image')->extension();


            $path = Storage::disk('public')
                ->putFileAs(
                    'users',
                    $request->file('image'),
                    $filename,
                    [
                        'visibility' => 'public'
                    ]
                );


            $data['image'] = $path;


            /*
            |--------------------------------------------------------------------------
            | Delete old image
            |--------------------------------------------------------------------------
            */

            if (
                $oldImage &&
                Storage::disk('public')->exists($oldImage)
            ) {
                Storage::disk('public')->delete($oldImage);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $user->update($data);


        return $user->fresh();
    }


    public function deleteUserToken($user)
    {
        if ($user->tokens()->exists()) {
            $user->tokens()->delete();
        }
    }


    public function createToken($user)
    {
        return $user
            ->createToken(
                'user_token',
                ['user']
            )
            ->plainTextToken;
    }


    public function findById($id)
    {
        return User::findOrFail($id);
    }
}

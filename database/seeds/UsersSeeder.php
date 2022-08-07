<?php


use App\Http\Controllers\Auth\RegisterController;


use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;

class UsersSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user['name'] = 'test';
        $user['email'] = 'test@mail.com';
        $user['password'] = bcrypt('test');
        User::create($user);
    }
}

<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
            'name'=>"Wayan SUkerta",
            'email'=>'hello@wayansukerta.com',
            'password'=> Hash::make('mudita21'),
            'attemp'=>0
            ]
        ]);
    }
}

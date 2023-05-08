<?php

namespace W360\SecureData\Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use W360\SecureData\Models\Admin;
use W360\SecureData\Models\User;
use W360\SecureData\Models\Web;
use W360\SecureData\Tests\TestCase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_and_get_database_mysql(){

        $name = Factory::create()->firstName;
        User::create([
            'first_name' => $name,
            'last_name' => Factory::create()->lastName,
            'email' => Factory::create()->email,
            'identifier' => '110101001',
            'salary' => Factory::create()->randomFloat(10),
            'status' => true,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10)
        ]);

        $newUser = User::where('first_name', $name)->first();
        $this->assertEquals($name, $newUser->first_name);
    }

    /**
     * @test
     */
    public function relations_many_to_many_in_database_mysql(){

        $userName = Factory::create()->firstName;
        $adminName = Factory::create()->firstName;
        $pivotName = Factory::create()->firstName;

        $user = User::create([
            'first_name' => $userName,
            'last_name' => Factory::create()->lastName,
            'email' => Factory::create()->email,
            'identifier' => '110101001',
            'salary' => Factory::create()->randomFloat(10),
            'status' => true,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10)
        ]);

        $admin = Admin::create([
            'first_name' => $adminName,
            'last_name' => Factory::create()->lastName,
            'email' => Factory::create()->email,
            'identifier' => '110101001',
            'salary' => Factory::create()->randomFloat(10),
            'status' => true,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10)
        ]);

        Web::create([
            'name' => $pivotName,
            'url' => Factory::create()->url,
            'status' => true,
            'user_id' => $user->id,
            'admin_id' => $admin->id
        ]);

        $admin = Admin::find($admin->id);
        $user = $admin->users->first();
        $this->assertEquals($adminName, $admin->first_name);
        $this->assertEquals($userName, $user->first_name);
        $this->assertEquals($pivotName, $user->pivot->name);
    }

    /**
     * @test
     */
    public function create_in_database_mysql(){

        $adminName = Factory::create()->firstName;

        $user = User::create([
            'first_name' => $adminName,
            'last_name' => Factory::create()->lastName,
            'email' => Factory::create()->email,
            'identifier' => '198282828',
            'salary' => Factory::create()->randomFloat(10),
            'status' => true,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10)
        ]);

        $this->assertEquals($user->first_name, $adminName);
    }


    /**
     * @test
     */
    public function where_like_model_in_database_mysql(){

        User::create([
            'first_name' => 'T00000',
            'last_name' => Factory::create()->lastName,
            'email' => Factory::create()->email,
            'identifier' => '198282828',
            'salary' => Factory::create()->randomFloat(10),
            'status' => true,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10)
        ]);

        $object = User::where('first_name','LIKE', 'T0%')->first();
        $this->assertEquals($object->first_name, 'T00000');
    }

    /**
     * @test
     */
    public function where_and_pluck_in_database_mysql(){
        $validList = ['11111111', '11111112', '11111113', '11111114'];
        $noValidList = ['hole', 'history', 'closet'];
        $insertNames = array_merge($noValidList, $validList);
        foreach ($insertNames as $insertName) {
            Admin::create([
                'first_name' => $insertName,
                'last_name' => Factory::create()->lastName,
                'email' => Factory::create()->email,
                'identifier' => '198282828',
                  'salary' => Factory::create()->randomFloat(10),
                'status' => true,
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10)
            ]);
        }

        $admin = Admin::where('first_name','LIKE', '111111%')->groupBy('first_name')->pluck('first_name');
        $array = $admin->toArray();
        $this->assertEquals($validList, $array);
    }

    /**
     * @test
     */
    public function where_between_in_database_mysql(){
        $insertNames = ['Mathilde', 'Granville', 'Jasen', 'Maya', 'Five', 'Six', 'Seven'];
        foreach ($insertNames as $insertName) {
            Admin::create([
                'first_name' => $insertName,
                'last_name' => Factory::create()->lastName,
                'email' => Factory::create()->email,
                'identifier' => '198282828',
                'salary' => Factory::create()->randomFloat(10),
                'status' => true,
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10)
            ]);
        }

        $admins = Admin::whereBetween('id',[1,5])->get();
        $this->assertCount(5, $admins);
    }

    /**
     * @test
     */
    public function pagination_in_database_mysql(){
        $insertNames = ['Mathilde', 'Granville', 'Jasen', 'Maya', 'Five', 'Six', 'Seven'];
        foreach ($insertNames as $insertName) {
            Admin::create([
                'first_name' => $insertName,
                'last_name' => Factory::create()->lastName,
                'email' => Factory::create()->email,
                'identifier' => '198282828',
                'salary' => Factory::create()->randomFloat(10),
                'status' => true,
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10)
            ]);
        }
        $admin = Admin::paginate(3);
        $this->assertCount(3, $admin);
    }

    /**
     * @test
     */
    public function update_model_in_database_mysql(){

        $user = User::first();
        $insertName = 'UpdateNameMsql';
        $user->update([
            'first_name' => $insertName,
            'last_name' => Factory::create()->lastName,
            'email' => Factory::create()->email,
            'identifier' => '198282828',
            'salary' => Factory::create()->randomFloat(10),
            'status' => true,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10)
        ]);

        $user = User::first();
        $this->assertEquals($user->first_name, $insertName);
    }

}
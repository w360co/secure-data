<?php

namespace W360\SecureData\Tests\Feature;

use Faker\Factory;
use Illuminate\Support\Str;
use W360\SecureData\Models\Admin;
use W360\SecureData\Models\User;
use W360\SecureData\Models\Web;
use W360\SecureData\Tests\TestCase;

class DatabaseTest extends TestCase
{

    /**
     * @test
     */
    public function where_model_in_database_mysql(){
        $object = User::where('first_name', 'LIKE','jose')->first();
        $this->assertEquals('jose', $object->first_name);
    }

    /**
     * @test
     */
    public function generate_factory_in_database_mysql(){
        $user = User::create([
            'first_name' => Factory::create()->firstName,
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
            'first_name' => Factory::create()->firstName,
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
            'name' => Factory::create()->domainName,
            'url' => Factory::create()->url,
            'status' => true,
            'user_id' => $user->id,
            'admin_id' => $admin->id
        ]);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function insert_model_in_database_mysql(){

        User::updateOrInsert([
            'first_name' => 'lokillo',
        ],[
            'first_name' => 'perro',
            'last_name' => 'JAJAJA',
            'email' => 'elbert.toua@w360.co',
            'identifier' => '110101001',
            'salary' => 30000000,
            'status' => true,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10)
        ]);

        $object = User::where('first_name', 'perro')->first();
        $this->assertEquals($object->first_name, 'perro');
        $this->assertTrue(true);
    }


    /**
     * @test
     */
    public function create_model_in_database_mysql(){

        User::create([
            'first_name' => 'T00000',
            'last_name' => 'Otro',
            'email' => 'lotousaa@w360.co',
            'identifier' => '92121991',
            'salary' => 93000209,
            'status' => true,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10)
        ]);

        $object = User::where('first_name','LIKE', 'T0%')->first();
        print_r($object);
        $this->assertEquals($object->first_name, 'T00000');
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function select_pluck_in_database_mysql(){
        $admin = Admin::where('id',4)->first();
        $uno = $admin->users->first();
        print_r($uno->pivot->url);
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function single_select_in_database_mysql(){
        $admin = Admin::whereBetween('id',[1,5])->get();
        print_r($admin);
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function select_pagination_in_database_mysql(){
        $admin = Admin::paginate(3);
        print_r($admin->count());
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function update_model_in_database_mysql(){

        User::where('id',1)->update([
            'first_name' => 'otro',
            'last_name' => 'JAJAJA',
            'email' => 'elbert.toua@w360.co',
            'identifier' => '110101001',
            'salary' => 30000000,
            'status' => true,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10)
        ]);

        $object = User::where('first_name', 'otro')->first();
        $this->assertEquals($object->first_name, 'otro');
        $this->assertTrue(true);
    }

}
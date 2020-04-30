<?php

namespace Techneved\Admin\Login\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase 
{
    use RefreshDatabase;

    /** @test */
    public function create_admin_from_factory()
    {
        $admin = $this->createAdmin();
        $this->assertEquals(1, $admin->count());
        $this->assertDatabaseHas('admins',[
            'email'         => $admin->email,
            'first_name'    => $admin->first_name,
            'last_name'     => $admin->last_name,
            'mobile'        => $admin->mobile,
        ]);
    }
}
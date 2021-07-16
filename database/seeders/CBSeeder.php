<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Please wait updating the data...');

        $this->call('Cms_usersSeeder');
        $this->call('Cms_modulsSeeder');
        $this->call('Cms_privilegesSeeder');
        $this->call('Cms_privileges_rolesSeeder');
        $this->call('Cms_settingsSeeder');
        $this->call('CmsEmailTemplates');

        $this->command->info('Updating the data completed !');
    }
}

class CmsEmailTemplates extends Seeder
{
    public function run()
    {
        DB::table('cms_email_templates')->insert([
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Email Template Forgot Password Backend',
                'slug' => 'forgot_password_backend',
                'content' => '<p>Hi,</p><p>Someone requested forgot password, here is your new password : </p><p>[password]</p><p><br></p><p>--</p><p>Regards,</p><p>Admin</p>',
                'description' => '[password]',
                'from_name' => 'System',
                'from_email' => 'system@pinisi-elektra.com',
                'cc_email' => null,
            ]);
    }
}

class Cms_settingsSeeder extends Seeder
{
    public function run()
    {
    }
}

class Cms_privileges_rolesSeeder extends Seeder
{
    public function run()
    {
    }
}

class Cms_privilegesSeeder extends Seeder
{
    public function run()
    {
    }
}

class Cms_modulsSeeder extends Seeder
{
    public function run()
    {

        /*
            1 = Public
            2 = Setting
        */
    }
}

class Cms_usersSeeder extends Seeder
{
    public function run()
    {
    }
}

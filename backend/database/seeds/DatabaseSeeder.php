<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ollyx = \App\User::firstOrCreate(
            ['email' => 'gabrielbuzziv@gmail.com'],
            ['name' => 'Ollyx', 'password' => bcrypt('s3mc0ntr0l3')]
        );

        $this->command->info("{$ollyx->name} is Ready");

        $lennart = \App\User::firstOrCreate(
            ['email' => 'wheatlord123@hotmail.com'],
            ['name' => 'Lennart the smith', 'password' => bcrypt('s3mc0ntr0l3')]
        );

        $this->command->info("{$lennart->name} is Ready");

        $thoredo = \App\User::firstOrCreate(
            ['email' => 'jurgenstegeman@live.nl'],
            ['name' => 'Thoredo Hous', 'password' => bcrypt('s3mc0ntr0l3')]
        );

        $this->command->info("{$thoredo->name} is Ready");


        $ferox = \App\User::firstOrCreate(
            ['email' => 'ferox1989@hotmail.com'],
            ['name' => 'Ferox Smeden', 'password' => bcrypt('s3mc0ntr0l3')]
        );

        $this->command->info("{$ferox->name} is Ready");

        $wamaster = \App\User::firstOrCreate(
            ['email' => 'fabianb1990@hotmail.com'],
            ['name' => 'Wa Master', 'password' => bcrypt('s3mc0ntr0l3')]
        );

        $this->command->info("{$wamaster->name} is Ready");


        // $this->call(UsersTableSeeder::class);
    }
}

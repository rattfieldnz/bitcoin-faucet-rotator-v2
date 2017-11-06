<?php

use App\Models\AlertIcon;

class AlertIconsSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        AlertIcon::truncate();
        Schema::enableForeignKeyConstraints();
        $data = $this->csv_to_array(base_path() . '/database/seeds/csv_files/alert_icons.csv', ';');

        foreach ($data as $d) {
            $icon = new AlertIcon([
                'icon_class' => Purifier::clean($d['icon_class'], 'generalFields')
            ]);
            $icon->save();
        }

        $this->command->info(
            "Finished seeding " . count(AlertIcon::all()) . " alert icons."
        );
    }
}

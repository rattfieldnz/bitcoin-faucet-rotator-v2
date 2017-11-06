<?php

use App\Models\AlertType;
use Illuminate\Database\Seeder;

class AlertTypesSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        AlertType::truncate();
        Schema::enableForeignKeyConstraints();
        $data = $this->csv_to_array(base_path() . '/database/seeds/csv_files/alert_types.csv', ';');

        foreach ($data as $d) {
            $icon = new AlertType([
                'name' => Purifier::clean($d['name'], 'generalFields'),
                'description' => Purifier::clean($d['description'], 'generalFields'),
                'bootstrap_alert_class' => Purifier::clean($d['bootstrap_alert_class'], 'generalFields')
            ]);
            $icon->save();
        }

        $this->command->info(
            "Finished seeding " . count(AlertType::all()) . " alert types."
        );
    }
}

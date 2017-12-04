<?php

use App\Models\Alert;
use App\Models\AlertIcon;
use App\Models\AlertType;

class AlertSeeder extends BaseSeeder
{
    public function run()
    {
        $this->createAlerts(5);
    }

    private function createAlerts($count = 1)
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < $count; $i++) {
            $alertTypeIds = array_values(AlertType::all()->pluck('id')->toArray());
            $alertIconIds = array_values(AlertIcon::all()->pluck('id')->toArray());

            $title = $faker->text(50);
            $summary = $faker->text(255);
            $content = '<p>' . $faker->paragraph(5) . '</p>' .
                '<p>' . $faker->paragraph(5) . '</p>' .
                '<p>' . $faker->paragraph(5) . '</p>' .
                '<p>' . $faker->paragraph(5) . '</p>' .
                '<p>' . $faker->paragraph(5) . '</p>';
            $keywords = implode(', ', array_unique($faker->words(random_int(5, 15))));
            $alertTypeId = $faker->randomElement($alertTypeIds);
            $alertIconId = $faker->randomElement($alertIconIds);

            $alert = new Alert([
                'title' => $title,
                'summary' => $summary,
                'content' => $content,
                'keywords' => $keywords,
                'alert_type_id' => $alertTypeId,
                'alert_icon_id' => $alertIconId,
            ]);
            $alert->save();

            $this->command->info("Alert '" . $alert->title . "' was seeded.");
        }
    }
}

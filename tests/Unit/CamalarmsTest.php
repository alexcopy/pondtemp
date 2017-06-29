<?php


namespace App\Http\Models;


use Faker\Factory as Faker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CamalarmsTest extends TestCase
{

    protected $faker;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
        $this->faker = Faker::create();
    }


    public function testChemicalAnalysis()
    {
        foreach (range(1, 10) as $item) {
            factory(Camalarms::class)->create();
        }
        $resutl = Camalarms::all()->toArray();
        $this->assertCount(10, $resutl);
    }


    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    public function testParseJsonAndWrite()
    {

        $response = [
            "result" => 2,
            "value" => [
                [
                    "id" => $this->faker->unique()->unixTime,
                    "alarm_msg" => $this->faker->text(10),
                    "alarm_time" => $this->faker->date('Y-m-d H:i:s'),
                    "has_position" => $this->faker->boolean,
                    "version_num" => $this->faker->randomDigit,
                    "alarm_image" => $this->faker->text(100),
                    "alarm_type" => $this->faker->randomDigit,
                    "dev_id" => $this->faker->unixTime,
                    "alarm_id" => $this->faker->unique()->unixTime,
                    "alarm_level" => $this->faker->randomDigit,
                    "last_fresh_time" => $this->faker->unixTime,
                    "image_id" => $this->faker->randomDigit,
                    "ip" => $this->faker->ipv4
                ],
                [
                    "id" => $this->faker->unique()->unixTime,
                    "alarm_msg" => $this->faker->text(10),
                    "alarm_time" => $this->faker->date('Y-m-d H:i:s'),
                    "has_position" => $this->faker->boolean,
                    "version_num" => $this->faker->randomDigit,
                    "alarm_image" => $this->faker->text(100),
                    "alarm_type" => $this->faker->randomDigit,
                    "dev_id" => $this->faker->unixTime,
                    "alarm_id" => $this->faker->unique()->unixTime,
                    "alarm_level" => $this->faker->randomDigit,
                    "last_fresh_time" => $this->faker->unixTime,
                    "image_id" => $this->faker->randomDigit,
                    "ip" => $this->faker->ipv4
                ]
            ]
        ];

        $json = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode($response), true);

        foreach ($json['value'] as $value) {
            if (isset($value['id'])) {
                $msgid = $value['id'];
                $value['msgid'] = $msgid;
                unset($value['id']);
            }

             Camalarms::create($value);
        }
        $resutl = Camalarms::all()->toArray();
        $this->assertCount(2, $resutl);
    }
}

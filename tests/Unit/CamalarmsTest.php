<?php


namespace App\Http\Models;


use Faker\Factory as Faker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CamalarmsTest extends TestCase
{

    protected $faker;

    public function setUp():void
    {
        parent::setUp();
        Artisan::call('mysql:createdb', ['name' => 'pond_testing']);
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


    public function tearDown() :void
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

    public function testJsonWriteDb()
    {
        $x='{"result":5,"value":[{"id":170817106,"alarm_msg":"Camera","alarm_time":"2017-7-1 0:37:36","has_position":false,"version_num":1,"alarm_image":"","alarm_type":200,"dev_id":32177699,"alarm_id":170817106,"alarm_level":400,"last_fresh_time":1498840654925,"image_id":0,"ip":"116.62.31.134"},{"id":170814838,"alarm_msg":"Camera","alarm_time":"2017-7-1 0:35:34","has_position":false,"version_num":1,"alarm_image":"","alarm_type":200,"dev_id":32177699,"alarm_id":170814838,"alarm_level":400,"last_fresh_time":1498840533254,"image_id":0,"ip":"116.62.31.134"},{"id":170812486,"alarm_msg":"Camera","alarm_time":"2017-7-1 0:33:31","has_position":false,"version_num":1,"alarm_image":"","alarm_type":200,"dev_id":32177699,"alarm_id":170812486,"alarm_level":400,"last_fresh_time":1498840410364,"image_id":0,"ip":"116.62.31.134"},{"id":170810493,"alarm_msg":"Camera","alarm_time":"2017-7-1 0:31:45","has_position":false,"version_num":1,"alarm_image":"","alarm_type":200,"dev_id":32177699,"alarm_id":170810493,"alarm_level":400,"last_fresh_time":1498840304099,"image_id":0,"ip":"116.62.31.134"},{"id":170806310,"alarm_msg":"Camera","alarm_time":"2017-7-1 0:28:6","has_position":false,"version_num":1,"alarm_image":"","alarm_type":200,"dev_id":32177699,"alarm_id":170806310,"alarm_level":400,"last_fresh_time":1498840084647,"image_id":0,"ip":"116.62.31.134"}]} ';
        Camalarms::writeJsonToDb($x);
        $resutl = Camalarms::get(['alarm_id'])->toArray();
        $this->assertCount(5, $resutl);
        // Another test to check for duplications(shouldn't be added anymore to db)
        Camalarms::writeJsonToDb($x);
        $this->assertCount(5, $resutl);
        $x='{"result":0}';
        Camalarms::writeJsonToDb($x);
    }

}

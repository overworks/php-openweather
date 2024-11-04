<?php

namespace Minhyung\OpenWeather\Tests;

use Minhyung\OpenWeather\OneCall;
use Minhyung\OpenWeather\OpenWeather;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(OneCall::class)]
class OneCallTest extends TestCase
{
    /** @var \Minhyung\OpenWeather\OpenWeather */
    private $weather;
    /** @var \Minhyung\OpenWeather\OneCall */
    private $onecall;

    protected function setUp(): void
    {
        $apiKey = $_ENV['OPENWEATHER_API_KEY'];
        if (empty($apiKey)) {
            $this->markTestSkipped();
        }

        $this->weather = new OpenWeather($apiKey);
        $this->onecall = $this->weather->onecall();
    }

    #[TestWith([37.566, 126.9784, 'Asia/Seoul'])]
    public function testForecast(float $lat, float $lon, string $timezone): void
    {
        $result = $this->onecall->forecast($lat, $lon);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('timezone', $result);
        $this->assertEquals($timezone, $result['timezone']);
        $this->assertArrayHasKey('current', $result);
        $this->assertArrayHasKey('daily', $result);
    }

    #[TestWith([37.566, 126.9784, 'Asia/Seoul'])]
    public function testTimemachine(float $lat, float $lon, string $timezone): void
    {
        $dt = time();
        $result = $this->onecall->timemachine($lat, $lon, $dt);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('timezone', $result);
        $this->assertEquals($timezone, $result['timezone']);
        $this->assertArrayHasKey('data', $result);
    }

    #[TestWith([37.566, 126.9784])]
    public function testDaySummary(float $lat, float $lon): void
    {
        $date = date('Y-m-d');
        $result = $this->onecall->day_summary($lat, $lon, $date);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('date', $result);
        $this->assertEquals($date, $result['date']);
    }

    #[TestWith([37.566, 126.9784])]
    public function testOverview(float $lat, float $lon): void
    {
        $result = $this->onecall->overview($lat, $lon);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('weather_overview', $result);
    }
}

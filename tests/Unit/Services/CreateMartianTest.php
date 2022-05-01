<?php

namespace Tests\Unit\Services;

use App\Models\Martian;
use App\Services\CreateMartianService;
use PHPUnit\Framework\TestCase;

class CreateMartianTest extends TestCase
{
    /**
     * Test creating martian
     *
     * @return void
     */
    public function test_that_creating_martian_returns_martian_instance()
    {
        $data = [
            'name' => 'Kenneth',
            'age' => 60,
            'gender' => 'M',
            'supplies' => [
                ['id' => 1, 'quantity' => 1],
                ['id' => 2, 'quantity' => 2]
            ]
        ];
        $return = CreateMartianService::createMartian($data);
        $this->assertEquals(Martian::class, get_class($return));
    }
}

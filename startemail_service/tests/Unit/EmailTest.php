<?php

namespace Tests\Unit;

use App\Models\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function test_fillable()
    {
        $email = new Email();
        $this->assertEquals(['name', 'email', 'subject', 'status', 'service'],$email->getFillable());
    }
}

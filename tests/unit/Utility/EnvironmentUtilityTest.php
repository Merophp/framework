<?php
use PHPUnit\Framework\TestCase;

use Merophp\Framework\Utility\EnvironmentUtility;

/**
 * @covers \Merophp\Framework\Utility\EnvironmentUtility
 */
final class EnvironmentUtilityTest extends TestCase
{
    public function testIsCli()
    {
        $this->assertTrue(EnvironmentUtility::isCli());
    }
}

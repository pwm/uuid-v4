<?php
declare(strict_types=1);

namespace Pwm\UuidV4;

use PHPUnit\Framework\TestCase;
use Pwm\UuidV4\Exception\InvalidUuidV4;
use Pwm\UuidV4\Exception\InvalidUuidV4Seed;
use Throwable;
use function in_array;
use function random_bytes;

final class UuidV4Test extends TestCase
{
    /**
     * @test
     */
    public function it_creates_from_a_valid_uuid_v4_string(): void
    {
        self::assertInstanceOf(UuidV4::class, new UuidV4('30313233-3435-4637-b839-616263646566'));
    }

    /**
     * @test
     */
    public function it_casts_to_string(): void
    {
        $uuidV4String = '30313233-3435-4637-b839-616263646566';
        self::assertSame($uuidV4String, (string)new UuidV4($uuidV4String));
    }

    /**
     * @test
     */
    public function it_creates_from_valid_seed(): void
    {
        $uuidV4 = UuidV4::createFrom(random_bytes(16));
        $uuidV4String = (string)$uuidV4;

        self::assertInstanceOf(UuidV4::class, $uuidV4);
        self::assertSame('4', $uuidV4String[14]); // version check
        self::assertTrue(in_array($uuidV4String[19], ['8', '9', 'a', 'b'], true)); // variant check
    }

    /**
     * @test
     */
    public function it_creates_the_same_uuid_v4_from_the_same_seed(): void
    {
        $seed = random_bytes(16);

        self::assertSame((string)UuidV4::createFrom($seed), (string)UuidV4::createFrom($seed));
    }

    /**
     * @test
     */
    public function it_throws_on_invalid_uuid_v4_strings(): void
    {
        $invalidUuidV4Strings = [
            '',
            '1234',
            '30313233-3435-3637-b839-616263646566', // invalid version
            '30313233-3435-4637-c839-616263646566', // invalid variant
        ];

        foreach ($invalidUuidV4Strings as $invalidUuidV4String) {
            try {
                new UuidV4($invalidUuidV4String);
                self::assertTrue(false);
            } catch (Throwable $e) {
                self::assertInstanceOf(InvalidUuidV4::class, $e);
            }
        }
    }

    /**
     * @test
     */
    public function it_throws_on_invalid_seed_lengths(): void
    {
        $invalidSeedGen = function () {
            yield ''; // length(seed) === 0
            for ($i = 1; $i < 16; $i++) {
                yield random_bytes($i); // 1 <= length(seed) < 16
            }
            yield random_bytes(17); // length(seed) > 16
        };

        foreach ($invalidSeedGen() as $invalidSeed) {
            try {
                UuidV4::createFrom($invalidSeed);
                self::assertTrue(false);
            } catch (Throwable $e) {
                self::assertInstanceOf(InvalidUuidV4Seed::class, $e);
            }
        }
    }
}

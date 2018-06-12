<?php
declare(strict_types=1);

namespace Pwm\UuidV4;

use Pwm\UuidV4\Exception\InvalidUuidV4;
use Pwm\UuidV4\Exception\InvalidUuidV4Seed;
use function bin2hex;
use function chr;
use function ord;
use function preg_match;
use function str_split;
use function strlen;
use function vsprintf;

/**
 * Implementation of the RFC 4122 UUID version 4 (variant 1) data type
 */
class UuidV4
{
    public const PATTERN = '#^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$#i';

    /** @var string */
    protected $value;

    public function __construct(string $value)
    {
        if (preg_match(self::PATTERN, $value) !== 1) {
            throw new InvalidUuidV4('Not a valid UUID V4.');
        }

        $this->value = $value;
    }

    public static function createFrom(string $seed)
    {
        if (strlen($seed) !== 16) {
            throw new InvalidUuidV4Seed('Seed must be exactly 16 bytes.');
        }

        return new static(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(self::setFixedBits($seed)), 4)));
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private static function setFixedBits(string $seed): string
    {
        // Set the 4 most significant bits of the 7th character to 0b0100, restricting its possible
        // hex values to 0x40 - 0x4f, thus fixing the 13th character of the resulting UUID to 4.
        // This sets the UUID's version to 4, as per RFC 4122 4.1.3
        $seed[6] = chr(ord($seed[6]) & 0b00001111 | 0b01000000);

        // Set the 2 most significant bits of the 9th character to 0b10, restricting its possible
        // hex values to 0x80 - 0xbf, thus constraining the 17th character of the resulting UUID to 8, 9, A or B.
        // This sets the UUID's variant to 1, as per RFC 4122 4.1.1
        $seed[8] = chr(ord($seed[8]) & 0b00111111 | 0b10000000);

        return $seed;
    }
}

<?php

namespace W360\SecureData\Support;

class Hex
{

    /**
     * Convert a binary string into a hexadecimal string without cache-timing
     * leaks
     * @param string $binString
     * @return string
     */
    public static function encode(
        string $binString
    ): string {
        $hex = '';
        $len = Binary::safeStrlen($binString);
        for ($i = 0; $i < $len; ++$i) {
            /** @var array<int, int> $chunk */
            $chunk = \unpack('C', $binString[$i]);
            $c = $chunk[1] & 0xf;
            $b = $chunk[1] >> 4;

            $hex .= \pack(
                'CC',
                (87 + $b + ((($b - 10) >> 8) & ~38)),
                (87 + $c + ((($c - 10) >> 8) & ~38))
            );
        }
        return $hex;
    }

}
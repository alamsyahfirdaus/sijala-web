<?php

declare(strict_types=1);

namespace App\Services\Agora;

/**
 * -----------------------------------------------------------------------------
 * UTIL CLASS
 * -----------------------------------------------------------------------------
 * Helper class untuk proses packing dan unpacking data binary
 * yang digunakan oleh Agora AccessToken2.
 *
 * Jangan mengubah algoritma packing karena akan menyebabkan
 * token Agora menjadi invalid.
 * -----------------------------------------------------------------------------
 */
class Util
{
    /**
     * -------------------------------------------------------------------------
     * ASSERT EQUAL
     * -------------------------------------------------------------------------
     */
    public static function assertEqual(
        mixed $expected,
        mixed $actual
    ): void {

        $debug = debug_backtrace();

        $info =
            "\n- File:" . basename($debug[1]['file']) .
            ", Func:" . $debug[1]['function'] .
            ", Line:" . $debug[1]['line'];

        if ($expected !== $actual) {

            echo $info .
                "\n  Assert failed" .
                "\n    Expected : " . $expected .
                "\n    Actual   : " . $actual;

            return;
        }

        echo $info . "\n  Assert ok";
    }

    /**
     * -------------------------------------------------------------------------
     * PACK UINT16
     * -------------------------------------------------------------------------
     */
    public static function packUint16(int $value): string
    {
        return pack('v', $value);
    }

    /**
     * -------------------------------------------------------------------------
     * UNPACK UINT16
     * -------------------------------------------------------------------------
     */
    public static function unpackUint16(string &$data): int
    {
        $result = unpack(
            'v',
            substr($data, 0, 2)
        );

        $data = substr($data, 2);

        return (int) $result[1];
    }

    /**
     * -------------------------------------------------------------------------
     * PACK UINT32
     * -------------------------------------------------------------------------
     */
    public static function packUint32(int $value): string
    {
        return pack('V', $value);
    }

    /**
     * -------------------------------------------------------------------------
     * UNPACK UINT32
     * -------------------------------------------------------------------------
     */
    public static function unpackUint32(string &$data): int
    {
        $result = unpack(
            'V',
            substr($data, 0, 4)
        );

        $data = substr($data, 4);

        return (int) $result[1];
    }

    /**
     * -------------------------------------------------------------------------
     * PACK INT16
     * -------------------------------------------------------------------------
     */
    public static function packInt16(int $value): string
    {
        return pack('s', $value);
    }

    /**
     * -------------------------------------------------------------------------
     * UNPACK INT16
     * -------------------------------------------------------------------------
     */
    public static function unpackInt16(string &$data): int
    {
        $result = unpack(
            's',
            substr($data, 0, 2)
        );

        $data = substr($data, 2);

        return (int) $result[1];
    }

    /**
     * -------------------------------------------------------------------------
     * PACK STRING
     * -------------------------------------------------------------------------
     */
    public static function packString(string $value): string
    {
        return self::packUint16(strlen($value))
            . $value;
    }

    /**
     * -------------------------------------------------------------------------
     * UNPACK STRING
     * -------------------------------------------------------------------------
     */
    public static function unpackString(
        string &$data
    ): string {

        $length = self::unpackUint16($data);

        $result = unpack(
            'C*',
            substr($data, 0, $length)
        );

        $data = substr(
            $data,
            $length
        );

        if (empty($result)) {
            return '';
        }

        return implode(
            array_map(
                'chr',
                $result
            )
        );
    }

    /**
     * -------------------------------------------------------------------------
     * PACK MAP UINT32
     * -------------------------------------------------------------------------
     */
    public static function packMapUint32(
        array $data
    ): string {

        ksort($data);

        $binary = '';

        foreach ($data as $key => $value) {

            $binary .=
                self::packUint16((int) $key) .
                self::packUint32((int) $value);
        }

        return self::packUint16(count($data))
            . $binary;
    }

    /**
     * -------------------------------------------------------------------------
     * UNPACK MAP UINT32
     * -------------------------------------------------------------------------
     */
    public static function unpackMapUint32(
        string &$data
    ): array {

        $length = self::unpackUint16($data);

        $result = [];

        for ($i = 0; $i < $length; $i++) {

            $key = self::unpackUint16($data);

            $value = self::unpackUint32($data);

            $result[$key] = $value;
        }

        return $result;
    }
}
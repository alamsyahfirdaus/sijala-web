<?php

declare(strict_types=1);

namespace App\Services\Agora;

require_once __DIR__ . '/Util.php';
require_once __DIR__ . '/Services.php';

/**
 * -----------------------------------------------------------------------------
 * AGORA ACCESS TOKEN 2
 * -----------------------------------------------------------------------------
 * Token Builder untuk Agora RTC / RTM.
 *
 * Compatible:
 * - Agora SDK 4.x
 * - Flutter Agora RTC Engine
 * - Laravel 10+
 * -----------------------------------------------------------------------------
 */
class AccessToken2
{
    /**
     * Token Version
     */
    public const VERSION = '007';

    /**
     * Panjang versi token
     */
    public const VERSION_LENGTH = 3;

    /**
     * App Certificate
     */
    public string $appCert;

    /**
     * App ID
     */
    public string $appId;

    /**
     * Expire timestamp
     */
    public int $expire;

    /**
     * Issue timestamp
     */
    public int $issueTs;

    /**
     * Salt
     */
    public int $salt;

    /**
     * Registered services
     *
     * @var array<int, Service>
     */
    public array $services = [];

    /**
     * -------------------------------------------------------------------------
     * Constructor
     * -------------------------------------------------------------------------
     */
    public function __construct(
        string $appId = '',
        string $appCert = '',
        int $expire = 900
    ) {
        $this->appId = $appId;
        $this->appCert = $appCert;
        $this->expire = $expire;

        $this->issueTs = time();

        $this->salt = random_int(
            1,
            99999999
        );
    }

    /**
     * -------------------------------------------------------------------------
     * Register Service
     * -------------------------------------------------------------------------
     */
    public function addService(
        Service $service
    ): void {

        $this->services[
            $service->getServiceType()
        ] = $service;
    }

    /**
     * -------------------------------------------------------------------------
     * Build Token
     * -------------------------------------------------------------------------
     */
    public function build(): string
    {
        if (
            !self::isUuid($this->appId) ||
            !self::isUuid($this->appCert)
        ) {
            return '';
        }

        $signing = $this->getSign();

        $data =
            Util::packString($this->appId)
            . Util::packUint32($this->issueTs)
            . Util::packUint32($this->expire)
            . Util::packUint32($this->salt)
            . Util::packUint16(
                count($this->services)
            );

        ksort($this->services);

        foreach ($this->services as $service) {
            $data .= $service->pack();
        }

        $signature = hash_hmac(
            'sha256',
            $data,
            $signing,
            true
        );

        return self::getVersion()
            . base64_encode(
                zlib_encode(
                    Util::packString($signature)
                    . $data,
                    ZLIB_ENCODING_DEFLATE
                )
            );
    }

    /**
     * -------------------------------------------------------------------------
     * Generate Signature
     * -------------------------------------------------------------------------
     */
    public function getSign(): string
    {
        $hash = hash_hmac(
            'sha256',
            $this->appCert,
            Util::packUint32($this->issueTs),
            true
        );

        return hash_hmac(
            'sha256',
            $hash,
            Util::packUint32($this->salt),
            true
        );
    }

    /**
     * -------------------------------------------------------------------------
     * Get Version
     * -------------------------------------------------------------------------
     */
    public static function getVersion(): string
    {
        return self::VERSION;
    }

    /**
     * -------------------------------------------------------------------------
     * Validate UUID
     * -------------------------------------------------------------------------
     */
    public static function isUuid(
        string $value
    ): bool {

        return strlen($value) === 32
            && ctype_xdigit($value);
    }

    /**
     * Backward compatibility
     */
    public static function isUUid(
        string $value
    ): bool {

        return self::isUuid($value);
    }

    /**
     * -------------------------------------------------------------------------
     * Parse Token
     * -------------------------------------------------------------------------
     */
    public function parse(
        string $token
    ): bool {

        if (
            substr(
                $token,
                0,
                self::VERSION_LENGTH
            ) !== self::getVersion()
        ) {
            return false;
        }

        $data = zlib_decode(
            base64_decode(
                substr(
                    $token,
                    self::VERSION_LENGTH
                )
            )
        );

        if ($data === false) {
            return false;
        }

        $signature =
            Util::unpackString($data);

        $this->appId =
            Util::unpackString($data);

        $this->issueTs =
            Util::unpackUint32($data);

        $this->expire =
            Util::unpackUint32($data);

        $this->salt =
            Util::unpackUint32($data);

        $serviceCount =
            Util::unpackUint16($data);

        $services = [
            ServiceRtc::SERVICE_TYPE => new ServiceRtc(),
            ServiceRtm::SERVICE_TYPE => new ServiceRtm(),
            ServiceFpa::SERVICE_TYPE => new ServiceFpa(),
            ServiceChat::SERVICE_TYPE => new ServiceChat(),
            ServiceApaas::SERVICE_TYPE => new ServiceApaas(),
        ];

        for (
            $i = 0;
            $i < $serviceCount;
            $i++
        ) {

            $serviceType =
                Util::unpackUint16($data);

            if (!isset($services[$serviceType])) {
                return false;
            }

            $service =
                $services[$serviceType];

            $service->unpack($data);

            $this->services[
                $serviceType
            ] = $service;
        }

        return true;
    }
}
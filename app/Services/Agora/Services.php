<?php

declare(strict_types=1);

namespace App\Services\Agora;

/**
 * -----------------------------------------------------------------------------
 * BASE SERVICE
 * -----------------------------------------------------------------------------
 * Digunakan sebagai parent class untuk seluruh service Agora.
 * -----------------------------------------------------------------------------
 */
class Service
{
    /**
     * Service Type
     */
    protected int $type;

    /**
     * Daftar privilege service
     */
    protected array $privileges = [];

    public function __construct(int $serviceType)
    {
        $this->type = $serviceType;
    }

    /**
     * Tambah privilege service
     */
    public function addPrivilege(
        int $privilege,
        int $expire
    ): void {
        $this->privileges[$privilege] = $expire;
    }

    /**
     * Ambil service type
     */
    public function getServiceType(): int
    {
        return $this->type;
    }

    /**
     * Pack service
     */
    public function pack(): string
    {
        return
            Util::packUint16($this->type) .
            Util::packMapUint32($this->privileges);
    }

    /**
     * Unpack service
     */
    public function unpack(string &$data): void
    {
        $this->privileges =
            Util::unpackMapUint32($data);
    }
}

/**
 * -----------------------------------------------------------------------------
 * RTC SERVICE
 * -----------------------------------------------------------------------------
 * Digunakan untuk:
 * - Video Call
 * - Voice Call
 * - Live Streaming
 * -----------------------------------------------------------------------------
 */
class ServiceRtc extends Service
{
    public const SERVICE_TYPE = 1;

    public const PRIVILEGE_JOIN_CHANNEL = 1;

    public const PRIVILEGE_PUBLISH_AUDIO_STREAM = 2;

    public const PRIVILEGE_PUBLISH_VIDEO_STREAM = 3;

    public const PRIVILEGE_PUBLISH_DATA_STREAM = 4;

    public string $channelName;

    public string $uid;

    public function __construct(
        string $channelName = '',
        string $uid = ''
    ) {
        parent::__construct(self::SERVICE_TYPE);

        $this->channelName = $channelName;
        $this->uid = $uid;
    }

    public function pack(): string
    {
        return parent::pack()
            . Util::packString($this->channelName)
            . Util::packString($this->uid);
    }

    public function unpack(string &$data): void
    {
        parent::unpack($data);

        $this->channelName =
            Util::unpackString($data);

        $this->uid =
            Util::unpackString($data);
    }
}

/**
 * -----------------------------------------------------------------------------
 * RTM SERVICE
 * -----------------------------------------------------------------------------
 * Digunakan untuk Agora Messaging.
 * -----------------------------------------------------------------------------
 */
class ServiceRtm extends Service
{
    public const SERVICE_TYPE = 2;

    public const PRIVILEGE_LOGIN = 1;

    public string $userId;

    public function __construct(
        string $userId = ''
    ) {
        parent::__construct(self::SERVICE_TYPE);

        $this->userId = $userId;
    }

    public function pack(): string
    {
        return parent::pack()
            . Util::packString($this->userId);
    }

    public function unpack(string &$data): void
    {
        parent::unpack($data);

        $this->userId =
            Util::unpackString($data);
    }
}

/**
 * -----------------------------------------------------------------------------
 * FPA SERVICE
 * -----------------------------------------------------------------------------
 * Agora Fast Path Authentication.
 * -----------------------------------------------------------------------------
 */
class ServiceFpa extends Service
{
    public const SERVICE_TYPE = 4;

    public const PRIVILEGE_LOGIN = 1;

    public function __construct()
    {
        parent::__construct(self::SERVICE_TYPE);
    }
}

/**
 * -----------------------------------------------------------------------------
 * CHAT SERVICE
 * -----------------------------------------------------------------------------
 * Agora Chat.
 * -----------------------------------------------------------------------------
 */
class ServiceChat extends Service
{
    public const SERVICE_TYPE = 5;

    public const PRIVILEGE_USER = 1;

    public const PRIVILEGE_APP = 2;

    public string $userId;

    public function __construct(
        string $userId = ''
    ) {
        parent::__construct(self::SERVICE_TYPE);

        $this->userId = $userId;
    }

    public function pack(): string
    {
        return parent::pack()
            . Util::packString($this->userId);
    }

    public function unpack(string &$data): void
    {
        parent::unpack($data);

        $this->userId =
            Util::unpackString($data);
    }
}

/**
 * -----------------------------------------------------------------------------
 * APAAS SERVICE
 * -----------------------------------------------------------------------------
 * Agora Platform As A Service.
 * -----------------------------------------------------------------------------
 */
class ServiceApaas extends Service
{
    public const SERVICE_TYPE = 7;

    public const PRIVILEGE_ROOM_USER = 1;

    public const PRIVILEGE_USER = 2;

    public const PRIVILEGE_APP = 3;

    public string $roomUuid;

    public string $userUuid;

    public int $role;

    public function __construct(
        string $roomUuid = '',
        string $userUuid = '',
        int $role = -1
    ) {
        parent::__construct(self::SERVICE_TYPE);

        $this->roomUuid = $roomUuid;
        $this->userUuid = $userUuid;
        $this->role = $role;
    }

    public function pack(): string
    {
        return parent::pack()
            . Util::packString($this->roomUuid)
            . Util::packString($this->userUuid)
            . Util::packInt16($this->role);
    }

    public function unpack(string &$data): void
    {
        parent::unpack($data);

        $this->roomUuid =
            Util::unpackString($data);

        $this->userUuid =
            Util::unpackString($data);

        $this->role =
            Util::unpackInt16($data);
    }
}
<?php

namespace App\Services\Agora;

use App\Services\Agora\DynamicKey\RtcTokenBuilder2;

class AgoraService
{
    /**
     * --------------------------------------------------------------------------
     * DEFAULT TOKEN EXPIRE TIME (1 JAM)
     * --------------------------------------------------------------------------
     */
    public const TOKEN_EXPIRE_SECONDS = 3600;

    /**
     * --------------------------------------------------------------------------
     * GET AGORA APP ID
     * --------------------------------------------------------------------------
     */
    public static function getAppId(): string
    {
        return (string) config('services.agora.app_id', env('AGORA_APP_ID', ''));
    }

    /**
     * --------------------------------------------------------------------------
     * GET AGORA APP CERTIFICATE
     * --------------------------------------------------------------------------
     */
    protected static function getAppCertificate(): string
    {
        return (string) config('services.agora.app_certificate', env('AGORA_APP_CERTIFICATE', ''));
    }

    /**
     * --------------------------------------------------------------------------
     * VALIDATE AGORA CONFIGURATION
     * --------------------------------------------------------------------------
     *
     * @throws \Exception
     */
    public static function validateConfiguration(): void
    {
        if (blank(self::getAppId())) {
            throw new \Exception(
                'AGORA_APP_ID belum dikonfigurasi pada .env atau config/services.php'
            );
        }

        if (blank(self::getAppCertificate())) {
            throw new \Exception(
                'AGORA_APP_CERTIFICATE belum dikonfigurasi pada .env atau config/services.php'
            );
        }
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE RTC TOKEN
     * --------------------------------------------------------------------------
     *
     * @param string $channelName Nama channel Agora
     * @param int|string $uid UID user Agora (0 untuk wildcard)
     * @param int $expireSeconds Masa berlaku token dalam detik
     * @return string
     *
     * @throws \Exception
     */
    public static function generateRtcToken(
        string $channelName,
        int|string $uid = 0,
        int $expireSeconds = self::TOKEN_EXPIRE_SECONDS
    ): string {
        self::validateConfiguration();

        $trimmedChannel = trim($channelName);
        if (blank($trimmedChannel)) {
            throw new \Exception('Nama channel Agora tidak boleh kosong.');
        }

        $numericUid = is_numeric($uid) ? (int) $uid : 0;

        // Pada Agora AccessToken2:
        // Parameter ke-6 adalah tokenExpire (durasi dalam detik, BUKAN unix timestamp).
        // Parameter ke-7 adalah privilegeExpire (durasi dalam detik).
        $token = RtcTokenBuilder2::buildTokenWithUid(
            self::getAppId(),
            self::getAppCertificate(),
            $trimmedChannel,
            $numericUid,
            RtcTokenBuilder2::ROLE_PUBLISHER,
            $expireSeconds,
            $expireSeconds
        );

        // Logging yang aman tanpa membocorkan App Certificate atau Full Token
        \Illuminate\Support\Facades\Log::info('Agora Video Call: RTC Token generated', [
            'channel_name'    => $trimmedChannel,
            'uid'             => $numericUid,
            'role'            => 'ROLE_PUBLISHER',
            'expire_seconds'  => $expireSeconds,
            'token_generated' => !empty($token),
            'token_length'    => strlen($token),
        ]);

        return $token;
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE VIDEO CALL TOKEN
     * --------------------------------------------------------------------------
     */
    public static function generateVideoCallToken(
        string $channelName,
        int|string $uid = 0,
        int $expireSeconds = self::TOKEN_EXPIRE_SECONDS
    ): string {
        return self::generateRtcToken(
            channelName: $channelName,
            uid: $uid,
            expireSeconds: $expireSeconds
        );
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE VOICE CALL TOKEN
     * --------------------------------------------------------------------------
     */
    public static function generateVoiceCallToken(
        string $channelName,
        int|string $uid = 0,
        int $expireSeconds = self::TOKEN_EXPIRE_SECONDS
    ): string {
        return self::generateRtcToken(
            channelName: $channelName,
            uid: $uid,
            expireSeconds: $expireSeconds
        );
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE CHANNEL NAME
     * --------------------------------------------------------------------------
     * Format konsisten untuk Konseli & Konselor:
     * sijala_session_{sessionId}
     *
     * Jika dipanggil dengan 2 argumen (legacy callerId & receiverId),
     * dialihkan secara otomatis ke fixed channel konsisten tanpa timestamp acak.
     */
    public static function generateChannelName(
        int|string $sessionId,
        ?int $receiverId = null
    ): string {
        if ($receiverId !== null) {
            return self::generateFixedChannel((int) $sessionId, $receiverId);
        }

        return 'sijala_session_' . $sessionId;
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE FIXED CHANNEL (DETERMINISTIK TANPA TIMESTAMP ACAK)
     * --------------------------------------------------------------------------
     * Format:
     * consult_{min_id}_{max_id}
     */
    public static function generateFixedChannel(
        int $callerId,
        int $receiverId
    ): string {
        $users = [$callerId, $receiverId];
        sort($users);

        return sprintf('consult_%d_%d', $users[0], $users[1]);
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE UID AGORA
     * --------------------------------------------------------------------------
     * Menghasilkan integer 32-bit valid untuk Agora RTC.
     */
    public static function generateUid(mixed $user = null): int
    {
        if ($user instanceof \App\Models\User) {
            return (int) $user->id;
        }

        if (is_numeric($user) && (int) $user > 0) {
            return (int) $user;
        }

        return 0;
    }

    /**
     * --------------------------------------------------------------------------
     * GET DEFAULT TOKEN DURATION IN SECONDS
     * --------------------------------------------------------------------------
     */
    public static function getExpireSeconds(): int
    {
        return self::TOKEN_EXPIRE_SECONDS;
    }

    /**
     * --------------------------------------------------------------------------
     * GET TOKEN EXPIRED TIMESTAMP
     * --------------------------------------------------------------------------
     */
    public static function getExpiredTimestamp(
        int $expireSeconds = self::TOKEN_EXPIRE_SECONDS
    ): int {
        return time() + $expireSeconds;
    }

    /**
     * --------------------------------------------------------------------------
     * GET COMPLETE VIDEO CALL CREDENTIALS
     * --------------------------------------------------------------------------
     * Digunakan oleh Konseli dan Konselor untuk sesi konseling.
     */
    public static function getVideoCallCredentials(
        int|string $sessionId,
        mixed $user = null,
        int $expireSeconds = self::TOKEN_EXPIRE_SECONDS
    ): array {
        $channelName = self::generateChannelName($sessionId);
        $uid = self::generateUid($user);
        $token = self::generateVideoCallToken($channelName, $uid, $expireSeconds);
        $expiresAt = self::getExpiredTimestamp($expireSeconds);

        return [
            'app_id'       => self::getAppId(),
            'channel_name' => $channelName,
            'uid'          => $uid,
            'token'        => $token,
            'expires_at'   => $expiresAt,
            'expired_at'   => $expiresAt, // alias untuk backwards-compatibility
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE COMPLETE CALL DATA (KOMPATIBILITAS KONSULTASI)
     * --------------------------------------------------------------------------
     */
    public static function generateCallData(
        int $callerId,
        int $receiverId,
        int|string|null $sessionId = null,
        ?int $callerUid = null
    ): array {
        $channelName = ($sessionId !== null && !blank($sessionId))
            ? self::generateChannelName($sessionId)
            : self::generateFixedChannel($callerId, $receiverId);

        $uid = $callerUid ?? self::generateUid($callerId);
        $token = self::generateVideoCallToken($channelName, $uid);
        $expiresAt = self::getExpiredTimestamp();

        return [
            'app_id'       => self::getAppId(),
            'channel_name' => $channelName,
            'token'        => $token,
            'uid'          => $uid,
            'expires_at'   => $expiresAt,
            'expired_at'   => $expiresAt,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * RENEW AGORA RTC TOKEN
     * --------------------------------------------------------------------------
     */
    public static function renewToken(
        string $channelName,
        int|string $uid,
        int $expireSeconds = self::TOKEN_EXPIRE_SECONDS
    ): array {
        $token = self::generateVideoCallToken($channelName, $uid, $expireSeconds);
        $expiresAt = self::getExpiredTimestamp($expireSeconds);

        return [
            'app_id'       => self::getAppId(),
            'channel_name' => $channelName,
            'uid'          => (int) $uid,
            'token'        => $token,
            'expires_at'   => $expiresAt,
            'expired_at'   => $expiresAt,
        ];
    }
}

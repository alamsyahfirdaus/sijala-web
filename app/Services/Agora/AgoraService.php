<?php

namespace App\Services\Agora;

use App\Services\Agora\DynamicKey\RtcTokenBuilder2;

class AgoraService
{
    /**
     * --------------------------------------------------------------------------
     * DEFAULT TOKEN EXPIRE TIME
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
        return (string) config('services.agora.app_id');
    }

    /**
     * --------------------------------------------------------------------------
     * GET AGORA APP CERTIFICATE
     * --------------------------------------------------------------------------
     */
    protected static function getAppCertificate(): string
    {
        return (string) config('services.agora.app_certificate');
    }

    /**
     * --------------------------------------------------------------------------
     * VALIDATE AGORA CONFIGURATION
     * --------------------------------------------------------------------------
     *
     * @throws \Exception
     *                    --------------------------------------------------------------------------
     */
    protected static function validateConfiguration(): void
    {
        if (blank(self::getAppId())) {
            throw new \Exception(
                'AGORA_APP_ID belum dikonfigurasi.'
            );
        }

        if (blank(self::getAppCertificate())) {
            throw new \Exception(
                'AGORA_APP_CERTIFICATE belum dikonfigurasi.'
            );
        }
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE RTC TOKEN
     * --------------------------------------------------------------------------
     *
     * Digunakan untuk:
     * - Video Call
     * - Voice Call
     * - Real-Time Communication (RTC)
     *
     *
     *
     * @throws \Exception
     *                    --------------------------------------------------------------------------
     */
    public static function generateRtcToken(
        string $channelName,
        int|string $uid = 0,
        int $expireSeconds = self::TOKEN_EXPIRE_SECONDS
    ): string {

        self::validateConfiguration();

        if (blank(trim($channelName))) {
            throw new \Exception(
                'Nama channel Agora tidak boleh kosong.'
            );
        }

        $expireTimestamp = time() + $expireSeconds;

        return RtcTokenBuilder2::buildTokenWithUid(
            self::getAppId(),
            self::getAppCertificate(),
            $channelName,
            (string) $uid,
            RtcTokenBuilder2::ROLE_PUBLISHER,
            $expireTimestamp
        );
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE VIDEO CALL TOKEN
     * --------------------------------------------------------------------------
     *
     *
     *
     * @throws \Exception
     *                    --------------------------------------------------------------------------
     */
    public static function generateVideoCallToken(
        string $channelName
    ): string {

        return self::generateRtcToken(
            channelName: $channelName
        );
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE VOICE CALL TOKEN
     * --------------------------------------------------------------------------
     *
     *
     *
     * @throws \Exception
     *                    --------------------------------------------------------------------------
     */
    public static function generateVoiceCallToken(
        string $channelName
    ): string {

        return self::generateRtcToken(
            channelName: $channelName
        );
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE UNIQUE CHANNEL
     * --------------------------------------------------------------------------
     *
     * Format:
     *
     * consult_{caller}_{receiver}_{timestamp}
     *
     * --------------------------------------------------------------------------
     */
    public static function generateChannelName(
        int $callerId,
        int $receiverId
    ): string {

        return sprintf(
            'consult_%d_%d_%d',
            $callerId,
            $receiverId,
            time()
        );
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE FIXED CHANNEL
     * --------------------------------------------------------------------------
     *
     * Format:
     *
     * consult_5_12
     *
     * --------------------------------------------------------------------------
     */
    public static function generateFixedChannel(
        int $callerId,
        int $receiverId
    ): string {

        $users = [
            $callerId,
            $receiverId,
        ];

        sort($users);

        return sprintf(
            'consult_%d_%d',
            $users[0],
            $users[1]
        );
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
     * GENERATE COMPLETE CALL DATA
     * --------------------------------------------------------------------------
     *
     * Digunakan oleh ConsultationController.
     *
     *
     *
     * @throws \Exception
     *                    --------------------------------------------------------------------------
     */
    public static function generateCallData(
        int $callerId,
        int $receiverId
    ): array {

        $channelName = self::generateChannelName(
            $callerId,
            $receiverId
        );

        return [

            'app_id' => self::getAppId(),

            'channel_name' => $channelName,

            'token' => self::generateVideoCallToken(
                $channelName
            ),

            'uid' => 0,

            'expired_at' => self::getExpiredTimestamp(),

        ];
    }
}

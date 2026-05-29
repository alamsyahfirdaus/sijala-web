<?php

namespace App\Services\Agora;

use Exception;

class AgoraService
{
    /**
     * --------------------------------------------------------------------------
     * GENERATE RTC TOKEN
     * --------------------------------------------------------------------------
     * Membuat token RTC Agora untuk kebutuhan:
     * - Video Call
     * - Voice Call
     * - Real-Time Communication
     *
     * Token akan digunakan oleh Flutter saat melakukan:
     *
     * engine.joinChannel(
     *   token: token,
     *   channelId: channelName,
     *   uid: uid,
     *   options: ...
     * );
     *
     * --------------------------------------------------------------------------
     *
     * @param string $channelName
     * Nama channel Agora.
     *
     * @param int|string $uid
     * UID user Agora.
     * Gunakan 0 jika UID akan dibuat otomatis oleh Agora.
     *
     * @param int $expireSeconds
     * Masa berlaku token dalam detik.
     *
     * @return string
     *
     * @throws Exception
     * --------------------------------------------------------------------------
     */
    public static function generateRtcToken(
        string $channelName,
        int|string $uid = 0,
        int $expireSeconds = 3600
    ): string {

        $appId = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');

        /*
        |--------------------------------------------------------------------------
        | Validasi Konfigurasi Agora
        |--------------------------------------------------------------------------
        */
        if (empty($appId) || empty($appCertificate)) {
            throw new Exception(
                'AGORA_APP_ID atau AGORA_APP_CERTIFICATE belum dikonfigurasi pada file .env'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi Channel
        |--------------------------------------------------------------------------
        */
        if (empty(trim($channelName))) {
            throw new Exception(
                'Nama channel Agora tidak boleh kosong.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Expired Timestamp
        |--------------------------------------------------------------------------
        */
        $currentTimestamp = time();

        $privilegeExpiredTs =
            $currentTimestamp +
            $expireSeconds;

        /*
        |--------------------------------------------------------------------------
        | Generate RTC Token
        |--------------------------------------------------------------------------
        */
        return RtcTokenBuilder2::buildTokenWithUid(
            $appId,
            $appCertificate,
            $channelName,
            (string) $uid,
            ServiceRtc::ROLE_PUBLISHER,
            $privilegeExpiredTs
        );
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE VIDEO CALL TOKEN
     * --------------------------------------------------------------------------
     * Shortcut khusus Video Call SIJALA.
     *
     * Masa berlaku default:
     * 1 Jam
     * --------------------------------------------------------------------------
     *
     * @param string $channelName
     * @return string
     * --------------------------------------------------------------------------
     */
    public static function generateVideoCallToken(
        string $channelName
    ): string {

        return self::generateRtcToken(
            channelName: $channelName,
            uid: 0,
            expireSeconds: 3600
        );
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE VOICE CALL TOKEN
     * --------------------------------------------------------------------------
     * Digunakan jika suatu saat aplikasi
     * membutuhkan Voice Call tanpa video.
     * --------------------------------------------------------------------------
     *
     * @param string $channelName
     * @return string
     * --------------------------------------------------------------------------
     */
    public static function generateVoiceCallToken(
        string $channelName
    ): string {

        return self::generateRtcToken(
            channelName: $channelName,
            uid: 0,
            expireSeconds: 3600
        );
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE CHANNEL NAME
     * --------------------------------------------------------------------------
     * Format:
     *
     * consult_{caller}_{receiver}_{timestamp}
     *
     * Contoh:
     *
     * consult_5_12_1748500000
     *
     * --------------------------------------------------------------------------
     *
     * @param int $callerId
     * @param int $receiverId
     * @return string
     * --------------------------------------------------------------------------
     */
    public static function generateChannelName(
        int $callerId,
        int $receiverId
    ): string {

        return sprintf(
            'consult_%s_%s_%s',
            $callerId,
            $receiverId,
            time()
        );
    }

    /**
     * --------------------------------------------------------------------------
     * GENERATE FIXED CHANNEL
     * --------------------------------------------------------------------------
     * Digunakan jika satu konsultasi
     * harus selalu masuk ke channel yang sama.
     *
     * Contoh:
     *
     * consult_5_12
     *
     * --------------------------------------------------------------------------
     *
     * @param int $callerId
     * @param int $receiverId
     * @return string
     * --------------------------------------------------------------------------
     */
    public static function generateFixedChannel(
        int $callerId,
        int $receiverId
    ): string {

        $users = [
            $callerId,
            $receiverId
        ];

        sort($users);

        return sprintf(
            'consult_%s_%s',
            $users[0],
            $users[1]
        );
    }

    /**
     * --------------------------------------------------------------------------
     * GET TOKEN EXPIRATION
     * --------------------------------------------------------------------------
     * Digunakan untuk mengirim informasi
     * kapan token akan berakhir.
     * --------------------------------------------------------------------------
     *
     * @param int $expireSeconds
     * @return int
     * --------------------------------------------------------------------------
     */
    public static function getExpiredTimestamp(
        int $expireSeconds = 3600
    ): int {

        return time() + $expireSeconds;
    }
}
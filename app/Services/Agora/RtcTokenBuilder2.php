<?php

namespace App\Services\Agora;

class RtcTokenBuilder2
{
    /**
     * --------------------------------------------------------------------------
     * ROLE PUBLISHER
     * --------------------------------------------------------------------------
     * Digunakan untuk:
     * - Video Call
     * - Voice Call
     * - Broadcaster
     * --------------------------------------------------------------------------
     */
    public const ROLE_PUBLISHER = 1;

    /**
     * --------------------------------------------------------------------------
     * ROLE SUBSCRIBER
     * --------------------------------------------------------------------------
     * Digunakan untuk audience / viewer.
     * --------------------------------------------------------------------------
     */
    public const ROLE_SUBSCRIBER = 2;

    /**
     * --------------------------------------------------------------------------
     * BUILD TOKEN WITH UID
     * --------------------------------------------------------------------------
     */
    public static function buildTokenWithUid(
        string $appId,
        string $appCertificate,
        string $channelName,
        string|int $uid,
        int $role,
        int $tokenExpire,
        int $privilegeExpire = 0
    ): string {

        return self::buildTokenWithUserAccount(
            $appId,
            $appCertificate,
            $channelName,
            (string) $uid,
            $role,
            $tokenExpire,
            $privilegeExpire
        );
    }

    /**
     * --------------------------------------------------------------------------
     * BUILD TOKEN WITH USER ACCOUNT
     * --------------------------------------------------------------------------
     */
    public static function buildTokenWithUserAccount(
        string $appId,
        string $appCertificate,
        string $channelName,
        string $account,
        int $role,
        int $tokenExpire,
        int $privilegeExpire = 0
    ): string {

        $token = new AccessToken2(
            $appId,
            $appCertificate,
            $tokenExpire
        );

        $serviceRtc = new ServiceRtc(
            $channelName,
            $account
        );

        /*
        |--------------------------------------------------------------------------
        | JOIN CHANNEL
        |--------------------------------------------------------------------------
        */
        $serviceRtc->addPrivilege(
            ServiceRtc::PRIVILEGE_JOIN_CHANNEL,
            $privilegeExpire
        );

        /*
        |--------------------------------------------------------------------------
        | PUBLISHER PRIVILEGES
        |--------------------------------------------------------------------------
        */
        if ($role === self::ROLE_PUBLISHER) {

            $serviceRtc->addPrivilege(
                ServiceRtc::PRIVILEGE_PUBLISH_AUDIO_STREAM,
                $privilegeExpire
            );

            $serviceRtc->addPrivilege(
                ServiceRtc::PRIVILEGE_PUBLISH_VIDEO_STREAM,
                $privilegeExpire
            );

            $serviceRtc->addPrivilege(
                ServiceRtc::PRIVILEGE_PUBLISH_DATA_STREAM,
                $privilegeExpire
            );
        }

        $token->addService($serviceRtc);

        return $token->build();
    }

    /**
     * --------------------------------------------------------------------------
     * BUILD TOKEN WITH UID AND CUSTOM PRIVILEGES
     * --------------------------------------------------------------------------
     */
    public static function buildTokenWithUidAndPrivilege(
        string $appId,
        string $appCertificate,
        string $channelName,
        string|int $uid,
        int $tokenExpire,
        int $joinChannelPrivilegeExpire,
        int $pubAudioPrivilegeExpire,
        int $pubVideoPrivilegeExpire,
        int $pubDataStreamPrivilegeExpire
    ): string {

        return self::buildTokenWithUserAccountAndPrivilege(
            $appId,
            $appCertificate,
            $channelName,
            (string) $uid,
            $tokenExpire,
            $joinChannelPrivilegeExpire,
            $pubAudioPrivilegeExpire,
            $pubVideoPrivilegeExpire,
            $pubDataStreamPrivilegeExpire
        );
    }

    /**
     * --------------------------------------------------------------------------
     * BUILD TOKEN WITH ACCOUNT AND CUSTOM PRIVILEGES
     * --------------------------------------------------------------------------
     */
    public static function buildTokenWithUserAccountAndPrivilege(
        string $appId,
        string $appCertificate,
        string $channelName,
        string $account,
        int $tokenExpire,
        int $joinChannelPrivilegeExpire,
        int $pubAudioPrivilegeExpire,
        int $pubVideoPrivilegeExpire,
        int $pubDataStreamPrivilegeExpire
    ): string {

        $token = new AccessToken2(
            $appId,
            $appCertificate,
            $tokenExpire
        );

        $serviceRtc = new ServiceRtc(
            $channelName,
            $account
        );

        $serviceRtc->addPrivilege(
            ServiceRtc::PRIVILEGE_JOIN_CHANNEL,
            $joinChannelPrivilegeExpire
        );

        $serviceRtc->addPrivilege(
            ServiceRtc::PRIVILEGE_PUBLISH_AUDIO_STREAM,
            $pubAudioPrivilegeExpire
        );

        $serviceRtc->addPrivilege(
            ServiceRtc::PRIVILEGE_PUBLISH_VIDEO_STREAM,
            $pubVideoPrivilegeExpire
        );

        $serviceRtc->addPrivilege(
            ServiceRtc::PRIVILEGE_PUBLISH_DATA_STREAM,
            $pubDataStreamPrivilegeExpire
        );

        $token->addService($serviceRtc);

        return $token->build();
    }

    /**
     * --------------------------------------------------------------------------
     * BUILD RTC + RTM TOKEN
     * --------------------------------------------------------------------------
     */
    public static function buildTokenWithRtm(
        string $appId,
        string $appCertificate,
        string $channelName,
        string $account,
        int $role,
        int $tokenExpire,
        int $privilegeExpire = 0
    ): string {

        $token = new AccessToken2(
            $appId,
            $appCertificate,
            $tokenExpire
        );

        $serviceRtc = new ServiceRtc(
            $channelName,
            $account
        );

        $serviceRtc->addPrivilege(
            ServiceRtc::PRIVILEGE_JOIN_CHANNEL,
            $privilegeExpire
        );

        if ($role === self::ROLE_PUBLISHER) {

            $serviceRtc->addPrivilege(
                ServiceRtc::PRIVILEGE_PUBLISH_AUDIO_STREAM,
                $privilegeExpire
            );

            $serviceRtc->addPrivilege(
                ServiceRtc::PRIVILEGE_PUBLISH_VIDEO_STREAM,
                $privilegeExpire
            );

            $serviceRtc->addPrivilege(
                ServiceRtc::PRIVILEGE_PUBLISH_DATA_STREAM,
                $privilegeExpire
            );
        }

        $token->addService($serviceRtc);

        /*
        |--------------------------------------------------------------------------
        | RTM SERVICE
        |--------------------------------------------------------------------------
        */
        if (class_exists(ServiceRtm::class)) {

            $serviceRtm = new ServiceRtm(
                $account
            );

            $serviceRtm->addPrivilege(
                ServiceRtm::PRIVILEGE_LOGIN,
                $tokenExpire
            );

            $token->addService($serviceRtm);
        }

        return $token->build();
    }

    /**
     * --------------------------------------------------------------------------
     * BUILD RTC + RTM TOKEN (ADVANCED)
     * --------------------------------------------------------------------------
     */
    public static function buildTokenWithRtm2(
        string $appId,
        string $appCertificate,
        string $channelName,
        string $rtcAccount,
        int $rtcRole,
        int $rtcTokenExpire,
        int $joinChannelPrivilegeExpire,
        int $pubAudioPrivilegeExpire,
        int $pubVideoPrivilegeExpire,
        int $pubDataStreamPrivilegeExpire,
        string $rtmUserId,
        int $rtmTokenExpire
    ): string {

        $token = new AccessToken2(
            $appId,
            $appCertificate,
            $rtcTokenExpire
        );

        $serviceRtc = new ServiceRtc(
            $channelName,
            $rtcAccount
        );

        $serviceRtc->addPrivilege(
            ServiceRtc::PRIVILEGE_JOIN_CHANNEL,
            $joinChannelPrivilegeExpire
        );

        if ($rtcRole === self::ROLE_PUBLISHER) {

            $serviceRtc->addPrivilege(
                ServiceRtc::PRIVILEGE_PUBLISH_AUDIO_STREAM,
                $pubAudioPrivilegeExpire
            );

            $serviceRtc->addPrivilege(
                ServiceRtc::PRIVILEGE_PUBLISH_VIDEO_STREAM,
                $pubVideoPrivilegeExpire
            );

            $serviceRtc->addPrivilege(
                ServiceRtc::PRIVILEGE_PUBLISH_DATA_STREAM,
                $pubDataStreamPrivilegeExpire
            );
        }

        $token->addService($serviceRtc);

        if (class_exists(ServiceRtm::class)) {

            $serviceRtm = new ServiceRtm(
                $rtmUserId
            );

            $serviceRtm->addPrivilege(
                ServiceRtm::PRIVILEGE_LOGIN,
                $rtmTokenExpire
            );

            $token->addService($serviceRtm);
        }

        return $token->build();
    }
}
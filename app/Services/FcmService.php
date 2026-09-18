<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FcmService
{
    private static function executeCurl($url, array $headers, $postFields = null)
    {
        $ch = curl_init();
        $curlHeaders = [];
        foreach ($headers as $k => $v) {
            $curlHeaders[] = "$k: $v";
        }

        $opts = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_HTTPHEADER => $curlHeaders,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_FORBID_REUSE => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ];

        if ($postFields !== null) {
            $opts[CURLOPT_POST] = true;
            $opts[CURLOPT_POSTFIELDS] = is_array($postFields) ? json_encode($postFields) : $postFields;
        }

        curl_setopt_array($ch, $opts);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($errno) {
            throw new \Exception("cURL Error {$errno}: {$error}");
        }

        return ['code' => $httpCode, 'body' => $response];
    }

    private static function getAccessToken($serviceAccountPath)
    {
        return Cache::remember('fcm_oauth_token', 3300, function () use ($serviceAccountPath) {
            $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
            $credentials = new ServiceAccountCredentials($scopes, $serviceAccountPath);

            $tokenCallback = function ($request) {
                $url = (string) $request->getUri();
                $headers = [];
                foreach ($request->getHeaders() as $name => $values) {
                    $headers[$name] = implode(', ', $values);
                }
                $body = (string) $request->getBody();

                $res = self::executeCurl($url, $headers, $body);
                return new \GuzzleHttp\Psr7\Response($res['code'], [], $res['body']);
            };

            $token = $credentials->fetchAuthToken($tokenCallback);

            if (!isset($token['access_token'])) {
                throw new \Exception("Failed to obtain OAuth2 token for FCM: " . json_encode($token));
            }

            return $token['access_token'];
        });
    }

    public static function sendToTopic($topic, $title, $body, array $data = [])
    {
        Log::info("FCM Notification Triggered [Topic: {$topic}] - Title: {$title}");

        $serviceAccountPath = storage_path('app/firebase-service-account.json');
        if (!file_exists($serviceAccountPath)) {
            Log::warning("FCM Service Account file not found at: {$serviceAccountPath}");
            return false;
        }

        try {
            $accessToken = self::getAccessToken($serviceAccountPath);
            $json = json_decode(file_get_contents($serviceAccountPath), true);
            $projectId = $json['project_id'] ?? env('FIREBASE_PROJECT_ID');

            if (!$projectId) {
                Log::error("FCM Error: Firebase Project ID could not be determined.");
                return false;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $payload = [
                'message' => [
                    'topic' => $topic,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'android' => [
                        'priority' => 'HIGH',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => 'high_importance_channel',
                            'visibility' => 'PUBLIC',
                            'notification_priority' => 'PRIORITY_MAX'
                        ],
                    ],
                ]
            ];

            if (!empty($data)) {
                $payload['message']['data'] = array_map('strval', $data);
            }

            $res = self::executeCurl($url, [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ], $payload);

            if ($res['code'] === 200) {
                Log::info("FCM Topic Notification Sent Successfully: " . $res['body']);
                return true;
            } else {
                Log::error("FCM Topic Notification Failed [HTTP {$res['code']}]: " . $res['body']);
                return false;
            }
        } catch (\Exception $e) {
            Log::error("FCM Exception: " . $e->getMessage());
            return false;
        }
    }

    public static function sendToToken($fcmToken, $title, $body, array $data = [])
    {
        if (empty($fcmToken)) {
            return false;
        }

        Log::info("FCM Notification Triggered [Token: {$fcmToken}] - Title: {$title}");

        $serviceAccountPath = storage_path('app/firebase-service-account.json');
        if (!file_exists($serviceAccountPath)) {
            Log::warning("FCM Service Account file not found at: {$serviceAccountPath}");
            return false;
        }

        try {
            $accessToken = self::getAccessToken($serviceAccountPath);
            $json = json_decode(file_get_contents($serviceAccountPath), true);
            $projectId = $json['project_id'] ?? env('FIREBASE_PROJECT_ID');

            if (!$projectId) {
                Log::error("FCM Error: Firebase Project ID could not be determined.");
                return false;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'android' => [
                        'priority' => 'HIGH',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => 'high_importance_channel',
                            'visibility' => 'PUBLIC',
                            'notification_priority' => 'PRIORITY_MAX'
                        ],
                    ],
                ]
            ];

            if (!empty($data)) {
                $payload['message']['data'] = array_map('strval', $data);
            }

            $res = self::executeCurl($url, [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ], $payload);

            if ($res['code'] === 200) {
                Log::info("FCM Token Notification Sent Successfully: " . $res['body']);
                return true;
            } else {
                Log::error("FCM Token Notification Failed [HTTP {$res['code']}]: " . $res['body']);
                return false;
            }
        } catch (\Exception $e) {
            Log::error("FCM Exception: " . $e->getMessage());
            return false;
        }
    }
}

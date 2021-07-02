<?php

/**
 * @package   mod_matrix
 * @copyright 2020, New Vector Ltd (Trading as Element)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3 or later
 */

namespace mod_matrix;

use Curl\Curl;

defined('MOODLE_INTERNAL') || exit;

require_once __DIR__ . '/../vendor/autoload.php';

class bot
{
    private $hsUrl;

    private $accessToken;

    public function __construct(
        string $hsUrl,
        string $accessToken
    ) {
        $this->hsUrl = $hsUrl;
        $this->accessToken = $accessToken;
    }

    /**
     * @throws \RuntimeException
     */
    public static function instance(): self
    {
        $config = get_config('mod_matrix');

        if (!property_exists($config, 'hs_url')) {
            throw new \RuntimeException(sprintf(
                'Module configuration should have a "%s" property, but it does not.',
                'hs_url'
            ));
        }

        $hsUrl = $config->hs_url;

        if (!is_string($hsUrl)) {
            throw new \RuntimeException(sprintf(
                'Module configuration "%s" should be a string, got "%s" instead..',
                'hs_url',
                is_object($hsUrl) ? get_class($hsUrl) : gettype($hsUrl)
            ));
        }

        if (!property_exists($config, 'access_token')) {
            throw new \RuntimeException(sprintf(
                'Module configuration should have a "%s" property, but it does not.',
                'access_token'
            ));
        }

        $accessToken = $config->access_token;

        if (!is_string($accessToken)) {
            throw new \RuntimeException(sprintf(
                'Module configuration "%s" should be a string, got "%s" instead..',
                'access_token',
                is_object($accessToken) ? get_class($accessToken) : gettype($accessToken)
            ));
        }

        return new self(
            $hsUrl,
            $accessToken
        );
    }

    /**
     * @see https://matrix.org/docs/api/client-server/#!/User32data/getTokenOwner
     */
    public function whoami()
    {
        $r = $this->request(
            'GET',
            '/_matrix/client/r0/account/whoami'
        );

        return $r['user_id'];
    }

    /**
     * @see https://matrix.org/docs/api/client-server/#!/Room32creation/createRoom
     * @param mixed $opts
     */
    public function create_room($opts = [])
    {
        $r = $this->request(
            'POST',
            '/_matrix/client/r0/createRoom',
            [],
            $opts
        );

        return $r['room_id'];
    }

    /**
     * @see https://matrix.org/docs/api/client-server/#!/Room32membership/inviteBy3PID
     * @param mixed $user_id
     * @param mixed $room_id
     */
    public function invite_user($user_id, $room_id)
    {
        return $this->request(
            'POST',
            '/_matrix/client/r0/rooms/' . urlencode($room_id) . '/invite',
            [],
            [
                'user_id' => $user_id,
            ]
        );
    }

    /**
     * @see https://matrix.org/docs/api/client-server/#!/Room32membership/kick
     * @param mixed $user_id
     * @param mixed $room_id
     */
    public function kick_user($user_id, $room_id)
    {
        return $this->request(
            'POST',
            '/_matrix/client/r0/rooms/' . urlencode($room_id) . '/kick',
            [],
            [
                'user_id' => $user_id,
            ]
        );
    }

    /**
     * @see https://matrix.org/docs/api/client-server/#!/Room32participation/getRoomStateWithKey
     * @param mixed $room_id
     * @param mixed $event_type
     * @param mixed $state_key
     */
    public function get_state($room_id, $event_type, $state_key)
    {
        return $this->request(
            'GET',
            '/_matrix/client/r0/rooms/' . urlencode($room_id) . '/state/' . urlencode($event_type) . '/' . urlencode($state_key)
        );
    }

    /**
     * @see https://matrix.org/docs/api/client-server/#!/Room32participation/setRoomStateWithKey
     * @param mixed $room_id
     * @param mixed $event_type
     * @param mixed $state_key
     * @param mixed $content
     */
    public function set_state($room_id, $event_type, $state_key, $content)
    {
        return $this->request(
            'PUT',
            '/_matrix/client/r0/rooms/' . urlencode($room_id) . '/state/' . urlencode($event_type) . '/' . urlencode($state_key),
            [],
            $content
        );
    }

    /**
     * @see https://matrix.org/docs/api/client-server/#!/Room32participation/getMembersByRoom
     * @param mixed $room_id
     */
    public function get_effective_joins($room_id)
    {
        $members = $this->request(
            'GET',
            '/_matrix/client/r0/rooms/' . urlencode($room_id) . '/members'
        );

        $user_ids = [];

        foreach ($members['chunk'] as $ev) {
            if ($ev['content'] && $ev['content']['membership']) {
                $membership = $ev['content']['membership'];

                if ('join' == $membership || 'invite' == $membership) {
                    $user_ids[] = $ev['state_key'];
                }
            }
        }

        return $user_ids;
    }

    public function debug($val)
    {
        $val = var_export($val, true);

        $this->request(
            'PUT',
            '/_matrix/client/r0/rooms/!cujtuCldotJLtvQGiQ:localhost/send/m.room.message/m' . microtime() . 'r' . mt_rand(0, 100),
            [],
            [
                'msgtype' => 'm.text',
                'body' => $val,
            ]
        );
    }

    /**
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    private function request(
        string $method,
        string $path,
        array $qs = [],
        array $body = []
    ) {
        $allowedMethods = [
            'GET',
            'POST',
            'PUT',
        ];

        if (!in_array($method, $allowedMethods, true)) {
            throw new \InvalidArgumentException('unknown method: ' . $method);
        }

        $curl = new Curl();
        $curl->setDefaultJsonDecoder($assoc = true);
        $curl->setHeader('Authorization', 'Bearer ' . $this->accessToken);
        $curl->setHeader('Content-Type', 'application/json');

        if ('GET' === $method) {
            $curl->get($this->hsUrl . $path, $qs);
        } elseif ('POST' === $method) {
            $curl->setUrl($this->hsUrl . $path, $qs);
            $curl->post($curl->getUrl(), $body);
        } elseif ('PUT' === $method) {
            $curl->setUrl($this->hsUrl . $path, $qs);
            $curl->put($curl->getUrl(), $body);
        }

        if ($curl->error) {
            throw new \Exception('request failed - Code: ' . $curl->errorCode . ' Message: ' . $curl->errorMessage);
        }

        return $curl->response;
    }
}
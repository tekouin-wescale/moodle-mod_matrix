<?php

/**
 * @package   mod_matrix
 * @copyright 2020, New Vector Ltd (Trading as Element)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3 or later
 */

namespace mod_matrix\Matrix\Application;

use mod_matrix\Matrix;

interface Api
{
    /**
     * @see https://matrix.org/docs/api/client-server/#!/User32data/getTokenOwner
     */
    public function whoami(): Matrix\Domain\UserId;

    /**
     * @see https://matrix.org/docs/api/client-server/#!/Room32creation/createRoom
     * @param mixed $opts
     */
    public function createRoom($opts = []): Matrix\Domain\RoomId;

    /**
     * @see https://matrix.org/docs/api/client-server/#!/Room32membership/inviteBy3PID
     */
    public function inviteUser(
        Matrix\Domain\UserId $userId,
        Matrix\Domain\RoomId $roomId
    ): void;

    /**
     * @see https://matrix.org/docs/api/client-server/#!/Room32membership/kick
     */
    public function kickUser(
        Matrix\Domain\UserId $userId,
        Matrix\Domain\RoomId $roomId
    ): void;

    /**
     * @see https://matrix.org/docs/api/client-server/#!/Room32participation/getRoomStateWithKey
     * @param mixed $eventType
     * @param mixed $stateKey
     */
    public function getState(
        Matrix\Domain\RoomId $roomId,
        $eventType,
        $stateKey
    );

    /**
     * @see https://matrix.org/docs/api/client-server/#!/Room32participation/setRoomStateWithKey
     * @param mixed $eventType
     * @param mixed $stateKey
     * @param mixed $content
     */
    public function setState(
        Matrix\Domain\RoomId $roomId,
        $eventType,
        $stateKey,
        $content
    ): void;

    /**
     * @see https://matrix.org/docs/api/client-server/#!/Room32participation/getMembersByRoom
     *
     * @return array<int, Matrix\Domain\UserId>
     */
    public function getMembersOfRoom(Matrix\Domain\RoomId $roomId): array;

    public function debug($val): void;
}

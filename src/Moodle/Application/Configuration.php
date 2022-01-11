<?php

declare(strict_types=1);

/**
 * @package   mod_matrix
 * @copyright 2020, New Vector Ltd (Trading as Element)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3 or later
 */

namespace mod_matrix\Moodle\Application;

final class Configuration
{
    private $accessToken;
    private $elementUrl;
    private $homeserverUrl;

    private function __construct(
        string $accessToken,
        string $elementUrl,
        string $homeserverUrl
    ) {
        $this->accessToken = $accessToken;
        $this->elementUrl = $elementUrl;
        $this->homeserverUrl = $homeserverUrl;
    }

    public static function default(): self
    {
        return new self(
            '',
            'https://matrix-client.matrix.org',
            '',
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function fromObject(\stdClass $object): self
    {
        if (!\property_exists($object, 'access_token')) {
            throw new \InvalidArgumentException(\sprintf(
                'Configuration should have an "%s" property, but it does not.',
                'access_token',
            ));
        }

        $accessToken = $object->access_token;

        if (!\is_string($accessToken)) {
            throw new \InvalidArgumentException(\sprintf(
                'Configuration "%s" should be a string, got "%s" instead.',
                'access_token',
                \is_object($accessToken) ? \get_class($accessToken) : \gettype($accessToken),
            ));
        }

        if (!\property_exists($object, 'element_url')) {
            throw new \InvalidArgumentException(\sprintf(
                'Configuration should have an "%s" property, but it does not.',
                'element_url',
            ));
        }

        $elementUrl = $object->element_url;

        if (!\is_string($elementUrl)) {
            throw new \InvalidArgumentException(\sprintf(
                'Configuration "%s" should be a string, got "%s" instead..',
                'element_url',
                \is_object($elementUrl) ? \get_class($elementUrl) : \gettype($elementUrl),
            ));
        }

        if (!\property_exists($object, 'homeserver_url')) {
            throw new \InvalidArgumentException(\sprintf(
                'Configuration should have an "%s" property, but it does not.',
                'homeserver_url',
            ));
        }

        $homeserverUrl = $object->homeserver_url;

        if (!\is_string($homeserverUrl)) {
            throw new \InvalidArgumentException(\sprintf(
                'Configuration "%s" should be a string, got "%s" instead..',
                'homeserver_url',
                \is_object($homeserverUrl) ? \get_class($homeserverUrl) : \gettype($homeserverUrl),
            ));
        }

        return new self(
            \trim($accessToken),
            \trim($elementUrl),
            \trim($homeserverUrl),
        );
    }

    public function accessToken(): string
    {
        return $this->accessToken;
    }

    public function elementUrl(): string
    {
        return $this->elementUrl;
    }

    public function homeserverUrl(): string
    {
        return $this->homeserverUrl;
    }
}
<?php

declare(strict_types=1);

/**
 * @package   mod_matrix
 * @copyright 2020, New Vector Ltd (Trading as Element)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3 or later
 */

namespace mod_matrix\Test\Unit\Matrix\Application;

use mod_matrix\Matrix;
use mod_matrix\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \mod_matrix\Matrix\Application\Configuration
 */
final class ConfigurationTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testDefaultReturnsConfiguration(): void
    {
        $configuration = Matrix\Application\Configuration::default();

        self::assertSame('', $configuration->accessToken());
        self::assertSame('https://matrix-client.matrix.org', $configuration->elementUrl());
        self::assertSame('', $configuration->homeserverUrl());
    }

    public function testFromObjectRejectsObjectWhenAccessTokenPropertyIsMissing(): void
    {
        $faker = self::faker();

        $object = new \stdClass();

        $object->element_url = $faker->url();
        $object->homeserver_url = $faker->url();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Configuration should have an "%s" property, but it does not.',
            'access_token',
        ));

        Matrix\Application\Configuration::fromObject($object);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\BoolProvider::arbitrary()
     * @dataProvider \Ergebnis\DataProvider\FloatProvider::arbitrary()
     * @dataProvider \Ergebnis\DataProvider\IntProvider::arbitrary()
     * @dataProvider \Ergebnis\DataProvider\NullProvider::null()
     * @dataProvider \Ergebnis\DataProvider\ObjectProvider::object()
     *
     * @param mixed $accessToken
     */
    public function testFromObjectRejectsObjectWhenAccessTokenPropertyIsNotAString($accessToken): void
    {
        $faker = self::faker();

        $object = new \stdClass();

        $object->access_token = $accessToken;
        $object->element_url = $faker->url();
        $object->homeserver_url = $faker->url();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Configuration "%s" should be a string, got "%s" instead.',
            'access_token',
            \is_object($accessToken) ? \get_class($accessToken) : \gettype($accessToken),
        ));

        Matrix\Application\Configuration::fromObject($object);
    }

    public function testFromObjectRejectsObjectWhenElementUrlPropertyIsMissing(): void
    {
        $faker = self::faker();

        $object = new \stdClass();

        $object->access_token = $faker->sha1();
        $object->homeserver_url = $faker->url();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Configuration should have an "%s" property, but it does not.',
            'element_url',
        ));

        Matrix\Application\Configuration::fromObject($object);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\BoolProvider::arbitrary()
     * @dataProvider \Ergebnis\DataProvider\FloatProvider::arbitrary()
     * @dataProvider \Ergebnis\DataProvider\IntProvider::arbitrary()
     * @dataProvider \Ergebnis\DataProvider\NullProvider::null()
     * @dataProvider \Ergebnis\DataProvider\ObjectProvider::object()
     *
     * @param mixed $elementUrl
     */
    public function testFromObjectRejectsObjectWhenElementUrlPropertyIsNotAString($elementUrl): void
    {
        $faker = self::faker();

        $object = new \stdClass();

        $object->access_token = $faker->sha1();
        $object->element_url = $elementUrl;
        $object->homeserver_url = $faker->url();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Configuration "%s" should be a string, got "%s" instead.',
            'element_url',
            \is_object($elementUrl) ? \get_class($elementUrl) : \gettype($elementUrl),
        ));

        Matrix\Application\Configuration::fromObject($object);
    }

    public function testFromObjectRejectsObjectWhenHomeserverUrlPropertyIsMissing(): void
    {
        $faker = self::faker();

        $object = new \stdClass();

        $object->access_token = $faker->sha1();
        $object->element_url = $faker->sha1();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Configuration should have an "%s" property, but it does not.',
            'homeserver_url',
        ));

        Matrix\Application\Configuration::fromObject($object);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\BoolProvider::arbitrary()
     * @dataProvider \Ergebnis\DataProvider\FloatProvider::arbitrary()
     * @dataProvider \Ergebnis\DataProvider\IntProvider::arbitrary()
     * @dataProvider \Ergebnis\DataProvider\NullProvider::null()
     * @dataProvider \Ergebnis\DataProvider\ObjectProvider::object()
     *
     * @param mixed $homeserverUrl
     */
    public function testFromObjectRejectsObjectWhenHomeserverUrlPropertyIsNotAString($homeserverUrl): void
    {
        $faker = self::faker();

        $object = new \stdClass();

        $object->access_token = $faker->sha1();
        $object->element_url = $faker->url();
        $object->homeserver_url = $homeserverUrl;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Configuration "%s" should be a string, got "%s" instead.',
            'homeserver_url',
            \is_object($homeserverUrl) ? \get_class($homeserverUrl) : \gettype($homeserverUrl),
        ));

        Matrix\Application\Configuration::fromObject($object);
    }

    public function testFromObjectReturnsConfiguration(): void
    {
        $faker = self::faker();

        $object = new \stdClass();

        $object->access_token = $faker->sha1();
        $object->element_url = $faker->url();
        $object->homeserver_url = $faker->url();

        $configuration = Matrix\Application\Configuration::fromObject($object);

        self::assertSame($object->access_token, $configuration->accessToken());
        self::assertSame($object->element_url, $configuration->elementUrl());
        self::assertSame($object->homeserver_url, $configuration->homeserverUrl());
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank()
     */
    public function testFromObjectReturnsConfigurationWhenFieldsAreUntrimmed(string $whitespace): void
    {
        $faker = self::faker();

        $object = new \stdClass();

        $object->access_token = \sprintf(
            '%s%s%s',
            $whitespace,
            $faker->sha1(),
            $whitespace,
        );

        $object->element_url = \sprintf(
            '%s%s%s',
            $whitespace,
            $faker->url(),
            $whitespace,
        );
        $object->homeserver_url = \sprintf(
            '%s%s%s',
            $whitespace,
            $faker->url(),
            $whitespace,
        );

        $configuration = Matrix\Application\Configuration::fromObject($object);

        self::assertSame(\trim($object->access_token), $configuration->accessToken());
        self::assertSame(\trim($object->element_url), $configuration->elementUrl());
        self::assertSame(\trim($object->homeserver_url), $configuration->homeserverUrl());
    }
}

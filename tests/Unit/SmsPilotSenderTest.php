<?php

use PHPUnit\Framework\TestCase;

final class SmsPilotSenderTest extends TestCase
{
    public function testBuildsJsonRequestAndAcceptsSuccessfulResponse(): void
    {
        $requestedUrl = null;
        $sender = new SmsPilotSender(
            apiKey: 'test-api-key',
            sender: 'BOOKS',
            testMode: true,
            httpClient: static function (string $url) use (&$requestedUrl): string {
                $requestedUrl = $url;

                return '{"send":[{"server_id":"10000","phone":"79081234567","price":"1.68","status":"0"}],"balance":"11908.50","cost":"1.68"}';
            },
        );

        $payload = $sender->sendWithResponse('+79990000001', 'Новая книга');

        self::assertIsString($requestedUrl);
        self::assertStringContainsString('apikey=test-api-key', $requestedUrl);
        self::assertStringContainsString('to=%2B79990000001', $requestedUrl);
        self::assertStringContainsString('send=%D0%9D%D0%BE%D0%B2%D0%B0%D1%8F%20%D0%BA%D0%BD%D0%B8%D0%B3%D0%B0', $requestedUrl);
        self::assertStringContainsString('from=BOOKS', $requestedUrl);
        self::assertStringContainsString('format=json', $requestedUrl);
        self::assertStringContainsString('test=1', $requestedUrl);
        self::assertSame('0', $payload['send'][0]['status']);
        self::assertSame('11908.50', $payload['balance']);
    }

    public function testThrowsWhenApiKeyIsMissing(): void
    {
        $sender = new SmsPilotSender('');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('SMSPilot API key is missing.');

        $sender->send('+79990000001', 'Новая книга');
    }

    public function testThrowsWhenGatewayReturnsApiError(): void
    {
        $sender = new SmsPilotSender(
            apiKey: 'test-api-key',
            httpClient: static fn(string $url): string => '{"error":{"code":"111","description":"Invalid phone","description_ru":"Неправильный номер телефона"}}',
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('111');
        $this->expectExceptionMessage('Неправильный номер телефона');

        $sender->send('+79990000001', 'Новая книга');
    }

    public function testThrowsWhenGatewayReturnsInvalidJson(): void
    {
        $sender = new SmsPilotSender(
            apiKey: 'test-api-key',
            httpClient: static fn(string $url): string => 'not-json',
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid JSON');

        $sender->send('+79990000001', 'Новая книга');
    }

    public function testThrowsWhenGatewayRequestFails(): void
    {
        $sender = new SmsPilotSender(
            apiKey: 'test-api-key',
            httpClient: static fn(string $url) => false,
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('SMSPilot request failed.');

        $sender->send('+79990000001', 'Новая книга');
    }
}

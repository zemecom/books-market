<?php

declare(strict_types=1);

class SmsPilotSender implements SmsSenderInterface
{
    private const API_ENDPOINT = 'https://smspilot.ru/api.php';

    /** @var callable */
    private $httpClient;

    public function __construct(
        private string $apiKey,
        private string $sender = '',
        private bool $testMode = false,
        ?callable $httpClient = null,
    ) {
        $this->httpClient = $httpClient ?: [$this, 'performRequest'];
    }

    public function send(string $phone, string $message): void
    {
        $this->sendWithResponse($phone, $message);
    }

    public function sendWithResponse(string $phone, string $message): array
    {
        if ($this->apiKey === '') {
            throw new RuntimeException('SMSPilot API key is missing.');
        }

        $params = [
            'send' => $message,
            'to' => $phone,
            'apikey' => $this->apiKey,
            'format' => 'json',
        ];

        if ($this->sender !== '') {
            $params['from'] = $this->sender;
        }

        if ($this->testMode) {
            $params['test'] = '1';
        }

        $url = self::API_ENDPOINT . '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        $response = call_user_func($this->httpClient, $url);

        if ($response === false) {
            throw new RuntimeException('SMSPilot request failed.');
        }

        $payload = json_decode($response, true);
        if (!is_array($payload)) {
            throw new RuntimeException('SMSPilot returned invalid JSON.');
        }

        if (isset($payload['error']) && is_array($payload['error'])) {
            $code = (string) ($payload['error']['code'] ?? 'unknown');
            $description = (string) ($payload['error']['description_ru'] ?? $payload['error']['description'] ?? 'Unknown error');

            throw new RuntimeException(sprintf('SMSPilot error %s: %s', $code, $description));
        }

        if (!isset($payload['send'][0]['status'])) {
            throw new RuntimeException('SMSPilot returned unexpected response.');
        }

        if ((string) $payload['send'][0]['status'] !== '0') {
            throw new RuntimeException(sprintf('SMSPilot send failed with status %s.', (string) $payload['send'][0]['status']));
        }

        return $payload;
    }

    private function performRequest(string $url)
    {
        return @file_get_contents(
            $url,
            false,
            stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'ignore_errors' => true,
                ],
            ]),
        );
    }
}

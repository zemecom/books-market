<?php

require_once dirname(__DIR__, 2) . '/protected/components/Env.php';
require_once dirname(__DIR__, 2) . '/protected/services/notification/SmsSenderInterface.php';
require_once dirname(__DIR__, 2) . '/protected/services/notification/SmsPilotSender.php';

$dotEnv = Env::loadFile(dirname(__DIR__, 2) . '/.env');
$apiKey = Env::string('SMSPILOT_API_KEY', $dotEnv);
$sender = Env::string('SMSPILOT_SENDER', $dotEnv);
$phone = Env::string('SMSPILOT_TEST_PHONE', $dotEnv);
$testMode = Env::bool('SMSPILOT_TEST') || in_array(
    strtolower(Env::string('SMSPILOT_TEST', $dotEnv)),
    ['1', 'true', 'yes', 'on'],
    true,
);
$message = 'test';

if ($apiKey === '') {
    fwrite(STDERR, "SMSPILOT_API_KEY is missing.\n");
    exit(1);
}

if ($phone === '') {
    fwrite(STDERR, "SMSPILOT_TEST_PHONE is missing.\n");
    exit(1);
}

$transport = Env::string('SMS_TRANSPORT', $dotEnv, 'fake');
if ($transport !== 'smpilot') {
    fwrite(STDERR, "SMS transport is not smspilot. Current value: {$transport}\n");
    exit(1);
}

$senderClient = new SmsPilotSender($apiKey, $sender, $testMode);

try {
    $payload = $senderClient->sendWithResponse($phone, $message);
} catch (Throwable $exception) {
    fwrite(STDERR, "SMSPilot test failed: {$exception->getMessage()}\n");
    exit(1);
}

fwrite(
    STDOUT,
    sprintf(
        "SMSPilot test request accepted for %s (test mode: %s).\n",
        $phone,
        $testMode ? 'on' : 'off',
    ),
);
fwrite(STDOUT, "SMSPilot response:\n");
fwrite(STDOUT, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
exit(0);

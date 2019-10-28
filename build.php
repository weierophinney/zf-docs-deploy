<?php

use Dotenv\Dotenv;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;

require 'vendor/autoload.php';

$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

$client         = HttpClientDiscovery::find();
$messageFactory = MessageFactoryDiscovery::find();
$streamFactory  = StreamFactoryDiscovery::find();

$baseRequest = $messageFactory->createRequest('POST', 'https://zfbot.mwop.net/docs')
    ->withHeader('Authorization', sprintf('Bearer %s', getenv('ZFBOT_TOKEN')))
    ->withHeader('Accept', 'application/json')
    ->withHeader('Content-Type', 'application/json');

$fh = fopen('./component-list.txt', 'r');
while (! feof($fh)) {
    $line = fgets($fh);
    if (empty($line)) {
        continue;
    }

    $repo = sprintf('zendframework/%s', trim($line));
    $stream = $streamFactory->createStream(json_encode([
        'repo' => $repo,
    ], JSON_UNESCAPED_SLASHES));

    $request = $baseRequest->withBody($stream);

    printf("[%s] Triggering documentation build for %s...", date('Y-m-d H:i:s'), $repo);
    $response = $client->sendRequest($request);
    printf(" %d %s\n", $response->getStatusCode(), $response->getReasonPhrase());

    // Give zfbot a chance to keep up with the queue
    sleep(15);
}

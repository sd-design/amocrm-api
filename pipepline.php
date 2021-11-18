<?php

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\Leads\Pipelines\PipelinesCollection;
use AmoCRM\Collections\Leads\Pipelines\Statuses\StatusesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\Leads\Pipelines\PipelineModel;
use AmoCRM\Models\Leads\Pipelines\Statuses\StatusModel;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ . '/bootstrap.php';

$accessToken = getToken();

/**
 * @noinspection PhpRedundantVariableDocTypeInspection
 * @var AmoCRMApiClient $apiClient
 */
$apiClient->setAccessToken($accessToken)
    ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
    ->onAccessTokenRefresh(
        function (AccessTokenInterface $accessToken, string $baseDomain) {
            saveToken(
                [
                    'accessToken' => $accessToken->getToken(),
                    'refreshToken' => $accessToken->getRefreshToken(),
                    'expires' => $accessToken->getExpires(),
                    'baseDomain' => $baseDomain,
                ]
            );
        }
    );
$pipelinesService = $apiClient->pipelines();
try {
    $pipelinesCollection = $pipelinesService->get();
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
foreach ($pipelinesCollection as $lead) {
    $fields = $lead->toArray();
    var_dump($fields['name']);
}
echo '<pre>' . $pipelinesCollection->count() . ' воронок в аккаунте</pre>';


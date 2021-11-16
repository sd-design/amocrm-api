<?php
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\TasksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\TaskModel;
use League\OAuth2\Client\Token\AccessTokenInterface;

include_once __DIR__ . '/bootstrap.php';

$accessToken = getToken();
$baseDomain = 'deaqua.amocrm.ru';

$apiClient->setAccessToken($accessToken)
    ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
    ->onAccessTokenRefresh(function (AccessTokenInterface $accessToken, string $baseDomain) {
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

$tasksService = $apiClient->tasks();

$tasksFilter = new \AmoCRM\Filters\TasksFilter();
$tasksFilter->setTaskTypeId(TaskModel::TASK_TYPE_ID_MEETING);
try {
    $tasksCollection = $tasksService->get($tasksFilter);
} catch (AmoCRMApiException $e) {
    printError($e);
    die;
}
var_dump($tasksCollection->toArray());
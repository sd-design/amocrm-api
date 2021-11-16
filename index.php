<?php
$subdomain = 'deaqua'; //Поддомен нужного аккаунта
$link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса

/** Соберем данные для запроса */
$data = [
    'client_id' => 'bbe6e14f-cc3f-4119-91a0-c675f5df01b4',
    'client_secret' => 'BaDobfDWOkJbb8DEK5q8zNg9JjNizDbjaAw1ZrPkCfwyorZDPUAjndeXU9i3Iyl9',
    'grant_type' => 'authorization_code',
    'code' => 'def5020004bd33dc11c403183c89c3685aa440c19a4c5b28b8e341b76fe9ae8a0095b16798c4488896b2baef936b77235c0f5275188f4d8b9dab8cbee8afcc48397f6f3363ddcf28bf641f892848c77b74a5366cfb61b5e9c30b9f73da7ebd746e0487693d31eede431272e37d8115a6901f771c018298efc488b0e1788bcd5a5a13a172208eea9946e4af5d3dccb2386e8e2daa34a710e87028d66866ffd03983016489c7b4e3e06e23121282f5a7191deaca95642837d16ca97892cfad2a9d4675c1a871841a842e46b9b87bb57b764a22e9df1cbecd442b6e0d70d05249833a7567bbb406771f44fcae38665bf729eee52e45f0dda0ca48bb3300231d09bd02d00b81df417a193e1a6a12f35602751f21f9c3104911dbddd1ea6d59f469b4a942da2f4293a467ef911596cb8250d51bdc02c405deeffed56177915dcbd61432408f60f0e06e6211f1b323715375766d4ad487c117183e1143e34bde3dcc20a37b1c9dac43c06fb939443256921f4545b48eb36453d376949761f457346cb9219983828dc4190f1f4743b65943c8e4d8e26d842a535d584e7383f8ad7aa659c5798dac1580b395571130fe592a7423639d82e585b9c17afed785f2816c1cb14ea3bba1',
    'redirect_uri' => 'http://api.deaqua.market/',
];

/**
 * Нам необходимо инициировать запрос к серверу.
 * Воспользуемся библиотекой cURL (поставляется в составе PHP).
 * Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
 */
$curl = curl_init(); //Сохраняем дескриптор сеанса cURL
/** Устанавливаем необходимые опции для сеанса cURL  */
curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
curl_setopt($curl,CURLOPT_URL, $link);
curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
curl_setopt($curl,CURLOPT_HEADER, false);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
/** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
$code = (int)$code;
$errors = [
    400 => 'Bad request',
    401 => 'Unauthorized',
    403 => 'Forbidden',
    404 => 'Not found',
    500 => 'Internal server error',
    502 => 'Bad gateway',
    503 => 'Service unavailable',
];

try
{
    /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
    if ($code < 200 || $code > 204) {
        throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
    }
}
catch(\Exception $e)
{
    die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
}

/**
 * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
 * нам придётся перевести ответ в формат, понятный PHP
 */
$response = json_decode($out, true);

$access_token = $response['access_token']; //Access токен
$refresh_token = $response['refresh_token']; //Refresh токен
$token_type = $response['token_type']; //Тип токена
$expires_in = $response['expires_in'];
printf($out);
var_dump($response);
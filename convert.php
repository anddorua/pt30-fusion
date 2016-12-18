<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 18.12.16
 * Time: 22:07
 */

require_once (__DIR__ . '/vendor/autoload.php');

$template_file_name = 'py3o_example_template.odt';
$body = fopen('templates/' . $template_file_name, 'r');

$client = new GuzzleHttp\Client();
$res = $client->request('POST', 'http://localhost:8765/form', [
    'multipart' => [
        [
            'name' => 'targetformat',
            'contents' => 'pdf',
        ],
        [
            'name' => 'datadict',
            'contents' => json_encode([
                'document' => [
                    'total' => '999999999.99',
                ],
                'items' => [
                    [ 'val1' => 'Item1 Value1', 'val2' => 'Item1 Value2', 'val3' => 'Item1 Value3', 'Currency' => 'EUR', 'Amount' => '12345.35', 'InvoiceRef' => '#1234' ],
                    [ 'val1' => 'Item2 Value1', 'val2' => 'Item2 Value2', 'val3' => 'Item2 Value3', 'Currency' => 'EUR', 'Amount' => '6666.77', 'InvoiceRef' => '#0001' ],
                    [ 'val1' => 'Item3 Value1', 'val2' => 'Item3 Value2', 'val3' => 'Item3 Value3', 'Currency' => 'EUR', 'Amount' => '77777.88', 'InvoiceRef' => '#0002' ],
                    [ 'val1' => 'Item4 Value1', 'val2' => 'Item4 Value2', 'val3' => 'Item4 Value3', 'Currency' => 'EUR', 'Amount' => '888888.99', 'InvoiceRef' => '#0003' ],
                ],
            ]),
        ],
        [
            'name' => 'image_mapping',
            'contents' => '{}',
        ],
        [
            'name' => 'tmpl_file',
            'contents' => $body,
            'filename' => $template_file_name,
        ],
    ],
]);

echo "result = {$res->getStatusCode()}\n";

if ($res->getStatusCode() == 400) {
    print_r(json_decode($res->getBody()->getContents()));
} else {
    $res_file_name = 'results/' . basename($template_file_name, '.odt') . '.pdf';
    file_put_contents($res_file_name, $res->getBody());
}
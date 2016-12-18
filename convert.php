<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 18.12.16
 * Time: 22:07
 */

require_once (__DIR__ . '/vendor/autoload.php');

$template_file_name = 'fusion2pdf.odt';
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
                    'person_name' => 'Aide',
                    'person_surname' => 'Florent',
                    'person_company' => 'XCG Consulting',
                    'person_url' => 'http://www.xcg-consulting.fr'
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
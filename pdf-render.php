<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;

function abort(int $code, string $message): never
{
    http_response_code($code);
    header('Content-Type: text/plain; charset=utf-8');
    echo $message;
    exit;
}

$templateName = $_GET['template'] ?? 'incident_report';

if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $templateName)) {
    abort(400, 'Invalid template name.');
}

$tmplDir = __DIR__ . '/tmpl/' . $templateName;

$bundleFile   = $tmplDir . '/bundle.xml';
$templateFile = $tmplDir . '/template.php';
$dataFile     = $tmplDir . '/data.php';

if (!is_dir($tmplDir)) {
    abort(404, 'Template directory not found.');
}

if (!is_file($bundleFile)) {
    abort(500, 'bundle.xml missing.');
}

if (!is_file($templateFile)) {
    abort(500, 'template.php missing.');
}

/**
 * Load bundle.xml
 */
libxml_use_internal_errors(true);
$xml = simplexml_load_file($bundleFile);

if (!$xml) {
    abort(500, 'Invalid bundle.xml.');
}

$format   = (string)($xml->format ?? 'Letter');
$title    = (string)($xml->title ?? 'Document');
$filename = (string)($xml->filename ?? 'document.pdf');

$margins = [
    'left'   => (int)($xml->margins->left   ?? 12),
    'right'  => (int)($xml->margins->right  ?? 12),
    'top'    => (int)($xml->margins->top    ?? 12),
    'bottom' => (int)($xml->margins->bottom ?? 12),
];

/**
 * Load data
 */
$data = [];
if (is_file($dataFile)) {
    $data = require $dataFile;
    if (!is_array($data)) {
        abort(500, 'data.php must return an array.');
    }
}

/**
 * Render template
 */
ob_start();
try {
    require $templateFile;
    $html = ob_get_clean();
} catch (Throwable $e) {
    ob_end_clean();
    abort(500, "Template error:\n" . $e->getMessage());
}

/**
 * Render PDF
 */
$fontDir = $bundleDir . '/fonts';

$mpdf = new \Mpdf\Mpdf([
    'tempDir' => __DIR__ . '/tmp',
    'format'  => $format,
    'margin_left'   => $margins['left'],
    'margin_right'  => $margins['right'],
    'margin_top'    => $margins['top'],
    'margin_bottom' => $margins['bottom'],

    'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
        $fontDir,
    ]),
    'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
        'opensans' => [
            'R'  => 'OpenSans-Regular.ttf',
            'B'  => 'OpenSans-Bold.ttf',
            'I'  => 'OpenSans-Italic.ttf',
            'BI' => 'OpenSans-BoldItalic.ttf',
        ],
    ],
    'default_font' => 'opensans',
]);


$mpdf->SetTitle($title);
$mpdf->WriteHTML($html);

$download = ($_GET['download'] ?? '1') === '1';
$mpdf->Output($filename, $download ? 'D' : 'I');

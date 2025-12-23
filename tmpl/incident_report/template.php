<?php
declare(strict_types=1);


$displayData ??= [];
$task = $displayData['task'] ?? 'previewPdf';

$incident  = ($displayData['incident'] ?? null);
$account  = ($displayData['account'] ?? null);
$client   =  ($displayData['client'] ?? null);
$business =  ($displayData['business'] ?? null);
$items    = $incident->items ?? [];

$timeline = $displayData['timeline'] ?? [];

$imgHr     = realpath(__DIR__ . '/images/custom-hr.jpg') ?: '';
$imgBullet = realpath(__DIR__ . '/images/invoice-bullet.png') ?: '';

function e($s): string {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Incident <?= e($incident['number'] ?? ''); ?></title>

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            font-size: 10pt;
            color: #3A3A3A;
            margin: 20mm auto;
            width: 188mm;
        }

        .header {

            margin-bottom: 8mm;
        }

        .logo-company-block {

            margin-bottom: 5mm;
        }

        .company-info,
        .client-info {
            font-size: 8pt;
            line-height: 1.5;
        }

        .header-right h1 {
            margin: 0 0 4mm 0;
            font-size: 20pt;
            color: #539CCD;
        }

        h1 {
            color: #539CCD;
            white-space: nowrap;
        }

        .invoice-meta p {
            margin: 1mm 0;
            font-size: 9pt;
        }

        .section {
            margin-top: 10mm;
        }

        .account-heading {
            font-size: 13pt;
            font-weight: bold;
            margin: 6mm 0 4mm;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4mm;
        }

        .items-table th,
        .items-table td {
            padding: 4px 6px;
        }

        .items-table th {
            background: #FFFFFF;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        .total {
            text-align: right;
            margin-top: 6mm;
            font-size: 12pt;
            font-weight: bold;
            color: #539CCD;
        }

        .footer {
            font-size: 9pt;
            font-weight: normal;
            margin-top: 10mm;
            line-height: 1.4;
        }

        .final-company-info {
            text-align: center;
            margin-top: 16mm;
            font-size: 10pt;
            color: #444;
            line-height: 1.4;
        }

        .timeline-section table thead tr th {
            border: 1px solid #CCC;
        }
        .timeline-section table tbody tr th:nth-child(2) {
    
            border-right: 0px !important;
        }

        .timeline-section tr td {
            border: 0px;
            border-bottom: 1px solid #CCC;
            padding: 5px;
        }

        .timeline-section tr td:nth-child(1) {
            border-left: 1px solid #CCC;
        }

        .timeline-section tr td:nth-child(2) {
            border-left: 1px solid #CCC;
            border-right: 1px solid #CCC;
        }
    </style>
</head>

<body>

<table class="header" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 8mm;">
    <tr valign="top">
        <td style="width: 50%;">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="vertical-align: top; padding-left: 10px;">
                        <div class="company-info" style="text-align: left;">
                            <table cellpadding="0" cellspacing="0">
                                <tr valign="top">
                                    <td style="text-align: right; vertical-align: top; padding-right: 12px;">
                                        <?php if (!empty($business['company_name'])): ?>
                                            <?php
                                                // Split "Trevor Bice Webdesign" into two parts
                                                $companyName = $business['company_name'];
                                                $nameParts = explode(' ', $companyName, 3);
                                                $firstName = $nameParts[0] ?? '';
                                                $lastName = $nameParts[1] ?? '';
                                                $webdesign = strtoupper($nameParts[2] ?? '');
                                            ?>
                                            <span style="font-size:10pt; color:#000;line-height:8pt;text-transform:uppercase;"><?php echo htmlspecialchars("{$firstName} {$lastName}"); ?></span><br>
                                            <span style="font-size:16pt; font-weight:bold;line-height:8pt; letter-spacing:2px;"><?php echo htmlspecialchars($webdesign); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: left; font-size:7pt; color:#666; vertical-align: top;">
                                        <?php if (!empty($business['company_address_1'])): ?>
                                            <div><?php echo nl2br(htmlspecialchars($business['company_address_1'])); ?></div>
                                        <?php endif; ?>
                                        <?php
                                            $city = $business['company_city'] ?? '';
                                            $state = $business['company_state'] ?? '';
                                            $zip = $business['company_zip'] ?? '';
                                            $addressLine = trim("{$city}" . ($state ? ", {$state}" : '') . ($zip ? " {$zip}" : ''));
                                        ?>
                                        <?php if ($addressLine): ?>
                                            <div><?php echo htmlspecialchars($addressLine); ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($business['company_phone'])): ?>
                                            <div><?php echo htmlspecialchars($business['company_phone']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>

            <br/><br/><br/>

            <div class="client-info" style="margin-top: 0;line-height:1.2em;font-size:12pt;font-weight:300;">
                <p style="margin:0px;"><?php echo htmlspecialchars($client['name'] ?? $incident->client_name ?? ''); ?></p>
                <?php if (!empty($client['address_1'])): ?>
                    <p style="margin:0px;"><?php echo htmlspecialchars($client['address_1']); ?></p>
                <?php endif; ?>
                <?php if (!empty($client['address_2'])): ?>
                    <p style="margin:0px;"><?php echo htmlspecialchars($client['address_2']); ?></p>
                <?php endif; ?>
                <?php if (!empty($client['city']) || !empty($client['state']) || !empty($client['zip'])): ?>
                    <p style="margin:0px;">
                        <?php echo htmlspecialchars($client['city'] ?? ''); ?>
                        <?php echo !empty($client['state']) ? ', ' . htmlspecialchars($client['state']) : ''; ?>
                        <?php echo htmlspecialchars($client['zip'] ?? ''); ?>
                    </p>
                <?php endif; ?>
            </div>
        </td>
        
        <td style="width: 50%; vertical-align: top; text-align: right;">
            <div class="header-right">
                <h1 style="font-weight:300;letter-spacing:1pt;">Incident Report</h1>
                <div class="incident-meta" style="text-align: right;">
                    
                </div>
            </div>
        </td>
    </tr>
</table>



<img src="<?= e($imgHr) ?>" alt="" style="width:100%; height:1px; margin:0;" />
<div class="section account-heading">
    <?php echo htmlspecialchars($account->name ?? ''); ?><br/>
    <h6 style="margin:0px;padding:0px;margin-top:2mm;"><?php echo htmlspecialchars($incident['report']['title'] ?? ''); ?></h6>
</div>
<img src="<?= e($imgHr) ?>" alt="" style="width:100%; height:1px; margin:0;" />

    <!-- Summary -->
    <div class="section">
        <div class="section-title" style="font-weight:bold;text-transform:uppercase;">Executive Summary</div>
        <p><?php echo $incident['summary'] ?? ''; ?></p>

    </div>

    <!-- Root Cause -->
    <div class="section">
        <div class="section-title" style="font-weight:bold;text-transform:uppercase;">Root Cause</div>
        <p><?php echo $incident['root_cause'] ?? ''; ?></p>
    </div>


        <!-- Timeline -->
    <div class="section timeline-section">
        <div class="section-title" style="font-weight:bold;text-transform:uppercase;">Timeline</div>
        <?php
        // Protect usort: only sort if $timeline is an array with at least 2 elements
        if (is_array($timeline) && count($timeline) > 1) {
            // Sort oldest → newest using DateTime for reliability.
            usort($timeline, function ($a, $b) {
            $aDate = is_array($a) ? (string) ($a['date'] ?? '') : '';
            $aTime = is_array($a) ? (string) ($a['time'] ?? '') : '';
            $bDate = is_array($b) ? (string) ($b['date'] ?? '') : '';
            $bTime = is_array($b) ? (string) ($b['time'] ?? '') : '';

            // Default to midnight when time is missing.
            $aTimeForParse = $aTime !== '' ? $aTime : '00:00';
            $bTimeForParse = $bTime !== '' ? $bTime : '00:00';

            $aDt = \DateTime::createFromFormat('m/d/Y H:i', trim("{$aDate} {$aTimeForParse}")) ?: null;
            $bDt = \DateTime::createFromFormat('m/d/Y H:i', trim("{$bDate} {$bTimeForParse}")) ?: null;

            $aTs = $aDt ? $aDt->getTimestamp() : PHP_INT_MAX;
            $bTs = $bDt ? $bDt->getTimestamp() : PHP_INT_MAX;

            if ($aTs === $bTs) {
                // Stable-ish fallback to keep deterministic order.
                return strcmp("{$aDate} {$aTime}", "{$bDate} {$bTime}");
            }
            return $aTs <=> $bTs;
            });
        }
        ?>

        <table class="table" cellpadding="0" cellspacing="0">
            <thead>
                <tr style="background-color:#f5f5f5;">
                    <th style="width:18%;">Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                <?php foreach ($timeline as $row): ?>
                    <?php if (!is_array($row)) {
                        continue;
                    } ?>
                    <?php $rowClass = ($i++ % 2 === 0) ? '' : 'row-alt'; ?>
                    <tr class="<?= $rowClass ?>">
                        <td style="text-align:left;vertical-align:top;">
                            <strong><?= e($row['date'] ?? '—') ?></strong><br /><?= e($row['time'] ?? '—') ?></td>
                        <td><?= e($row['description'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <?php if (!empty($report['footer'])): ?>
            <strong><?= e($report['footer']) ?></strong>
        <?php endif; ?>
    </div>

</body>

</html>
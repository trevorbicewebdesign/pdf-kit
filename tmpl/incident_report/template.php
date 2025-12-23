<?php
declare(strict_types=1);

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">

<style>
body {
    font-family: sans-serif;
    font-size: 11pt;
    color: #222;
}

h1 {
    font-size: 18pt;
    margin-bottom: 6px;
}

.meta {
    margin-bottom: 18px;
    font-size: 10pt;
    color: #555;
}

.section {
    margin-top: 18px;
}

.section-title {
    font-weight: bold;
    border-bottom: 1px solid #333;
    margin-bottom: 6px;
}

ul {
    margin: 0;
    padding-left: 18px;
}

table.timeline {
    width: 100%;
    border-collapse: collapse;
    margin-top: 8px;
}

.timeline td {
    padding: 6px;
    border-bottom: 1px solid #ddd;
}

.timeline td:first-child {
    width: 25%;
    font-weight: bold;
}
</style>

</head>
<body>

<h1><?= e($data['incident']['title'] ?? 'Incident Report') ?></h1>

<div class="meta">
    <div><strong>ID:</strong> <?= e($data['incident']['id'] ?? '') ?></div>
    <div><strong>Date:</strong> <?= e($data['incident']['date'] ?? '') ?></div>
</div>

<div class="section">
    <div class="section-title">Summary</div>
    <ul>
        <?php foreach (($data['summary'] ?? []) as $line): ?>
            <li><?= e($line) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="section">
    <div class="section-title">Timeline</div>
    <table class="timeline">
        <?php foreach (($data['timeline'] ?? []) as $row): ?>
        <tr>
            <td><?= e($row['time']) ?></td>
            <td><?= e($row['event']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php if (!empty($data['notes'])): ?>
<div class="section">
    <div class="section-title">Notes</div>
    <p><?= e($data['notes']) ?></p>
</div>
<?php endif; ?>

</body>
</html>

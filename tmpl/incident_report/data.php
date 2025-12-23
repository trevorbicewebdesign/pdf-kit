<?php
declare(strict_types=1);

return [
    'incident' => [
        'title' => 'Supervision Payment Form Submission Failure',
        'date'  => '2025-12-19',
        'id'    => 'IR-2025-12-19-01',
    ],

    'summary' => [
        'A user experienced repeated failures submitting the Supervision Payment form.',
        'The failures manifested as blank screens or stalled submissions.',
        'Multiple overlapping configuration and rendering issues contributed to the behavior.',
    ],

    'timeline' => [
        ['time' => '11:30 AM PST', 'event' => 'User reports inability to submit form'],
        ['time' => '12:05 PM PST', 'event' => 'Initial investigation begins'],
        ['time' => '2:40 PM PST',  'event' => 'Root causes identified'],
    ],

    'notes' => 'Incident resolved after corrective actions were applied and verified.',
];

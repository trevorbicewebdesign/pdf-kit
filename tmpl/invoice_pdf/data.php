<?php
declare(strict_types=1);

return [
    'task' => 'previewPdf',

    'client' => [
        'name' => 'Process Therapy Institute',
        'address' => '456 Client Rd',
        'city' => 'Eureka',
        'state' => 'CA',
        'zip' => '95501',
        'phone' => '(555) 555-1234',
        'email' => 'contact@processtherapy.com',
    ],

    'account' => [
        'name' => 'Process Therapy Institute',
        
    ],

    'business' => [
        'company_name' => 'Trevor Bice Webdesign',
        'company_address_1' => '123 Example St',
        'company_city' => 'Arcata',
        'company_state' => 'CA',
        'company_zip' => '95521',
        'company_phone' => '(555) 555-5555',
        'company_email' => 'trevor@example.com',
    ],

    'invoice' => [
        'number' => '0001',
        'client_name' => 'Process Therapy Institute',
        'title' => 'Website Maintenance',
        'status' => 'Open',
        'due_date' => '2025-12-31',
        'summary' => 'Monthly maintenance and updates.',
        'total' => '$150.00',
        'notes' => "Thank you!\nPayment due Net 15.",
        'items' => [
            [
                'name' => 'Maintenance retainer',
                'quantity' => '1',
                'rate' => '$150.00',
                'subtotal' => '$150.00',
            ],
            [
                'name' => 'Additional updates',
                'quantity' => '2',
                'rate' => '$50.00',
                'subtotal' => '$100.00',
            ],
            [
                'name' => 'Hosting fee',
                'quantity' => '1',
                'rate' => '$20.00',
                'subtotal' => '$20.00',
            ],
        ],
    ],
];

<?php
declare(strict_types=1);

return [
    'business' => [
        'company_name' => 'Trevor Bice Webdesign',
        'company_address_1' => '123 Example St',
        'company_city' => 'Arcata',
        'company_state' => 'CA',
        'company_zip' => '95521',
        'company_phone' => '(555) 555-5555',
        'company_email' => 'trevor@example.com',
    ],

    'client' => [
        'name' => 'Process Therapy Institute',
        'address_1' => '12345 St.',
        'address_2' => '',
        'city' => 'Nowhere',
        'state' => 'California',
        'zip' => '99999'
    ],

    'account' => [
        'name' => 'Process Therapy Institute',
    ],



    'incident' => [
        'number' => 'IR-2025-12-19-01',
        'status' => 'Resolved',
        'due_date' => null,
        'summary' => '
            <p>
            A single user reported issues submitting the Supervision Payment form, encountering either a blank screen or a stalled process despite using various devices and browsers. Investigation confirmed the form was not fundamentally broken; the problems stemmed from two primary issues:
            </p>
            <div style="margin-left: 2em;">
                <h4>1. Gravity Forms Protected form URL reused</h4>
                <p>
                    The user was repeatedly accessing a cached or bookmarked form URL that contained a <code>protection</code> parameter. This parameter is automatically added by Gravity Forms following a blocked or duplicate submission attempt. Consequently, the form was intentionally blocked on every subsequent attempt, even when it appeared to load correctly.
                </p>
                <h4>2. Accordion layout hiding validation errors</h4>
                <p>
                    The form is embedded within an accordion layout. Validation errors are only visible when the accordion is open. When submission errors occurred, the accordion did not automatically reopen, preventing the user from seeing the error messages or guidance needed to correct the issues. This phenomenon, which was observed in a Zoom recording with Shea Smith and Mushkie after the correct URL issue was resolved, suggests that many form submissions with errors are likely not completed due to the hidden validation feedback.
                </p>
            </div>
            <h4>Additional Unrelated Email Issue:</h4>
            <p>
            While troubleshooting the other issues it was discovered that starting on the 4th, the system was unable to send some emails because the sender was set to the WordPress variable <code>{admin_email}</code>, which was configured as todd@all-d.com. This setting was a result of an existing, incomplete administrative email change—the attempted update had not yet been confirmed.
            </p>
            <p>
            This underlying configuration issue was exposed by a recent WordPress update to the PHP Mailer library, which introduced stricter handling for outgoing administrative emails. Consequently, administrative notifications became unreliable.
            </p>
        ',
        'report' => [
            'id' => 'IR-2025-12-19-01',
            'title' => 'Supervision Payment Form Submission Failure',
            'subtitle' => 'Intermittent checkout failures affecting a single user',
            'status' => 'Resolved',
            'created_date' => '2025-12-19',
            'updated_date' => '2025-12-20',
            'timezone' => 'PST',
        ],
        'root_cause' => '
            <p>
            The incident involving the Supervision Payment form submission failures was caused by two primary factors: the reuse of a Gravity Forms submission protection URL and the form\'s location within an accordion-based user interface that hid feedback.
            </p>
            <h4>Primary Cause: Protected Submission URL Reuse</h4>
            <p>
            Gravity Forms implements a mechanism to prevent duplicate submissions by redirecting the user back to the form with a specific query parameter: <code>?gf_protect_submission=1</code>.
            </p>
            <p>
            If a user navigates to or reuses this protected URL via a browser bookmark, history, or cache then Gravity Forms will block subsequent submissions, treating them as protected resubmission attempts. The form may load normally, but the submission process will not be completed.
            </p>
            <p>
            This protection mechanism is intended to prevent duplicates, but when triggered, it can result in a confusing user experience, leading to:
            </p>
            <ul>
            <li>A blank page, a perpetually spinning indicator, or a page reload without any visible error message, particularly if the theme or front-end layout conceals Gravity Forms error feedback. The user perceives this lack of feedback as a system crash.</li>
            <li>This issue uniquely explains why the problem was largely isolated to one specific user (Mushkie), even across different browsers and devices.</li>
            </ul>
            <h4>Secondary Cause: Hidden Validation and Error Feedback</h4>
            <p>
            The Supervision Payment form is nested within an accordion element which is based on the Oxygen block builder. Gravity Forms displays validation and processing error messages inside the form container. In this setup, these messages are only visible if the accordion panel containing the form is expanded. When the page reloads, it\'s not expanded and the user is unable to see the error messages.
            </p>
            <h5>Impact on User Experience:</h5>
            <ul>
            <li>After submission failures, the user did not reopen the accordion panel. Consequently, any error messages were hidden from view.</li>
            <li>This lack of communication directly contributed to the "nothing happens" perception, even when the system was attempting to provide feedback on why the submission failed.</li>
            </ul>
            <h5>Current Required Fix:</h5>
            <p>
            Even after the protected URL issue was resolved, the user experienced submission failures due to unaddressed form validation errors, which remained unseen because the accordion panel was closed. A code or layout correction is necessary to ensure that the accordion panel automatically expands upon a submission failure to reveal the form and its error messages. Without this fix, users may continue to encounter "silent failures."
            </p>
            <h4>Additional Findings</h4>
            <ul>
            <li>
                <strong>WordPress admin email state became incompatible with stricter outbound mail validation after the December 3 update</strong><br>
                On December 3, WordPress core updated. WordPress includes its own PHP Mail library stack for sending outbound email. A stricter version of its mail handling resulted in warnings and failure to send administrative emails when the site admin email setting was set to a domain other than icpnyc.org (all-d.com).
            </li>
            <li>
                In this case, Todd had changed the admin email to remove his email address, but the change requires following a confirmation email which had not been completed. WordPress stores the “new admin email” as pending until the confirmation link is clicked. Since this was never clicked, it was never 100% updated.
            </li>
            </ul>
            <h5>Mitigating Actions</h5>
            <p>
            The admin email was updated to icpnycinfo@icpnyc.org, which brought the system back into a valid configuration.
            </p>
        ',
    ],
    'timeline' => [
        [
            'date' => '2025-10-31',
            'time' => '',
            'description' => 'Mushkie reported that her supervision payment submission did not go through despite multiple attempts. This was the first documented indication of the issue affecting her specifically.',
        ],
        [
            'date' => '2025-11-03',
            'time' => '',
            'description' => 'Mushkie reported continued submission failures, including a blank screen after clicking Submit. She noted attempting submission again on a work computer with the same result.',
        ],
        [
            'date' => '2025-11-11',
            'time' => '',
            'description' => 'Mushkie confirmed the issue persisted across macOS and Windows systems using Safari and Chrome browsers.',
        ],
        [
            'date' => '2025-11-17',
            'time' => '',
            'description' => 'Todd reached out to Trevor to ask what might be preventing Mushkie from successfully submitting the Supervision Payment Form.',
        ],
        [
            'date' => '2025-11-19',
            'time' => '',
            'description' => 'Trevor suggested the issue could be related to Dropbox and requested a sample of the file Mushkie was attempting to upload. Trevor suspected a large file upload and confirmed that no related changes had been made and that other form submissions were succeeding.',
        ],
        [
            'date' => '2025-12-03',
            'time' => '',
            'description' => 'A WordPress core update was automatically applied by SiteGround. This update modified the PHP Mailer library bundled with WordPress.',
        ],
        [
            'date' => '2025-12-04',
            'time' => '09:12',
            'description' => 'Alina Sweret’s Supervision Payment Form submission produced an error indicating that WordPress was unable to send the notification email and could not instantiate the mail function.',
        ],
        [
            'date' => '2025-12-05',
            'time' => '',
            'description' => 'The server PHP version was migrated from PHP 7.4.33 to PHP 8.3.',
        ],
        [
            'date' => '2025-12-12',
            'time' => '11:40',
            'description' => 'Shea reached out to Todd with a Zoom recording demonstrating Mushkie’s issues with the Supervision Payment Form.',
        ],
        [
            'date' => '2025-12-15',
            'time' => '16:05',
            'description' => 'Todd reached out to Trevor with the video Shea recorded documenting the issue. Evidence confirmed that the form was not submitted due to an additional query string parameter ?gf_protect_submission=1.',
        ],
        [
            'date' => '2025-12-15',
            'time' => '18:35',
            'description' => 'Trevor updated the WordPress Admin Email setting from todd@all-d.com to icpnycinfo@icpnyc.org to resolve the “Could Not Instantiate Mail Function” error.',
        ],
    ],
];

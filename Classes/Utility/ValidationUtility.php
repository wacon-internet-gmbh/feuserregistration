<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension feuserregistration.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Kevin Chileong Lee, info@wacon.de, WACON Internet GmbH
 */

namespace Wacon\Feuserregistration\Utility;

class ValidationUtility
{
    /**
     * Validates an email address based on standard formats.
     *
     * @param string $email The email address to check.
     * @return bool True if the email is valid, false otherwise.
     */
    public static function isValidEmail(string $email): bool
    {
        // 1. Sanitize the email to remove any characters that are not allowed.
        $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

        // 2. Validate the sanitized email.
        // The function returns the email string if it's valid, and false if it's not.
        if (filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
            // 3. Extra check: Ensure the original and sanitized emails are the same.
            // This catches emails with invalid characters like "test<script>@example.com".
            return strtolower($email) === strtolower($sanitizedEmail);
        }

        return false;
    }
}

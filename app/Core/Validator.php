<?php

namespace App\Core;

/**
 * Simple form validator.
 *
 *   $validator = new Validator($_POST, [
 *       'email' => 'required|email|max:150',
 *       'phone' => 'required|phone',
 *   ]);
 *   if ($validator->fails()) {
 *       $errors = $validator->errors();
 *   }
 *
 * Rules:
 *   required         field must not be empty
 *   email            valid email address
 *   phone            BD mobile number 01XXXXXXXXX
 *   numeric          a number (10, 2.5)
 *   integer          a whole number
 *   date             a date like 2026-10-25
 *   future_or_today  a date that is not in the past
 *   min:8            at least 8 characters
 *   max:100          at most 100 characters
 *   gte:0            number must be 0 or more
 *   in:a,b,c         must be one of a, b, c
 *   confirmed        "password" must match "password_confirmation"
 *   different:other  must not be the same as the "other" field
 */
class Validator
{
    private array $data;
    private array $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;

        foreach ($rules as $field => $ruleText) {
            $this->validateField($field, explode('|', $ruleText));
        }
    }

    public function fails(): bool
    {
        return count($this->errors) > 0;
    }

    /** Returns ['field' => 'error message', ...] */
    public function errors(): array
    {
        return $this->errors;
    }

    private function validateField(string $field, array $rules): void
    {
        $value = trim((string) ($this->data[$field] ?? ''));
        $label = ucfirst(str_replace('_', ' ', $field)); // "contact_phone" → "Contact phone"

        if ($value === '') {
            if (in_array('required', $rules)) {
                $this->errors[$field] = "$label is required.";
            }
            return; // empty optional field: nothing more to check
        }

        foreach ($rules as $rule) {
            // "max:100" → $ruleName = "max", $parameter = "100"
            $parts = explode(':', $rule, 2);
            $ruleName = $parts[0];
            $parameter = $parts[1] ?? '';

            $error = $this->checkRule($ruleName, $parameter, $value, $field, $label);
            if ($error !== '') {
                $this->errors[$field] = $error;
                return; // show only the first error of a field
            }
        }
    }

    /** Returns an error message, or an empty string when the value is OK. */
    private function checkRule(string $rule, string $parameter, string $value, string $field, string $label): string
    {
        switch ($rule) {
            case 'required':
                return '';

            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return "$label must be a valid email address.";
                }
                return '';

            case 'phone':
                if (!preg_match('/^01[3-9][0-9]{8}$/', $value)) {
                    return "$label must be a valid 11 digit mobile number (01XXXXXXXXX).";
                }
                return '';

            case 'numeric':
                if (!is_numeric($value)) {
                    return "$label must be a number.";
                }
                return '';

            case 'integer':
                if (!ctype_digit($value)) {
                    return "$label must be a whole number.";
                }
                return '';

            case 'date':
                if (!$this->isDate($value)) {
                    return "$label must be a valid date.";
                }
                return '';

            case 'future_or_today':
                if (!$this->isDate($value) || $value < date('Y-m-d')) {
                    return "$label cannot be in the past.";
                }
                return '';

            case 'min':
                if (strlen($value) < (int) $parameter) {
                    return "$label must be at least $parameter characters.";
                }
                return '';

            case 'max':
                if (strlen($value) > (int) $parameter) {
                    return "$label must not be longer than $parameter characters.";
                }
                return '';

            case 'gte':
                if (!is_numeric($value) || (float) $value < (float) $parameter) {
                    return "$label must be $parameter or more.";
                }
                return '';

            case 'in':
                if (!in_array($value, explode(',', $parameter))) {
                    return "$label is not a valid option.";
                }
                return '';

            case 'confirmed':
                if ($value !== ($this->data[$field . '_confirmation'] ?? '')) {
                    return "$label confirmation does not match.";
                }
                return '';

            case 'different':
                if ($value === trim($this->data[$parameter] ?? '')) {
                    return "$label must be different from " . str_replace('_', ' ', $parameter) . '.';
                }
                return '';
        }

        throw new \Exception("Unknown validation rule: $rule");
    }

    /** Is the value a real date in Y-m-d format? */
    private function isDate(string $value): bool
    {
        $date = \DateTime::createFromFormat('Y-m-d', $value);
        return $date !== false && $date->format('Y-m-d') === $value;
    }
}

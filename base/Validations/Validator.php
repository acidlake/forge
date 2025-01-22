<?php
namespace Base\Validations;

use Base\Interfaces\ValidatorInterface;

class Validator implements ValidatorInterface
{
    private array $data;
    private array $errors = [];

    // Constructor accepts the data to be validated
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    // Method to validate the data against a set of rules
    public function validate(array $rules): ValidatorInterface
    {
        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode("|", $ruleString);
            foreach ($rulesArray as $rule) {
                $this->applyRule($field, $rule);
            }
        }

        return $this;
    }

    // Method to apply a validation rule to a field
    private function applyRule(string $field, string $rule): void
    {
        // Handle parameterized rules (e.g., min:8, unique:users,email)
        [$ruleName, $parameter] = explode(":", $rule . ":");

        switch ($ruleName) {
            case "required":
                if (!isset($this->data[$field]) || empty($this->data[$field])) {
                    $this->addError($field, "The $field field is required.");
                }
                break;

            case "string":
                if (
                    isset($this->data[$field]) &&
                    !is_string($this->data[$field])
                ) {
                    $this->addError(
                        $field,
                        "The $field field must be a string."
                    );
                }
                break;

            case "email":
                if (
                    isset($this->data[$field]) &&
                    !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)
                ) {
                    $this->addError(
                        $field,
                        "The $field field must be a valid email address."
                    );
                }
                break;

            case "min":
                if (
                    isset($this->data[$field]) &&
                    strlen($this->data[$field]) < (int) $parameter
                ) {
                    $this->addError(
                        $field,
                        "The $field field must be at least $parameter characters long."
                    );
                }
                break;
            case "max":
                if (
                    isset($this->data[$field]) &&
                    strlen($this->data[$field]) > (int) $parameter
                ) {
                    $this->addError(
                        $field,
                        "The $field field must not exceed $parameter characters."
                    );
                }
                break;

            case "unique":
                // Example formats:
                // - 'unique:users' (checks `id` by default)
                // - 'unique:users,email' (checks specific field)
                // - 'unique:users,email,exclude_id' (exclude a specific record)

                $parameters = explode(",", $parameter);

                // Extract table name and optional field/column
                $table = $parameters[0] ?? null;
                $field = $parameters[1] ?? $field; // Defaults to the current field
                $excludeId = $parameters[2] ?? null; // Optionally exclude a specific ID

                if (!$table) {
                    throw new \InvalidArgumentException(
                        "The unique rule requires at least a table name (e.g., 'unique:users')."
                    );
                }

                // Use ORM to dynamically check uniqueness
                $modelClass = "\\App\\Models\\" . ucfirst(rtrim($table, "s")); // Example: 'users' -> '\App\Models\User'
                if (!class_exists($modelClass)) {
                    throw new \RuntimeException(
                        "Model for table '{$table}' does not exist."
                    );
                }

                $value = $this->data[$field] ?? null;

                // Build a query to check for duplicates
                $query = $modelClass::where([$field => $value]);

                // Exclude a specific record, if provided
                if ($excludeId) {
                    $query = $query->where([
                        $modelClass::getPrimaryKey() => ["!=", $excludeId],
                    ]);
                }

                // Check if the record exists
                $exists = $query->exists();

                if ($exists) {
                    $this->addError(
                        $field,
                        "The $field field must be unique in the $table table."
                    );
                }
                break;

            default:
                $this->addError(
                    $field,
                    "Validation rule $ruleName is not supported."
                );
        }
    }

    // Add an error message for a specific field
    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    // Check if validation fails
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    // Get the errors for validation
    public function errors(): array
    {
        return $this->errors;
    }

    // Get the original data that was validated
    public function getData(): array
    {
        return $this->data;
    }
}

<?php



class Validator
{
    /**
     * @param array $data   $_POST or custom data
     * @param array $rules  ['field' => 'required|email|min:10', ...]
     * @return array [cleanData, errors]
     */
    public static function validate(array $data, array $rules): array
    {
        $clean = [];
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? null;

            if (is_string($value)) {
                $value = trim($value);
            }

            $clean[$field] = $value;

            $ruleList = array_filter(array_map('trim', explode('|', (string)$ruleString)));

            foreach ($ruleList as $rule) {
                [$name, $param] = self::parseRule($rule);

                if ($name === 'required') {
                    if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                        $errors[$field] = 'champ requis';
                        break;
                    }
                }

                if (($value === null || $value === '') && $name !== 'required') {
                    continue;
                }

                if ($name === 'email') {
                    if (!filter_var((string)$value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field] = 'email invalide';
                        break;
                    }
                }

                if ($name === 'min') {
                    $min = (int)$param;
                    if (mb_strlen((string)$value) < $min) {
                        $errors[$field] = "minimum {$min} caractères";
                        break;
                    }
                }

                if ($name === 'max') {
                    $max = (int)$param;
                    if (mb_strlen((string)$value) > $max) {
                        $errors[$field] = "maximum {$max} caractères";
                        break;
                    }
                }

                if ($name === 'int') {
                    if (!self::isIntLike($value)) {
                        $errors[$field] = 'doit être un nombre entier';
                        break;
                    }
                    $clean[$field] = (int)$value;
                }

                if ($name === 'between') {
                    $parts = array_map('trim', explode(',', (string)($param ?? '')));
                    $a = isset($parts[0]) ? (int)$parts[0] : 0;
                    $b = isset($parts[1]) ? (int)$parts[1] : 0;

                    $intVal = self::isIntLike($value) ? (int)$value : null;
                    if ($intVal === null || $intVal < $a || $intVal > $b) {
                        $errors[$field] = "doit être entre {$a} et {$b}";
                        break;
                    }
                    $clean[$field] = $intVal;
                }

                if ($name === 'in') {
                    $allowed = array_map('trim', explode(',', (string)$param));
                    $val = is_string($value) ? trim($value) : (string)$value;

                    if (!in_array($val, $allowed, true)) {
                        $errors[$field] = 'valeur invalide';
                        break;
                    }
                    $clean[$field] = $val;
                }
            }
        }

        return [$clean, $errors];
    }

    private static function parseRule(string $rule): array
    {
        $parts = explode(':', $rule, 2);
        $name = strtolower(trim($parts[0]));
        $param = $parts[1] ?? null;
        return [$name, $param];
    }

    private static function isIntLike(mixed $value): bool
    {
        if (is_int($value)) return true;
        if (!is_string($value) && !is_float($value)) return false;
        return preg_match('/^-?\d+$/', (string)$value) === 1;
    }
}
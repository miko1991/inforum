<?php


namespace Kernel;


class Controller
{
    public function validate($data): array {
        $errors = [];

        foreach ($data as $index => $values) {
            foreach ($values as $key => $rules) {
                if (!isset($_POST[$key])) continue;
                $value = $_POST[$key];
                $field_string = $this->keyToString($key);

                $matches = explode(".", $rules);
                foreach ($matches as $wholeRule) {
                    $sub_matches = explode("=", $wholeRule);
                    $rule = $sub_matches[0];
                    $rule_data = null;
                    if (isset($sub_matches[1])) {
                        $rule_data = $sub_matches[1];
                    }
                    switch ($rule) {
                        case "required":
                            if (empty($value)) $errors[$key][] = "Field {$field_string} is required";
                            break;
                        case "email":
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) $errors[$key][] = "Field {$field_string} must be a valid email";
                            break;
                        case "unique":
                            $stmt = Kernel::$db->prepare("SELECT * FROM {$rule_data} WHERE {$key} = ?");
                            $stmt->execute([$value]);
                            $exists = $stmt->fetch(\PDO::FETCH_OBJ);

                            if ($exists || empty($value)) $errors[$key][] = "Field {$field_string} must be unique";
                            break;
                        case "minLength":
                            if (strlen($value) < $rule_data) $errors[$key][] = "Field {$field_string} must be at least {$rule_data} characters long";
                            break;
                        case "maxLength":
                            if (strlen($value) > $rule_data) $errors[$key][] = "Field {$field_string} must be maximally {$rule_data} characters long";
                            break;
                        case "sameAs":
                            $rule_data_string = $this->keyToString($rule_data);
                            if ($value !== $_POST[$rule_data]) $errors[$key][] = "Field {$field_string} must be same as {$rule_data_string}";
                            break;
                    }
                }
            }

        }

        return $errors;
    }

    private function keyToString($key) {
        $return = "";
        $matches = explode("_", $key);
        foreach ($matches as $match) {
            $return .= ucfirst($match) . " ";
        }

        return substr($return, 0, -1);
    }
 }
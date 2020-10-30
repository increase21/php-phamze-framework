<?php

class validator
{
    public static function CheckRequiredFields(array $expected_fields, $input = null)
    {
        $empty_fields = [];
        if (is_null($input)) {
            return;
        }
        if (is_array($input)) {
            if (!\count($expected_fields)) {
                return;
            }
            foreach ($expected_fields as $fields) {
                if (!array_key_exists($fields, $input) || empty($fields)) {
                    array_push($empty_fields, $fields);
                }
            }

            return $empty_fields;
        }
        if (is_object($input)) {
            foreach ($expected_fields as $fields) {
                if (!property_exists($input, $fields) || empty($fields)) {
                    array_push($empty_fields, $fields);
                }
            }

            return $empty_fields;
        }

        return 'Invalid parameter received';
    }

    public static function IsTooLong($input, int $length)
    {
        return strlen($input) > $length;
    }

    public static function IsTooShort($input, int $length)
    {
        return strlen($input) < $length;
    }

    public static function IsExactLength($input, int $length)
    {
        return strlen($input) === $length;
    }

    public static function IsNumber($input)
    {
        return is_numeric($input);
    }

    public static function IsEmail($input)
    {
        return filter_var($input, FILTER_VALIDATE_EMAIL);
    }

    public static function IsAlphabet($input)
    {
        return ctype_alpha($input);
    }

    public static function IsDate($date, $format = 'Y-m-d')
    {
        date_default_timezone_set('UTC');
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public static function IsTime($input)
    {
        return preg_match('/^\d{2}:\d{2}:?\d?\d?$/', $input);
    }

    public static function IsGender($input)
    {
        return in_array(strtolower($input), ['male', 'female']);
    }

    public static function IsBloodGroup($input)
    {
        return preg_match('/^[abo+-]+$/i', $input);
    }

    public static function IsAdminRole($input)
    {
        return in_array(strtolower($input), ['admin', 'teacher', 'others']);
    }

    public static function IsPhoneNumber($input)
    {
        return preg_match('/^[+0-9]{6,16}/', $input);
    }

    public static function IsName($input)
    {
        return preg_match('/^[a-z ]+$/i', $input);
    }

    public static function GenerateToken($len)
    {
        $leter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-';
        $token = '';
        $leterLen = strlen($leter);
        for ($i = 0; $i < $len; ++$i) {
            $token .= $leter[rand(0, $leterLen - 1)];
        }

        return $token;
    }

    public static function GetPageNumber($page = null)
    {
        return ($page === null || $page === '' || intval($page) === 1) ? 0 : (intval($page) - 1) * 50;
    }

    public static function CheckUsername($name)
    {
        return preg_match('/^[a-z]+\.[a-z]+\d/i', $name);
    }

    public static function RunQueryField($input)
    {
        return preg_match('/^[\d\-\/]+$/', $input) ? 'number' : preg_match('/^[a-z\s]+$/', $input) ? 'string' : null;
    }

    public static function GetInputValueString($Obj, $field)
    {
        if (!is_object($Obj)) {
            return false;
        }
        if (!is_string($field)) {
            return false;
        }

        return property_exists($Obj, $field) && is_string($Obj->$field) ? db::DbEscapeString($Obj->$field) : '';
    }

    public static function GetInputValueArray($Obj, $field)
    {
        if (!is_object($Obj)) {
            return false;
        }
        if (!is_string($field)) {
            return false;
        }

        return property_exists($Obj, $field) && is_array($Obj->$field) ? $Obj->$field : '';
    }

    public static function IsMoney($input)
    {
        return preg_match('/^\d+(\,?\.?\d?)+$/', $input);
    }

    public static function IsURL($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}

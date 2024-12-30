<?php


class FormateHelper
{

    public function convert_number_to_words($number)
    {
        $hyphen = '-';
        $conjunction = ' And ';
        $separator = ' ';
        $negative = 'Negative ';
        $decimal = ' Point ';
        $dictionary = array(
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety',
            100 => 'Hundred',
            1000 => 'Thousand',
            1000000 => 'Million',
            1000000000 => 'Billion',
            1000000000000 => 'Trillion',
            1000000000000000 => 'Quadrillion',
            1000000000000000000 => 'Quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                '$this -> convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }



    function convert_number_to_words_bangla($number)
    {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'শূন্য',
            1 => 'এক',
            2 => 'দুই',
            3 => 'তিন',
            4 => 'চার',
            5 => 'পাচ',
            6 => 'ছয়',
            7 => 'সাত',
            8 => 'আট',
            9 => 'নয়',
            10 => 'দশ',
            11 => 'এগারো',
            12 => 'বার',
            13 => 'তের',
            14 => 'চৌদ্দ',
            15 => 'পনেরো',
            16 => 'ষোল',
            17 => 'সতেরো',
            18 => 'আঠারো',
            19 => 'উনিশ',
            20 => 'বিশ',
            30 => 'তিরিশ',
            40 => 'চল্লিশ',
            50 => 'পঞ্চাশ',
            60 => 'ষাট',
            70 => 'সত্তুর',
            80 => 'আশি',
            90 => 'নব্বই',
            100 => 'একশো',
            1000 => 'হাজার',
            1000000 => 'মিলিয়ন',
            1000000000 => 'বিলিয়ন',
            1000000000000 => 'ট্রিলিয়ন',
            1000000000000000 => 'কোয়াড্রিলিওন',
            1000000000000000000 => 'কোয়ান্টিলিন'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    public function monthView($selectedMonth = 0)
    {

        $monthArray = array(
            "1" => 'January',
            "2" => 'February',
            "3" => 'March',
            "4" => 'April',
            "5" => 'May',
            "6" => 'June',
            "7" => 'July',
            "8" => 'August',
            "9" => 'September',
            "10" => 'October',
            "11" => 'November',
            "12" => 'December',
        );

        foreach($monthArray as $key => $month){
            if($key == $selectedMonth){
                echo '<option value="'.$key.'" selected >'.$month.'</option>';
            }else{
                echo '<option value="'.$key.'">'.$month.'</option>';
            }

        }

    }
    public function yearView($selectedYear = 0)
    {

        for($i = 2015; $i <= 2025; $i++){
            if($i == $selectedYear){
                echo '<option value="'.$i.'" selected >'.$i.'</option>';
            }else{
                echo '<option value="'.$i.'">'.$i.'</option>';
            }

        }

    }

    /**
     * Created by Mehedi.
     * To check in the _useraccess table user's workPermission have string like edit, delete
     * then unserialze function throw error and code stop execute.
     * To handle this situation uses this class
     * Date: 10-Mar-18
     * link : https://gist.github.com/cs278/217091
     * <ul>
     * <li>boolean: <code>b:1;</code></li>
     * <li>integer: <code>i:1;</code></li>
     * <li>double: <code>d:0.2;</code></li>
     * <li>string: <code>s:4:"test";</code></li>
     * <li>array: <code>a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}</code></li>
     * <li>object: <code>O:8:"stdClass":0:{}</code></li>
     * <li>null: <code>N;</code></li>
     * </ul>
     */


    function is_serialized($value, &$result = null)
    {
        // Bit of a give away this one
        if (!is_string($value))
        {
            return false;
        }
        // Serialized false, return true. unserialize() returns false on an
        // invalid string or it could return false if the string is serialized
        // false, eliminate that possibility.
        if ($value === 'b:0;')
        {
            $result = false;
            return true;
        }
        $length	= strlen($value);
        $end	= '';
        switch ($value[0])
        {
            case 's':
                if ($value[$length - 2] !== '"')
                {
                    return false;
                }
            case 'b':
            case 'i':
            case 'd':
                // This looks odd but it is quicker than isset()ing
                $end .= ';';
            case 'a':
            case 'O':
                $end .= '}';
                if ($value[1] !== ':')
                {
                    return false;
                }
                switch ($value[2])
                {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                        break;
                    default:
                        return false;
                }
            case 'N':
                $end .= ';';
                if ($value[$length - 1] !== $end[0])
                {
                    return false;
                }
                break;
            default:
                return false;
        }
        if (($result = @unserialize($value)) === false)
        {
            $result = null;
            return false;
        }
        return true;
    }
}
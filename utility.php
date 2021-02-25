<?php
/***
 * Convert Number into Human Readable String
 *
 * $number         NUMBER  Accept number to be converted
 *
 * $numberInWord   STRING  Return Human Readable String
*/
if (!function_exists('convert_number_to_words'))
{
    function convert_number_to_words($number)
    {
        $decimal = round($number - ($no = floor($number)) , 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 => '',
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
            90 => 'Ninety'
        );
        $digits = array(
            '',
            'Hundred',
            'Thousand',
            'Lakh',
            'Crore'
        );
        while ($i < $digits_length)
        {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number)
            {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            }
            else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? " " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        $numberInWord = ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
        return $numberInWord;
    }
}

/***
 * Convert CSV File Data into Associative Array
 *
 * $filename    String     Absolute path of file
 * $delimiter   Character  Separator between two or more Data
 *
 * $data        Array      Return an Associative Array
*/
if (!function_exists('csv_to_array'))
{
    function csv_to_array($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header) $header = $row;
                else $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}

/***
 * Send Firebase Notification
 *
 * $token          String        Absolute path of file
 * $notification   Object        Notification object
 * $server_key     String        Server Key
 *
 * $data           Array   Return an Associative Array
*/
if (!function_exists('send_firebase_notification'))
{
    function send_firebase_notification($token = '', $notification = array() , $server_key = '')
    {
        $returnData = array(
            'success' => false,
            'message' => '',
            'data' => array()
        );

        // If server key not provided
        if (empty($server_key))
        {
            $returnData['message'] = 'Server key not provided!';
            return $returnData;
        }

        // If token not provided
        if (empty($server_key))
        {
            $returnData['message'] = 'Device token not provided!';
            return $returnData;
        }

        // If token not provided
        if (empty($notification))
        {
            $returnData['message'] = 'Notification details not provided!';
            return $returnData;
        }

        $url = "https://fcm.googleapis.com/fcm/send";
        $arrayToSend = array(
            'to' => $token,
            'notification' => $notification,
            'priority' => 'high'
        );
        $headers = array(
            'Content-Type: application/json',
            'Authorization: key=' . $serverKey
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayToSend));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($err)
        {
            $returnData['message'] = $err;
            return false;
        }

        $returnData['data'] = $response;
        $returnData['message'] = 'Successfully notified to firebase service!';
        return $returnData;
    }
}


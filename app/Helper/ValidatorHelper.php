<?php

namespace App\Helper;
use Illuminate\Http\Request;

class ValidatorHelper
{
    public static function validate($validator)
    {
        if($validator->fails()){
            $msg =[];
            foreach ($validator->errors()->getMessages() as $key => $value) {
                foreach ($value as $value1) {
                    $anotherMessages = self::anotherMessages();
                    if (isset($anotherMessages[$value1])) {
                        $value1 = $anotherMessages[$value1];
                    }
                    $msg[$key] = $value1;
                }
            }
            abort(400,json_encode($msg));
        }
    }

    protected static function anotherMessages()
    {
        $anotherMessages = [];
        $anotherMessages = [
            'email has already been taken.'=>'Email sudah digunakan, silahkan gunakan Alamat Email lain.',
            'prefix has already been taken.'=>'ID Kota sudah digunakan, silahkan gunakan ID Kota lain.',
            'prefix must be 4 digits.'=>'ID Kota harus berisi 4 digit.',
            'nop must be 18 digits.'=>'NOP harus berisi 18 digit.',
            'tahun must be 4 digits.'=>'Tahun harus berisi 4 digit.',
            'city id has already been taken.'=>'Kota/Kabupaten sudah digunakan, silahkan gunakan Kota/Kabupaten lain.',
            'selected biller id is invalid.'=>'Kota yg di pilih tidak sesuai ketentuan yg berlaku.'
        ];
        return $anotherMessages;
    }
}

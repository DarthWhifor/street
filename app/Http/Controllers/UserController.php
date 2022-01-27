<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        $n = 1;
        $persons = Person::orderBy('last_name')->get();
        return view('file-import', ['persons' => $persons, 'n' => $n]);
    }

    public function fileImport(Request $request)
    {
        $file = $request->file('file');
        if ($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            $this->checkUploadedFileProperties($extension, $fileSize);
            $location = 'uploads';
            $file->move($location, $filename);
            $filepath = public_path($location . "/" . $filename);
            $file = fopen($filepath, "r");
            $importData_arr = array();
            $i = 0;
            $filterArray = ['and', '.', '&'];
            while (($filedata = fgetcsv($file, 1000)) !== false) {
                $num = count(array_filter($filedata));
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    $stringToArray = explode(' ', $filedata[$c]);
                    $anotherArray =  str_replace($filterArray, '', $stringToArray );
                    $importData_arr[$i][] = $anotherArray;
                }
                $i++;
            }
            fclose($file);
            $j = 0;
            foreach ($importData_arr as $importData) {
                $j++;
                $oneLetter = '';
                foreach ($importData[0] as $imD) {
                    if (strlen($imD) === 1) {
                        $oneLetter = $imD;
                    }
                }
                if (count($importData[0]) === 4 && empty($importData[0][1])) {
                    $person = new Person();
                    $person->title = $importData[0][0];
                    $person->last_name = $importData[0][3];
                    $person->save();
                    $person2 = new Person();
                    $person2->title = $importData[0][2];
                    $person2->last_name = $importData[0][3];
                    $person2->save();
                    $j++;
                } elseif (count($importData[0]) === 5 && empty($importData[0][1])) {
                    $person = new Person();
                    $person->title = $importData[0][0];
                    $person->first_name = $importData[0][3];
                    $person->last_name = $importData[0][4];
                    $person->save();
                    $person2 = new Person();
                    $person2->title = $importData[0][2];
                    $person2->last_name = $importData[0][4];
                    $person2->save();
                    $j++;
                } elseif (count($importData[0]) === 7 && empty($importData[0][3])) {
                    $person = new Person();
                    $person->title = $importData[0][0];
                    $person->first_name = $importData[0][1];
                    $person->last_name = $importData[0][2];
                    $person->save();
                    $person2 = new Person();
                    $person2->title = $importData[0][4];
                    $person2->first_name = $importData[0][5];
                    $person2->last_name = $importData[0][6];
                    $person2->save();
                    $j++;
                } else {
                    $person = new Person();
                    $person->title = $importData[0][0];
                    $person->first_name = strlen($importData[0][1]) > 1 ? $importData[0][1] : '';
                    $person->initial = $oneLetter;
                    $person->last_name = $importData[0][3] ?? $importData[0][2];
                    $person->save();
                }
            }
            return back()->with('success', $j . ' records successfully uploaded!');
        }
        return back()->with('error', 'Please, select CSV file!');
    }

    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array('csv', 'xlsx');
        $maxFileSize = 2097152;
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
            } else {
                throw new \Exception('No file was uploaded', Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
            }
        } else {
            throw new \Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }
    }


}

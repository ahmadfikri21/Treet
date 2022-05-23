<?php

namespace App\Exports;

use App\Models\jawaban;
use App\Models\tester;
use App\Models\task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStartRow;

class hasil implements FromView, WithStartRow
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function view() : view {
        $data['task'] = Task::getTaskJoin(session()->get('kode_pengujian'));
        $data['id_task'] = Task::select('id_task')->where('kode_pengujian', '=', session()->get('kode_pengujian'))->get();
        $data['detailHasil'] = Jawaban::getDetailHasil(session()->get('kode_pengujian'));
        $data['detailHasil'] = Jawaban::addNomorTask($data['detailHasil'], $data['id_task']);
        $data['detailHasil'] = Jawaban::convertPathIdToNama($data['detailHasil']);
        $data['task'] = Jawaban::convertPathIdToNama($data['task']);

        // mengambil perhitungan akurasi
        $hasil['akurasi'] = Jawaban::countHasil($data['detailHasil'], 'akurasi', $data['id_task']);
        // mengambil perhitungan directness
        $hasil['directness'] = Jawaban::countHasil($data['detailHasil'], 'directness', $data['id_task']);
        // mengambil keseluruhan timetocompletion
        $hasil['timeToCompletion'] = Jawaban::timeToCompletion($data['detailHasil'], $data['id_task']);
        // mengambil data informasi pengujian
        $data['informasiPengujian'] = Tester::where("kode_pengujian", "=", session()->get('kode_pengujian'))->first();

        // mengambil key waktu dari time to completion
        $waktu = Tester::splitWaktu($hasil['timeToCompletion']);

        // mengambil hasil perhitungan boxplot tiap task
        $time = [];
        foreach($waktu as $row){
            array_push($time, Tester::getBoxPlotData($row));
        }
    
        // memanggil fungsi combineHasil
        $hasil = Tester::combineHasil($hasil['akurasi'],$hasil['directness'], $time);

        return view('export.detailHasil', [
            'hasil' => $hasil,
            'informasiPengujian' => $data['informasiPengujian'],
            'detailHasil' => $data['detailHasil'],
            'task' => $data['task']
        ]);
    }
    
    public function startRow(): int{
        return 2;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PenanggungJawab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PenanggungJawabController extends Controller
{
    public function index()
    {
        return view('Admin.penaggung');
    }

    public function data_penaggung()
    {
        $data = PenanggungJawab::get();
        return DataTables::of($data)
            ->addColumn('checkbox', function ($data) {
                return "<input type='checkbox' class='child-cb' value='$data->id'/>";
            })->addColumn('action', function ($data) {
                return "<a href='#' data-id='$data->id' class='edit menu-icon tf-icons me-2'><i class='bx bx-edit-alt'></i></a><a href='#' data-id='$data->id' class='hapus' style='color:red;'><i class='bx bx-trash'></i></a>";
            })
            ->rawColumns(['action', 'checkbox'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama_penanggung_jawab' => 'required',
        ], [
            'nama_penanggung_jawab.required' => 'nama wajib diisi'
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        }

        PenanggungJawab::create([
            'nama_penanggung_jawab' => $request->nama_penanggung_jawab,
        ]);

        return response()->json(['success' => 'success menambahkan penanggung jawab']);
    }

    public function delete($id)
    {
        $penanggung = PenanggungJawab::find($id);
        $penanggung->delete();
        return response()->json(['success' => 'success menghapus']);
    }

    public function edit($id)
    {
        $penanggung = PenanggungJawab::find($id);
        return response()->json(['result' => $penanggung]);
    }

    public function update(Request $request, $id)
    {
        $penanggung = PenanggungJawab::find($id);
        $penanggung->nama_penanggung_jawab = $request->nama_penanggung_jawab;
        $penanggung->save();
        // $penanggung->update([
        //     'nama_penanggung_jawab' => $request->nama_penanggung_jawab
        // ]);
        return response()->json(['success' => 'update berhasil']);
    }
}
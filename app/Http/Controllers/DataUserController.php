<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\PenanggungJawab;
use App\Models\Purposes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class DataUserController extends Controller
{
    public function index()
    {
        $penanggung_jawab = PenanggungJawab::all();
        return view('Admin.input_data.data_user', compact('penanggung_jawab'));
    }

    public function data_user()
    {
        $data = Purposes::with('penanggung_jawab', 'document')->where('proses_sertifikat', 'masuk')->get();
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                return "<div class='d-flex align-items-center'><a href='#' data-id='$data->id' class='edit menu-icon tf-icons me-2'><i class='bx bx-edit-alt'></i></a><a href='#' data-id='$data->id' class='hapus' style='color:red;'><i class='bx bx-trash'></i></a><a href='#' class='end' data-id=' $data->id'><i class='bx bx-check'></i></a></div>";
            })->addColumn('checkbox', function ($data) {
                return "<input type='checkbox' class='child-cb' value='$data->id'/>";
            })->addColumn('document', function ($data) {; // Assume 'document' is the relationship method

                // Process the array of documents and render HTML
                $html = '<ul>';
                foreach ($data->document as $document) {
                    $html .= '<li><a href="' . asset('storage/Document/' . $document->document) . '">' . $document->document . '</a></li>';
                    // Adjust the property (e.g., 'document_name') based on your actual structure
                }
                $html .= '</ul>';

                return $html;
            })
            ->rawColumns(['action', 'checkbox', 'document'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'penanggung_jawab_id' => 'required',
            'nama_pemohon' => 'required',
            'domisili' => 'required',
            'nomor_sertifikat' => 'required',
            'desa' => 'required',
            'no_berkas' => 'required',
            'document.*' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        }
        $purposes = Purposes::create([
            'penanggung_jawab_id' => $request->penanggung_jawab_id,
            'nama_pemohon' => $request->nama_pemohon,
            'domisili' => $request->domisili,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'desa' => $request->desa,
            'no_berkas' => $request->no_berkas,
        ]);

        foreach ($request->file('document') as $file) {
            $document_name = Str::random(10) . "." . $file->getClientOriginalExtension();
            $file->storeAs('public/Document', $document_name);
            Document::create([
                'document' => $document_name,
                'purpose_id' => $purposes->id
            ]);
        }


        return response()->json(['success' => 'Berhasil menambahkan data']);
    }

    public function edit($id)
    {
        $data = Purposes::with('penanggung_jawab')->find($id);
        return response()->json(['success' => $data]);
    }
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'penanggung_jawab_id' => 'required',
            'nama_pemohon' => 'required',
            'domisili' => 'required',
            'nomor_sertifikat' => 'required',
            'desa' => 'required',
            'no_berkas' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        }

        $purposes = Purposes::find($id);
        if ($request->hasFile('document')) {
            $document_file = $request->file('document');
            $document_name = Str::random(10) . "." . $document_file->getClientOriginalExtension();
            $document_file->storeAs('public/Document', $document_name);
            Storage::delete('public/Document/' . $purposes->document);
            $purposes->document = $document_name;
        }
        $purposes->penanggung_jawab_id = $request->penanggung_jawab_id;
        $purposes->nama_pemohon = $request->nama_pemohon;
        $purposes->domisili = $request->domisili;
        $purposes->nomor_sertifikat = $request->nomor_sertifikat;
        $purposes->desa = $request->desa;
        $purposes->no_berkas = $request->no_berkas;
        $purposes->save();

        return response()->json(['success' => 'Berhasil mengupdate']);
    }

    public function delete($id)
    {
        $purposes = Purposes::find($id);
        $purposes->delete();
        return response()->json(['success' => 'Berhasil menghapus']);
    }

    public function end($id)
    {
        $purposes = Purposes::find($id);
        $purposes->proses_sertifikat = "keluar";
        $purposes->save();

        return response()->json(['success' => $purposes->nama_pemohon . " berhasil diupdate"]);
    }
}

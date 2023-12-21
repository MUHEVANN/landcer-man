<?php

namespace App\Http\Controllers;

use App\Exports\PurposeExport;
use App\Models\Document;
use App\Models\PenanggungJawab;
use App\Models\Purposes;
use Carbon\Carbon;
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
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return "<div class='d-flex align-items-center'><a href='#' data-id='$data->id' class='edit menu-icon tf-icons me-2'><i class='bx bx-edit-alt'></i></a><a href='#' data-id='$data->id' class='hapus' style='color:red;'><i class='bx bx-trash'></i></a><a href='#' class='end' data-id=' $data->id'><i class='bx bx-check'></i></a></div>";
            })
            ->addColumn('checkbox', function ($data) {
                return "<input type='checkbox' class='child-cb' value='$data->id'/>";
            })
            ->addColumn('proses_sertifikat', function ($data) {
                return $data->proses_sertifikat === 'masuk' ? "<span class='badge bg-success'>Masuk</span>" : "<span class='badge bg-danger'>Keluar</span>";
            })
            ->addColumn('document', function ($data) {; // Assume 'document' is the relationship method

                // Process the array of documents and render HTML
                $html = '<ul>';
                foreach ($data->document as $document) {
                    $html .= '<li><a href="' . asset('storage/Document/' . $document->document) . '">' . $document->document . '</a></li>';
                    // Adjust the property (e.g., 'document_name') based on your actual structure
                }
                $html .= '</ul>';

                return $html;
            })
            ->rawColumns(['action', 'checkbox', 'document', 'proses_sertifikat'])
            ->make(true);
    }

    public function create(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'penanggung_jawab_id' => 'required',
            'document.*' => 'required',
            'jenis_pekerjaan' => 'required',


        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        }
        $purposes = Purposes::create([
            'penanggung_jawab_id' => $request->penanggung_jawab_id,
            'nama_pemohon' => $request->nama_pemohon,
            'domisili' => $request->domisili,
            'no_akta' => $request->no_akta,
            'bank_name' => $request->bank_name,
            'keterangan' => $request->keterangan,
            'jenis_pekerjaan' => $request->jenis_pekerjaan,
            'proses_permohonan' => $request->proses_permohonan,
            'tanggal' => $request->tanggal,

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
            'document.*' => 'required',
            'jenis_pekerjaan' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        }

        $purposes = Purposes::find($id);
        if ($request->hasFile('document')) {
            $docs = Document::where('purpose_id', $purposes->id)->get();
            foreach ($docs as $doc) {
                $doc->delete();
            }
            foreach ($request->file('document') as $file) {
                $document_name = Str::random(10) . "." . $file->getClientOriginalExtension();
                $file->storeAs('public/Document', $document_name);
                Storage::delete('public/Document/' . $purposes->document);
                Document::create([
                    'document' => $document_name,
                    'purpose_id' => $purposes->id
                ]);
            }
        }
        $purposes->penanggung_jawab_id = $request->penanggung_jawab_id;
        $purposes->nama_pemohon = $request->nama_pemohon;
        $purposes->no_akta = $request->no_akta;
        $purposes->jenis_pekerjaan = $request->jenis_pekerjaan;
        $purposes->proses_permohonan = $request->proses_permohonan;
        $purposes->keterangan = $request->keterangan;
        $purposes->tanggal = $request->tanggal;
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

    public function export_purposes()
    {
        return (new PurposeExport)->download('purposes-' . Carbon::now()->format('YmdHis') . '.xlsx');
    }
}

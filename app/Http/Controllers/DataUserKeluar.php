<?php

namespace App\Http\Controllers;

use App\Models\PenanggungJawab;
use App\Models\Purposes;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DataUserKeluar extends Controller
{
    public function index()
    {
        $penanggung_jawab = PenanggungJawab::all();

        return view('Admin.keluar.index', compact('penanggung_jawab'));
    }

    public function data_user()
    {
        $data = Purposes::with('penanggung_jawab', 'document')->get();
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                return "<a href='#' data-id='$data->id' class='edit menu-icon tf-icons me-2'><i class='bx bx-edit-alt'></i></a><a href='#' data-id='$data->id' class='hapus' style='color:red;'><i class='bx bx-trash'></i></a>";
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
}

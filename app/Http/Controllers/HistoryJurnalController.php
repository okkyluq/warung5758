<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Repositories\JurnalRepositoryInterface;
use App\HistoryJurnal;
use Carbon\Carbon;
use Codedge\Fpdf\Facades\Fpdf;


class HistoryJurnalController extends Controller
{
    private $jurnalRepo;

    public function __construct(JurnalRepositoryInterface $jurnalRepo)
    {
        $this->jurnalRepo = $jurnalRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $history = HistoryJurnal::select(['id', 'kode_journal', 'tgl_set', 'keterangan'])->orderBy('created_at', 'desc');

            return Datatables::of($history)
                    ->addIndexColumn()
                    ->editColumn('tgl_set', function($data){
                        return date('d/m/Y', strtotime($data->tgl_set));
                    })
                    ->addColumn('action', function($data){
                        return '<ul class="icons-list">
                                <li class="text-primary-600"><a href="'.url("akutansi/jurnal/".$data->id).'"><i class="icon-eye"></i></a></li>
                                <li class="text-primary-600"><a target="_blank" href="'.url("akutansi/lihat-jurnal/".$data->kode_journal).'"><i class="icon-file-pdf"></i></a></li>
                            </ul>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);


        }
        return view('backoffice.page.history_jurnal.index');
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        $history = HistoryJurnal::findOrFail($id);
        return view('backoffice.page.history_jurnal.detail', [
            'history' => $history
        ]);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function kode_journal()
    {
        $check = HistoryJurnal::select('kode_journal', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $kode_journal = $check->kode_journal;
        } else {
            $bulan_last = date('m');
            $kode_journal = 'JURNAL'.date('ym').'000';
        }

        // JURNAL2101001

        if ($bulan_last >= date('m')) {
            $lengthx = strlen("JURNAL") + 4;
            $lastNoUrut = (int)substr($kode_journal, $lengthx, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'JURNAL'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lengthx = strlen("JURNAL") + 4;
            $lastNoUrut = (int)substr($kode_journal, $lengthx, 3);
            $tmp = 000+1;
            $kd = 'JURNAL'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }

    public function jurnal_pdf($nojurnal)
    {
        $history = HistoryJurnal::with(['det_history_jurnal'])->where('kode_journal', $nojurnal)->first();
        Fpdf::SetTitle('Jurnal Umum '.$history->kode_journal, false);
        Fpdf::AddPage('L', array(215, 139));
        Fpdf::SetMargins(10, 50, 20);
        Fpdf::SetAutoPageBreak(false);
        Fpdf::SetFont('Helvetica','B',12);
        Fpdf::Cell(94,6,"JURNAL UMUM", 0,0,'L');
        Fpdf::SetFont('Helvetica','BI',12);
        Fpdf::Cell(94,6,"WARUNG MAKAN 5758", 0,1,'R');
        Fpdf::Line(10, 16, 198, 16);
        Fpdf::Line(10, 16.5, 198, 16.5);
        Fpdf::setY(18);
        Fpdf::SetFont('Helvetica','B',9);
        Fpdf::Cell(30,5,"Kode Jurnal", 0,0,'L');
        Fpdf::Cell(3,5,":", 0,0,'L');
        Fpdf::Cell(30,5, $history->kode_journal, 0,1,'L');
        Fpdf::Cell(30,5,"Hari/Tanggal", 0,0,'L');
        Fpdf::Cell(3,5,":", 0,0,'L');
        Fpdf::Cell(30,5, Carbon::createFromFormat('Y-m-d', $history->tgl_set)->format('d/m/Y'), 0,1,'L');
        Fpdf::Cell(30,5,"Tipe Jurnal", 0,0,'L');
        Fpdf::Cell(3,5,":", 0,0,'L');
        Fpdf::Cell(30,5, preg_replace('/([a-z])([A-Z])/s','$1 $2', str_replace('App\\', '', $history->historyjurnalable_type)), 0,1,'L');
        Fpdf::ln();
        Fpdf::SetFont('Helvetica','B',9);
        Fpdf::SetFillColor(34, 120, 169);
        Fpdf::SetTextColor(255, 255, 255);
        Fpdf::SetDrawColor(34, 120, 169);
        Fpdf::Cell(10,8,"No.", 1,0,'C', 1);
        Fpdf::Cell(20,8,"No Akun", 1,0, 'C', 1);
        Fpdf::Cell(45,8,"Nama Akun", 1,0, 'L', 1);
        Fpdf::Cell(30,8,"Debit", 1,0, 'R', 1);
        Fpdf::Cell(30,8,"Kredit", 1,0, 'R', 1);
        Fpdf::Cell(53,8,"Keterangan", 1,0, 'C', 1);
        Fpdf::ln();
        Fpdf::SetFillColor(221, 236, 255);
        Fpdf::SetTextColor(0, 0, 0);
        Fpdf::SetDrawColor(221, 236, 255);
        $no = 1;
        foreach ($history->det_history_jurnal as $isi) {
            Fpdf::SetFont('Helvetica','',9);
            Fpdf::Cell(10,8, $no++, 1,0,'C', 1);
            Fpdf::Cell(20,8, $isi->akun->kode_akun, 1,0, 'C', 1);
            Fpdf::Cell(45,8, $isi->akun->nama_akun, 1,0, 'L', 1);
            Fpdf::Cell(30,8, 'Rp.'.number_format($isi->nominal_debit, 0), 1,0, 'R', 1);
            Fpdf::Cell(30,8, 'Rp.'.number_format($isi->nominal_kredit, 0), 1,0, 'R', 1);
            Fpdf::Cell(53,8, $isi->keterangan, 1,0, 'L', 1);
            Fpdf::ln();
        }
        Fpdf::SetFont('Helvetica','B',9);
        Fpdf::SetFillColor(34, 120, 169);
        Fpdf::SetTextColor(255, 255, 255);
        Fpdf::SetDrawColor(34, 120, 169);
        Fpdf::Cell(75,8,"TOTAL", 1,0, 'C', 1);
        Fpdf::Cell(30,8, 'Rp.'.number_format($history->det_history_jurnal->sum('nominal_debit'), 0), 1,0, 'R', 1);
        Fpdf::Cell(30,8, 'Rp.'.number_format($history->det_history_jurnal->sum('nominal_debit'), 0), 1,0, 'R', 1);
        Fpdf::Cell(53,8, "", 1,0, 'L', 1);

        Fpdf::Output('Jurnal Umum.pdf', 'I', true);


        exit;
    }
}

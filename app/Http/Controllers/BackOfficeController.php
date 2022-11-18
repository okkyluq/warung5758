<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\User;
use App\HistoryKas;
use App\HistoryHutang;
use App\HistoryPiutang;
use App\TransaksiPenjualan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BackOfficeController extends Controller
{
    public function dashboard(Request $request, DashboardService $dashboardService)
    {
        $saldo_kas = HistoryKas::sum(DB::raw('nominal_debit - nominal_kredit'));
        $hutang = HistoryHutang::where('status_lunas', '0')->sum(DB::raw('sisa_pembayaran'));
        $piutang = HistoryPiutang::where('status_lunas', '0')->sum(DB::raw('sisa_pembayaran'));
        $sum_transaksi_penjualan = TransaksiPenjualan::sum('total');

        return view('backoffice.page.home.index', [
            'saldo_kas' => $saldo_kas,
            'hutang' => $hutang,
            'piutang' => $piutang,
            'omzet' => $sum_transaksi_penjualan,
        ]);
    }

    public function view_ubah_password(Request $request)
    {
        if ($request->isMethod('POST')) {
                $password = Auth::user()->password;
                if (Hash::check($request->password_lama, $password)) {
                    $user = User::findOrFail(Auth::user()->id);
                    $user->password = Hash::make($request->password_baru);
                    $user->save();
                    return redirect()->back()->with('success', 'Berhasil Ubah Password');
                } else {
                    return redirect()->back()->with('failed', 'Gagal Merubah Password, Password Anda Salah');
                }
        } else {
            return view('backoffice.page.ubah_password.index');
        }
    }

    public function chartOmsetPenjualan(Request $request)
    {
        try {
            $sum_transaksi_penjualan = TransaksiPenjualan::whereMonth('tgl_penjualan', $request->bulan)->whereYear('tgl_penjualan', $request->tahun)->sum(DB::raw('total'));
            $piutang = HistoryPiutang::where('status_lunas', '0')->whereMonth('created_at', $request->bulan)->whereYear('created_at', $request->tahun)->sum(DB::raw('sisa_pembayaran'));
            return response()->json([
                'status' => 'sukses',
                'data' => [
                    round($sum_transaksi_penjualan),
                    $piutang,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function chartOmsetBulan(Request $request, DashboardService $dashboardService)
    {
        try {
            $sum_month = collect(range(1, 12))->transform(function($item, $key) use ($request, $dashboardService)  {
                $new['month'] = Carbon::create()->day(1)->month($item)->format('M');
                $new['sum']   = round($dashboardService->getSumByMonthYear($item, $request->tahun), 0);
                return $new;
            });

            return response()->json([
                'status' => 'sukses',
                'data' => $sum_month
            ], 200);

        } catch (\Exception $e) {
            return response()->json($e, 400);
        }


    }

    public function chartOmsetTgl(Request $request, DashboardService $dashboardService)
    {
        try {
            $start_date = Carbon::createFromFormat('Y-m-d', $request->tahun.'-'.$request->bulan.'-01')->firstOfMonth()->format('Y-m-d');
            $end_date = Carbon::createFromFormat('Y-m-d', $request->tahun.'-'.$request->bulan.'-20')->lastOfMonth()->format('Y-m-d');
            $sum_date = collect(CarbonPeriod::create($start_date, $end_date))->transform(function($item, $key) use ($dashboardService) {
                $new['date'] = $item->format('d');
                $new['sum']  = round($dashboardService->getSumByDateYear($item->format('Y-m-d')), 0);
                return $new;
            });
            return response()->json([
                'status' => 'sukses',
                'data' => $sum_date
            ], 200);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }
}

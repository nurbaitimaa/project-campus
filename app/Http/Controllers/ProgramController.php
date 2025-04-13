<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    public function index()
    {
    	// mengambil data dari table program
    	$pegawai = DB::table('program')->get();
 
    	// mengirim data pegawai ke view index
    	return view('index',['program' => $pegawai]);
 
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $boards = Board::orderBy('category')->get();
        return view('dashboard', compact('boards'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $boardCount = Board::count();
        return view('admin.index', compact('boardCount'));
    }
} 
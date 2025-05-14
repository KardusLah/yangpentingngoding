<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::all();
        return view('be.bank.index', compact('banks'));
    }

    public function create()
    {
        return view('be.bank.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:100',
            'atas_nama' => 'nullable|string|max:100',
        ]);
        Bank::create($request->all());
        return redirect()->route('bank.index')->with('success', 'Bank berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        return view('be.bank.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:100',
            'atas_nama' => 'nullable|string|max:100',
        ]);
        $bank->update($request->all());
        return redirect()->route('bank.index')->with('success', 'Bank berhasil diupdate!');
    }

    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();
        return redirect()->route('bank.index')->with('success', 'Bank berhasil dihapus!');
    }
}

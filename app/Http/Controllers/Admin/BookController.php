<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    // Index: Menampilkan daftar buku
    public function index(Request $request)
    {
        $books = Book::query();

        // Pencarian buku berdasarkan input user
        $books->when($request->search, function (Builder $query) use ($request) {
            $query->where(function (Builder $q) use ($request) {
                $q->where('title', 'LIKE', "%{$request->search}%")
                    ->orWhere('publisher', 'LIKE', "%{$request->search}%")
                    ->orWhere('writer', 'LIKE', "%{$request->search}%")
                    ->orWhere('publish_year', 'LIKE', "%{$request->search}%")
                    ->orWhere('category', 'LIKE', "%{$request->search}%");
            });
        });

        $books = $books->latest('id')->paginate(10);

        return view('admin.books.index', compact('books'));
    }

    // Create: Menampilkan form tambah buku
    public function create()
    {
        return view('admin.books.create');
    }

    // Store: Menyimpan data buku baru
    public function store(Request $request)
{
    $book = $request->validate([
        'title' => 'required|string|max:255',
        'synopsis' => 'required|string',
        'publisher' => 'required|string|max:255',
        'writer' => 'required|string|max:255',
        'publish_year' => 'required|numeric',
        'cover' => 'nullable|image|max:2048',
        'pdf' => 'nullable|file|mimes:pdf|max:10000',
        'category' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'status' => ['required', Rule::in(Book::STATUSES)],
    ]);

    try {

         // Set status awal berdasarkan jumlah
         if ($book['amount'] > 0) {
            $book['status'] = Book::STATUSES['Available'];
        } else {
            $book['status'] = Book::STATUSES['Unavailable'];
        }

        // Simpan cover jika ada
        if ($request->hasFile('cover')) {
            $book['cover'] = $request->file('cover')->store('covers', 'public');
        }

        // Simpan PDF jika ada
        if ($request->hasFile('pdf')) {
            $book['pdf_path'] = $request->file('pdf')->store('pdfs', 'public');
        }

        // Simpan data buku ke database
        Book::create($book);

        return redirect()->route('admin.books.index')->with('success', 'Berhasil menambah buku.');
    } catch (\Exception $e) {
        return back()->withErrors(['msg' => 'Terjadi kesalahan saat menyimpan buku: ' . $e->getMessage()]);
    }
}


    // Edit: Menampilkan form edit buku
    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    // Update: Memperbarui data buku
    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'synopsis' => ['required', 'string'],
            'publisher' => ['required', 'string', 'max:255'],
            'writer' => ['required', 'string', 'max:255'],
            'publish_year' => ['required', 'numeric'],
            'cover' => ['nullable', 'file', 'image', 'max:2048'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10000'],
            'category' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric'],
            'status' => ['required', Rule::in(Book::STATUSES)],
        ]);

        try {
            // Update status berdasarkan jumlah
        if ($data['amount'] > 0) {
            $data['status'] = Book::STATUSES['Available'];
        } else {
            $data['status'] = Book::STATUSES['Unavailable'];
        }
        
            if ($request->hasFile('cover')) {
                $data['cover'] = $request->file('cover')->store('covers', 'public');
                if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                    Storage::disk('public')->delete($book->cover);
                }
            }

            if ($request->hasFile('pdf')) {
                $data['pdf'] = $request->file('pdf')->store('pdfs', 'public');
                if ($book->pdf && Storage::disk('public')->exists($book->pdf)) {
                    Storage::disk('public')->delete($book->pdf);
                }
            }

            $book->update($data);

            return redirect()->route('admin.books.index')->with('success', 'Berhasil mengedit buku.');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Terjadi kesalahan saat memperbarui buku.']);
        }
    }

    // Destroy: Menghapus buku beserta file terkait
    public function destroy(Book $book)
    {
        try {
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            if ($book->pdf && Storage::disk('public')->exists($book->pdf)) {
                Storage::disk('public')->delete($book->pdf);
            }

            $book->delete();

            return redirect()->route('admin.books.index')->with('success', 'Berhasil menghapus buku.');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Terjadi kesalahan saat menghapus buku.']);
        }
    }
}

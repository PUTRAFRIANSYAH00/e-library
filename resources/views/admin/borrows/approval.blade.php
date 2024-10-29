<x-app-layout>
    <div class="container py-5">
        <h1>Persetujuan Peminjaman Buku</h1>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Jumlah</th>
                        <th>Durasi</th>
                        <th>Tanggal Request</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrows as $borrow)
                    <tr>
                        <td>{{ $borrow->user->name }}</td>
                        <td>{{ $borrow->book->title }}</td>
                        <td>{{ $borrow->amount }}</td>
                        <td>{{ $borrow->duration }} hari</td>
                        <td>{{ $borrow->borrowed_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.borrows.approve', $borrow) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm">Setujui</button>
                            </form>
                            <form action="{{ route('admin.borrows.reject', $borrow) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-danger btn-sm">Tolak</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $borrows->links() }}
    </div>
</x-app-layout>
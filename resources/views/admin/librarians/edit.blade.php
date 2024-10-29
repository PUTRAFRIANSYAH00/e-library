<x-admin-layout title="Edit Pustakawan">
    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="row" action="{{ route('admin.librarians.update', $librarian) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengedit pustakawan ini?')">
                @csrf
                @method('PUT')

                <div class="col-12 mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $librarian->name) }}">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="col-12 col-md-6 mb-3">
                    <label for="number" class="form-label">Nomor</label>
                    <input type="number" name="number" class="form-control" id="number"
                        value="{{ old('number', $librarian->number) }}">
                    @error('number')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <input type="text" name="address" class="form-control" id="address"
                        value="{{ old('address', $librarian->address) }}">
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="col-12 col-md-6 mb-3">
                    <label class="form-label mb-3">Jenis Kelamin</label>
                    <br />
                    @foreach (\App\Models\User::GENDERS as $gender)
                        <div class="form-check form-check-inline">
                            <input @checked(old('gender', $librarian->gender) === $gender) class="form-check-input" type="radio" name="gender"
                                id="{{ $gender }}" value="{{ $gender }}">
                            <label class="form-check-label" for="{{ $gender }}">{{ $gender }}</label>
                        </div>
                    @endforeach
                    <br />
                    @error('gender')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password">
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                </div>

                <div class="d-flex w-100 mt-3 justify-content-end">
                    <a href="{{ route('admin.librarians.index') }}" class="btn btn-warning mx-3">Kembali</a>
                    <button type="submit" class="btn btn-primary mx-3">Edit</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

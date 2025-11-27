@extends('layouts.admin.app')

@section('content')
    {{-- Start Main Content --}}
    <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="#">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
                <li class="breadcrumb-item active">Edit Pelanggan</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Edit Pelanggan</h1>
                <p class="mb-0">Edit data pelanggan</p>
            </div>
            <div>
                <a href="{{ route('pelanggan.show', $pelanggan->pelanggan_id) }}" class="btn btn-info text-white">
                    <i class="fas fa-eye me-1"></i> Lihat Detail
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('pelanggan.update', $pelanggan->pelanggan_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name', $pelanggan->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" value="{{ old('last_name', $pelanggan->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birthday" class="form-label">Birthday</label>
                                <input type="date" class="form-control @error('birthday') is-invalid @enderror" 
                                       id="birthday" name="birthday" value="{{ old('birthday', $pelanggan->birthday) }}" required>
                                @error('birthday')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                    <option value="">Pilih Gender</option>
                                    <option value="Male" {{ old('gender', $pelanggan->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $pelanggan->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $pelanggan->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $pelanggan->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $pelanggan->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Multiple File Upload Section -->
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card border-0 shadow">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">File Upload (Multiple Files)</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Existing Files -->
                                        @if($pelanggan->files)
                                            <div class="mb-4">
                                                <h6 class="mb-3">File Terupload:</h6>
                                                <div class="row">
                                                    @foreach(json_decode($pelanggan->files, true) as $file)
                                                        <div class="col-md-4 mb-2">
                                                            <div class="card file-card">
                                                                <div class="card-body p-2">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div class="file-info">
                                                                            <small class="d-block text-truncate" style="max-width: 150px;">
                                                                                {{ $file }}
                                                                            </small>
                                                                            <small class="text-muted">
                                                                                {{ Storage::size('public/pelanggan-files/' . $file) }} bytes
                                                                            </small>
                                                                        </div>
                                                                        <div class="file-actions">
                                                                            <a href="{{ Storage::url('pelanggan-files/' . $file) }}" 
                                                                               target="_blank" class="btn btn-sm btn-info">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <form action="{{ route('pelanggan.destroy-file', [$pelanggan->pelanggan_id, $file]) }}" 
                                                                                  method="POST" style="display:inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                                                        onclick="return confirm('Hapus file ini?')">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- New File Upload -->
                                        <div class="mb-3">
                                            <label for="files" class="form-label">Upload File Baru</label>
                                            <input type="file" class="form-control @error('files.*') is-invalid @enderror" 
                                                   id="files" name="files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            @error('files.*')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Format: JPG, PNG, PDF, DOC, DOCX. Maksimal 2MB per file.
                                            </small>
                                        </div>

                                        <!-- File Preview -->
                                        <div id="filePreview" class="mt-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-success">Update Pelanggan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- End Main Content --}}
@endsection

@push('styles')
<style>
    .file-card {
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    .file-card:hover {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0,123,255,0.3);
    }
    .file-actions .btn {
        padding: 0.25rem 0.5rem;
        margin-left: 0.25rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Preview file sebelum upload
    document.getElementById('files').addEventListener('change', function(e) {
        const filePreview = document.getElementById('filePreview');
        filePreview.innerHTML = '';
        
        if (this.files.length > 0) {
            const fileList = document.createElement('div');
            fileList.className = 'alert alert-info';
            fileList.innerHTML = '<strong>File yang akan diupload:</strong>';
            
            const list = document.createElement('ul');
            list.className = 'mb-0 mt-2';
            
            for (let i = 0; i < this.files.length; i++) {
                const listItem = document.createElement('li');
                listItem.textContent = this.files[i].name + ' (' + (this.files[i].size / 1024).toFixed(2) + ' KB)';
                list.appendChild(listItem);
            }
            
            fileList.appendChild(list);
            filePreview.appendChild(fileList);
        }
    });
</script>
@endpush
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
                <li class="breadcrumb-item active">Detail Pelanggan</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Detail Pelanggan</h1>
                <p class="mb-0">Informasi lengkap pelanggan</p>
            </div>
            <div>
                <a href="{{ route('pelanggan.edit', $pelanggan->pelanggan_id) }}" class="btn btn-warning text-white me-2">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Pelanggan -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Informasi Pelanggan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Nama Lengkap</strong></td>
                            <td>{{ $pelanggan->first_name }} {{ $pelanggan->last_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Lahir</strong></td>
                            <td>{{ \Carbon\Carbon::parse($pelanggan->birthday)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Gender</strong></td>
                            <td>{{ $pelanggan->gender }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>{{ $pelanggan->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Telepon</strong></td>
                            <td>{{ $pelanggan->phone }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- File Pendukung -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">File Pendukung</h6>
                </div>
                <div class="card-body">
                    <!-- Form Upload File -->
                    <div class="mb-4">
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="ref_table" value="pelanggan">
                            <input type="hidden" name="ref_id" value="{{ $pelanggan->pelanggan_id }}">
                            
                            <div class="mb-3">
                                <label for="files" class="form-label">Upload File Pendukung</label>
                                <input type="file" class="form-control" id="files" name="files[]" multiple 
                                       accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">
                                <small class="form-text text-muted">
                                    Format: JPG, PNG, PDF, DOC, DOCX, XLS, XLSX. Maksimal 2MB per file.
                                </small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" id="uploadBtn">
                                <i class="fas fa-upload me-1"></i> Upload File
                            </button>
                        </form>
                    </div>

                    <!-- List File -->
                    <div id="fileList">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat file...</p>
                        </div>
                    </div>
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
        border-radius: 0.375rem;
        transition: all 0.3s ease;
    }
    .file-card:hover {
        border-color: #28a745;
        box-shadow: 0 0 5px rgba(40,167,69,0.3);
    }
    .file-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.25rem;
    }
    .file-actions .btn {
        padding: 0.25rem 0.5rem;
        margin-left: 0.25rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Load files saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        loadFiles();
        
        // Handle form upload
        const uploadForm = document.getElementById('uploadForm');
        if (uploadForm) {
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault();
                uploadFiles();
            });
        }
    });

    // Fungsi untuk memuat daftar file
    function loadFiles() {
        const refTable = 'pelanggan';
        const refId = {{ $pelanggan->pelanggan_id }};
        
        // Show loading
        document.getElementById('fileList').innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat file...</p>
            </div>
        `;
        
        // GUNAKAN URL RELATIVE
        fetch(`/files/${refTable}/${refId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Files loaded:', data); // Debug
            const fileList = document.getElementById('fileList');
            
            if (data.success && data.files && data.files.length > 0) {
                fileList.innerHTML = renderFileList(data.files);
            } else {
                fileList.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                        <p>Belum ada file pendukung</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading files:', error);
            document.getElementById('fileList').innerHTML = `
                <div class="alert alert-danger">
                    Gagal memuat daftar file. Error: ${error.message}
                    <br><small>Pastikan route '/files/{refTable}/{refId}' tersedia</small>
                </div>
            `;
        });
    }

    // Fungsi untuk render daftar file
    function renderFileList(files) {
        if (!files || files.length === 0) {
            return '<div class="text-center text-muted py-4">Tidak ada file</div>';
        }
        
        let html = '<div class="row">';
        
        files.forEach(file => {
            // Gunakan URL yang benar untuk file
            const fileUrl = `/storage/uploads/${file.filename}`;
            const isImage = file.extension && ['jpg', 'jpeg', 'png', 'gif'].includes(file.extension.toLowerCase());
            const fileSize = file.file_size ? (file.file_size / 1024).toFixed(2) : '0';
            
            html += `
                <div class="col-md-6 mb-3">
                    <div class="card file-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                ${isImage ? 
                                    `<img src="${fileUrl}" alt="${file.original_name}" class="file-thumbnail me-3" onerror="this.style.display='none'">` :
                                    `<i class="fas fa-file fa-2x text-muted me-3"></i>`
                                }
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-1 text-truncate" style="max-width: 150px;" title="${file.original_name}">
                                        ${file.original_name}
                                    </h6>
                                    <small class="text-muted">${fileSize} KB - ${file.extension || 'file'}</small>
                                </div>
                                <div class="file-actions">
                                    <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="${fileUrl}" download="${file.original_name}" class="btn btn-sm btn-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteFile(${file.id})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        return html;
    }

    // Fungsi upload files
    function uploadFiles() {
        const form = document.getElementById('uploadForm');
        const formData = new FormData(form);
        const uploadBtn = document.getElementById('uploadBtn');
        
        // Validasi apakah ada file yang dipilih
        const filesInput = document.getElementById('files');
        if (filesInput.files.length === 0) {
            showAlert('Pilih file terlebih dahulu!', 'warning');
            return;
        }
        
        // Disable button dan show loading
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';
        
        // GUNAKAN URL RELATIVE
        fetch('/upload', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Upload response:', data); // Debug
            if (data.success) {
                // Reset form
                form.reset();
                // Reload file list
                loadFiles();
                // Show success message
                showAlert('File berhasil diupload!', 'success');
            } else {
                showAlert(data.message || 'Gagal upload file', 'danger');
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            showAlert('Terjadi kesalahan saat upload: ' + error.message, 'danger');
        })
        .finally(() => {
            // Enable button
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i class="fas fa-upload me-1"></i> Upload File';
        });
    }

    // Fungsi hapus file
    function deleteFile(fileId) {
        if (!confirm('Apakah Anda yakin ingin menghapus file ini?')) {
            return;
        }
        
        // GUNAKAN URL RELATIVE
        fetch(`/files/${fileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Delete response:', data); // Debug
            if (data.success) {
                showAlert('File berhasil dihapus!', 'success');
                loadFiles(); // Reload file list
            } else {
                showAlert(data.message || 'Gagal menghapus file', 'danger');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            showAlert('Terjadi kesalahan saat menghapus file: ' + error.message, 'danger');
        });
    }

    // Fungsi untuk menampilkan alert
    function showAlert(message, type) {
        // Hapus alert lama jika ada
        const oldAlert = document.querySelector('.alert-dismissible');
        if (oldAlert) {
            oldAlert.remove();
        }
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Tambahkan alert di atas card
        const card = document.querySelector('.card');
        card.parentNode.insertBefore(alertDiv, card);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
</script>
@endpush
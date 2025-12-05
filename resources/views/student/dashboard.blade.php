@extends('layouts.templates')

@push('title', 'Student Dashboard')

@push('styles')
<style>
    .student-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--bs-primary);
    }
    
    .avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(45deg, var(--bs-primary), var(--bs-purple));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 600;
    }
    
    .nav-tabs-student {
        border-bottom: 2px solid #dee2e6;
    }
    
    .nav-tabs-student .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s;
    }
    
    .nav-tabs-student .nav-link:hover {
        border-bottom-color: #dee2e6;
        color: var(--bs-primary);
    }
    
    .nav-tabs-student .nav-link.active {
        /* color: var(--bs-primary); */
        border-bottom-color: var(--bs-primary);
        background-color: transparent;
    }
    
    .schedule-card {
        border-left: 4px solid var(--bs-primary);
        transition: transform 0.2s;
    }
    
    .schedule-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .attendance-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .day-header {
        background: linear-gradient(135deg, var(--bs-primary), #6c63ff);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 10px 10px 0 0;
        margin-bottom: 0;
    }
    
    .profile-card {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .info-row {
        border-bottom: 1px solid #e3e6f0;
        padding: 0.75rem 0;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
    }
    
    .info-value {
        color: #6c757d;
    }
    
    .photo-upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .photo-upload-area:hover {
        border-color: var(--bs-primary);
        background-color: rgba(var(--bs-primary-rgb), 0.05);
    }
    
    .photo-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto;
        display: block;
        border: 3px solid var(--bs-primary);
    }
</style>
@endpush

@section('content')
@livewire('student.dashboard')
@endsection

{{-- @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
        
        // Auto-hide alerts
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(() => {
                alert.classList.remove('show');
            }, 5000);
        }
        
        // Photo upload preview
        document.getElementById('photo').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photoPreview').src = e.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    });
    
    // Livewire listeners
    document.addEventListener('livewire:initialized', () => {
        @this.on('profile-updated', () => {
            // Show success message
            const toast = new bootstrap.Toast(document.getElementById('successToast'));
            toast.show();
        });
    });
</script>
@endpush --}}
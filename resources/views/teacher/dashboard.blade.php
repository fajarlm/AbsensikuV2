@extends('layouts.templates')

{{-- @section('title', 'Dahsboard') --}}
@push('styles')
    <style>
        
        .avatar-circle-sm {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .card {
            border: none;
            border-radius: 15px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.05);
        }

        .border-start {
            border-left-width: 4px !important;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
            border-color: var(--bs-primary);
        }

        .btn {
            border-radius: 10px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }

        .badge {
            border-radius: 10px;
            padding: 0.5em 1em;
            font-weight: 500;
        }

        .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }

        .status-badge {
            padding: 0.35rem 1rem;
            font-size: 0.85rem;
        }

        .schedule-info {
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>
@endpush
@section('content')
    @livewire('teacher.dashboard')
@endsection


{{-- @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => {
                new bootstrap.Tooltip(tooltip);
            });

            // Auto-hide flash messages
            const toastEl = document.querySelector('.toast');
            if (toastEl) {
                setTimeout(() => {
                    toastEl.classList.remove('show');
                }, 4000);
            }

            // Close modal with escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && @this.showModal) {
                    @this.resetModal();
                }
            });

            // Close modal when clicking outside
            document.addEventListener('click', function(e) {
                const modal = document.querySelector('.modal.show');
                if (modal && e.target === modal && @this.showModal) {
                    @this.resetModal();
                }
            });
        });

        // Livewire event listeners
        document.addEventListener('livewire:initialized', () => {
            @this.on('attendance-saved', (event) => {
                // Show success message
                const toast = new bootstrap.Toast(document.getElementById('successToast'));
                toast.show();
            });
        });
    </script>
@endpush --}}

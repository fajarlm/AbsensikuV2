@extends('layouts.templates')

@push('title', 'Student Dashboard')

@push('styles')
<style>
    :root {
        --student-primary: #4f46e5;
        --student-primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        --student-bg-soft: #f8fafc;
        --student-card-shadow: 0 10px 30px -10px rgba(79, 70, 229, 0.1), 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        --student-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        background-color: #f1f5f9 !important;
    }

    .premium-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: var(--student-card-shadow);
        transition: var(--student-transition);
        overflow: hidden;
    }

    .premium-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -15px rgba(79, 70, 229, 0.15), 0 2px 5px 0 rgba(0, 0, 0, 0.05);
    }

    .dashboard-banner {
        background: var(--student-primary-gradient);
        color: white;
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.3);
        position: relative;
        overflow: hidden;
        border: none;
    }

    .dashboard-banner::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 80%);
        top: -100px;
        right: -100px;
        border-radius: 50%;
        pointer-events: none;
    }

    .dashboard-banner::before {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        bottom: -50px;
        left: -50px;
        border-radius: 50%;
        pointer-events: none;
    }

    .student-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        transition: var(--student-transition);
    }

    .student-avatar:hover {
        transform: scale(1.05);
        border-color: rgba(255, 255, 255, 0.6);
    }

    .nav-pills-student {
        background: #ffffff;
        padding: 0.5rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    .nav-pills-student .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: var(--student-transition);
        background-color: transparent;
    }

    .nav-pills-student .nav-link:hover {
        color: var(--student-primary);
        background-color: #f1f5f9;
    }

    .nav-pills-student .nav-link.active {
        background: var(--student-primary-gradient) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px -3px rgba(79, 70, 229, 0.4);
    }

    /* Schedule design */
    .day-badge {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .schedule-item {
        border-left: 4px solid var(--student-primary);
        border-radius: 4px 12px 12px 4px;
        background: #ffffff;
        transition: var(--student-transition);
        border-top: 1px solid #f1f5f9;
        border-right: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
    }

    .schedule-item:hover {
        border-left-color: #7c3aed;
        transform: translateX(4px);
    }

    .time-badge {
        background-color: #e0e7ff;
        color: #4338ca;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 8px;
        font-size: 0.85rem;
    }

    .attendance-badge-pill {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.35rem 0.85rem;
        border-radius: 50rem;
        letter-spacing: 0.025em;
    }

    /* Custom File Upload */
    .custom-file-upload {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        background-color: #f8fafc;
        cursor: pointer;
        transition: var(--student-transition);
    }

    .custom-file-upload:hover {
        border-color: var(--student-primary);
        background-color: #e0e7ff;
    }

    .info-row-item {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-row-item:last-child {
        border-bottom: none;
    }

    .info-row-label {
        font-weight: 600;
        color: #64748b;
    }

    .info-row-value {
        color: #0f172a;
        font-weight: 500;
    }

    /* Modal / Details */
    .detail-item {
        background-color: #f8fafc;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.75rem;
    }

    /* Animation classes */
    .fade-in-up {
        animation: fadeInUp 0.4s ease forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
@livewire('student.dashboard')
@endsection
 <!-- Scripts -->
 {{-- overlay --}}
 <script data-navigate-track
     src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
     crossorigin="anonymous"></script>

 <script data-navigate-track src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
     crossorigin="anonymous"></script>

 <script data-navigate-track src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
     crossorigin="anonymous"></script>

 <script data-navigate-track src="{{ asset('adminLTE/dist/js/adminlte.min.js') }}"></script>

 {{-- mdb --}}
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.2.0/mdb.umd.min.js"></script>

 {{-- sweetalert2 --}}
 <script data-navigate-track src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <script>
     document.addEventListener('livewire:init', () => {
         Livewire.on('swal:alert', (data) => {
             const eventData = Array.isArray(data) ? data[0] : data;
             Swal.fire({
                 title: eventData.title || 'Informasi',
                 text: eventData.text || '',
                 icon: eventData.icon || 'info',
                 confirmButtonText: 'OK',
                 customClass: {
                     confirmButton: 'btn btn-primary px-4 rounded-3'
                 },
                 buttonsStyling: false
             });
         });
     });
 </script>

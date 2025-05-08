// upload-bukti.js
document.addEventListener('DOMContentLoaded', function() {
    // Handle upload bukti modal
    const uploadBuktiButtons = document.querySelectorAll('.upload-bukti');
    const uploadBuktiForm = document.getElementById('uploadBuktiForm');
    const modal = new bootstrap.Modal(document.getElementById('uploadBuktiModal'));
    
    uploadBuktiButtons.forEach(button => {
        button.addEventListener('click', function() {
            const reservasiId = this.getAttribute('data-id');
            uploadBuktiForm.action = `/reservasi/${reservasiId}/upload-bukti`;
            modal.show();
        });
    });
    
    // Preview image before upload
    const fotoInput = document.getElementById('foto');
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('profile-img-preview').src = event.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
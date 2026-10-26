<!-- Universal Modern Glassmorphic Confirmation Modal -->
<div class="modal fade" id="appUniversalConfirmModal" tabindex="-1" aria-labelledby="appConfirmModalTitle" aria-hidden="true" style="backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content" id="appConfirmModalCard" style="background: #161B33; border: 1px solid rgba(166, 156, 172, 0.35); border-radius: 22px; color: #FFFFFF; box-shadow: 0 25px 60px rgba(0, 0, 0, 0.75); overflow: hidden; transition: all 0.25s ease;">
            <div class="modal-body p-4 text-center">
                <!-- Glowing Dynamic Icon -->
                <div id="appConfirmModalIconWrap" class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px; border-radius: 50%; transition: all 0.25s ease;">
                    <i id="appConfirmModalIcon" class="bi fs-1" style="font-size: 2rem;"></i>
                </div>

                <!-- Title & Message -->
                <h4 class="fw-bold mb-1 font-monospace text-white" id="appConfirmModalTitle">Konfirmasi</h4>
                <p class="text-secondary small mb-3" id="appConfirmModalMessage" style="color: var(--colors-muted) !important; font-size: 0.88rem; line-height: 1.5;"></p>

                <!-- Optional Highlighted Item Info -->
                <div id="appConfirmModalItemBox" class="p-3 rounded-3 text-start mb-3 d-none" style="background: rgba(13, 12, 29, 0.65); border: 1px solid rgba(166, 156, 172, 0.2);">
                    <div class="small text-secondary mb-1 font-monospace" style="font-size: 0.72rem; color: var(--colors-muted) !important;">TARGET / ITEM:</div>
                    <div class="fw-bold text-white fs-6 text-truncate font-monospace" id="appConfirmModalItemText"></div>
                </div>

                <!-- Action Buttons: Batal & Konfirmasi -->
                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button type="button" class="btn px-4 py-2.5 fw-semibold font-monospace flex-grow-1" data-bs-dismiss="modal" style="background: rgba(71, 73, 115, 0.35); border: 1px solid rgba(166, 156, 172, 0.25); color: #FFFFFF; border-radius: 12px; transition: all 0.2s;">
                        Batal
                    </button>
                    <button type="button" id="appConfirmModalActionBtn" class="btn px-4 py-2.5 fw-bold font-monospace flex-grow-1 d-inline-flex align-items-center justify-content-center gap-2" style="border: none; color: #FFFFFF; border-radius: 12px; transition: all 0.2s;">
                        <i id="appConfirmBtnIcon" class="bi"></i>
                        <span id="appConfirmBtnText">Konfirmasi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        let activeConfirmCallback = null;

        window.showAppConfirmModal = function(options) {
            const modalEl = document.getElementById('appUniversalConfirmModal');
            if (!modalEl) return;

            const card = document.getElementById('appConfirmModalCard');
            const iconWrap = document.getElementById('appConfirmModalIconWrap');
            const icon = document.getElementById('appConfirmModalIcon');
            const title = document.getElementById('appConfirmModalTitle');
            const message = document.getElementById('appConfirmModalMessage');
            const itemBox = document.getElementById('appConfirmModalItemBox');
            const itemText = document.getElementById('appConfirmModalItemText');
            const actionBtn = document.getElementById('appConfirmModalActionBtn');
            const btnIcon = document.getElementById('appConfirmBtnIcon');
            const btnText = document.getElementById('appConfirmBtnText');

            const type = options.type || 'danger'; // 'danger', 'success', 'warning', 'info'
            activeConfirmCallback = typeof options.onConfirm === 'function' ? options.onConfirm : null;

            title.textContent = options.title || 'Konfirmasi Tindakan';
            message.textContent = options.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';

            // Item Box
            if (options.itemText) {
                itemText.textContent = options.itemText;
                itemBox.classList.remove('d-none');
            } else {
                itemBox.classList.add('d-none');
            }

            // Theme Customization (Glassmorphic Gradients)
            if (type === 'success') {
                card.style.background = 'radial-gradient(circle at 50% 0%, rgba(16, 185, 129, 0.18) 0%, #161B33 80%)';
                card.style.borderColor = 'rgba(16, 185, 129, 0.4)';
                card.style.boxShadow = '0 25px 60px rgba(0, 0, 0, 0.75), 0 0 35px rgba(16, 185, 129, 0.18)';

                iconWrap.style.background = 'rgba(16, 185, 129, 0.15)';
                iconWrap.style.border = '2px solid rgba(16, 185, 129, 0.4)';
                iconWrap.style.boxShadow = '0 0 24px rgba(16, 185, 129, 0.3)';
                icon.className = 'bi ' + (options.icon || 'bi-check2-circle');
                icon.style.color = '#34d399';

                actionBtn.style.background = 'linear-gradient(135deg, #10B981 0%, #059669 100%)';
                actionBtn.style.boxShadow = '0 6px 18px rgba(16, 185, 129, 0.35)';
                btnIcon.className = 'bi ' + (options.btnIcon || 'bi-check2');
                btnText.textContent = options.confirmText || 'Ya, Lanjutkan';
            } else if (type === 'warning') {
                card.style.background = 'radial-gradient(circle at 50% 0%, rgba(245, 158, 11, 0.18) 0%, #161B33 80%)';
                card.style.borderColor = 'rgba(245, 158, 11, 0.4)';
                card.style.boxShadow = '0 25px 60px rgba(0, 0, 0, 0.75), 0 0 35px rgba(245, 158, 11, 0.18)';

                iconWrap.style.background = 'rgba(245, 158, 11, 0.15)';
                iconWrap.style.border = '2px solid rgba(245, 158, 11, 0.4)';
                iconWrap.style.boxShadow = '0 0 24px rgba(245, 158, 11, 0.3)';
                icon.className = 'bi ' + (options.icon || 'bi-exclamation-triangle-fill');
                icon.style.color = '#fbbf24';

                actionBtn.style.background = 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)';
                actionBtn.style.boxShadow = '0 6px 18px rgba(245, 158, 11, 0.35)';
                btnIcon.className = 'bi ' + (options.btnIcon || 'bi-arrow-repeat');
                btnText.textContent = options.confirmText || 'Ya, Lanjutkan';
            } else {
                // Danger (Default)
                card.style.background = 'radial-gradient(circle at 50% 0%, rgba(255, 77, 109, 0.18) 0%, #161B33 80%)';
                card.style.borderColor = 'rgba(255, 77, 109, 0.4)';
                card.style.boxShadow = '0 25px 60px rgba(0, 0, 0, 0.75), 0 0 35px rgba(255, 77, 109, 0.18)';

                iconWrap.style.background = 'rgba(255, 77, 109, 0.15)';
                iconWrap.style.border = '2px solid rgba(255, 77, 109, 0.4)';
                iconWrap.style.boxShadow = '0 0 24px rgba(255, 77, 109, 0.3)';
                icon.className = 'bi ' + (options.icon || 'bi-trash3-fill');
                icon.style.color = '#ff4d6d';

                actionBtn.style.background = 'linear-gradient(135deg, #ff4d6d 0%, #c9184a 100%)';
                actionBtn.style.boxShadow = '0 6px 18px rgba(255, 77, 109, 0.35)';
                btnIcon.className = 'bi ' + (options.btnIcon || 'bi-trash-fill');
                btnText.textContent = options.confirmText || 'Ya, Hapus';
            }

            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();
        };

        // Modal Action Button Listener
        document.addEventListener('DOMContentLoaded', function() {
            const actionBtn = document.getElementById('appConfirmModalActionBtn');
            const modalEl = document.getElementById('appUniversalConfirmModal');

            if (actionBtn && modalEl) {
                actionBtn.addEventListener('click', function() {
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                    if (typeof activeConfirmCallback === 'function') {
                        const cb = activeConfirmCallback;
                        activeConfirmCallback = null;
                        cb();
                    }
                });
            }

            // Universal Interceptor for Forms with data-confirm
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form && form.hasAttribute('data-confirm') && !form.dataset.confirmed) {
                    e.preventDefault();
                    const title = form.getAttribute('data-confirm-title') || 'Konfirmasi';
                    const message = form.getAttribute('data-confirm') || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
                    const type = form.getAttribute('data-confirm-type') || 'danger';
                    const itemText = form.getAttribute('data-confirm-item') || '';
                    const confirmText = form.getAttribute('data-confirm-btn') || (type === 'danger' ? 'Ya, Hapus' : (type === 'success' ? 'Ya, Lanjutkan' : 'Konfirmasi'));
                    const icon = form.getAttribute('data-confirm-icon') || '';

                    window.showAppConfirmModal({
                        title: title,
                        message: message,
                        itemText: itemText,
                        type: type,
                        confirmText: confirmText,
                        icon: icon,
                        onConfirm: function() {
                            form.dataset.confirmed = 'true';
                            form.submit();
                        }
                    });
                }
            });

            // Universal Interceptor for Links with data-confirm
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a[data-confirm]');
                if (link && !link.dataset.confirmed) {
                    e.preventDefault();
                    const title = link.getAttribute('data-confirm-title') || 'Konfirmasi';
                    const message = link.getAttribute('data-confirm') || 'Apakah Anda yakin ingin melanjutkan?';
                    const type = link.getAttribute('data-confirm-type') || 'warning';
                    const confirmText = link.getAttribute('data-confirm-btn') || 'Lanjutkan';
                    const icon = link.getAttribute('data-confirm-icon') || '';

                    window.showAppConfirmModal({
                        title: title,
                        message: message,
                        type: type,
                        confirmText: confirmText,
                        icon: icon,
                        onConfirm: function() {
                            link.dataset.confirmed = 'true';
                            window.location.href = link.href;
                        }
                    });
                }
            });
        });
    })();
</script>

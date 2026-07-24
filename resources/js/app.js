import './bootstrap';

// Smooth reveal-on-scroll and button micro-interactions.
(() => {
    const reveals = document.querySelectorAll('.reveal');

    if (reveals.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (! entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('show');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -48px 0px' });

        reveals.forEach((element) => observer.observe(element));
    }

    document.querySelectorAll('.btn').forEach((button) => {
        button.addEventListener('pointermove', (event) => {
            const rect = button.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            button.style.setProperty('--cursor-x', `${x}px`);
            button.style.setProperty('--cursor-y', `${y}px`);
        });
        button.addEventListener('pointerdown', () => button.classList.add('is-pressed'));
        button.addEventListener('pointerup', () => window.setTimeout(() => button.classList.remove('is-pressed'), 120));
        button.addEventListener('pointerleave', () => button.classList.remove('is-pressed'));
    });

    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (event) => {
            const targetId = anchor.getAttribute('href').slice(1);

            if (! targetId) {
                return;
            }

            const target = document.getElementById(targetId);

            if (! target) {
                return;
            }

            event.preventDefault();
            const top = target.getBoundingClientRect().top + window.scrollY - 84;
            window.scrollTo({ top, behavior: 'smooth' });
        });
    });
})();

document.querySelectorAll('.tilt-card').forEach((card) => {
    card.addEventListener('mousemove', (event) => {
        const rect = card.getBoundingClientRect();
        const x = (event.clientX - rect.left) / rect.width - 0.5;
        const y = (event.clientY - rect.top) / rect.height - 0.5;

        card.style.transform = `perspective(900px) rotateX(${y * -8}deg) rotateY(${x * 8}deg) translateY(-6px)`;
    });

    card.addEventListener('mouseleave', () => {
        card.style.transform = '';
    });
});

// Public upload polling: reflect admin toggle changes without reload
(() => {
    const shell = document.querySelector('.public-upload-shell');
    if (! shell) return;

    const kategori = shell.dataset.kategori;
    const actor = shell.dataset.actor;
    const endpoint = '/api/upload-statuses';

    async function checkStatus() {
        try {
            const res = await fetch(endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (! res.ok) return;
            const statuses = await res.json();
            const isOpen = !! statuses[kategori];
            const statusLabel = shell.querySelector('.upload-status-label strong');
            const lastNode = shell.querySelector('.upload-last-update');

            if (! isOpen) {
                shell.innerHTML = `
                    <div class="auth-card">
                        <p class="eyebrow">Sesi Kompre</p>
                        <h1>Sesi belum dibuka</h1>
                        <p class="auth-intro">Form upload ${kategori.toUpperCase()} untuk ${actor.charAt(0).toUpperCase() + actor.slice(1)} belum diaktifkan admin.</p>
                        <a class="btn primary full" href="/">Kembali ke Beranda</a>
                    </div>
                `;
            } else {
                if (statusLabel) statusLabel.textContent = 'Dibuka';
                if (lastNode) lastNode.textContent = 'Terakhir diperiksa: ' + new Date().toLocaleString();
            }
        } catch (err) {
            // ignore network errors silently
            console.error('Polling error', err);
        }
    }

    // initial check and interval
    checkStatus();
    setInterval(checkStatus, 10000);

    // If Echo is available, listen for server push updates
    if (window.Echo) {
        try {
            window.Echo.channel('repository-settings').listen('RepositorySettingUpdated', (payload) => {
                if (! payload || payload.kategori !== kategori) return;

                const isOpen = payload.status === 'open';
                if (! isOpen) {
                    shell.innerHTML = `
                        <div class="auth-card">
                            <p class="eyebrow">Sesi Kompre</p>
                            <h1>Sesi belum dibuka</h1>
                            <p class="auth-intro">Form upload ${kategori.toUpperCase()} untuk ${actor.charAt(0).toUpperCase() + actor.slice(1)} belum diaktifkan admin.</p>
                            <a class="btn primary full" href="/">Kembali ke Beranda</a>
                        </div>
                    `;
                } else {
                    const statusLabel = shell.querySelector('.upload-status-label strong');
                    const lastNode = shell.querySelector('.upload-last-update');
                    if (statusLabel) statusLabel.textContent = 'Dibuka';
                    if (lastNode) lastNode.textContent = 'Terakhir diperbarui: ' + new Date(payload.updated_at).toLocaleString();
                }
            });
        } catch (e) {
            // ignore Echo init errors
            console.warn('Echo listener failed', e);
        }
    }
})();

document.querySelectorAll('.viewer-shell').forEach((viewer) => {
    viewer.addEventListener('contextmenu', (event) => event.preventDefault());
});

document.addEventListener('keydown', (event) => {
    const key = event.key.toLowerCase();
    const protectedViewer = document.querySelector('.viewer-shell');

    if (! protectedViewer) {
        return;
    }

    // Block common save/print/view-source shortcuts and devtools keys
    const blockedCombos = [
        ['s'], // Ctrl+S / Cmd+S
        ['p'], // Ctrl+P / Cmd+P
        ['u'], // Ctrl+U / View source
    ];

    if ((event.ctrlKey || event.metaKey) && (blockedCombos.some(c => c.includes(key)) || (event.shiftKey && key === 's'))) {
        event.preventDefault();
    }

    // Block devtools keys
    if (event.key === 'F12' || (event.ctrlKey && event.shiftKey && ['i', 'c', 'j'].includes(key))) {
        event.preventDefault();
    }
});

// Prevent copying when viewer is active
document.addEventListener('copy', (e) => {
    if (document.querySelector('.viewer-shell')) {
        e.preventDefault();
    }
});

// Prevent context menu on document rows (repository listing)
document.querySelectorAll('.document-row').forEach((row) => {
    row.addEventListener('contextmenu', (e) => e.preventDefault());
});

// Admin upload session toggle — submit via AJAX and update UI without reload
document.querySelectorAll('.upload-toggle-form').forEach((form) => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const submitBtn = form.querySelector('.toggle-btn');
        const statusNode = form.querySelector('.upload-status');
        const formData = new FormData(form);
        const action = form.getAttribute('action');
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');

        if (! tokenMeta) {
            return form.submit();
        }

        submitBtn.disabled = true;
        const origText = submitBtn.textContent;
        submitBtn.textContent = 'Tunggu...';

        try {
            const res = await fetch(action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': tokenMeta.getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData,
            });

            if (! res.ok) throw new Error('Network error');

            const appliedStatus = String(formData.get('status'));
            const isOpen = appliedStatus === 'open';
            form.querySelector('input[name="status"]').value = isOpen ? 'closed' : 'open';

            statusNode.textContent = isOpen ? 'Dibuka untuk upload' : 'Ditutup untuk upload';
            form.classList.toggle('is-open', isOpen);
            form.classList.toggle('is-closed', ! isOpen);
            submitBtn.classList.toggle('primary', ! isOpen);
            submitBtn.classList.toggle('secondary', isOpen);
            submitBtn.textContent = isOpen ? 'Tutup' : 'Buka';
        } catch (err) {
            console.error(err);
            // Fallback: submit the form normally
            form.submit();
        } finally {
            submitBtn.disabled = false;
            if (submitBtn.textContent === 'Tunggu...') {
                submitBtn.textContent = origText;
            }
        }
    });
});

// Modal preview handler
(() => {
    const modal = document.getElementById('viewer-modal');
    if (! modal) return;

    const iframe = modal.querySelector('iframe');
    const watermarkNode = modal.querySelector('.viewer-watermark');
    const closeBtn = modal.querySelector('.viewer-modal-close');
    const fsToggle = modal.querySelector('.viewer-modal-fullscreen-toggle');

    const fullscreenElement = () => (
        document.fullscreenElement ||
        document.webkitFullscreenElement ||
        document.mozFullScreenElement ||
        document.msFullscreenElement
    );

    async function requestFullscreen(element) {
        const request = element.requestFullscreen ||
            element.webkitRequestFullscreen ||
            element.mozRequestFullScreen ||
            element.msRequestFullscreen;

        if (! request) {
            throw new Error('Fullscreen API tidak tersedia');
        }

        await request.call(element);
    }

    async function exitFullscreen() {
        const exit = document.exitFullscreen ||
            document.webkitExitFullscreen ||
            document.mozCancelFullScreen ||
            document.msExitFullscreen;

        if (exit) {
            await exit.call(document);
        }
    }

    async function openModal(metaUrl) {
        try {
            const res = await fetch(metaUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (! res.ok) throw new Error('Gagal mengambil metadata');
            const json = await res.json();
            iframe.src = json.fileUrl;
            modal.classList.add('fullscreen');
            document.body.classList.add('viewer-fullscreen-open');
            if (json.watermark) {
                watermarkNode.textContent = json.watermark;
                watermarkNode.style.display = 'block';
            } else {
                watermarkNode.style.display = 'none';
            }
            modal.setAttribute('aria-hidden', 'false');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } catch (err) {
            console.error('Preview error', err);
            alert('Gagal membuka preview dokumen.');
        }
    }

    function closeModal() {
        if (fullscreenElement() === modal) {
            exitFullscreen().catch(() => {});
        }

        iframe.src = '';
        watermarkNode.textContent = '';
        watermarkNode.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        modal.style.display = 'none';
        modal.classList.remove('fullscreen');
        document.body.classList.remove('viewer-fullscreen-open');
        document.body.style.overflow = '';
    }

    // Fullscreen API: modal toggle
    let isInFullscreenMode = false;

    if (fsToggle) {
        fsToggle.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopImmediatePropagation();

            try {
                if (fullscreenElement()) {
                    await exitFullscreen();
                    isInFullscreenMode = false;
                    fsToggle.textContent = 'Full';
                } else {
                    await requestFullscreen(modal);
                    isInFullscreenMode = true;
                    fsToggle.textContent = 'Exit';
                }
            } catch (err) {
                console.warn('Fullscreen toggle failed', err);
                modal.classList.add('fullscreen');
            }
        });
    }
    
    if (fsToggle) {
        fsToggle.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            try {
                if (!document.fullscreenElement) {
                    // Try to request fullscreen
                    const elem = modal;
                    if (elem.requestFullscreen) {
                        await elem.requestFullscreen();
                        isInFullscreenMode = true;
                    } else if (elem.webkitRequestFullscreen) {
                        await elem.webkitRequestFullscreen();
                        isInFullscreenMode = true;
                    } else if (elem.mozRequestFullScreen) {
                        await elem.mozRequestFullScreen();
                        isInFullscreenMode = true;
                    } else if (elem.msRequestFullscreen) {
                        await elem.msRequestFullscreen();
                        isInFullscreenMode = true;
                    }
                    fsToggle.textContent = '⤫';
                } else {
                    if (document.exitFullscreen) {
                        await document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        await document.webkitExitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        await document.mozCancelFullScreen();
                    } else if (document.msExitFullscreen) {
                        await document.msExitFullscreen();
                    }
                    isInFullscreenMode = false;
                    fsToggle.textContent = '⤢';
                }
            } catch (e) {
                console.warn('Fullscreen toggle:', e);
                // Fallback: just show notification
                alert('Mode fullscreen tidak didukung browser Anda. Preview sudah ditampilkan dengan ukuran maksimal.');
            }
        });
    }

    // Standalone page fullscreen button
    const pageFsBtn = document.getElementById('page-fullscreen-btn');
    if (pageFsBtn) {
        pageFsBtn.textContent = 'Full';
        pageFsBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopImmediatePropagation();

            try {
                const frame = document.querySelector('.viewer-frame') || document.documentElement;

                if (fullscreenElement()) {
                    await exitFullscreen();
                    pageFsBtn.textContent = 'Full';
                } else {
                    await requestFullscreen(frame);
                    pageFsBtn.textContent = 'Exit';
                }
            } catch (err) {
                console.warn('Page fullscreen failed', err);
            }
        });

        pageFsBtn.addEventListener('click', async () => {
            try {
                const frame = document.querySelector('.viewer-frame') || document.documentElement;
                if (! document.fullscreenElement) {
                    await (frame.requestFullscreen ? frame.requestFullscreen() : frame.webkitRequestFullscreen());
                } else {
                    await (document.exitFullscreen ? document.exitFullscreen() : document.webkitExitFullscreen());
                }
            } catch (e) {
                console.warn('Page fullscreen failed', e);
            }
        });
    }

    // Update UI when fullscreen state changes
    const handleFullscreenChange = () => {
        const inFs = !!document.fullscreenElement;
        if (fsToggle) {
            fsToggle.textContent = inFs ? '⤫' : '⤢';
            fsToggle.setAttribute('aria-label', inFs ? 'Keluar fullscreen' : 'Fullscreen');
        }
    };
    
    document.addEventListener('fullscreenchange', handleFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
    document.addEventListener('mozfullscreenchange', handleFullscreenChange);
    document.addEventListener('msfullscreenchange', handleFullscreenChange);

    const setReadableFullscreenLabels = () => {
        const label = fullscreenElement() ? 'Exit' : 'Full';

        if (fsToggle) {
            fsToggle.textContent = label;
        }

        if (pageFsBtn) {
            pageFsBtn.textContent = label;
        }
    };

    document.addEventListener('fullscreenchange', setReadableFullscreenLabels);
    document.addEventListener('webkitfullscreenchange', setReadableFullscreenLabels);
    document.addEventListener('mozfullscreenchange', setReadableFullscreenLabels);
    document.addEventListener('msfullscreenchange', setReadableFullscreenLabels);
    setReadableFullscreenLabels();

    closeBtn.addEventListener('click', closeModal);
    modal.querySelector('.viewer-modal-backdrop').addEventListener('click', closeModal);

    document.querySelectorAll('.preview-btn').forEach((btn) => {
        btn.addEventListener('click', (e) => {
    
            const url = btn.dataset.metaUrl;
            if (! url) return;
            openModal(url);
        });
    });

    // handle window resize while modal open: keep fullscreen at all times
    window.addEventListener('resize', () => {
        if (modal.style.display !== 'flex') return;
        // Always maintain fullscreen on all screen sizes
        modal.classList.add('fullscreen');
        iframe.style.height = 'calc(100vh - 48px)';
    });
})();

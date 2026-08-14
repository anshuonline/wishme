document.addEventListener('DOMContentLoaded', () => {
    // --- Advanced GSAP Entry Animation & Confetti ---
    const enterBtn = document.getElementById('enterBtn');
    const entryOverlay = document.getElementById('entryOverlay');
    const tricolorSweep = document.getElementById('tricolorSweep');
    const mainBody = document.getElementById('mainBody');

    // Float animation for ornaments using GSAP
    if (document.querySelector('.ornament-1')) {
        gsap.to('.ornament-1', {
            y: -window.innerHeight - 500,
            x: 60,
            rotation: 15,
            duration: 20,
            ease: "none",
            repeat: -1
        });
        
        gsap.to('.ornament-2', {
            y: -window.innerHeight - 600,
            x: -40,
            rotation: -10,
            duration: 25,
            ease: "none",
            repeat: -1,
            delay: 3
        });
    }

    if(enterBtn) {
        enterBtn.addEventListener('click', function() {
            // Fire Confetti!
            if (typeof confetti !== 'undefined') {
                const duration = 3000;
                const end = Date.now() + duration;

                (function frame() {
                    confetti({ particleCount: 5, angle: 60, spread: 55, origin: { x: 0 }, colors: ['#FF9933', '#FFFFFF', '#138808'] });
                    confetti({ particleCount: 5, angle: 120, spread: 55, origin: { x: 1 }, colors: ['#FF9933', '#FFFFFF', '#138808'] });
                    if (Date.now() < end) requestAnimationFrame(frame);
                }());
            }

            // GSAP Timeline
            const tl = gsap.timeline();
            tl.to(tricolorSweep, { y: "0%", duration: 0.8, ease: "power3.inOut" })
              .set(entryOverlay, { display: "none" })
              .to(tricolorSweep, { y: "-100%", duration: 0.8, ease: "power3.inOut" })
              .from("header", { y: -50, opacity: 0, duration: 0.6, ease: "back.out(1.7)" }, "-=0.4")
              .from("main .animate-slide-up", { y: 50, opacity: 0, duration: 0.8, stagger: 0.2, ease: "power3.out" }, "-=0.4");
              
            if (document.querySelector('.ornament')) {
                tl.from(".ornament", { scale: 0, opacity: 0, duration: 1, stagger: 0.3, ease: "elastic.out(1, 0.5)" }, "-=0.8");
            }

            if(mainBody) mainBody.classList.remove('locked');
        });
    }

    // --- File Upload & Cropper Logic ---
    const fileInput = document.getElementById('user_image');
    const dropZone = document.getElementById('drop-zone');
    const uploadContent = document.getElementById('upload-content');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeImageBtn = document.getElementById('remove-image');
    
    const cropperModal = document.getElementById('cropperModal');
    const cropperImage = document.getElementById('cropperImage');
    const closeCropperBtn = document.getElementById('closeCropperBtn');
    const cancelCropBtn = document.getElementById('cancelCropBtn');
    const applyCropBtn = document.getElementById('applyCropBtn');
    const croppedInput = document.getElementById('user_image_cropped');
    let cropper = null;

    // Visual Editor Nodes
    const visualEditorModal = document.getElementById('visualEditorModal');
    const editorUserImage = document.getElementById('editorUserImage');
    const editorFrameImage = document.getElementById('editorFrameImage');
    const closeEditorBtn = document.getElementById('closeEditorBtn');
    const cancelEditorBtn = document.getElementById('cancelEditorBtn');
    const applyEditorBtn = document.getElementById('applyEditorBtn');
    const editorZoom = document.getElementById('editorZoom');
    const editorContainer = document.getElementById('editorContainer');
    
    // Hidden inputs
    const imgXInput = document.getElementById('img_x');
    const imgYInput = document.getElementById('img_y');
    const imgScaleInput = document.getElementById('img_scale');
    
    let editorState = {
        x: 0,
        y: 0,
        scale: 1,
        isDragging: false,
        startX: 0,
        startY: 0
    };

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if(e.target.files.length > 0) handleFile(e.target.files[0]);
        });
    }

    if (dropZone) {
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-india-saffron', 'bg-orange-50/30');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-india-saffron', 'bg-orange-50/30');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-india-saffron', 'bg-orange-50/30');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handleFile(e.dataTransfer.files[0]);
            }
        });
    }

    function handleFile(file) {
        if (!file) return;

        // 2MB validation
        if (file.size > 2 * 1024 * 1024) {
            alert('File size exceeds 2MB limit. Please upload a smaller image.');
            fileInput.value = '';
            return;
        }

        if (!file.type.match('image.*')) {
            alert('Please upload an image file (JPG, PNG, WEBP).');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            if (cropperModal) {
                cropperImage.src = e.target.result;
                cropperModal.classList.remove('hidden');
                cropperModal.classList.add('flex');
                
                // We must wait for the image to load and the modal to be visible
                cropperImage.onload = () => {
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1, 
                        viewMode: 1,
                        autoCropArea: 1,
                        background: true,
                    });
                };
            } else {
                // Direct upload without Cropper or Visual Editor (for frame.php)
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    
                    let width = img.naturalWidth;
                    let height = img.naturalHeight;
                    const maxDim = 1200;
                    
                    if (width > maxDim || height > maxDim) {
                        if (width > height) {
                            height = Math.round((height / width) * maxDim);
                            width = maxDim;
                        } else {
                            width = Math.round((width / height) * maxDim);
                            height = maxDim;
                        }
                    }
                    
                    canvas.width = width;
                    canvas.height = height;
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    const compressedBase64 = canvas.toDataURL('image/jpeg', 0.85);
                    if (croppedInput) croppedInput.value = compressedBase64;
                    showPreview(compressedBase64);
                };
                img.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    }

    // Update overlay frame when selecting a new template
    const frameRadios = document.querySelectorAll('input[name="frame_template"]');
    frameRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if (editorFrameImage && e.target.checked) {
                editorFrameImage.src = 'templates/' + e.target.value;
            }
        });
    });

    if (imagePreviewContainer) {
        // Removed clicking preview to open visual editor
        imagePreviewContainer.addEventListener('click', (e) => {
            if(e.target.id === 'remove-image' || e.target.closest('#remove-image')) return;
        });
    }

    function closeCropper() {
        if (cropperModal) {
            cropperModal.classList.add('hidden');
            cropperModal.classList.remove('flex');
        }
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (!croppedInput || !croppedInput.value) {
            if(fileInput) fileInput.value = '';
        }
    }

    if (closeCropperBtn) closeCropperBtn.addEventListener('click', closeCropper);
    if (cancelCropBtn) cancelCropBtn.addEventListener('click', closeCropper);

    if (applyCropBtn) {
        applyCropBtn.addEventListener('click', () => {
            if (!cropper) return;
            const canvas = cropper.getCroppedCanvas({ width: 800, height: 800 });
            const base64Image = canvas.toDataURL('image/jpeg', 0.9);
            
            if (croppedInput) croppedInput.value = base64Image;
            showPreview(base64Image);
            closeCropper();
        });
    }

    function showPreview(src) {
        if (!imagePreview) return;
        imagePreview.src = src;
        if(uploadContent) uploadContent.classList.add('hidden');
        if(imagePreviewContainer) imagePreviewContainer.classList.remove('hidden');
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if(fileInput) fileInput.value = '';
            if(croppedInput) croppedInput.value = '';
            if(imagePreview) imagePreview.src = '';
            if(uploadContent) uploadContent.classList.remove('hidden');
            if(imagePreviewContainer) imagePreviewContainer.classList.add('hidden');
        });
    }

    // Handle form submission loading overlay
    const wishForm = document.getElementById('wishForm');
    const frameForm = document.getElementById('frameForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    function showLoading() {
        if(loadingOverlay) {
            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');
        }
    }

    if (wishForm) wishForm.addEventListener('submit', showLoading);
    if (frameForm) frameForm.addEventListener('submit', showLoading);
});

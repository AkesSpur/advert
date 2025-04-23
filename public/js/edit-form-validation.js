document.addEventListener("DOMContentLoaded", function () {
    const profileForm = document.getElementById("profileForm");
    const photoInputs = document.querySelectorAll(".photo-input");
    const videoInput = document.querySelector(".video-input");
    const photoLabels = document.querySelectorAll(".photo-label");
    const videoLabel = document.querySelector(".video-label");
    
    // Initialize pricing checkboxes based on input values
    function initializePricingCheckboxes() {
        const pricingCheckboxes = document.querySelectorAll(".pricing-checkbox");
        
        pricingCheckboxes.forEach((checkbox, index) => {
            // Get all price inputs for this pricing option
            const priceInputs = document.querySelectorAll(`.pricing-input[data-index="${index}"]`);
            if (!priceInputs.length) {
                // If data-index is not set, try to find inputs by context
                const container = checkbox.closest('div').parentElement;
                if (container) {
                    const inputs = container.querySelectorAll('input[type="text"]');
                    // Check if any input has a value
                    inputs.forEach(input => {
                        if (input.value && parseInt(input.value) > 0) {
                            checkbox.checked = true;
                        }
                        
                        // Add event listener to ensure checkbox is checked when price is entered
                        input.addEventListener('input', function() {
                            if (this.value && parseInt(this.value) > 0) {
                                checkbox.checked = true;
                            }
                        });
                    });
                }
            } else {
                // Check if any input has a value
                let hasValue = false;
                priceInputs.forEach(input => {
                    if (input.value && parseInt(input.value) > 0) {
                        hasValue = true;
                    }
                    
                    // Add event listener to ensure checkbox is checked when price is entered
                    input.addEventListener('input', function() {
                        if (this.value && parseInt(this.value) > 0) {
                            checkbox.checked = true;
                        }
                    });
                });
                
                // If any input has a value, check the checkbox
                if (hasValue) {
                    checkbox.checked = true;
                }
            }
        });
    }

    // Setup delete photo functionality
    function setupPhotoDeleteButtons() {
        const deletePhotoLabels = document.querySelectorAll(
            'input[name="delete_photos[]"]'
        );
        if (deletePhotoLabels && deletePhotoLabels.length > 0) {
            deletePhotoLabels.forEach((checkbox) => {
                const label = checkbox.closest("label");
                if (label) {
                    // Remove existing event listeners to prevent duplicates
                    const newLabel = label.cloneNode(true);
                    label.parentNode.replaceChild(newLabel, label);

                    // Get the new checkbox reference after cloning
                    const newCheckbox = newLabel.querySelector(
                        'input[type="checkbox"]'
                    );

                    // Add direct click handler to the label
                    newLabel.addEventListener("click", function (e) {
                        // Toggle the checkbox when the label is clicked
                        newCheckbox.checked = !newCheckbox.checked;

                        // Visual feedback
                        if (newCheckbox.checked) {
                            newLabel.classList.add("bg-red-700");
                            // Add visual indication to the parent container
                            const photoContainer = newLabel.closest(
                                ".photo-upload-container"
                            );
                            if (photoContainer) {
                                photoContainer.classList.add("opacity-50");
                                
                                // Show upload option when image is marked for deletion
                                const img = photoContainer.querySelector("img");
                                const uploadLabel = document.createElement("label");
                                uploadLabel.className = "cursor-pointer absolute inset-0 flex items-center justify-center text-center text-sm text-white bg-black bg-opacity-50 rounded-xl photo-label";
                                uploadLabel.innerHTML = `
                                    <div class="photo-placeholder">
                                        <div class="text-3xl mb-1">+</div>
                                        Добавить фото
                                    </div>
                                    <input type="file" name="photos[]" accept="image/*" class="hidden photo-input">
                                `;
                                
                                // Add the upload label to the container
                                const imgContainer = img.parentNode;
                                imgContainer.appendChild(uploadLabel);
                                
                                // Setup the new file input
                                const newInput = uploadLabel.querySelector(".photo-input");
                                if (newInput) {
                                    newInput.addEventListener("change", function(e) {
                                        const file = e.target.files[0];
                                        if (!file) return;
                                        
                                        // Validate file type
                                        const validImageTypes = [
                                            "image/jpeg",
                                            "image/png",
                                            "image/gif",
                                            "image/webp",
                                        ];
                                        if (!validImageTypes.includes(file.type)) {
                                            toastr.error(
                                                "Пожалуйста, загрузите изображение в формате JPEG, PNG, GIF или WEBP"
                                            );
                                            newInput.value = "";
                                            return;
                                        }
                                        
                                        // Validate file size (max 5MB)
                                        if (file.size > 5 * 1024 * 1024) {
                                            toastr.error("Размер изображения не должен превышать 5MB");
                                            newInput.value = "";
                                            return;
                                        }
                                        
                                        // Create object URL for preview
                                        const objectUrl = URL.createObjectURL(file);
                                        img.src = objectUrl;
                                        
                                        // Unmark the image for deletion
                                        newCheckbox.checked = false;
                                        newLabel.classList.remove("bg-red-700");
                                        photoContainer.classList.remove("opacity-50");
                                        
                                        // Remove the upload label
                                        uploadLabel.remove();
                                    });
                                }
                            }
                        } else {
                            newLabel.classList.remove("bg-red-700");
                            // Remove visual indication from the parent container
                            const photoContainer = newLabel.closest(
                                ".photo-upload-container"
                            );
                            if (photoContainer) {
                                photoContainer.classList.remove("opacity-50");
                                
                                // Remove the upload label if it exists
                                const uploadLabel = photoContainer.querySelector(".photo-label");
                                if (uploadLabel) {
                                    uploadLabel.remove();
                                }
                            }
                        }

                        e.preventDefault(); // Prevent default label behavior
                        e.stopPropagation(); // Stop event propagation
                    });

                    // Also add click event to the X button inside the label
                    const deleteButton = newLabel.querySelector("button");
                    if (deleteButton) {
                        deleteButton.addEventListener("click", function (e) {
                            // Toggle the checkbox
                            newCheckbox.checked = !newCheckbox.checked;

                            // Visual feedback
                            if (newCheckbox.checked) {
                                newLabel.classList.add("bg-red-700");
                                // Add visual indication to the parent container
                                const photoContainer = newLabel.closest(
                                    ".photo-upload-container"
                                );
                                if (photoContainer) {
                                    photoContainer.classList.add("opacity-50");
                                    
                                    // Show upload option when image is marked for deletion
                                    const img = photoContainer.querySelector("img");
                                    const uploadLabel = document.createElement("label");
                                    uploadLabel.className = "cursor-pointer absolute inset-0 flex items-center justify-center text-center text-sm text-white bg-black bg-opacity-50 rounded-xl photo-label";
                                    uploadLabel.innerHTML = `
                                        <div class="photo-placeholder">
                                            <div class="text-3xl mb-1">+</div>
                                            Добавить фото
                                        </div>
                                        <input type="file" name="photos[]" accept="image/*" class="hidden photo-input">
                                    `;
                                    
                                    // Add the upload label to the container
                                    const imgContainer = img.parentNode;
                                    imgContainer.appendChild(uploadLabel);
                                    
                                    // Setup the new file input
                                    const newInput = uploadLabel.querySelector(".photo-input");
                                    if (newInput) {
                                        newInput.addEventListener("change", function(e) {
                                            const file = e.target.files[0];
                                            if (!file) return;
                                            
                                            // Validate file type
                                            const validImageTypes = [
                                                "image/jpeg",
                                                "image/png",
                                                "image/gif",
                                                "image/webp",
                                            ];
                                            if (!validImageTypes.includes(file.type)) {
                                                toastr.error(
                                                    "Пожалуйста, загрузите изображение в формате JPEG, PNG, GIF или WEBP"
                                                );
                                                newInput.value = "";
                                                return;
                                            }
                                            
                                            // Validate file size (max 5MB)
                                            if (file.size > 5 * 1024 * 1024) {
                                                toastr.error("Размер изображения не должен превышать 5MB");
                                                newInput.value = "";
                                                return;
                                            }
                                            
                                            // Create object URL for preview
                                            const objectUrl = URL.createObjectURL(file);
                                            img.src = objectUrl;
                                            
                                            // Unmark the image for deletion
                                            newCheckbox.checked = false;
                                            newLabel.classList.remove("bg-red-700");
                                            photoContainer.classList.remove("opacity-50");
                                            
                                            // Remove the upload label
                                            uploadLabel.remove();
                                        });
                                    }
                                }
                            } else {
                                newLabel.classList.remove("bg-red-700");
                                // Remove visual indication from the parent container
                                const photoContainer = newLabel.closest(
                                    ".photo-upload-container"
                                );
                                if (photoContainer) {
                                    photoContainer.classList.remove(
                                        "opacity-50"
                                    );
                                    
                                    // Remove the upload label if it exists
                                    const uploadLabel = photoContainer.querySelector(".photo-label");
                                    if (uploadLabel) {
                                        uploadLabel.remove();
                                    }
                                }
                            }

                            e.stopPropagation(); // Prevent event from bubbling to label
                            e.preventDefault(); // Prevent default behavior
                        });
                    }
                }
            });
        }
    }

    // Call the setup functions immediately
    setupPhotoDeleteButtons();
    initializePricingCheckboxes();
    initializePaidServices();
    setupPricingInputs();

    // Setup delete video functionality
    const deleteVideoCheckbox = document.querySelector(
        'input[name="delete_video"]'
    );
    if (deleteVideoCheckbox) {
        const label = deleteVideoCheckbox.closest("label");
        if (label) {
            // Add click event to the label itself
            label.addEventListener("click", function (e) {
                // Toggle the checkbox when the label is clicked
                deleteVideoCheckbox.checked = !deleteVideoCheckbox.checked;

                // Visual feedback
                if (deleteVideoCheckbox.checked) {
                    label.classList.add("bg-red-700");
                } else {
                    label.classList.remove("bg-red-700");
                }

                e.preventDefault(); // Prevent default label behavior
            });

            // Also add click event to the X span inside the label
            const deleteSpan = label.querySelector("span");
            if (deleteSpan) {
                deleteSpan.addEventListener("click", function (e) {
                    // Toggle the checkbox
                    deleteVideoCheckbox.checked = !deleteVideoCheckbox.checked;

                    // Visual feedback
                    if (deleteVideoCheckbox.checked) {
                        label.classList.add("bg-red-700");
                    } else {
                        label.classList.remove("bg-red-700");
                    }

                    e.stopPropagation(); // Prevent event from bubbling to label
                });
            }
        }
    }

    const toastr = {
        error: function (message) {
            const toast = document.createElement("div");
            toast.className =
                "fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50 transform transition-all duration-500 ease-in-out";
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.add("opacity-0");
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 500);
            }, 3000);
        },
        success: function (message) {
            const toast = document.createElement("div");
            toast.className =
                "fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50 transform transition-all duration-500 ease-in-out";
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.add("opacity-0");
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 500);
            }, 3000);
        },
    };

    // Sync contact checkboxes with inputs
    function setupContactFieldSync(checkboxId, inputId) {
        const checkbox = document.getElementById(checkboxId);
        const input = document.getElementById(inputId);

        if (checkbox && input) {
            // Initial state - if input has value, check the checkbox
            if (input.value.trim()) {
                checkbox.checked = true;
            }

            // When input gets focus or value changes, ensure checkbox is checked
            input.addEventListener("focus", () => {
                checkbox.checked = true;
            });

            input.addEventListener("input", () => {
                if (input.value.trim()) {
                    checkbox.checked = true;
                }
            });

            // When checkbox is unchecked, clear the input
            checkbox.addEventListener("change", () => {
                if (!checkbox.checked) {
                    input.value = "";
                }
            });
        }
    }

    // Setup sync for all contact fields
    setupContactFieldSync("has_telegram", "telegram");
    setupContactFieldSync("has_viber", "viber");
    setupContactFieldSync("has_whatsapp", "whatsapp");

    // Initialize paid services checkboxes and price inputs
    function initializePaidServices() {
        const paidServiceCheckboxes = document.querySelectorAll(
            'input[name="paid_services[]"]'
        );
        
        paidServiceCheckboxes.forEach((checkbox) => {
            const serviceId = checkbox.value;
            const priceInput = document.querySelector(
                `input[name="paid_service_prices[${serviceId}]"]`
            );
            
            // If price input has a value, ensure checkbox is checked
            if (priceInput && priceInput.value && parseInt(priceInput.value) > 0) {
                checkbox.checked = true;
            }
            
            // Add event listener to ensure checkbox is checked when price is entered
            if (priceInput) {
                priceInput.addEventListener('input', function() {
                    if (this.value && parseInt(this.value) > 0) {
                        checkbox.checked = true;
                    }
                });
            }
        });
    }
    
    // Set data-index attributes for pricing inputs
    function setupPricingInputs() {
        // Set data-index for Выезд inputs
        const vyezdInputs = [
            document.querySelector('input[name="vyezd_1hour"]'),
            document.querySelector('input[name="vyezd_2hours"]'),
            document.querySelector('input[name="vyezd_night"]')
        ];
        
        vyezdInputs.forEach(input => {
            if (input) input.setAttribute('data-index', '0');
        });
        
        // Set data-index for Апартаменты inputs
        const appartamentiInputs = [
            document.querySelector('input[name="appartamenti_1hour"]'),
            document.querySelector('input[name="appartamenti_2hours"]'),
            document.querySelector('input[name="appartamenti_night"]')
        ];
        
        appartamentiInputs.forEach(input => {
            if (input) input.setAttribute('data-index', '1');
        });
    }
    
    // Add click handlers to photo labels to trigger file input
    if (photoLabels && photoLabels.length > 0) {
        photoLabels.forEach((label, index) => {
            const placeholder = label.querySelector(".photo-placeholder");
            if (placeholder) {
                placeholder.addEventListener("click", function (e) {
                    const input = label.querySelector(".photo-input");
                    if (input) {
                        input.click();
                    }
                    e.stopPropagation();
                });
            }
        });
    }

    // Add click handler to video label
    if (videoLabel) {
        const placeholder = videoLabel.querySelector(".video-placeholder");
        if (placeholder) {
            placeholder.addEventListener("click", function (e) {
                const input = videoLabel.querySelector(".video-input");
                if (input) {
                    input.click();
                }
                e.stopPropagation();
            });
        }
    }

    // Form validation before submit
    if (profileForm) {
        profileForm.addEventListener("submit", function (e) {
            let isValid = true;
            let errorMessage = "";

            // Validate hair color selection
            const hairColorInputs = document.querySelectorAll(
                'input[name="hair_color"]'
            );
            let hairColorSelected = false;
            hairColorInputs.forEach((input) => {
                if (input.checked) {
                    hairColorSelected = true;
                }
            });

            if (!hairColorSelected) {
                isValid = false;
                errorMessage += "Пожалуйста, выберите цвет волос.\n";
            }

            // Validate payment methods
            const paymentMethods = [
                document.querySelector('input[name="payment_wmz"]'),
                document.querySelector('input[name="payment_card"]'),
                document.querySelector('input[name="payment_sbp"]'),
            ];

            let paymentSelected = false;
            paymentMethods.forEach((method) => {
                if (method && method.checked) {
                    paymentSelected = true;
                }
            });

            if (!paymentSelected) {
                isValid = false;
                errorMessage +=
                    "Пожалуйста, выберите хотя бы один способ оплаты.\n";
            }

            // Validate pricing
            const pricingCheckboxes =
                document.querySelectorAll(".pricing-checkbox");
            let pricingSelected = false;

            pricingCheckboxes.forEach((checkbox, index) => {
                if (checkbox.checked) {
                    pricingSelected = true;

                    // If pricing option is selected, at least one price input should have a value
                    const priceInputs = document.querySelectorAll(
                        `.pricing-input[data-index="${index}"]`
                    );
                    let hasPriceValue = false;
                    
                    // If data-index is not set, try to find inputs by context
                    if (!priceInputs.length) {
                        const container = checkbox.closest('div').parentElement;
                        if (container) {
                            const inputs = container.querySelectorAll('input[type="text"]');
                            inputs.forEach(input => {
                                if (input.value && parseInt(input.value) > 0) {
                                    hasPriceValue = true;
                                }
                            });
                        }
                    } else {
                        priceInputs.forEach((input) => {
                            if (input.value && parseInt(input.value) > 0) {
                                hasPriceValue = true;
                            }
                        });
                    }

                    if (!hasPriceValue) {
                        isValid = false;
                        errorMessage += `Пожалуйста, укажите стоимость для выбранного варианта: ${
                            index === 0 ? "Выезд" : "Апартаменты"
                        }.\n`;
                    }
                }
            });

            if (!pricingSelected) {
                isValid = false;
                errorMessage +=
                    "Пожалуйста, выберите хотя бы один вариант стоимости (Выезд или Апартаменты).\n";
            }

            // Validate paid services - ensure each checked service has a price
            const paidServiceCheckboxes = document.querySelectorAll(
                'input[name="paid_services[]"]'
            );
            
            // Validate at least one paid service is selected
            const hasPaidService = Array.from(paidServiceCheckboxes).some(
                (checkbox) => checkbox.checked
            );
            if (!hasPaidService) {
                isValid = false;
                errorMessage += "Выберите хотя бы одну платную услугу\n";
            }
            
            // Check if any checked service has a price
            let hasServiceWithPrice = false;
            
            paidServiceCheckboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    const serviceId = checkbox.value;
                    const priceInput = document.querySelector(
                        `input[name="paid_service_prices[${serviceId}]"]`
                    );

                    if (priceInput && priceInput.value && parseInt(priceInput.value) > 0) {
                        hasServiceWithPrice = true;
                    } else if (priceInput) {
                        isValid = false;
                        // Get the service name from the label
                        const serviceName = checkbox
                            .closest("label")
                            .textContent.trim();
                        errorMessage += `Пожалуйста, укажите цену для платной услуги: ${serviceName}\n`;
                    }
                }
            });
            
            // If services are selected but none have prices
            if (hasPaidService && !hasServiceWithPrice) {
                isValid = false;
                errorMessage += "Пожалуйста, укажите стоимость для выбранных услуг\n";
            }

            // Validate neighborhoods selection
            const neighborhoodCheckboxes = document.querySelectorAll(
                'input[name="neighborhoods[]"]'
            );
            const hasNeighborhood = Array.from(neighborhoodCheckboxes).some(
                (checkbox) => checkbox.checked
            );
            if (!hasNeighborhood) {
                isValid = false;
                errorMessage += "Выберите хотя бы один район\n";
            }
            
            // Validate metro stations selection
            const metroCheckboxes = document.querySelectorAll(
                'input[name="metro_stations[]"]'
            );
            const hasMetro = Array.from(metroCheckboxes).some(
                (checkbox) => checkbox.checked
            );
            if (!hasMetro) {
                isValid = false;
                errorMessage += "Выберите хотя бы одну станцию метро\n";
            }

            // Validate services selection
            const serviceCheckboxes =
                document.querySelectorAll(".service-checkbox, input[name='services[]']");
            const hasService = Array.from(serviceCheckboxes).some(
                (checkbox) => checkbox.checked
            );
            if (!hasService) {
                isValid = false;
                errorMessage += "Выберите хотя бы одну услугу\n";
            }

            // For edit form, we don't require photos if there are existing photos
            // Check if there are existing photos (look for images that are not marked for deletion)
            const existingPhotos = document.querySelectorAll('.photo-upload-container img');
            const deletePhotoCheckboxes = document.querySelectorAll('input[name="delete_photos[]"]');
            
            // Count photos that are not marked for deletion
            let nonDeletedPhotoCount = 0;
            if (existingPhotos.length > 0) {
                // For each existing photo, check if it's marked for deletion
                existingPhotos.forEach((photo, index) => {
                    if (index < deletePhotoCheckboxes.length) {
                        if (!deletePhotoCheckboxes[index].checked) {
                            nonDeletedPhotoCount++;
                        }
                    } else {
                        nonDeletedPhotoCount++;
                    }
                });
            }
            
            // Check if any new photos are being uploaded
            const newPhotoUploads = Array.from(photoInputs).some(
                (input) => input.files && input.files.length > 0
            );
            
            // If no existing photos (or all marked for deletion) and no new uploads
            if (nonDeletedPhotoCount === 0 && !newPhotoUploads) {
                isValid = false;
                errorMessage += "Требуется загрузить хотя бы одно фото\n";
            }

            if (!isValid) {
                e.preventDefault();
                toastr.error(errorMessage);
                return false;
            }
        });
    }

    // Photo preview functionality
    photoInputs.forEach((input) => {
        input.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file type
            const validImageTypes = [
                "image/jpeg",
                "image/png",
                "image/gif",
                "image/webp",
            ];
            if (!validImageTypes.includes(file.type)) {
                toastr.error(
                    "Пожалуйста, загрузите изображение в формате JPEG, PNG, GIF или WEBP"
                );
                input.value = "";
                return;
            }

            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                toastr.error("Размер изображения не должен превышать 5MB");
                input.value = "";
                return;
            }

            const container = this.closest(".photo-upload-container");
            const placeholder = container.querySelector(".photo-placeholder");
            const preview = container.querySelector(".photo-preview");
            const previewImg = preview.querySelector("img");

            // Create object URL for preview
            const objectUrl = URL.createObjectURL(file);
            previewImg.src = objectUrl;

            // Show preview, hide placeholder
            placeholder.classList.add("hidden");
            preview.classList.remove("hidden");

            // Setup remove button
            const removeBtn = preview.querySelector(".remove-photo");
            removeBtn.addEventListener("click", function (event) {
                event.preventDefault();
                // Clear the file input
                input.value = "";
                // Hide preview, show placeholder
                preview.classList.add("hidden");
                placeholder.classList.remove("hidden");
                // Release object URL
                URL.revokeObjectURL(objectUrl);
            });
        });
    });

    // Video preview functionality
    if (videoInput) {
        videoInput.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file type
            const validVideoTypes = ["video/mp4", "video/webm", "video/ogg"];
            if (!validVideoTypes.includes(file.type)) {
                toastr.error(
                    "Пожалуйста, загрузите видео в формате MP4, WEBM или OGG"
                );
                videoInput.value = "";
                return;
            }

            // Validate file size (max 50MB)
            if (file.size > 50 * 1024 * 1024) {
                toastr.error("Размер видео не должен превышать 50MB");
                videoInput.value = "";
                return;
            }

            const container = this.closest(".video-upload-container");
            const placeholder = container.querySelector(".video-placeholder");
            const preview = container.querySelector(".video-preview");
            const previewVideo = preview.querySelector("video");

            // Create object URL for preview
            const objectUrl = URL.createObjectURL(file);
            previewVideo.src = objectUrl;

            // Show preview, hide placeholder
            placeholder.classList.add("hidden");
            preview.classList.remove("hidden");

            // Setup remove button
            const removeBtn = preview.querySelector(".remove-video");
            removeBtn.addEventListener("click", function (event) {
                event.preventDefault();
                // Clear the file input
                videoInput.value = "";
                // Hide preview, show placeholder
                preview.classList.add("hidden");
                placeholder.classList.remove("hidden");
                // Release object URL
                URL.revokeObjectURL(objectUrl);
            });
        });
    }

    // Setup pricing checkbox and input sync
    const pricingCheckboxes = document.querySelectorAll(".pricing-checkbox");
    pricingCheckboxes.forEach((checkbox, index) => {
        const relatedInputs = document.querySelectorAll(
            `.pricing-input[data-index="${index}"]`
        );

        // Check if any input has a value, if so, check the checkbox
        let hasValue = false;
        relatedInputs.forEach((input) => {
            if (input.value && parseInt(input.value) > 0) {
                hasValue = true;
            }
        });
        
        if (hasValue) {
            checkbox.checked = true;
        }

        // When any input gets focus or value changes, ensure checkbox is checked
        relatedInputs.forEach((input) => {
            input.addEventListener("focus", () => {
                checkbox.checked = true;
            });

            input.addEventListener("input", () => {
                if (input.value.trim()) {
                    checkbox.checked = true;
                } else {
                    // Check if all inputs are empty, if so, uncheck the checkbox
                    const allEmpty = Array.from(relatedInputs).every(
                        (inp) => !inp.value.trim()
                    );
                    if (allEmpty) {
                        checkbox.checked = false;
                    }
                }
            });
        });

        // When checkbox is unchecked, clear all related inputs
        checkbox.addEventListener("change", () => {
            if (!checkbox.checked) {
                relatedInputs.forEach((input) => {
                    input.value = "";
                });
            }
        });
    });

    // Setup paid service price inputs
    const paidServiceCheckboxes = document.querySelectorAll('input[name="paid_services[]"]');
    
    // Check if any paid service has a price value
    let hasPaidServiceWithPrice = false;
    
    paidServiceCheckboxes.forEach((checkbox) => {
        const serviceId = checkbox.value;
        const priceInput = document.querySelector(
            `input[name="paid_service_prices[${serviceId}]"]`
        );
        
        // If price input has value, ensure checkbox is checked
        if (priceInput && priceInput.value && parseInt(priceInput.value) > 0) {
            checkbox.checked = true;
            hasPaidServiceWithPrice = true;
        }
        
        // When price input gets focus, ensure checkbox is checked
        if (priceInput) {
            priceInput.addEventListener("focus", () => {
                checkbox.checked = true;
            });
            
            priceInput.addEventListener("input", () => {
                if (priceInput.value.trim() && parseInt(priceInput.value) > 0) {
                    checkbox.checked = true;
                }
            });
            
            // When checkbox is unchecked, clear price input
            checkbox.addEventListener("change", () => {
                if (!checkbox.checked) {
                    priceInput.value = "";
                }
            });
        }
    });
    
    // Validate paid services on page load
    if (!hasPaidServiceWithPrice) {
        // Check if we need to show an initial warning
        const pricingCheckboxes = document.querySelectorAll(".pricing-checkbox");
        let pricingSelected = false;
        
        pricingCheckboxes.forEach((checkbox, index) => {
            if (checkbox.checked) {
                pricingSelected = true;
                
                // Check if any price input has a value
                const priceInputs = document.querySelectorAll(
                    `.pricing-input[data-index="${index}"]`
                );
                let hasPriceValue = false;
                
                priceInputs.forEach((input) => {
                    if (input.value && parseInt(input.value) > 0) {
                        hasPriceValue = true;
                    }
                });
                
                if (!hasPriceValue) {
                    console.warn(`Необходимо указать стоимость для выбранного варианта: ${index === 0 ? "Выезд" : "Апартаменты"}.`);
                }
            }
        });
    }
});
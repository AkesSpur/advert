document.addEventListener("DOMContentLoaded", function () {
    const profileForm = document.getElementById("profileForm");
    const photoInputs = document.querySelectorAll(".photo-input");
    const videoInput = document.querySelector(".video-input");
    const photoLabels = document.querySelectorAll(".photo-label");
    const videoLabel = document.querySelector(".video-label");

    // Setup delete photo functionality
    function setupPhotoDeleteButtons() {
        const deletePhotoLabels = document.querySelectorAll(
            'input[name="delete_photos[]"]'
        );
        if (deletePhotoLabels && deletePhotoLabels.length > 0) {
            deletePhotoLabels.forEach((checkbox) => {
                const label = checkbox.closest("label");
                if (label) {
                    // Make sure the checkbox is initially unchecked
                    checkbox.checked = false;
                    label.classList.remove("bg-red-700");

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
                            const photoContainer = label.closest(
                                ".photo-upload-container"
                            );
                            if (photoContainer) {
                                photoContainer.classList.add("opacity-50");
                            }
                        } else {
                            newLabel.classList.remove("bg-red-700");
                            // Remove visual indication from the parent container
                            const photoContainer = label.closest(
                                ".photo-upload-container"
                            );
                            if (photoContainer) {
                                photoContainer.classList.remove("opacity-50");
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
                                const photoContainer = label.closest(
                                    ".photo-upload-container"
                                );
                                if (photoContainer) {
                                    photoContainer.classList.add("opacity-50");
                                }
                            } else {
                                newLabel.classList.remove("bg-red-700");
                                // Remove visual indication from the parent container
                                const photoContainer = label.closest(
                                    ".photo-upload-container"
                                );
                                if (photoContainer) {
                                    photoContainer.classList.remove(
                                        "opacity-50"
                                    );
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

    // Call the setup function immediately
    setupPhotoDeleteButtons();

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

    // Initialize Alpine.js data for districts and metro stations if not already done
    if (window.Alpine) {
        const locationSection = document.querySelector("[x-data]");
        if (locationSection) {
            const alpineData = Alpine.store("locationData") || {};

            // Make sure the init function properly sets up names instead of IDs
            if (typeof Alpine.initializeComponent === "function") {
                const originalInit = Alpine.initializeComponent;
                Alpine.initializeComponent = function (component) {
                    originalInit(component);

                    // If this is our location component
                    if (
                        component.el &&
                        component.el.hasAttribute("x-data") &&
                        component.el
                            .getAttribute("x-data")
                            .includes("selectedDistrictIds")
                    ) {
                        // Make sure init properly sets names
                        if (component.$data.init) {
                            const originalDataInit = component.$data.init;
                            component.$data.init = function () {
                                originalDataInit.call(this);

                                // Ensure we're using names for display
                                this.selectedDistricts =
                                    this.selectedDistrictIds.map(
                                        (id) => this.allDistricts[id]
                                    );
                                this.selectedStations =
                                    this.selectedStationIds.map(
                                        (id) => this.allStations[id]
                                    );
                            };
                        }
                    }
                };
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

    // Add click handlers to photo labels to trigger file input
    if (photoLabels && photoLabels.length > 0) {
        photoLabels.forEach((label, index) => {
            // Make the entire label clickable, not just the placeholder
            label.addEventListener("click", function (e) {
                // Only trigger the file input click if the event target is the label itself or the placeholder
                // This prevents double-opening of the file dialog
                if (e.target === this || e.target.closest('.photo-placeholder')) {
                    const input = this.querySelector(".photo-input");
                    if (input) {
                        input.click();
                    }
                }
                e.stopPropagation();
            });
        });
    }

    // Add click handler to video label
    if (videoLabel) {
        videoLabel.addEventListener("click", function (e) {
            // Only trigger the file input click if the event target is the label itself or the placeholder
            // This prevents double-opening of the file dialog
            if (e.target === this || e.target.closest('.video-placeholder')) {
                const input = this.querySelector(".video-input");
                if (input) {
                    input.click();
                }
            }
            e.stopPropagation();
        });
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

                    priceInputs.forEach((input) => {
                        if (input.value && input.value > 0) {
                            hasPriceValue = true;
                        }
                    });

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
            paidServiceCheckboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    const serviceId = checkbox.value;
                    const priceInput = document.querySelector(
                        `input[name="paid_service_prices[${serviceId}]"]`
                    );

                    if (
                        priceInput &&
                        (!priceInput.value || priceInput.value <= 0)
                    ) {
                        isValid = false;
                        // Get the service name from the label
                        const serviceName = checkbox
                            .closest("label")
                            .textContent.trim();
                        errorMessage += `Пожалуйста, укажите цену для платной услуги: ${serviceName}\n`;
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
                toastr.error(errorMessage);
                return false;
            }
        });
    }

  // Handle multiple photo selection and distribution across available labels
  photoInputs.forEach((input) => {
    input.addEventListener("change", function (e) {
        if (this.files && this.files.length > 0) {
            // If multiple files are selected, distribute them across available empty photo containers
            if (this.files.length > 1) {
                // Get all empty photo containers
                const emptyPhotoLabels = Array.from(photoLabels).filter(label => {
                    // Check if this label doesn't have an image preview yet
                    const container = label.closest('.photo-upload-container');
                    if (!container) return false;
                    
                    // Check if there's no image or the image is the default placeholder
                    const img = container.querySelector('img');
                    return !img || img.src.includes('placeholder');
                });
                
                // Distribute files across empty containers
                for (let i = 0; i < Math.min(this.files.length, emptyPhotoLabels.length); i++) {
                    const file = this.files[i];
                    const label = emptyPhotoLabels[i];
                    
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
                        continue;
                    }
                    
                    // Validate file size (max 5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        toastr.error("Размер изображения не должен превышать 5MB");
                        continue;
                    }
                    
                    // Create a new file list with just this file
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    
                    // Find the input in this label and set its files
                    const labelInput = label.querySelector('.photo-input');
                    if (labelInput) {
                        labelInput.files = dataTransfer.files;
                        
                        // Trigger change event to update preview
                        const event = new Event('change', { bubbles: true });
                        labelInput.dispatchEvent(event);
                    }
                }
                
                // Clear the original input if we distributed files
                if (this.files.length > 0 && emptyPhotoLabels.length > 0) {
                    this.value = '';
                }
                return;
            }
            
            // Single file handling
            const file = this.files[0];
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
                this.value = "";
                return;
            }

            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                toastr.error("Размер изображения не должен превышать 5MB");
                this.value = "";
                return;
            }

            const container = this.closest(".photo-upload-container");
            if (container) {
                const placeholder = container.querySelector(".photo-placeholder");
                const preview = container.querySelector(".photo-preview");
                const img = container.querySelector("img");

                // Create object URL for preview
                const objectUrl = URL.createObjectURL(file);
                if (img) {
                    img.src = objectUrl;
                }

                // Show preview, hide placeholder if they exist
                if (placeholder) {
                    placeholder.classList.add("hidden");
                }
                if (preview) {
                    preview.classList.remove("hidden");
                }
            }
        }
    });
});


    // Photo preview functionality
    photoInputs.forEach((input) => {
        // Add multiple attribute to ensure multiple file selection works
        input.setAttribute("multiple", "multiple");
        
        input.addEventListener("change", function (e) {
            // Stop event propagation to prevent reopening file explorer
            e.stopPropagation();
            e.preventDefault();
            
            // Get all selected files
            const files = Array.from(e.target.files);
            if (!files.length) return;
            
            // Validate all files first
            const validImageTypes = [
                "image/jpeg",
                "image/png",
                "image/gif",
                "image/webp",
            ];
            
            // Check if any file is invalid
            const invalidFile = files.find(file => !validImageTypes.includes(file.type));
            if (invalidFile) {
                toastr.error(
                    "Пожалуйста, загрузите изображение в формате JPEG, PNG, GIF или WEBP"
                );
                input.value = "";
                return;
            }
            
            // Check if any file is too large
            const largeFile = files.find(file => file.size > 5 * 1024 * 1024);
            if (largeFile) {
                toastr.error("Размер изображения не должен превышать 5MB");
                input.value = "";
                return;
            }
            
            // Get the current container
            const currentContainer = this.closest(".photo-upload-container");
            
            // Process the first file in the current container
            const firstFile = files[0];
            processFileInContainer(firstFile, currentContainer, input);
            
            // If there are more files, find empty containers and process them
            if (files.length > 1) {
                // Get all empty containers (those with visible placeholders)
                const allContainers = document.querySelectorAll(".photo-upload-container");
                const emptyContainers = Array.from(allContainers).filter(container => {
                    // Container is empty if placeholder is visible
                    const placeholder = container.querySelector(".photo-placeholder");
                    return placeholder && !placeholder.classList.contains("hidden");
                });
                
                // Process remaining files in empty containers
                for (let i = 1; i < files.length && i-1 < emptyContainers.length; i++) {
                    const container = emptyContainers[i - 1];
                    const containerInput = container.querySelector(".photo-input");
                    
                    // Just use the file directly without setting containerInput.files
                    processFileInContainer(files[i], container, containerInput);
                    
                }
            }
        });
    });
    
    // Helper function to process a file in a container
    function processFileInContainer(file, container, input) {
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
        }
    

    // Initialize the remove buttons for existing photos
    document.querySelectorAll('.remove-photo').forEach(button => {
        // Remove existing event listeners to prevent duplicates
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);
        
        newButton.addEventListener('click', function(event) {
            event.preventDefault();
            const preview = this.closest('.photo-preview');
            const container = preview.closest('.photo-upload-container');
            const placeholder = container.querySelector('.photo-placeholder');
            const input = container.querySelector('.photo-input');
            
            // Clear the file input
            if (input) input.value = '';
            
            // Hide preview, show placeholder
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
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

    // Form validation
    if (profileForm) {
        profileForm.addEventListener("submit", function (e) {
            let isValid = true;
            let errorMessages = [];

            // Validate at least one photo is uploaded
            const hasPhoto = Array.from(photoInputs).some(
                (input) => input.files.length > 0
            );
            if (!hasPhoto) {
                isValid = false;
                errorMessages.push("Требуется загрузить хотя бы одно фото");
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
                errorMessages.push("Выберите хотя бы один район");
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
                errorMessages.push("Выберите хотя бы одну станцию метро");
            }

            // Validate services selection
            const serviceCheckboxes =
                document.querySelectorAll(".service-checkbox");
            const hasService = Array.from(serviceCheckboxes).some(
                (checkbox) => checkbox.checked
            );
            if (!hasService) {
                isValid = false;
                errorMessages.push("Выберите хотя бы одну услугу");
            }

            // Validate pricing inputs
            const pricingCheckboxes =
                document.querySelectorAll(".pricing-checkbox");

            pricingCheckboxes.forEach((checkbox, index) => {
                const relatedInputs = document.querySelectorAll(
                    `.pricing-input[data-index="${index}"]`
                );

                // If checkbox is checked, at least one related input must have value
                if (checkbox.checked) {
                    const hasValue = Array.from(relatedInputs).some((input) =>
                        input.value.trim()
                    );
                    if (!hasValue) {
                        isValid = false;
                        errorMessages.push(
                            `Заполните хотя бы одно поле цены для ${checkbox.nextElementSibling.textContent.trim()}`
                        );
                    }
                }
                // If any input has value, checkbox must be checked
                else {
                    const hasValue = Array.from(relatedInputs).some((input) =>
                        input.value.trim()
                    );
                    if (hasValue) {
                        isValid = false;
                        errorMessages.push(
                            `Отметьте чекбокс для ${checkbox.nextElementSibling.textContent.trim()} если вы указываете цены`
                        );
                    }
                }
            });

            // Validate messaging services
            const telegramCheckbox = document.getElementById("has_telegram");
            const telegramInput = document.getElementById("telegram");
            const viberCheckbox = document.getElementById("has_viber");
            const viberInput = document.getElementById("viber");
            const whatsappCheckbox = document.getElementById("has_whatsapp");
            const whatsappInput = document.getElementById("whatsapp");

            // If input has value, checkbox must be checked
            if (telegramInput && telegramCheckbox) {
                if (telegramInput.value.trim() && !telegramCheckbox.checked) {
                    isValid = false;
                    errorMessages.push(
                        "Отметьте чекбокс Телеграм если вы указываете ник"
                    );
                }
                // If checkbox is checked, input must have value
                if (telegramCheckbox.checked && !telegramInput.value.trim()) {
                    isValid = false;
                    errorMessages.push("Укажите ник Телеграм");
                }
            }

            if (viberInput && viberCheckbox) {
                if (viberInput.value.trim() && !viberCheckbox.checked) {
                    isValid = false;
                    errorMessages.push(
                        "Отметьте чекбокс Viber если вы указываете ник"
                    );
                }
                // If checkbox is checked, input must have value
                if (viberCheckbox.checked && !viberInput.value.trim()) {
                    isValid = false;
                    errorMessages.push("Укажите ник Viber");
                }
            }

            if (whatsappInput && whatsappCheckbox) {
                if (whatsappInput.value.trim() && !whatsappCheckbox.checked) {
                    isValid = false;
                    errorMessages.push(
                        "Отметьте чекбокс WhatsApp если вы указываете ник"
                    );
                }
                // If checkbox is checked, input must have value
                if (whatsappCheckbox.checked && !whatsappInput.value.trim()) {
                    isValid = false;
                    errorMessages.push("Укажите ник WhatsApp");
                }
            }

            // If validation fails, prevent form submission and show errors
            if (!isValid) {
                e.preventDefault();
                errorMessages.forEach((message) => {
                    toastr.error(message);
                });
            }
        });
    }
});

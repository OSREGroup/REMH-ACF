// Uploading via "Use Your Drive" and ACF integration
// See also remh-acf.php



// =========================
// AJAX CALL - Form Loaded 
// =========================
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('your-form-id'); // Replace with your form's ID

    if (form) {
        const newValue = document.getElementById('your-input-id').value; // Replace with your input field's ID

        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `action=update_acf_field&value=${encodeURIComponent(newValue)}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error updating ACF field:', data.data);
            }
        })
        .catch(error => console.error('Error:', error));
    }
});



// =========================
// Configuration Object
// =========================

// Used to map the 'Field IDs' from WS Form to the 'Form ID'
// Listed in sequential order to match the front end

const formConfig = {
	'17': { // 17 = External Maintenance Request
        'referenceNumberField': 'external_maintenance_request_ref_no',
        'propertyCodeField': 'property_code_upload',
        // ... other fields specific to Form ID 17
    },
    '18': { // 18 = Common Area Maintenance Request
        'referenceNumberField': 'common_area_maintenance_request_ref_no',
        'propertyCodeField': 'property_code_upload',
        // ... other fields specific to Form ID 18
    },
    '19': { // 19 = Rooms Maintenance Request
        'referenceNumberField': 'rooms_maintenance_request_ref_no',
        'propertyCodeField': 'property_code_upload',
        // ... other fields specific to Form ID 19
    },
    '16': { // 16 = External Damages Report Form
        'referenceNumberField': 'external_damages_ref_no',
        'propertyCodeField': 'property_code_upload',
        // ... other fields specific to Form ID 16
    },
    '15': { // 15 = Common Area Damages Report Form
        'referenceNumberField': 'common_area_damages_ref_no',
        'propertyCodeField': 'property_code_upload',
        // ... other fields specific to Form ID 15
    },
	'11': { // 11 = Rooms Damages Repot Form
        'referenceNumberField': 'room_damages_ref_no',
        'propertyCodeField': 'property_code_upload',
        // ... other fields specific to Form ID 11
    },
    // ... add configurations for other forms
};

document.querySelectorAll('form').forEach(formElement => {
    formElement.addEventListener('submit', function(event) {
        event.preventDefault();

        const formId = this.getAttribute('data-id');
        
        // Check if the form ID exists in the configuration
        if (formConfig[formId]) {
            const referenceNumber = this.querySelector(`[name="${formConfig[formId].referenceNumberField}"]`).value;
            const propertyCode = this.querySelector(`[name="${formConfig[formId].propertyCodeField}"]`).value;
        }
    });
});

// Generic Event Listener for Form Submission
//document.querySelectorAll('form').forEach(function(formElement) {
//    formElement.addEventListener('submit', function(event) {
//        event.preventDefault();
//
//        var formId = this.getAttribute('data-id');
        
        // Check if the form ID exists in the configuration
        if (formConfig[formId]) {
            var referenceNumber = this.querySelector('[name="' + formConfig[formId].referenceNumberField + '"]').value;
            var propertyCode = this.querySelector('[name="' + formConfig[formId].propertyCodeField + '"]').value;
        }
    });
});

// Process Shortcode
function processShortcode(container, shortcode, identifierType) {
    fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `action=execute_useyourdrive_shortcode&identifierType=${identifierType}&nonce=${nonceValue}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.includes("Error:")) {
            console.error("Error processing AJAX request:", data);
        }
    })
    .catch(error => console.error("AJAX request failed:", error));
}


// =========================
// Custom Identifiers
// =========================

jQuery(document).ready(() => {
    jQuery('.useyourdrive-container').each(function() {
        const container = jQuery(this);
        const identifierType = container.attr('data-damage-type'); 
        
        // Maintenance Logic
        if (identifierType === 'external_maintenance') {
            // Logic for external maintenance
        } else if (identifierType === 'common_maintenance') {
            // Logic for common area maintenance
        } else if (identifierType === 'room_maintenance') {
            // Logic for room maintenance
        }
        
        // Damages Logic
        else if (identifierType === 'damages_external') {
            // Logic for external damages
        } else if (identifierType === 'damages_common') {
            // Logic for common area damages
        } else if (identifierType === 'damages_rooms') {
            // Logic for room damages
        }
        // ... and so on for other types

        fetch(localizedData.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `action=execute_useyourdrive_shortcode&identifierType=${identifierType}&nonce=${localizedData.nonce}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.includes("Error:")) {
                console.error("Error processing AJAX request:", data);
            }
        })
        .catch(error => console.error("AJAX request failed:", error));
    });
});

// Make the AJAX call for this container
jQuery.ajax({
	        type: 'POST',
            url: localizedData.ajax_url,
            data: {
                action: 'execute_useyourdrive_shortcode',
                identifierType: identifierType, // Set dynamically based on the container's attribute
                nonce: localizedData.nonce,
            },
            success: function(response) {
                if(response.includes("Error:")) {
                    console.error("Error processing AJAX request:", response);
                } else {
                    // Handle the successful response, e.g., display a message or update the UI
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX request failed:", textStatus, errorThrown);
            }
        });
    });
});


// =========================
// Nonce Handler
// =========================

const nonceValue = remh_acf_data.ajax_nonce;

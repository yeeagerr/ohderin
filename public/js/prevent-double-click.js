/**
 * Prevent Double Click on Submit Buttons
 * Automatically disables submit buttons after form submission to prevent duplicate requests
 */

document.addEventListener('DOMContentLoaded', function() {
    // Prevent double click on all form submit buttons
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButtons = form.querySelectorAll('button[type="submit"]');
            
            submitButtons.forEach(button => {
                // Store original text
                if (!button.dataset.originalText) {
                    button.dataset.originalText = button.innerHTML;
                }
                
                // Disable button and show loading state
                button.disabled = true;
                button.style.opacity = '0.6';
                button.style.cursor = 'not-allowed';
                button.innerHTML = '<span style="display: inline-flex; align-items: center; gap: 6px;"><svg class="inline animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Memproses...</span></span>';
            });
        });
    });
    
    // Add helper function to manually trigger prevent double click
    window.preventDoubleClick = function(buttonElement) {
        if (!buttonElement) return;
        
        if (!buttonElement.dataset.originalText) {
            buttonElement.dataset.originalText = buttonElement.innerHTML;
        }
        
        buttonElement.disabled = true;
        buttonElement.style.opacity = '0.6';
        buttonElement.style.cursor = 'not-allowed';
        buttonElement.innerHTML = '<span style="display: inline-flex; align-items: center; gap: 6px;"><svg class="inline animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Memproses...</span></span>';
    };
    
    // Add helper function to reset button (in case of error)
    window.resetSubmitButton = function(buttonElement) {
        if (!buttonElement) return;
        
        buttonElement.disabled = false;
        buttonElement.style.opacity = '1';
        buttonElement.style.cursor = 'pointer';
        if (buttonElement.dataset.originalText) {
            buttonElement.innerHTML = buttonElement.dataset.originalText;
        }
    };
});

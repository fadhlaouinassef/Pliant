window.feedbackForm = function() {
    return {
        rating: 3,
        isSubmitting: false,
        showToast: false,
        toastMessage: '',
        toastType: 'success',
        
        setRating(value) {
            this.rating = value;
        },
        
        closeForm() {
            document.getElementById('feedback-form').style.display = 'none';
            this.resetForm();
        },
        
        resetForm() {
            this.rating = 3;
            document.getElementById('user-name').value = '';
            document.getElementById('user-comment').value = '';
        },
        
        submitForm() {
            if (this.rating === 0) {
                this.showToastMessage('Veuillez sélectionner une note', 'error');
                return;
            }
            
            this.isSubmitting = true;
            const form = document.getElementById('avis-form');
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Erreur lors de l\'envoi du formulaire');
            })
            .then(data => {
                this.isSubmitting = false;
                this.showToastMessage('Votre avis a été envoyé avec succès!', 'success');
                this.resetForm();
                this.closeForm();
            })
            .catch(error => {
                this.isSubmitting = false;
                this.showToastMessage(error.message, 'error');
            });
        },
        
        showToastMessage(message, type) {
            this.toastMessage = message;
            this.toastType = type;
            this.showToast = true;
            
            setTimeout(() => {
                this.showToast = false;
            }, 3000);
        }
    };
};

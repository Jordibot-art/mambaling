function submitRequest(documentName, price) {
    if (price === "Free") {
        alert(`Your request for ${documentName} has been submitted successfully!`);
    } else {
        document.getElementById('payment-modal').style.display = 'block';
        document.getElementById('payment-modal').setAttribute('data-document', documentName);
    }
}

function processPayment(method) {
    const documentName = document.getElementById('payment-modal').getAttribute('data-document');
    alert(`Payment for ${documentName} via ${method} has been submitted successfully!`);
    closeModal();
}

function closeModal() {
    document.getElementById('payment-modal').style.display = 'none';
}

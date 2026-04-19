document.getElementById("documentForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const file = formData.get("document");

    // Handle document upload here (optional)

    try {
        const response = await fetch("https://api.paymongo.com/v1/payment_intents", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization: "Basic " + btoa("YOUR_SECRET_KEY")
            },
            body: JSON.stringify({
                data: {
                    attributes: {
                        amount: 50000, // Amount in cents (e.g., 500.00 PHP)
                        currency: "PHP",
                        payment_method_allowed: ["card", "gcash", "grab_pay"],
                        capture_type: "automatic"
                    }
                }
            })
        });

        const result = await response.json();

        if (result.data) {
            const paymentIntentId = result.data.id;

            // Redirect user to PayMongo checkout page
            window.location.href = result.data.attributes.next_action.redirect.url;
        } else {
            alert("Failed to create payment. Please try again.");
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Something went wrong. Please try again.");
    }
});

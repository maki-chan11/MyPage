// Optional: Confirm before removing a cart item
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("a[href*='remove=']").forEach(link => {
        link.addEventListener("click", (e) => {
            if (!confirm("Are you sure you want to remove this item from the cart?")) {
                e.preventDefault();
            }
        });
    });
});

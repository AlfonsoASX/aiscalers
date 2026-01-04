<?php
/**
 * Footer Component
 */
?>
<!-- Scripts -->
<script>
    // Copy to clipboard function
    async function copyToClipboard(text, buttonId) {
        try {
            await navigator.clipboard.writeText(text);
            const button = document.getElementById(buttonId);
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>¡Copiado!';
            setTimeout(() => {
                button.innerHTML = originalHTML;
            }, 2000);
        } catch (err) {
            console.error('Error copying:', err);
        }
    }

    // Format JSON
    function formatJSON(json) {
        try {
            const parsed = typeof json === 'string' ? JSON.parse(json) : json;
            return JSON.stringify(parsed, null, 2);
        } catch (e) {
            return json;
        }
    }
</script>
</body>

</html>
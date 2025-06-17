<div id="message" class="fixed bottom-3 max-w-[93%] right-3 z-[52] rankinginfo3 bg-gray-600 text-white p-2 rounded shadow leading-none opacity-0 translate-y-15 transition-all duration-500"></div>
<!-- Footer -->
<footer class="bg-gradient-to-r from-[#222A40] via-[#101735] to-[#222A40] text-white p-4 text-center sticky top-full">
    <p>&copy; {{ date('Y') }} <span class="text-gradient font-semibold text-xl">AdDoodles</span> • All rights reserved.</p>
    <!-- <button onclick="displayToster('John has joined AdDoodles with $100 from Russia')" class="mt-4 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded">
        Show Message
    </button> -->
</footer>

<script>
    function displayToster(msg) {
        const message = document.getElementById('message');
        message.innerHTML = msg;
        message.classList.remove('opacity-0', 'translate-y-15');
        message.classList.add('opacity-100', 'translate-y-0');
        setTimeout(function() {
            message.classList.remove('opacity-100', 'translate-y-0');
            message.classList.add('opacity-0', 'translate-y-15');
        }, 3000);
    }
</script>
<div class="fixed bottom-5 right-5 z-50 space-y-3 w-80" id="custom-alert-container">

    @foreach (['sukses', 'error', 'info', 'warning'] as $type)
        @if (session($type))
            <div
                class="alert-custom-trigger hidden flex gap-3 p-4 rounded-2xl shadow-lg border backdrop-blur-sm
                bg-white/80 dark:bg-neutral-900/80

                {{ $type === 'sukses' ? 'border-green-300 text-green-600 dark:text-green-400' : '' }}
                {{ $type === 'error' ? 'border-red-300 text-red-600 dark:text-red-400' : '' }}
                {{ $type === 'info' ? 'border-blue-300 text-blue-600 dark:text-blue-400' : '' }}
                {{ $type === 'warning' ? 'border-yellow-300 text-yellow-600 dark:text-yellow-400' : '' }}
                ">

                <!-- Flux Icon -->
                <div class="mt-1">

                    @if ($type === 'sukses')
                        <flux:icon name="check-circle" />
                    @endif

                    @if ($type === 'error')
                        <flux:icon name="x-circle" />
                    @endif

                    @if ($type === 'info')
                        <flux:icon name="information-circle" />
                    @endif

                    @if ($type === 'warning')
                        <flux:icon name="exclamation-circle" />
                    @endif

                </div>

                <!-- Text -->
                <div class="flex flex-col">

                    <!-- Title -->
                    <span class="font-semibold">
                        {{ ucfirst($type) }}
                    </span>

                    <!-- Message -->
                    <span class="text-sm opacity-80">
                        {{ session($type) }}
                    </span>

                </div>

            </div>
        @endif
    @endforeach

</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {

        const alertBox = document.querySelectorAll('.alert-custom-trigger');

        if (alertBox.length > 0) {

            alertBox.forEach(el => {

                el.style.display = "flex";
                el.style.opacity = "0";
                el.style.transform = "translateY(20px)";
                el.style.transition = "all .4s ease";

                setTimeout(() => {
                    el.style.opacity = "1";
                    el.style.transform = "translateY(0)";
                }, 50);

                setTimeout(() => {

                    el.style.opacity = "0";
                    el.style.transform = "translateY(20px)";

                    setTimeout(() => el.remove(), 400);

                }, 4000);

            });

        }

    });
</script>

<x-layouts.auth.simple :title="'Access Pending'">

    <div class="text-center space-y-4">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-50 border border-amber-200">
            <svg class="h-7 w-7 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <div>
            <h1 class="text-xl font-semibold text-gray-900">Approval Pending</h1>
            <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                Your account is awaiting approval. We'll email you as soon as an admin reviews your request — usually within a day or two.
            </p>
        </div>

        <div class="rounded-lg bg-blue-50 border border-blue-100 px-4 py-3 text-sm text-blue-700">
            Keep an eye on your inbox for an approval notification.
        </div>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button type="submit"
                class="w-full inline-flex justify-center items-center rounded-lg border border-gray-300 bg-white text-gray-700 px-5 py-2.5 text-sm font-medium hover:bg-gray-50 transition">
            Log Out
        </button>
    </form>

</x-layouts.auth.simple>

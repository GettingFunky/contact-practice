<x-guest-layout>
    <div class="mx-auto max-w-2xl px-4 py-10">
        <h1 class="text-2xl font-semibold mb-6">Επικοινωνία</h1>

        @if(session('success'))
            <div class="mb-6 rounded border border-green-200 bg-green-50 p-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('contact.store') }}" method="POST" novalidate class="space-y-6">
            @csrf

            <x-form.honeypot />

            <x-form.group name="name" label="Όνομα" :required="true">
                <x-form.input type="text" name="name" :value="old('name')" required />
            </x-form.group>

            <x-form.group name="email" label="Email" :required="true">
                <x-form.input type="email" name="email" :value="old('email')" required autocomplete="email" />
            </x-form.group>

            <x-form.group name="phone" label="Τηλέφωνο (προαιρετικό)">
                <x-form.input type="text" name="phone" :value="old('phone')" inputmode="tel" autocomplete="tel" />
            </x-form.group>

            <x-form.group name="message" label="Μήνυμα" :required="true">
                <x-form.textarea name="message" rows="5" required>{{ old('message') }}</x-form.textarea>
            </x-form.group>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    Αποστολή
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>

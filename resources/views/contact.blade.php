{{-- Δημόσια φόρμα επικοινωνίας --}}
{{-- Επιλογή layout: με Breeze βολεύει το guest layout για public σελίδες --}}
<x-guest-layout>
    <div class="mx-auto max-w-2xl px-4 py-10">
        <h1 class="text-2xl font-semibold mb-6">Επικοινωνία</h1>

        {{-- Success Flash (PRG): δείχνουμε μήνυμα αφού επιστρέψει ο controller --}}
        @if(session('success'))
            <div class="mb-6 rounded border border-green-200 bg-green-50 p-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Φόρμα: POST στο named route contact.store, με CSRF --}}
        <form action="{{ route('contact.store') }}" method="POST" novalidate class="space-y-6">
            @csrf

            {{-- Honeypot: πρέπει να παραμείνει κενό (rules: size:0) --}}
            <div class="hidden">
                <label for="website">Website</label>
                <input type="text" id="website" name="website" value="">
            </div>

            {{-- Όνομα --}}
            <div>
                <label for="name" class="block text-sm font-medium">Όνομα</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    aria-required="true"
                    @class([
                        'mt-1 block w-full rounded border px-3 py-2 outline-none',
                        // Αν έχει error, δώσε κόκκινο περίγραμμα
                        $errors->has('name') ? 'border-red-500 focus:ring-2 focus:ring-red-300' : 'border-gray-300 focus:ring-2 focus:ring-blue-300',
                    ])
                    aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                    aria-describedby="{{ $errors->has('name') ? 'name-error' : '' }}"
                >
                {{-- Σταθερός χώρος για error text: αποφεύγει layout shift --}}
                <p id="name-error" class="mt-1 min-h-[1.25rem] text-sm text-red-600">
                    @error('name') {{ $message }} @enderror
                </p>
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    aria-required="true"
                    @class([
                        'mt-1 block w-full rounded border px-3 py-2 outline-none',
                        $errors->has('email') ? 'border-red-500 focus:ring-2 focus:ring-red-300' : 'border-gray-300 focus:ring-2 focus:ring-blue-300',
                    ])
                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                    aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                >
                <p id="email-error" class="mt-1 min-h-[1.25rem] text-sm text-red-600">
                    @error('email') {{ $message }} @enderror
                </p>
            </div>

            {{-- Τηλέφωνο (προαιρετικό) --}}
            <div>
                <label for="phone" class="block text-sm font-medium">Τηλέφωνο (προαιρετικό)</label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    @class([
                        'mt-1 block w-full rounded border px-3 py-2 outline-none',
                        $errors->has('phone') ? 'border-red-500 focus:ring-2 focus:ring-red-300' : 'border-gray-300 focus:ring-2 focus:ring-blue-300',
                    ])
                    aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                    aria-describedby="{{ $errors->has('phone') ? 'phone-error' : '' }}"
                >
                <p id="phone-error" class="mt-1 min-h-[1.25rem] text-sm text-red-600">
                    @error('phone') {{ $message }} @enderror
                </p>
            </div>

            {{-- Μήνυμα --}}
            <div>
                <label for="message" class="block text-sm font-medium">Μήνυμα</label>
                <textarea
                    id="message"
                    name="message"
                    rows="5"
                    required
                    aria-required="true"
                    @class([
                        'mt-1 block w-full rounded border px-3 py-2 outline-none',
                        $errors->has('message') ? 'border-red-500 focus:ring-2 focus:ring-red-300' : 'border-gray-300 focus:ring-2 focus:ring-blue-300',
                    ])
                    aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}"
                    aria-describedby="{{ $errors->has('message') ? 'message-error' : '' }}"
                >{{ old('message') }}</textarea>
                <p id="message-error" class="mt-1 min-h-[1.25rem] text-sm text-red-600">
                    @error('message') {{ $message }} @enderror
                </p>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    Αποστολή
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>

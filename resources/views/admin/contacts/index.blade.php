{{-- Admin λίστα επαφών (protected via auth middleware) --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Μηνύματα Επικοινωνίας') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    @if($contacts->count() === 0)
                        <p class="text-gray-600">Δεν υπάρχουν μηνύματα ακόμη.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 text-left text-sm font-semibold text-gray-700">
                                        <th class="px-4 py-3">Ημερομηνία</th>
                                        <th class="px-4 py-3">Όνομα</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3">Τηλέφωνο</th>
                                        <th class="px-4 py-3">Μήνυμα (απόσπασμα)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-sm">
                                    @foreach($contacts as $contact)
                                        <tr class="hover:bg-gray-50">
                                            <td class="whitespace-nowrap px-4 py-3 text-gray-700">
                                                {{ $contact->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">
                                                {{ $contact->name }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-blue-700">
                                                <a href="mailto:{{ $contact->email }}" class="hover:underline">
                                                    {{ $contact->email }}
                                                </a>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-gray-700">
                                                {{ $contact->phone ?? '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">
                                                {{ \Illuminate\Support\Str::limit($contact->message, 80) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{-- Tailwind pagination links (Breeze στυλ) --}}
                            {{ $contacts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

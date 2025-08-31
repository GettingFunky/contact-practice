<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Δεν έχουμε ρόλους εδώ. Ο καθένας μπορεί να στείλει φόρμα.
        return true;
    }

    /**
     * Καθαρισμοί πριν τρέξει το validation.
     * Γιατί: θες να αξιολογείς "καθαρά" δεδομένα (trim, normalize τηλεφώνου).
     */
    protected function prepareForValidation(): void
    {
        $name = $this->input('name');
        $email = $this->input('email');
        $phone = $this->input('phone');
        $message = $this->input('message');

        $this->merge([
            'name'    => is_string($name) ? trim($name) : $name,
            'email'   => is_string($email) ? trim($email) : $email,
            // Απλή κανονικοποίηση τηλεφώνου: μόνο ψηφία και + (αν ξεκινάει έτσι)
            'phone'   => is_string($phone)
                ? preg_replace('/(?<!^)\D+/', '', ltrim($phone)) // κρατάει ψηφία (και το αρχικό + αν υπήρχε)
                : $phone,
            'message' => is_string($message) ? trim($message) : $message,
        ]);
    }

    public function rules(): array
    {
        return [

            'name'    => ['bail','required','string','min:3','max:150'],
            'email'   => ['bail','required','string','email','max:191'],
            'phone'   => ['nullable','string','max:30'],
            'message' => ['bail','required','string','min:10','max:1000'],


            'website' => ['nullable','string','size:0'],
        ];
    }

    public function messages(): array
    {

        return [
            'name.required'    => 'Το όνομα είναι υποχρεωτικό.',
            'name.min'         => 'Το όνομα πρέπει να έχει τουλάχιστον :min χαρακτήρες.',
            'name.max'         => 'Το όνομα δεν μπορεί να ξεπερνά τους :max χαρακτήρες.',

            'email.required'   => 'Το email είναι υποχρεωτικό.',
            'email.email'      => 'Το email δεν φαίνεται έγκυρο.',
            'email.max'        => 'Το email δεν μπορεί να ξεπερνά τους :max χαρακτήρες.',

            'phone.max'        => 'Το τηλέφωνο δεν μπορεί να ξεπερνά τους :max χαρακτήρες.',

            'message.required' => 'Το μήνυμα είναι υποχρεωτικό.',
            'message.min'      => 'Το μήνυμα πρέπει να έχει τουλάχιστον :min χαρακτήρες.',
            'message.max'      => 'Το μήνυμα δεν μπορεί να ξεπερνά τους :max χαρακτήρες.',

            // Honeypot
            'website.size'     => 'Αδυναμία αποστολής αιτήματος.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'    => 'όνομα',
            'email'   => 'email',
            'phone'   => 'τηλέφωνο',
            'message' => 'μήνυμα',
        ];
    }
}

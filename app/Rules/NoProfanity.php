<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoProfanity implements ValidationRule
{
    /**
     * List of inappropriate words to filter
     */
    private array $bannedWords = [
        'fuck', 'fucking', 'fucked',
        'shit', 'shitting', 'shitted',
        'damn', 'damned',
        'ass', 'asshole',
        'bitch', 'bitches',
        'bastard',
        'crap',
        'hell',
        'idiot', 'stupid',
        'hate', 'hateful',
        'kill', 'killing', 'killed',
        'die', 'dying', 'death',
        'violence', 'violent',
        'attack', 'attacking',
        'hurt', 'hurting',
        'fight', 'fighting',
        'weapon', 'weapons',
        'gun', 'guns',
        'knife', 'knives',
        'bomb', 'bombs',
        'terror', 'terrorist',
        'murder', 'murdering',
        'suicide', 'suicidal',
        // Add more words as needed
    ];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        \Log::info('NoProfanity rule called', [
            'attribute' => $attribute,
            'value_length' => is_string($value) ? strlen($value) : 0,
            'value_preview' => is_string($value) ? substr($value, 0, 50) : 'not_string',
        ]);

        if (! is_string($value)) {
            return;
        }

        $lowercaseValue = strtolower($value);

        // Remove punctuation and split into words
        $normalizedText = preg_replace('/[^a-z0-9\s]/', ' ', $lowercaseValue);
        $words = preg_split('/\s+/', $normalizedText, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($this->bannedWords as $bannedWord) {
            // Check for exact word matches (whole word only)
            foreach ($words as $word) {
                if ($word === $bannedWord) {
                    \Log::warning('Profanity detected in NoProfanity rule', [
                        'attribute' => $attribute,
                        'banned_word' => $bannedWord,
                        'matched_word' => $word,
                    ]);
                    $fail('⚠️ Warning: Your :attribute contains inappropriate language. Please use respectful language.');

                    return;
                }
            }

            // Also check if the banned word appears as a whole word in the text (with word boundaries)
            // This catches cases where the word might be at the start/end or surrounded by punctuation
            $pattern = '/\b'.preg_quote($bannedWord, '/').'\b/';
            if (preg_match($pattern, $lowercaseValue)) {
                \Log::warning('Profanity detected in NoProfanity rule (pattern match)', [
                    'attribute' => $attribute,
                    'banned_word' => $bannedWord,
                    'pattern' => $pattern,
                ]);
                $fail('⚠️ Warning: Your :attribute contains inappropriate language. Please use respectful language.');

                return;
            }
        }
    }
}

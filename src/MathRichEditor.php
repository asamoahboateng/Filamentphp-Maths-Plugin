<?php

namespace KwameBoateng\MathRichEditor;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Concerns\HasPlaceholder;

class MathRichEditor extends Field
{
    use HasPlaceholder;

    protected string $view = 'math-rich-editor::math-rich-editor';

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(static function (MathRichEditor $component, $state): void {
            if (is_array($state)) {
                return;
            }

            try {
                $decoded = json_decode($state, true, 512, JSON_THROW_ON_ERROR);
                $component->state($decoded);
            } catch (\JsonException $e) {
                $component->state(null);
            }
        });

        $this->dehydrateStateUsing(static function ($state) {
            return json_encode($state);
        });
    }
}

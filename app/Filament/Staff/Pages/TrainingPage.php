<?php

namespace App\Filament\Staff\Pages;

use App\Models\Training;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Actions\Action as PageAction;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

class TrainingPage extends Page
{
    use WithPagination;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static string $view = 'filament.staff.pages.training-page';

    protected static ?string $navigationLabel = 'Training & Guidelines';

    protected static ?string $title = 'Training & Guidelines';

    protected static ?string $navigationGroup = 'Resources';

    protected static ?int $navigationSort = 1;

    public $selectedType = '';
    public $perPage = 12;

    public function mount(): void
    {
        $this->selectedType = '';
    }

    public function getTrainings(): LengthAwarePaginator
    {
        $query = Training::query()->where('is_published', true);

        if ($this->selectedType) {
            $query->where('type', $this->selectedType);
        }

        return $query->orderBy('created_at', 'desc')->paginate($this->perPage);
    }

    public function filterByType($type): void
    {
        $this->selectedType = $type;
        $this->resetPage();
    }

    public function clearFilter(): void
    {
        $this->selectedType = '';
        $this->resetPage();
    }

    public function viewTraining($trainingId): void
    {
        $training = Training::findOrFail($trainingId);

        $this->dispatch('open-modal', [
            'id' => 'training-modal',
            'training' => $training
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}

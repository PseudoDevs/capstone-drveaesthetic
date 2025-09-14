<?php

namespace App\Filament\Staff\Pages;

use App\Models\TimeLogs;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class TimeClock extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Time Clock';
    
    protected static ?string $slug = 'time-clock';

    protected static string $view = 'filament.staff.pages.time-clock';
    
    public ?TimeLogs $todayLog = null;
    
    public string $currentTime = '';
    
    public bool $isLoading = false;

    public function mount(): void
    {
        $this->todayLog = TimeLogs::getTodaysLog(Auth::id());
        $this->currentTime = now()->format('H:i:s');
    }
    
    public function clockIn(): void
    {
        if ($this->isLoading) return;
        
        $this->isLoading = true;
        
        if ($this->todayLog && $this->todayLog->isActive()) {
            Notification::make()
                ->title('Already Clocked In')
                ->body('You are already clocked in today.')
                ->warning()
                ->send();
            
            $this->isLoading = false;
            return;
        }
        
        if ($this->todayLog) {
            Notification::make()
                ->title('Already Clocked Out')
                ->body('You have already completed your work for today.')
                ->warning()
                ->send();
            
            $this->isLoading = false;
            return;
        }
        
        $now = now();
        
        $this->todayLog = TimeLogs::create([
            'user_id' => Auth::id(),
            'clock_in' => $now,
            'date' => $now->toDateString(),
        ]);
        
        Notification::make()
            ->title('Clocked In Successfully')
            ->body('Welcome! You clocked in at ' . $now->format('H:i'))
            ->success()
            ->send();
            
        $this->isLoading = false;
    }
    
    public function clockOutAction(): Action
    {
        return Action::make('clockOut')
            ->label('Clock Out')
            ->icon('heroicon-o-arrow-right-on-rectangle')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Confirm Clock Out')
            ->modalDescription('Are you sure you want to clock out? This will end your work session for today.')
            ->modalSubmitActionLabel('Yes, Clock Out')
            ->action(function () {
                $this->clockOut();
            });
    }

    public function clockOut(): void
    {
        if ($this->isLoading) return;
        
        $this->isLoading = true;
        
        if (!$this->todayLog) {
            Notification::make()
                ->title('Not Clocked In')
                ->body('You need to clock in first.')
                ->warning()
                ->send();
                
            $this->isLoading = false;
            return;
        }
        
        if (!$this->todayLog->isActive()) {
            Notification::make()
                ->title('Already Clocked Out')
                ->body('You have already clocked out today.')
                ->warning()
                ->send();
                
            $this->isLoading = false;
            return;
        }
        
        $now = now();
        $this->todayLog->update(['clock_out' => $now]);
        $this->todayLog->calculateTotalHours();
        $this->todayLog->refresh();
        
        Notification::make()
            ->title('Clocked Out Successfully')
            ->body('Good work! You clocked out at ' . $now->format('H:i') . ' (Total: ' . number_format($this->todayLog->total_hours, 2) . ' hrs)')
            ->success()
            ->send();
            
        $this->isLoading = false;
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    $this->todayLog = TimeLogs::getTodaysLog(Auth::id());
                    $this->currentTime = now()->format('H:i:s');
                    
                    Notification::make()
                        ->title('Refreshed')
                        ->success()
                        ->send();
                }),
        ];
    }
}

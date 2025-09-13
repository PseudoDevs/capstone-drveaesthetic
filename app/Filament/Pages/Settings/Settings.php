<?php

namespace App\Filament\Pages\Settings;

use Closure;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;

class Settings extends BaseSettings
{
    public function schema(): array|Closure
    {
        return [
            Tabs::make('Settings')
                ->schema([
                    Tabs\Tab::make('General')
                        ->schema([
                            Section::make('Site Information')
                                ->schema([
                                    TextInput::make('general.site_name')
                                        ->label('Site Name')
                                        ->default(env('APP_NAME', 'Capstone Aesthetic'))
                                        ->required(),

                                    TextInput::make('general.site_tagline')
                                        ->label('Site Tagline')
                                        ->default('Dermatology and Skin Care'),

                                    Textarea::make('general.site_description')
                                        ->label('Site Description')
                                        ->default('Professional dermatology and aesthetic treatments for beautiful, healthy skin.')
                                        ->rows(3),
                                ]),

                            Section::make('About Page Banner')
                                ->schema([
                                    TextInput::make('general.about_banner_title')
                                        ->label('Banner Title')
                                        ->default('About Us.')
                                        ->required(),

                                    Textarea::make('general.about_banner_description')
                                        ->label('Banner Description')
                                        ->default('Saunas are used all over the world to improve health, to enjoy and relax.')
                                        ->rows(2),

                                    FileUpload::make('general.about_banner_image')
                                        ->label('Banner Background Image')
                                        ->directory('about-page')
                                        ->image()
                                        ->imageResizeMode('force')
                                        ->imageResizeTargetWidth('1920')
                                        ->imageResizeTargetHeight('490')
                                        ->maxSize(5120)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->helperText('Upload banner image with 1920x490 pixels or it will be resized automatically'),
                                ]),

                            Section::make('About Us Section')
                                ->schema([
                                    TextInput::make('general.about_title')
                                        ->label('About Section Title')
                                        ->default('We Have 25+ Years Experience.')
                                        ->required(),

                                    Textarea::make('general.about_description')
                                        ->label('About Description')
                                        ->default('Saunas are used all over the world to improve health enjoy relax. During the clients stay in sauna, body is sweating and from harmful substances and toxins.')
                                        ->rows(3),

                                    Textarea::make('general.about_content')
                                        ->label('About Content')
                                        ->default('It has different attractions – tropical rain, fog, dew, wall jets and it is combined with sound, caribbian storm, aroma and various lighting effects, what makes you have an unforgettable filling.')
                                        ->rows(3),

                                    FileUpload::make('general.about_image_1')
                                        ->label('About Image 1')
                                        ->directory('about-page')
                                        ->image()
                                        ->maxSize(2048)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->helperText('First image in the about section'),

                                    FileUpload::make('general.about_image_2')
                                        ->label('About Image 2')
                                        ->directory('about-page')
                                        ->image()
                                        ->maxSize(2048)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->helperText('Second image in the about section'),

                                    FileUpload::make('general.about_image_3')
                                        ->label('About Image 3')
                                        ->directory('about-page')
                                        ->image()
                                        ->maxSize(2048)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->helperText('Third image in the about section'),
                                ]),

                            Section::make('Mission & Vision')
                                ->schema([
                                    TextInput::make('general.mission_title')
                                        ->label('Mission Title')
                                        ->default('Our Mission.')
                                        ->required(),

                                    Textarea::make('general.mission_subtitle')
                                        ->label('Mission Subtitle')
                                        ->default('Saunas are used all over the world to improve health enjoy relax. During the clients stay in sauna, body is sweating and from harmful substances and toxins.')
                                        ->rows(3),

                                    Textarea::make('general.mission_description')
                                        ->label('Mission Description')
                                        ->default('It has different attractions – tropical rain, fog, dew, wall jets and it is combined with sound, caribbian storm, aroma and various lighting effects, what makes you have an unforgettable filling.')
                                        ->rows(4),

                                    TextInput::make('general.vision_title')
                                        ->label('Vision Title')
                                        ->default('Our Vision.')
                                        ->required(),

                                    Textarea::make('general.vision_description_1')
                                        ->label('Vision Description (Paragraph 1)')
                                        ->default('It has different attractions – tropical rain, fog, dew, wall jets and it is combined with sound, caribbian storm, aroma and various lighting effects, what makes you have an unforgettable filling.')
                                        ->rows(3),

                                    Textarea::make('general.vision_description_2')
                                        ->label('Vision Description (Paragraph 2)')
                                        ->default('It has different attractions – tropical rain, fog, dew, wall jets and it is combined with sound, caribbian storm, aroma and various lighting effects, what makes you have an unforgettable filling.')
                                        ->rows(3),
                                ]),
                        ]),

                    Tabs\Tab::make('Contact')
                        ->schema([
                            Section::make('Banner Section')
                                ->schema([
                                    TextInput::make('contact.banner_title')
                                        ->label('Banner Title')
                                        ->default('Contact Us.')
                                        ->required(),

                                    Textarea::make('contact.banner_description')
                                        ->label('Banner Description')
                                        ->default('Saunas are used all over the world to improve health to enjoy and relax electronic typesetting.')
                                        ->rows(3),
                                ]),

                            Section::make('Contact Information')
                                ->schema([
                                    TextInput::make('contact.phone_1')
                                        ->label('Primary Phone')
                                        ->default('+00 569 846 358')
                                        ->required(),

                                    TextInput::make('contact.phone_2')
                                        ->label('Secondary Phone')
                                        ->default('+00 896 387 439'),

                                    TextInput::make('contact.address_line_1')
                                        ->label('Address Line 1')
                                        ->default('1569  Davis Place,')
                                        ->required(),

                                    TextInput::make('contact.address_line_2')
                                        ->label('Address Line 2')
                                        ->default('Philippines, 1000'),

                                    TextInput::make('contact.email_1')
                                        ->label('Primary Email')
                                        ->email()
                                        ->default('support@gmail.com')
                                        ->required(),

                                    TextInput::make('contact.email_2')
                                        ->label('Secondary Email')
                                        ->email()
                                        ->default('gilkan4@gmail.com'),
                                ]),
                        ]),

                    Tabs\Tab::make('Team')
                        ->schema([
                            Section::make('Our Expert And Dedicated Team')
                                ->schema([
                                    TextInput::make('team.section_title')
                                        ->label('Section Title')
                                        ->default('Our Expert And Dedicated Team')
                                        ->required(),

                                    Textarea::make('team.section_description')
                                        ->label('Section Description')
                                        ->default('Meet our professional team of experts dedicated to providing you with the best aesthetic and dermatological care.')
                                        ->rows(3),

                                    Repeater::make('team.members')
                                        ->label('Team Members')
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Full Name')
                                                ->required(),
                                            
                                            TextInput::make('position')
                                                ->label('Position/Title')
                                                ->required(),
                                            
                                            FileUpload::make('image')
                                                ->label('Profile Image')
                                                ->directory('team-members')
                                                ->image()
                                                ->imageResizeMode('force')
                                                ->imageCropAspectRatio('270:320')
                                                ->imageResizeTargetWidth('270')
                                                ->imageResizeTargetHeight('320')
                                                ->maxSize(2048)
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                                ->helperText('Upload image with 270x320 pixels or it will be resized automatically')
                                                ->required(),
                                            
                                            Textarea::make('bio')
                                                ->label('Short Bio')
                                                ->rows(3)
                                                ->maxLength(500)
                                                ->helperText('Optional brief description (max 500 characters)'),
                                        ])
                                        ->defaultItems(3)
                                        ->default([
                                            [
                                                'name' => 'Dr. Sarah Johnson',
                                                'position' => 'Chief Dermatologist',
                                                'bio' => 'Experienced dermatologist with over 15 years in aesthetic treatments.',
                                            ],
                                            [
                                                'name' => 'Dr. Michael Chen',
                                                'position' => 'Aesthetic Specialist',
                                                'bio' => 'Specialized in advanced skin care and cosmetic procedures.',
                                            ],
                                            [
                                                'name' => 'Emily Rodriguez',
                                                'position' => 'Senior Aesthetician',
                                                'bio' => 'Licensed aesthetician passionate about healthy, beautiful skin.',
                                            ],
                                        ])
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                        ->reorderable()
                                        ->addActionLabel('Add Team Member'),
                                ]),
                        ]),

                    Tabs\Tab::make('Footer')
                        ->schema([
                            Section::make('About Us Section')
                                ->schema([
                                    TextInput::make('footer.about_title')
                                        ->label('About Title')
                                        ->default('About Us.')
                                        ->required(),

                                    Textarea::make('footer.about_description')
                                        ->label('About Description')
                                        ->default('The pleasant temperature, similar body temperature, extending beneath client\'s body, frees the body negative tension caused by everyday stress.')
                                        ->rows(4)
                                        ->required(),

                                    TextInput::make('footer.contact_address_line_1')
                                        ->label('Contact Address Line 1')
                                        ->default('176 W street name,')
                                        ->required(),

                                    TextInput::make('footer.contact_address_line_2')
                                        ->label('Contact Address Line 2')
                                        ->default('New York, NY 10014'),

                                    TextInput::make('footer.contact_phone')
                                        ->label('Contact Phone')
                                        ->default('+00 568 468 349')
                                        ->required(),
                                ]),


                            Section::make('Copyright Section')
                                ->schema([
                                    TextInput::make('footer.copyright_text')
                                        ->label('Copyright Text')
                                        ->default('Copyright@2025 {{ env(\'APP_NAME\') }}. All rights reserved')
                                        ->required(),
                                ]),
                        ]),

                ]),
        ];
    }
}

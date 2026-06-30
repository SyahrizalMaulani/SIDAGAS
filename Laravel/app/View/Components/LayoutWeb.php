<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LayoutWeb extends Component
{
    public string $title;
    public string $description;
    public array|string|null $schema;

    public function __construct(string $title = 'SIDAGAS', string $description = '', array|string|null $schema = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->schema = $schema;
    }

    public function render(): View|Closure|string
    {
        return view('components.layout-web');
    }
}

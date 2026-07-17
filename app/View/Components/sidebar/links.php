<?php

namespace App\View\Components\sidebar;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class links extends Component
{
    /**
     * Create a new component instance.
     */
    public string $title, $route, $icon, $active;

    public function __construct($title, $route, $icon)
    {
        $this->title = $title;
        $this->route = $route;
        $this->icon = $icon;
        $basepath = $this->generatePath($route);
        $this->active = request()->routeIs($basepath) ? 'bg-blue-300 text-white' : '';
    }

    public function generatePath($route)
    {
        if (str_contains($route, '.')) {
            $path = explode('.', $route);
            return $path[0] . '.*';
        } else {
            return $route;
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.sidebar.links');
    }
}

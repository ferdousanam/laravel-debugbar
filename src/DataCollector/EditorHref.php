<?php

namespace Barryvdh\Debugbar\DataCollector;

use InvalidArgumentException;

trait EditorHref
{
    /**
     * A list of known editor strings.
     *
     * @var array
     */
    protected $editors = [
        'sublime' => 'subl://open?url=file://%file&line=%line',
        'textmate' => 'txmt://open?url=file://%file&line=%line',
        'emacs' => 'emacs://open?url=file://%file&line=%line',
        'macvim' => 'mvim://open/?url=file://%file&line=%line',
        'phpstorm' => 'phpstorm://open?file=%file&line=%line',
        'idea' => 'idea://open?file=%file&line=%line',
        'vscode' => 'vscode://file/%file:%line',
        'vscode-insiders' => 'vscode-insiders://file/%file:%line',
        'vscode-remote' => 'vscode://vscode-remote/%file:%line',
        'vscode-insiders-remote' => 'vscode-insiders://vscode-remote/%file:%line',
        'vscodium' => 'vscodium://file/%file:%line',
        'nova' => 'nova://core/open/file?filename=%file&line=%line',
        'xdebug' => 'xdebug://%file@%line',
        'atom' => 'atom://core/open/file?filename=%file&line=%line',
        'espresso' => 'x-espresso://open?filepath=%file&lines=%line',
        'netbeans' => 'netbeans://open/?f=%file:%line',
    ];

    /**
     * Get the editor href for a given file and line, if available.
     *
     * @param string $filePath
     * @param int    $line
     *
     * @throws InvalidArgumentException If editor resolver does not return a string
     *
     * @return null|string
     */
    protected function getEditorHref($filePath, $line)
    {
        if (empty(config('debugbar.editor'))) {
            return null;
        }

        if (empty($this->editors[config('debugbar.editor')])) {
            throw new InvalidArgumentException(
                'Unknown editor identifier: ' . config('debugbar.editor') . '. Known editors:' .
                implode(', ', array_keys($this->editors))
            );
        }

        $filePath = $this->replaceSitesPath($filePath);

        $url = str_replace(['%file', '%line'], [$filePath, $line], $this->editors[config('debugbar.editor')]);

        return $url;
    }

    /**
     * Replace remote path
     *
     * @param string $filePath
     *
     * @return string
     */
    protected function replaceSitesPath($filePath)
    {
        return str_replace(config('debugbar.remote_sites_path'), config('debugbar.local_sites_path'), $filePath);
    }
}

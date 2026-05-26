<?php

declare(strict_types=1);

class Controller extends CController
{
    private const DEFAULT_PER_PAGE = 5;
    private const ALLOWED_PER_PAGE_VALUES = [5, 10, 25, 50];

    public $layout = '//layouts/main';
    public $menu = [];
    public $breadcrumbs = [];

    protected function resolvePerPage(string $sessionKey): int
    {
        $perPage = null;

        if (isset($_GET['perPage'])) {
            $candidate = (int) $_GET['perPage'];
            if (in_array($candidate, self::ALLOWED_PER_PAGE_VALUES, true)) {
                $perPage = $candidate;
            }
        }

        if ($perPage === null && Yii::app()->hasComponent('session') && isset(Yii::app()->session[$sessionKey])) {
            $candidate = (int) Yii::app()->session[$sessionKey];
            if (in_array($candidate, self::ALLOWED_PER_PAGE_VALUES, true)) {
                $perPage = $candidate;
            }
        }

        if ($perPage === null) {
            $perPage = self::DEFAULT_PER_PAGE;
        }

        if (Yii::app()->hasComponent('session')) {
            Yii::app()->session[$sessionKey] = $perPage;
        }

        return $perPage;
    }
}

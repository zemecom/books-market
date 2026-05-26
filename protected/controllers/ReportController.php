<?php

declare(strict_types=1);

class ReportController extends Controller
{
    public function actionTopAuthors(): void
    {
        $year = isset($_GET['year']) && $_GET['year'] !== '' ? (int) $_GET['year'] : null;
        $rows = Yii::app()->services->topAuthorsReportService()->getTopAuthors($year);
        $this->render('topAuthors', [
            'rows' => $rows,
            'year' => $year,
        ]);
    }
}

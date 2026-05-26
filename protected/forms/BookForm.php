<?php

declare(strict_types=1);

class BookForm extends CFormModel
{
    public ?string $title = null;
    public ?string $description = null;
    public ?string $isbn = null;
    public ?string $publishYear = null;
    public array $authorIds = [];
    public ?CUploadedFile $coverFile = null;

    public function rules(): array
    {
        return [
            ['title, isbn, publishYear', 'required'],
            ['description', 'safe'],
            ['isbn', 'length', 'max' => 64],
            ['publishYear', 'match', 'pattern' => '/^\d{4}$/', 'message' => 'Publish year must be a 4-digit year.'],
            ['publishYear', 'validatePublishYear'],
            ['authorIds', 'validateAuthors'],
            ['coverFile', 'file', 'allowEmpty' => true, 'types' => 'jpg, jpeg, png, gif'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'Title',
            'description' => 'Description',
            'isbn' => 'ISBN',
            'publishYear' => 'Publish Year',
            'authorIds' => 'Authors',
            'coverFile' => 'Cover Image',
        ];
    }

    public function getPublishYearValue(): int
    {
        return (int) $this->publishYear;
    }

    public function toPublishedAt(): string
    {
        return sprintf('%04d-01-01', $this->getPublishYearValue());
    }

    public function validateAuthors(string $attribute, array $params): void
    {
        $authorIds = array_filter(array_map('intval', (array) $this->$attribute));
        if ($authorIds === []) {
            $this->addError($attribute, 'Select at least one author.');
        }
    }

    public function validatePublishYear(string $attribute, array $params): void
    {
        $currentYear = (int) Yii::app()->clock->now()->format('Y');
        $inputYear = (int) $this->$attribute;

        if ($inputYear > $currentYear) {
            $this->addError($attribute, 'Publish year cannot be in the future.');
        }
    }
}

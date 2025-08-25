<?php

namespace Services;

use Models\Note;

class NotesService {
    public function __construct(private Note $model) {}

    public function getAllNotes(): array {
        return $this->model->getAll();
    }
}